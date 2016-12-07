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

    const NORMAL_MEMBER = 0;

    const MONTHLY_VIP = 1;

    const ANNUAL_VIP = 2;

    const PERMANENT_VIP = 3;

    const MONTHLY_VIP_PERIOD = 2592000;

    const ANNUAL_VIP_PERIOD = 31536000;

    const PERMANENT_VIP_PERIOD = 315360000;

    private $_user;

    private $_uid;

    private $_member_row = false;

    private $vip_type = 0;

    public function __construct($user_or_id){
        if($user_or_id instanceof WP_User){
            $this->_user = $user_or_id;
        }else{
            $this->_user = get_user_by('id', (int)$user_or_id);
        }

        if($this->_user){
            $this->_uid = $this->_user->ID;
            foreach (get_object_vars($this->_user) as $key => $value){
                $this->{$key} = $value;
            }
        }
    }

    private function __get($property_name) {
        switch ($property_name){
            case 'vip_type':
                return $this->get_vip_type();
            default:
                if(isset($this->$property_name)){
                    return $this->$property_name;
                }else{
                    return null;
                }
        }
    }

    private function __set($property_name, $value) {
        switch ($property_name){
            case 'vip_type': // 不允许外部设值
                break;
            default:
                $this->$property_name = $value;
        }
    }

    private function get_vip_type() {
        if($this->_member_row === false){
            $row = tt_get_member_row($this->_uid);
            $this->_member_row = $row;
            $this->vip_type = $row->user_type;
            return $row->user_type;
        }
        return 0;
    }

    public function is_vip(){
        return $this->get_vip_type() > self::NORMAL_MEMBER;
    }

    public function is_monthly_vip(){
        return $this->get_vip_type() == self::MONTHLY_VIP;
    }

    public function is_annual_vip(){
        return $this->get_vip_type() == self::ANNUAL_VIP;
    }

    public function is_permanent_vip(){
        return $this->get_vip_type() == self::PERMANENT_VIP;
    }
}
