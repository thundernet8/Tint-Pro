<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/03 01:10
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * Class Member
 */
class Member{

    private $_user;

    public $vip_type = 'normal';

    public function __construct($user_or_id){
        if($user_or_id instanceof WP_User){
            $this->_user = $user_or_id;
        }elseif((int)$user_or_id > 0){
            $this->_user = get_user_by('id', (int)$user_or_id);
        }else{
            // TODO: error
        }

//        if($this->_user){
//            foreach (get_object_vars($this->_user) as $key => $value){
//                $this->{$key} = $value;
//            }
//        }
    }

    public function is_vip(){
        return true; //TODO
    }

    public function is_monthly_vip(){
        return true; //TODO
    }
}
