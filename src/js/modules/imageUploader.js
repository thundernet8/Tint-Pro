/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/15 23:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

//var WebUploader = require('../vender/webuploader.html5only');
import Utils from './utils';
import {popMsgbox} from './msgbox';

var _body = $('body');

var _initAvatarUpload = function () {
    _handleAvatarUpload();
};

var _handleAvatarUpload = function () {
    var options = {
        // 选完文件后，是否自动上传。
        auto: true,
    
        // swf文件路径
        //swf: BASE_URL + '/js/Uploader.swf',
    
        // 文件接收服务端。
        server: Utils.getAbsUrl('site/upload'),
    
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id: '.avatar-picker',
            innerHTML: '',
            multiple: false
        },
    
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        compress: {
            width: 100,
            height: 100,
            // 图片质量，只有type为`image/jpeg`的时候才有效。
            quality: 90,
            // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
            allowMagnify: false,
            // 是否允许裁剪。
            crop: true,
            // 是否保留头部meta信息。
            preserveHeaders: true,
            // 如果发现压缩后文件大小比原来还大，则使用原来图片
            // 此属性可能会影响图片自动纠正功能
            noCompressIfLarger: false,
            // 单位字节，如果图片大小小于此值，不会采用压缩。
            compressSize: 0
        },
        formData: {
            imgFor: 'avatar'
        }
    };
    if(typeof WebUploader == 'undefined') return false;
    var uploader = WebUploader.create(options);
    
    uploader.on( 'startUpload', function(){
        Utils.showFullLoader('tico-spinner2', '正在上传中...')
    } );
    
    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        //console.log(percentage);
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file, response ) {
        popMsgbox.success({
           title: '头像上传成功'
        });
        $('.local-avatar-label>img').attr('src', response.data.avatar);
        $('.local-avatar-label>input[name="avatar"]').prop('checked', true).val('custom');
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        popMsgbox.error({
            title: '上传头像失败'
        });
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file ) {
        Utils.hideFullLoader();
    });
};

//
var ImageUploader = {
    initAvatarUpload: _initAvatarUpload
};

export default ImageUploader;