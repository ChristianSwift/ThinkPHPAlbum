function postlogin() {
    if (document.getElementById('user').value == '' || document.getElementById('pswd').value == '') {
        showError('登录失败，用户名和密码不能为空。请检查并补全后再次尝试！');
        return false;
    }
    var user = document.getElementById('user').value;
    var pswd = document.getElementById('pswd').value;
    ajax({
        method: 'POST',
        url: './api.php?c=index&a=login',
        data: {
            user: user,
            pswd: MD5(pswd)
        },
        isXML: true,
        success: function (response) {
            var authcode = response.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = response.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode === 200) {
                //服务器返回注册成功
                alert("用户登录成功！");
                location.href = "./admin.php";
                return true;
            }
            else {
                //注册被服务器拒绝
                log("登录发生异常，但远程服务器正确的响应了本次请求。错误代码：" + authcode + "，错误详情：" + message + "。此信息仅供技术人员鉴定系统运行状态！");
                alert("登录失败！错误原因：" + message);
                return authcode;
            }
        },
        failure: function (state) {
            log("登录发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！");
            alert("远程服务器处于忙碌状态，网络请求异常。");
            return false;
            //ajax异常回调
        }
    });
}

function postreg() {
    if (document.getElementById('user').value == '' || document.getElementById('pswd').value == '' || document.getElementById('mail').value == '') {
        showError('用户名、密码或邮箱不能为空，请检查并补全后再次尝试。');
        return false;
    }
    var user = document.getElementById('user').value;
    var pswd = document.getElementById('pswd').value;
    var mail = document.getElementById('mail').value;
    ajax({
        method: 'POST',
        url: './api.php?c=index&a=register',
        data: {
            user: user,
            pswd: MD5(pswd),
            mail: mail
        },
        isXML: true,
        success: function (response) {
            var authcode = response.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = response.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode === 200) {
                //服务器返回注册成功
                alert("用户注册成功！");
                location.href = "./admin.php?c=Login";
                return true;
            }
            else {
                //注册被服务器拒绝
                log("注册发生异常，但远程服务器正确的响应了本次请求。错误代码：" + authcode + "，错误详情：" + message + "。此信息仅供技术人员鉴定系统运行状态！");
                alert("注册失败！错误原因：" + message);
                return authcode;
            }
        },
        failure: function (state) {
            log("注册发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！");
            alert("远程服务器处于忙碌状态，网络请求异常。");
            return false;
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
    /*
    var msg_string = '<div class="ivu-message-notice move-up-leave-active move-up-leave-to" style="height: 50px;"><div class="ivu-message-notice-content"><div class="ivu-message-notice-content-text"><div class="ivu-message-custom-content ivu-message-error"><i class="ivu-icon ivu-icon-close-circled"></i><span>' + message_str + '</span></div></div></div></div>';
    document.getElementById('msgbox').innerHTML = msg_string;
    */
    alert(message_str);
}

function log(logstr) {
    console.log(logstr);
}