<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/23 22:53
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeNotificationsVM
 */
class MeNotificationsVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;

    /**
     * @var string 通知类型
     */
    private $_type;

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
     * @param   string $type      通知类型
     * @param   int    $page      分页
     * @param   int    $limit     每页最多显示数量
     * @return  static
     */
    public static function getInstance($user_id = 0, $type = 'all', $page = 1, $limit = 20) {
        $instance = new static();
        $type = in_array($type, array('comment', 'star', 'credit', 'update')) ? $type : array('comment', 'star', 'update', 'notification', 'credit'); //TODO add more
        $type_str = is_array($type) ? implode('_', $type) : $type;
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_user' . $user_id . '_type' . $type_str;
        $instance->_userId = $user_id;
        $instance->_type = $type;
        $instance->_page = $page;
        $instance->_limit = $limit;
        $instance->_enableCache = false; //禁用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $notifications = tt_get_messages($this->_type, $this->_limit, ($this->_page - 1) * $this->_limit, MsgReadStatus::ALL);
        $count = $notifications ? count($notifications) : 0;
        $total = tt_count_messages( $this->_type, MsgReadStatus::ALL);
        $max_pages = ceil($total / $this->_limit);
        $pagination_base = is_array($this->_type) ? tt_url_for('all_notify') . '/page/%#%' : tt_url_for($this->_type . '_notify') . '/page/%#%';
        return (object)array(
            'count' => $count,
            'notifications' => $notifications,
            'total' => $total,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}