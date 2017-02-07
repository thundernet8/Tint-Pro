<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/23 21:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeMessagesVM
 */
class MeMessagesVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;

    /**
     * @var string 消息类型
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
     * @param   string $type      消息类型
     * @param   int    $page      分页
     * @param   int    $limit     每页最多显示数量
     * @return  static
     */
    public static function getInstance($user_id = 0, $type = 'inbox', $page = 1, $limit = 20) {
        $instance = new static();
        $type = in_array($type, array('inbox', 'sendbox')) ? $type : 'inbox';
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
        // $user_id = get_current_user_id();
        $messages = $this->_type == 'sendbox' ? tt_get_sent_pm(0, $this->_limit, ($this->_page - 1) * $this->_limit) : tt_get_pm(0, $this->_limit, ($this->_page - 1) * $this->_limit, MsgReadStatus::ALL);
        $count = $messages ? count($messages) : 0;
        $total = $this->_type == 'sendbox' ? tt_count_sent_pm(0) : tt_count_pm(0, MsgReadStatus::ALL);
        $max_pages = ceil($total / $this->_limit);
        $pagination_base = $this->_type == 'sendbox' ? tt_url_for('out_msg') . '/page/%#%' : tt_url_for('in_msg') . '/page/%#%';
        return (object)array(
            'count' => $count,
            'messages' => $messages,
            'total' => $total,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}