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
 * @link https://webapproach.net/tint.html
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
     * @var bool
     */
    protected $_enableCache = true;

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
     * @var int
     */
    protected $_cacheInterval = 3600;

    /**
     * @var int
     */
    protected $_objectCacheInterval = 3600;

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
     * @return  BaseVM
     */
    public static function getInstance() {
        if(static::$_instance && static::$_instance instanceof static) { // static::$_instance instanceof static防止子类都共用基类一个实例
            return static::$_instance;
        }

        $args = func_get_args();
        if(count($args)) {
            //if(version_compare(PHP_VERSION,'5.5.0','ge')) {
                //$reflect = new ReflectionClass(static::class); //static::class 是PHP5.5的新特性
            //}else{
                $reflect = new ReflectionClass(get_called_class());
            //}

            static::$_instance = $reflect->newInstanceArgs($args);
        }
        static::$_instance = new static();

//        if(!static::$_instance->_cacheKey) {
//            static::$_instance->_cacheKey = 'tt_cache_' . static::$_instance->_cacheUpdateFrequency . '_vm_' . static::class;
//        }

        static::$_instance->configInstance();

        return static::$_instance;
    }


    /**
     * 配置实例(主要为了延迟数据的获取，不便放到构造器中)
     *
     * @since   2.0.0
     * @return  void
     */
    protected function configInstance() {
        // cache key
        if(!$this->_cacheKey) {
            $this->_cacheKey = 'tt_cache_' . $this->_cacheUpdateFrequency . '_vm_' . get_called_class();
        }

        if($cache = $this->getDataFromCache()) {
            $this->modelData = $cache;
        }else{
            $data = $this->getRealData();
            $this->setDataToCache($data);
            $this->modelData = $data;
        }
    }

    /**
     * 从缓存获取数据
     *
     * @since   2.0.0
     * @access  protected
     * @return mixed
     */
    protected function getDataFromCache() {
        // DEBUG模式不使用缓存 //TODO
        if(TT_DEBUG || tt_get_option('tt_disable_cache', false) || !($this->_enableCache) || (isset($_GET['cache']) && $_GET['cache'] == 0)) {
            return false;
        }

        $transient = get_transient($this->_cacheKey);
        if(!$transient) {
            return false;
        }

        $cacheObj = (object)maybe_unserialize($transient);
        $this->cacheTime = $cacheObj->cacheTime;
        $this->isCache = true;

        return (object)$cacheObj->data;
    }

    /**
     * 设置缓存
     *
     * @since   2.0.0
     * @access  protected
     * @param   mixed $data
     * @return  void
     */
    protected function setDataToCache($data) {
        if(!$data || TT_DEBUG) {
            return;
        }
        $cacheTime = current_time('mysql');

        $store = maybe_serialize(array(
            'data' => $data,
            'cacheTime' => $cacheTime
        ));
        set_transient($this->_cacheKey, $store, wp_using_ext_object_cache() ? $this->_objectCacheInterval : $this->_cacheInterval);
    }

    /**
     * 获取实时数据
     *
     * @since   2.0.0
     * @access  protected
     * @return  mixed
     */
    abstract protected function getRealData();
}