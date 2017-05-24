<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/22 20:10
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeCreditRecordsVM
 */
class MeCreditRecordsVM extends BaseVM {

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
        //$instance->_enableCache = false; //TODO debug use
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $records = tt_get_credit_messages($this->_limit, ($this->_page - 1) * $this->_limit);
        $count = $records ? count($records) : 0;
        $total = tt_count_credit_messages();
        $max_pages = ceil($total / $this->_limit);
        $pagination_base = tt_url_for('my_credits') . '/page/%#%';
        return (object)array(
            'count' => $count,
            'records' => $records,
            'total' => $total,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}