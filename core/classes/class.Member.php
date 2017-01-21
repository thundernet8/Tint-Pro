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
 * @link https://webapproach.net/tint.html
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

    const EXPIRED_VIP = 9;

    const MONTHLY_VIP_PERIOD = 2592000;

    const ANNUAL_VIP_PERIOD = 31536000;

    const PERMANENT_VIP_PERIOD = 315360000;

    private $_user;

    private $_uid;

    private $_member_row = false;

    private $vip_type = 0;

//    private $join_time = 'N/A';
//
//    private $expire_time = 'N/A';

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

    public function __get($property_name) {
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

    public function __set($property_name, $value) {
        switch ($property_name){
            case 'vip_type': // 不允许外部设值
                break;
            default:
                $this->$property_name = $value;
        }
    }

    private function get_vip_type() {
        if($this->_member_row === false){
            $this->_member_row = tt_get_member_row($this->_uid);
        }
        if(!$this->_member_row) {
            return Member::NORMAL_MEMBER;
        }
        if($this->_member_row->endTimeStamp <= time()) { //已过期
            // TODO do_action membership_expired
            return Member::EXPIRED_VIP;
        }
        if(in_array($this->_member_row->user_type, array(Member::PERMANENT_VIP, Member::ANNUAL_VIP, Member::MONTHLY_VIP))) {
            return $this->_member_row->user_type;
        }
        return Member::MONTHLY_VIP;
    }

    /**
     * 获取VIP开通时间
     *
     * @since 2.0.0
     * @return string
     */
    public function get_vip_join_time() {
        if($this->_member_row === false){
            $this->_member_row = tt_get_member_row($this->_uid);
        }
        if(!$this->_member_row) {
            return 'N/A';
        }
        return $this->_member_row->startTime;
    }

    /**
     * 获取VIP过期时间
     *
     * @since 2.0.0
     * @return string
     */
    public function get_vip_expire_time() {
        if($this->_member_row === false){
            $this->_member_row = tt_get_member_row($this->_uid);
        }
        if(!$this->_member_row) {
            return 'N/A';
        }
        return $this->_member_row->endTime;
    }

    public function is_vip(){
        return in_array($this->get_vip_type(), array(Member::PERMANENT_VIP, Member::ANNUAL_VIP, Member::MONTHLY_VIP));
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
