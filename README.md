![https://old.webapproach.net/shop/tint-pro.html](https://img.shields.io/badge/version-Tint--2.0.0-green.svg?style=flat)

## Tint

基于WordPress内容管理系统的高级扩展主题([原Tinection 2](https://old.webapproach.net/shop/tint-pro.html))

Tint为付费主题，购买地址为[WebApproach商店](https://old.webapproach.net/shop/tint-pro.html)

Tint主题介绍：[查看介绍](https://old.webapproach.net/tint.html)

Tint主题专用git仓库和wiki: [访问WebApproach仓库](https://git.webapproach.net/WebApproach/Tint-Pro)


## Tint特点

Tint颠覆了以往的WordPress主题开发模式，在各种MVVM框架大行其道的今天，有必要将其优点引入到这之中来。

类似MVVM，Tint添加了View Model层，其有以下若干优点:

    * 降低View Controller或View的重量，例如通常WordPress直接在页面模板中混杂各种运行计算逻辑或函数以及HTML代码，现在页面模板主要作为控制器，可以引入多个小的模块页面，数据由响应的ViewModel类实例提供并输入到页面
    
    * 更方便的数据管理，易于在ViewModel中引入缓存控制器，目前缓存控制逻辑在Base ViewModel中，而具体的ViewModel可以拥有缓存控制开关和缓存时间等配置
    
    * 更清晰的目录结构，Tint完全改写了原有的模板加载方式，不让各类模板完全集中在主题主目录，代码文件按照功能/类型存放，如assets、core/classes、core/functions、core/templates、core/viewModels等，修改或二次开发更方便

Tint使用了工程化的开发方式，src为开发目录，各种功能脚本按模块开发并最终打包压缩为一份适用于生产环境的JS文件，如果你需要添加或修改功能，非常容易添加模块或修改已有模块。同样对于使用Less开发的样式表也是如此。

Tint集成了多种缓存方式，包括如下几种:

    * 数据库片段：将多个高度相关查询结果存为一份数据，降低查询次数(首页queries降低约40%至50~60queries)
    
    * Memcache对象缓存: 内存对象键值对缓存，只需要后台设置填写对应服务器地址和端口即可使用
    
    * Redis对象缓存: 同样是内存对象缓存，不同于Memcache的是其可以将数据同步到文件系统并随时恢复，配置也和Memcache一样只需填写服务器和端口

内存对象缓存能极大降低查询次数，目前[WebApproach](https://old.webapproach.net)已开启Memcache并将首页查询降低到个位数，稳定在7queries左右，相比数据库片段方式优势明显

REST API

Tint最大化利用了WP新版本的REST API功能，并将所有AJAX请求与REST API对接并完成相应的路由控制器，v1版本的API仍将基于cookie验证，但未来很容易过渡到v2基于token验证，实现更方便的跨域前后端真正分离

其他...见[介绍文章](https://old.webapproach.net/tint.html)

### 使用的开源代码\资源

* 1. \[ jQuery \]\[ v3.1.0 \]: [jquery.com](http://jquery.com/)
* 2. \[ SweetAlert \]\[ v1 \]: [t4t5.github.io/sweetalert](http://t4t5.github.io/sweetalert/)
* 3. \[ Bootstrap \]\[ v1 \]: [getbootstrap](http://getbootstrap.com/)
* 4. \[ WebUploader \]\[ v0.1.6 \]: [WebUploader](http://fex.baidu.com/webuploader/)
* ...