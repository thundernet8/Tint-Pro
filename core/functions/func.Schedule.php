<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/21 16:06
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 添加周循环的定时任务周期选项
 *
 * @since   2.0.0
 *
 * @param   array   $schedules
 * @return  array
 */
function tt_cron_add_weekly( $schedules ){
    $schedules['weekly'] = array(
        'interval' => 604800, // 1周 = 60秒 * 60分钟 * 24小时 * 7天
        'display' => __('Weekly','tt')
    );
    return $schedules;
}
add_filter('cron_schedules', 'tt_cron_add_weekly');


/**
 * 每小时执行的定时任务
 *
 * @since   2.0.0
 * @return  void
 */
function tt_setup_common_hourly_schedule() {
    if ( ! wp_next_scheduled( 'tt_setup_common_hourly_event' ) ) {
        // 1471708800是北京2016年8月21日00:00:00时间戳
        wp_schedule_event( 1471708800, 'hourly', 'tt_setup_common_hourly_event');
    }
}
add_action( 'wp', 'tt_setup_common_hourly_schedule' );


/**
 * 每天执行的定时任务
 *
 * @since   2.0.0
 * @return  void
 */
function tt_setup_common_daily_schedule() {
    if ( ! wp_next_scheduled( 'tt_setup_common_daily_event' ) ) {
        // 1471708800是北京2016年8月21日00:00:00时间戳
        wp_schedule_event( 1471708800, 'daily', 'tt_setup_common_daily_event');
    }
}
add_action( 'wp', 'tt_setup_common_daily_schedule' );


/**
 * 每两天执行的定时任务
 *
 * @since   2.0.0
 * @return  void
 */
function tt_setup_common_twicedaily_schedule() {
    if ( ! wp_next_scheduled( 'tt_setup_common_twicedaily_event' ) ) {
        // 1471708800是北京2016年8月21日00:00:00时间戳
        wp_schedule_event( 1471708800, 'twicedaily', 'tt_setup_common_twicedaily_event');
    }
}
add_action( 'wp', 'tt_setup_common_twicedaily_schedule' );


/**
 * 每周执行的定时任务
 *
 * @since   2.0.0
 * @return  void
 */
function tt_setup_common_weekly_schedule() {
    if ( ! wp_next_scheduled( 'tt_setup_common_weekly_event' ) ) {
        // 1471795200是北京2016年8月22日 星期一 00:00:00时间戳
        wp_schedule_event( 1471795200, 'twicedaily', 'tt_setup_common_weekly_event');
    }
}
add_action( 'wp', 'tt_setup_common_weekly_schedule' );
