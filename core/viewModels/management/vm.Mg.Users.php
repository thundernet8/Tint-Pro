<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 23:18
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MgUsersVM
 */
class MgUsersVM extends BaseVM {
    /**
     * @var int  分页
     */
    protected $_page = 1;

    /**
     * @var string  用户角色
     */
    protected $_type = 'all';

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   $page
     * @param   $type
     * @return  static
     */
    public static function getInstance($page = 1, $type = 'all') {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_type' . $type . '_page' . $page;
        $instance->_page = $page;
        $instance->_type = $type;
        //$instance->_enableCache = false; // TODO debug use
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $limit = 20; // 每页20条

        $args = array(
            //'role'         => '',
            'orderby'      => 'ID', //'login',
            'order'        => 'DESC',
            'offset'       => ($this->_page - 1) * $limit,
            'number'       => $limit,
            'count_total'  => true
        );
        if($this->_type != 'all') {
            $args['role'] = $this->_type;
        }

        $users_query = new WP_User_Query($args);
        $users = $users_query->get_results(); //get_users($args);
        $count = $users ? count($users) : 0;
        $total_count = $users_query->get_total();
        $max_pages = ceil($total_count / $limit);

        switch ($this->_type) {
            case 'administrator':
                $url_key = 'manage_admins';
                break;
            case 'editor':
                $url_key = 'manage_editors';
                break;
            case 'author':
                $url_key = 'manage_authors';
                break;
            case 'contributor':
                $url_key = 'manage_contributors';
                break;
            case 'subscriber':
                $url_key = 'manage_subscribers';
                break;
            default:
                $url_key = 'manage_users';
        }
        $pagination_base = tt_url_for($url_key) . '/page/%#%';

        return (object)array(
            'count' => $count,
            'users' => $users,
            'total' => $total_count,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}