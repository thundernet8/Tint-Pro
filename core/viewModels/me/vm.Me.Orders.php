<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/17 22:56
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeOrdersVM
 */
class MeOrdersVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;

    /**
     * @var string 订单货币类型
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
     * @param   string $type      订单货币类型
     * @param   int    $page      分页
     * @param   int    $limit     每页最多显示数量
     * @return  static
     */
    public static function getInstance($user_id = 0, $type = 'all', $page = 1, $limit = 20) {
        $instance = new static();
        $type = in_array($type, array('all', 'credit', 'cash')) ? $type : 'all';
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_user' . $user_id . '_type' . $type;
        $instance->_userId = $user_id;
        $instance->_type = $type;
        $instance->_page = $page;
        $instance->_limit = $limit;
        $instance->_enableCache = false; // 禁用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $orders = tt_get_user_orders($this->_userId, $this->_limit, ($this->_page - 1) * $this->_limit, $this->_type);
        $count = $orders ? count($orders) : 0;
        $total = tt_count_user_orders($this->_userId, $this->_type);
        $max_pages = ceil($total / $this->_limit);
        $pagination_base = tt_url_for('my_' . $this->_type . '_orders') . '/page/%#%';

        $copy_orders = array();
        foreach ($orders as $order){
            if($order->product_id > 0){
                $order->product_link = get_permalink($order->product_id);
            }else{
                $order->product_link = null;
            }
            $copy_orders[] = $order;
        }
        return (object)array(
            'count' => $count,
            'orders' => $copy_orders,
            'total' => $total,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}