<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/22 21:52
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * Class BaseVM
 */
abstract class BaseVM {
    /**
     * @var BaseVM
     */
    protected static $_instance = null;

    /**
     * @var string
     */
    protected $_cacheKey;

    /**
     * @var string
     */
    public $cacheTime;

    /**
     * @var bool
     */
    public $isCache = false;

    /**
     * @var string
     */
    protected $_cacheUpdateFrequency = 'hourly';

    /**
     * @var object
     */
    public $modelData;

    abstract protected function __construct();

    /**
     * 获取实例(确保单例)
     *
     * @since   2.0.0
     * @static
     * @access  public
     * @return BaseVM
     */
    public static function getInstance() {
        if(static::$_instance) {
            return static::$_instance;
        }

        $args = func_get_args();
        if(count($args)) {
            $reflect = new ReflectionClass(static::class);
            static::$_instance = $reflect->newInstanceArgs($args);
        }
        static::$_instance = new static();

        if(!static::$_instance->_cacheKey) {
            static::$_instance->_cacheKey = 'tt_cache_' . static::$_instance->_cacheUpdateFrequency . '_vm_' . static::class;
        }

        static::$_instance->configInstance();

        return static::$_instance;
    }


    /**
     * 配置实例(主要为了延迟数据的获取，不便放到构造器中)
     *
     * @since   2.0.0
     * @return  void
     */
    abstract protected function configInstance();

    /**
     * 从缓存获取数据
     *
     * @since   2.0.0
     * @access  protected
     * @return mixed
     */
    abstract protected function getDataFromCache();

    /**
     * 设置缓存
     *
     * @since   2.0.0
     * @access  protected
     * @param   mixed $data
     * @return  mixed
     */
    abstract protected function setDataToCache($data);

    /**
     * 获取实时数据
     *
     * @since   2.0.0
     * @access  protected
     * @return  mixed
     */
    abstract protected function getRealData();
}