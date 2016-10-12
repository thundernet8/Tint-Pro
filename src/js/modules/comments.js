/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/10 21:27
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import modalSignBox from './modalSignBox';
import Utils from './utils';

var _body = $('body');

// 主评论框
var _commentTextarea = $('#comment-text');
var _mainSubmitBtn = $('#submit');

// 评论组件
var _replyBtnSel = '.comment-meta>.respond-coin';
var _starBtnSel = '.comment-meta>.like';

// 回复框
var _replyWrapSel = '.respond-submit';
var _replyInputSel = '.respond-submit input';
var _replyTipSel = '.tip';

// 表情框Table
var _emotionIcoBtnSel = '.emotion-ico';
var _emotionsWrapSel = '.qqFace';
var _emotionImgSel = '.qqFace td>img';
var _qqFacePath = TT.themeRoot + '/assets/img/qqFace/';
var _qqFaceTable = '<table border="0" cellspacing="0" cellpadding="0">'
                    +  '<tbody>'
                    +      '<tr>'
                    +            '<td><img src="' + _qqFacePath + '1.gif' + '" data-code="[em_1]"></td>'
                    +            '<td><img src="' + _qqFacePath + '2.gif' + '" data-code="[em_2]"></td>'
                    +            '<td><img src="' + _qqFacePath + '3.gif' + '" data-code="[em_3]"></td>'
                    +            '<td><img src="' + _qqFacePath + '4.gif' + '" data-code="[em_4]"></td>'
                    +            '<td><img src="' + _qqFacePath + '5.gif' + '" data-code="[em_5]"></td>'
                    +            '<td><img src="' + _qqFacePath + '6.gif' + '" data-code="[em_6]"></td>'
                    +            '<td><img src="' + _qqFacePath + '7.gif' + '" data-code="[em_7]"></td>'
                    +            '<td><img src="' + _qqFacePath + '8.gif' + '" data-code="[em_8]"></td>'
                    +            '<td><img src="' + _qqFacePath + '9.gif' + '" data-code="[em_9]"></td>'
                    +            '<td><img src="' + _qqFacePath + '10.gif' + '" data-code="[em_10]"></td>'
                    +            '<td><img src="' + _qqFacePath + '11.gif' + '" data-code="[em_11]"></td>'
                    +            '<td><img src="' + _qqFacePath + '12.gif' + '" data-code="[em_12]"></td>'
                    +            '<td><img src="' + _qqFacePath + '13.gif' + '" data-code="[em_13]"></td>'
                    +            '<td><img src="' + _qqFacePath + '14.gif' + '" data-code="[em_14]"></td>'
                    +            '<td><img src="' + _qqFacePath + '15.gif' + '" data-code="[em_15]"></td>'
                    +        '</tr>'
                    +        '<tr>'
                    +            '<td><img src="' + _qqFacePath + '16.gif' + '" data-code="[em_16]"></td>'
                    +            '<td><img src="' + _qqFacePath + '17.gif' + '" data-code="[em_17]"></td>'
                    +            '<td><img src="' + _qqFacePath + '18.gif' + '" data-code="[em_18]"></td>'
                    +            '<td><img src="' + _qqFacePath + '19.gif' + '" data-code="[em_19]"></td>'
                    +            '<td><img src="' + _qqFacePath + '20.gif' + '" data-code="[em_20]"></td>'
                    +            '<td><img src="' + _qqFacePath + '21.gif' + '" data-code="[em_21]"></td>'
                    +            '<td><img src="' + _qqFacePath + '22.gif' + '" data-code="[em_22]"></td>'
                    +            '<td><img src="' + _qqFacePath + '23.gif' + '" data-code="[em_23]"></td>'
                    +            '<td><img src="' + _qqFacePath + '24.gif' + '" data-code="[em_24]"></td>'
                    +            '<td><img src="' + _qqFacePath + '25.gif' + '" data-code="[em_25]"></td>'
                    +            '<td><img src="' + _qqFacePath + '26.gif' + '" data-code="[em_26]"></td>'
                    +            '<td><img src="' + _qqFacePath + '27.gif' + '" data-code="[em_27]"></td>'
                    +            '<td><img src="' + _qqFacePath + '28.gif' + '" data-code="[em_28]"></td>'
                    +            '<td><img src="' + _qqFacePath + '29.gif' + '" data-code="[em_29]"></td>'
                    +            '<td><img src="' + _qqFacePath + '30.gif' + '" data-code="[em_30]"></td>'
                    +        '</tr>'
                    +        '<tr>'
                    +            '<td><img src="' + _qqFacePath + '31.gif' + '" data-code="[em_31]"></td>'
                    +            '<td><img src="' + _qqFacePath + '32.gif' + '" data-code="[em_32]"></td>'
                    +            '<td><img src="' + _qqFacePath + '33.gif' + '" data-code="[em_33]"></td>'
                    +            '<td><img src="' + _qqFacePath + '34.gif' + '" data-code="[em_34]"></td>'
                    +            '<td><img src="' + _qqFacePath + '35.gif' + '" data-code="[em_35]"></td>'
                    +            '<td><img src="' + _qqFacePath + '36.gif' + '" data-code="[em_36]"></td>'
                    +            '<td><img src="' + _qqFacePath + '37.gif' + '" data-code="[em_37]"></td>'
                    +            '<td><img src="' + _qqFacePath + '38.gif' + '" data-code="[em_38]"></td>'
                    +            '<td><img src="' + _qqFacePath + '39.gif' + '" data-code="[em_39]"></td>'
                    +            '<td><img src="' + _qqFacePath + '40.gif' + '" data-code="[em_40]"></td>'
                    +            '<td><img src="' + _qqFacePath + '41.gif' + '" data-code="[em_41]"></td>'
                    +            '<td><img src="' + _qqFacePath + '42.gif' + '" data-code="[em_42]"></td>'
                    +            '<td><img src="' + _qqFacePath + '43.gif' + '" data-code="[em_43]"></td>'
                    +            '<td><img src="' + _qqFacePath + '44.gif' + '" data-code="[em_44]"></td>'
                    +            '<td><img src="' + _qqFacePath + '45.gif' + '" data-code="[em_45]"></td>'
                    +        '</tr>'
                    +        '<tr>'
                    +            '<td><img src="' + _qqFacePath + '46.gif' + '" data-code="[em_46]"></td>'
                    +            '<td><img src="' + _qqFacePath + '47.gif' + '" data-code="[em_47]"></td>'
                    +            '<td><img src="' + _qqFacePath + '48.gif' + '" data-code="[em_48]"></td>'
                    +            '<td><img src="' + _qqFacePath + '49.gif' + '" data-code="[em_49]"></td>'
                    +            '<td><img src="' + _qqFacePath + '50.gif' + '" data-code="[em_50]"></td>'
                    +            '<td><img src="' + _qqFacePath + '51.gif' + '" data-code="[em_51]"></td>'
                    +            '<td><img src="' + _qqFacePath + '52.gif' + '" data-code="[em_52]"></td>'
                    +            '<td><img src="' + _qqFacePath + '53.gif' + '" data-code="[em_53]"></td>'
                    +            '<td><img src="' + _qqFacePath + '54.gif' + '" data-code="[em_54]"></td>'
                    +            '<td><img src="' + _qqFacePath + '55.gif' + '" data-code="[em_55]"></td>'
                    +            '<td><img src="' + _qqFacePath + '56.gif' + '" data-code="[em_56]"></td>'
                    +            '<td><img src="' + _qqFacePath + '57.gif' + '" data-code="[em_57]"></td>'
                    +            '<td><img src="' + _qqFacePath + '58.gif' + '" data-code="[em_58]"></td>'
                    +            '<td><img src="' + _qqFacePath + '59.gif' + '" data-code="[em_59]"></td>'
                    +            '<td><img src="' + _qqFacePath + '60.gif' + '" data-code="[em_60]"></td>'
                    +        '</tr>'
                    +        '<tr>'
                    +            '<td><img src="' + _qqFacePath + '61.gif' + '" data-code="[em_61]"></td>'
                    +            '<td><img src="' + _qqFacePath + '62.gif' + '" data-code="[em_62]"></td>'
                    +            '<td><img src="' + _qqFacePath + '63.gif' + '" data-code="[em_63]"></td>'
                    +            '<td><img src="' + _qqFacePath + '64.gif' + '" data-code="[em_64]"></td>'
                    +            '<td><img src="' + _qqFacePath + '65.gif' + '" data-code="[em_65]"></td>'
                    +            '<td><img src="' + _qqFacePath + '66.gif' + '" data-code="[em_66]"></td>'
                    +            '<td><img src="' + _qqFacePath + '67.gif' + '" data-code="[em_67]"></td>'
                    +            '<td><img src="' + _qqFacePath + '68.gif' + '" data-code="[em_68]"></td>'
                    +            '<td><img src="' + _qqFacePath + '69.gif' + '" data-code="[em_69]"></td>'
                    +            '<td><img src="' + _qqFacePath + '70.gif' + '" data-code="[em_70]"></td>'
                    +            '<td><img src="' + _qqFacePath + '71.gif' + '" data-code="[em_71]"></td>'
                    +            '<td><img src="' + _qqFacePath + '72.gif' + '" data-code="[em_72]"></td>'
                    +            '<td><img src="' + _qqFacePath + '73.gif' + '" data-code="[em_73]"></td>'
                    +            '<td><img src="' + _qqFacePath + '74.gif' + '" data-code="[em_74]"></td>'
                    +            '<td><img src="' + _qqFacePath + '75.gif' + '" data-code="[em_75]"></td>'
                    +        '</tr>'
                    +    '</tbody>'
                    +'</table>';

// AJAX 加载评论
var _commentsPerPage = TT.commentsPerPage || 20;
var _currentCommentPage = 1;

// 检查是否登录
var _checkLogin = function () {
   if(TT&&TT.uid&&parseInt(TT.uid)>0) {
       return true;
   }
   modalSignBox.show();
};

// 评论内容检查
var _validateComment = function (input) {
    var content = input.val();
    if(/^[\s]*$/.test(content)) {
        _showError('评论内容不能为空', input.parent().siblings(_errSel)); //TODO more 日文韩文
        return false;
    }
    return true;
};

var _showError = function (msg, errorBox) {
    errorBox.hide().html(msg).slideDown('slow', function(){
        setTimeout(function(){
            errorBox.slideUp().html('');
        }, 3000);
    });
};


// 提交状态
var _submitting = false;

// 当前提交的input或textarea以及点击的按钮
var _currentInput = null;
var _clickedSubmitBtn = null;
var _originalSubmitBtnText = '';
var _submitBtnIcon = '<i class="tico tico-spin"></i>';

// Hidden input
var _nonceInput = $('#tt_comment_nonce');

var _post = function () {
    // 提交评论
    var url = Utils.getAPIUrl('/comments');
    var data = {

    };
    var beforeSend = function () {
        if(_submitting) return;
        _submitting = true;
        if(_currentInput) {
            _currentInput.prop('disabled', true);
        }
        if(_clickedSubmitBtn) {
            _originalSubmitBtnText = _clickedSubmitBtn.text();
            _clickedSubmitBtn.prop('disabled', true).html(_submitBtnIcon);
        }
    };
    var finishRequest = function () {
        if(!_submitting) return;
        _submitting = false;
        if(_currentInput) {
            _currentInput.prop('disabled', false);
        }
        if(_clickedSubmitBtn) {
            _clickedSubmitBtn.text(_originalSubmitBtnText).prop('disabled', false);
        }
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            // TODO 嵌入评论

        }else{
            _showError(data.message, _currentInput.parent().siblings(_errSel));
        }
        finishRequest();
    };
    var error = function (xhr, textStatus, err) {
        _showError(xhr.responseJSON.message, _currentInput.parent().siblings(_errSel));
        finishRequest();
    };
    $.post({
        url: url,
        data: data,
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};

// AJAX 发表评论/回复
var _commentFormSel = '#respond .comment-form';
var _replyFormSel = '#respond .reply-form';
var _commentSubmitBtnSel = '.comment-form .comment-submit';
var _replySumitBtnSel = '.reply-form .reply-submit';
var _errSel = '.err';



// 导出模块
var postCommentsKit = {
    init: function () {
        // 绑定事件
        _body.on('click', _replyBtnSel, function () {
            // Toggle 回复框(同时检查是否登录)
            var $this = $(this);
            var _currentReplyWrap = $this.parent().parent('.comment-body').children(_replyWrapSel);
            _checkLogin();
            if(_currentReplyWrap.css('display')!=='block'){
                $('#respond '+_replyWrapSel).hide();
            }
            _currentReplyWrap.slideToggle();
        });
        _body.on('focus', _replyInputSel, function () {
            // 回复框输入光标位置
            var $this = $(this);
            var _paddingLeft = $this.parents(_replyWrapSel).find(_replyTipSel).width() + 10;
            $this.css('padding-left', _paddingLeft + 'px');
        });
        _body.on('click', _emotionIcoBtnSel, function () {
            // 插入表情Table
            var _qqFaceWrap = $(this).parent().children(_emotionsWrapSel);
            if(!/[\S]+/.test(_qqFaceWrap.html())) {
                _qqFaceWrap.html(_qqFaceTable);
            }
        });
        _body.on('click', _emotionImgSel, function () {
            // 输入表情
            var $this = $(this);
            var _qqFaceWrap = $this.parents(_emotionsWrapSel);
            var _inputBoxId = _qqFaceWrap.data('inputbox-id');
            var _inputBox = $('#'+_inputBoxId);
            var _emotionCode = $this.data('code');
            if(_inputBox.is('input')) {
                _inputBox.trigger('focus'); // 方便输入光标定位
                _inputBox.val(_inputBox.val() + _emotionCode);
            }else{
                _inputBox.text(_inputBox.text() + _emotionCode);
            }
        });
        
        _body.on('click', _commentTextarea, function () {
            _checkLogin();
        });
        
        _body.on('click', _commentSubmitBtnSel, function () {
            // 主评论提交
            var $this = $(this);
            if(_submitting || $this.prop('disabled')) return;
            var _textArea = $this.parent().find('textarea');
            if(_validateComment(_textArea)) {
                alert('ok');
            }
        });
        
        _body.on('click', _replySumitBtnSel, function () {
            // 回复提交
            var $this = $(this);
            if(_submitting || $this.prop('disabled')) return;
            var _input = $this.parent().parent().find('input');
            if(_validateComment(_input)) {
                alert('ok');
            }
        });
    }
};

export default postCommentsKit;