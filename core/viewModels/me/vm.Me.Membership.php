<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/20 22:26
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeMembershipVM
 */
class MeMembershipVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;

    /**
     * @var int 分页
     */
    private $_page;

    /**
     * @var int 每页最多显示数量
     */
    private $_limit;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $user_id   用户ID
     * @param   int    $page      分页
     * @param   int    $limit     每页最多显示数量
     * @return  static
     */
    public static function getInstance($user_id = 0, $page = 1, $limit = 20) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_user' . $user_id;
        $instance->_userId = $user_id;
        $instance->_page = $page;
        $instance->_limit = $limit;
        //$instance->_enableCache = false; //Debug use TODO clear
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $orders = tt_get_user_member_orders($this->_userId, $this->_limit, ($this->_page - 1) * $this->_limit);
        $count = $orders ? count($orders) : 0;
        $total = tt_count_user_member_orders($this->_userId);
        $max_pages = ceil($total / $this->_limit);
        $pagination_base = tt_url_for('my_membership') . '/page/%#%';

        $member = new Member($this->_userId);
        $info = array(
            'is_vip' => $member->is_vip(),
            'member_type' => $member->vip_type,
            'member_status' => tt_get_member_status_string($member->vip_type),
            'join_time' => $member->get_vip_join_time(),
            'end_time' => $member->get_vip_expire_time()
        );
        return (object)array(
            'info' => $info,
            'count' => $count,
            'orders' => $orders,
            'total' => $total,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}