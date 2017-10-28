function postlogin() {
    //TODO
}

function postreg() {
    if (document.getElementById('user').value == '' || document.getElementById('pswd').value == '' || document.getElementById('mail').value == '') {
        showError('用户名、密码或邮箱不能为空，请检查并补全后再次尝试。');
        return false;
    }
    var user = document.getElementById('user').value;
    var pswd = document.getElementById('pswd').value;
    ajax({
        method: 'POST',
        url: './api.php?c=index&a=register',
        data: {
            usr: user,
            pws: pswd
        },
        isXML: true,
        success: function (response) {
            var authcode = response.getElementsByTagName("status")[0].firstChild.nodeValue;
            if (authcode === 200) {
                //登录成功
                return true;
            }
            else {
                //发生错误
                return authcode;
            }
        },
        failure: function (state) {
            //ajax异常回调
        }
    });
}

/* 封装ajax函数
 * @param {string}opt.type http连接的方式，包括POST和GET两种方式
 * @param {string}opt.url 发送请求的url
 * @param {boolean}opt.async 是否为异步请求，true为异步的，false为同步的
 * @param {object}opt.data 发送的参数，格式为对象类型
 * @param {function}opt.success ajax发送并接收成功调用的回调函数
 */
function ajax(opt) {
    opt = opt || {};
    opt.method = opt.method.toUpperCase() || 'POST';
    opt.url = opt.url || '';
    opt.async = opt.async || true;
    opt.data = opt.data || null;
    opt.isXML = opt.isXML || null;
    opt.success = opt.success || function () { };
    opt.failure = opt.failure || function () { };
    var xhr = null;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xhr = new XMLHttpRequest();
    }
    else {
        xhr = new ActiveXObject('Microsoft.XMLHTTP');
    }
    var params = [];
    for (var key in opt.data) {
        params.push(key + '=' + opt.data[key]);
    }
    var postData = params.join('&');
    if (opt.method.toUpperCase() === 'POST') {
        xhr.open(opt.method, opt.url, opt.async);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf-8');
        xhr.send(postData);
    }
    else if (opt.method.toUpperCase() === 'GET') {
        xhr.open(opt.method, opt.url + '?' + postData, opt.async);
        xhr.send(null);
    }
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (opt.isXML == true) {
                opt.success(xhr.responseXML);
            }
            else {
                opt.success(xhr.responseText);
            }
        }
        else {
            opt.failure(xhr.status);
        }
    };
}

function showError(message_str) {
    var msg_string = '<div class="ivu-message-notice move-up-leave-active move-up-leave-to" style="height: 50px;"><div class="ivu-message-notice-content"><div class="ivu-message-notice-content-text"><div class="ivu-message-custom-content ivu-message-error"><i class="ivu-icon ivu-icon-close-circled"></i><span>' + message_str + '</span></div></div></div></div>';
    document.getElementById('msgbox').innerHTML = msg_string;
}