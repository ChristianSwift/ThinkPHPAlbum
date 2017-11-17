function postlogin() {
    if (document.getElementById('user').value == '' || document.getElementById('pswd').value == '') {
        alertify.notify('请输入用户名和密码！', 'error', 5, function(){ console.log('Get form infomation failed!'); });
        return false;
    }
    var user = document.getElementById('user').value;
    var pswd = document.getElementById('pswd').value;
    ajax({
        url: "./api.php?c=index&a=login",
        type: 'POST',
        data: {
            user: user,
            pswd: MD5(pswd)
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                //服务器返回注册成功
                alertify.notify('登录成功！', 'success', 5, function(){ console.log('Login successed'); });
                location.href = "./admin.php";
                return true;
            }
            else {
                //注册被服务器拒绝
                alertify.notify('用户名或密码错误！', 'error', 5, function(){ console.log('登录发生异常，可能是用户名或密码有误。错误代码：' + authcode + '，错误详情：' + message + '。此信息仅供技术人员鉴定系统运行状态！'); });
                //alert("登录失败！用户名或密码不正确，错误原因：" + message);
                return authcode;
            }
        },
        fail: function (status) {
            alertify.notify('链接服务器失败，请检查！', 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status); });
            return false;
        }
    });
}

/**
 * 用户注册公用逻辑
 * @param {string} s_token 会话密钥
 */
function postreg(s_token = '0') {
    if (document.getElementById('user').value == '' || document.getElementById('pswd').value == '' || document.getElementById('mail').value == '') {
        alertify.notify('表单内容存在空白，请重试！', 'error', 5, function(){ console.log('Form something empty!'); });
        return false;
    }
    var user = document.getElementById('user').value;
    var pswd = document.getElementById('pswd').value;
    var mail = document.getElementById('mail').value;
    ajax({
        url: "./api.php?c=index&a=register",
        type: 'POST',
        data: {
            user: user,
            pswd: MD5(pswd),
            token: s_token,
            mail: mail
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                //服务器返回注册成功
                alertify.notify('注册成功！', 'success', 5, function(){ console.log('Regist successed'); });
                if (s_token != 0) {
                    return true;
                }
                else {
                    location.href = "./admin.php?c=Login";
                    return true;
                }
            }
            else {
                //注册被服务器拒绝
                alertify.notify('注册失败！错误原因：' + message, 'error', 5, function(){ console.log('注册发生异常，但远程服务器正确的响应了本次请求。错误代码：' + authcode + '，错误详情：' + message + '。此信息仅供技术人员鉴定系统运行状态！'); });
                return authcode;
            }
        },
        fail: function (status) {
            alertify.notify('远程服务器忙碌' + message, 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status)} );
            return false;
        }
    });
}

function logout() {
    ajax({
        url: "./api.php?c=index&a=logout",
        type: 'POST',
        data: {
            token: Math.random()
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                //服务器返回注册成功
                alertify.notify('注销成功！', 'success', 5, function(){ console.log('Logout successed'); });
                location.href = "./admin.php?c=Login";
                return true;
            }
            else {
                //注册被服务器拒绝
                alertify.notify('注销失败！错误原因：' + message, 'error', 5, function(){ console.log('注销发生异常，但远程服务器正确的响应了本次请求。错误代码：' + authcode + '，错误详情：' + message + '。此信息仅供技术人员鉴定系统运行状态！'); });
                return authcode;
            }
        },
        fail: function (status) {
            alertify.notify('远程服务器忙碌！', 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status); });
            return false;
        }
    });
}

function submit_main() {
    var m_name = document.getElementById('myalbum_name').innerText;
    var m_nickname = document.getElementById('myalbum_nickname').innerText;
    var m_icon = document.getElementById('myalbum_icon').innerText;
    var m_logo = document.getElementById('myalbum_logo').innerText;
    var m_saying = document.getElementById('myalbum_saying').innerText;
    var m_author = document.getElementById('myalbum_author').innerText;
    var m_copyright = document.getElementById('myalbum_copyright').innerText;
    var m_jsondata = '{"name":"' + m_name + '","nickname":"' + m_nickname + '","icon":"' + m_icon + '","logo":"' + m_logo + '","saying":"' + m_saying + '","author":"' + m_author + '","copyright":"' + m_copyright + '"}';
    ajax({
        url: "./api.php?c=index&a=operate",
        type: 'POST',
        data: {
            mod: 'baseinfo',
            type: 'write',
            token: operator_id,
            data: m_jsondata
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                alertify.notify(message, 'success', 5, function(){ console.log('Main info update succeed!'); });
                return true;
            }
            else {
                alertify.notify('提交失败，错误详情：' + message, 'error', 5, function(){ console.log('Main info update failed!'); });
                return true;
            }
        },
        fail: function (status) {
            alertify.notify('远程服务器忙碌！', 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status); });
            return false;
        }
    });
}

function create_navi() {
    if (document.getElementById('add_navname').value == '' || document.getElementById('add_navlink').value == '') {
        alertify.notify('表单内容存在空白，请重试！', 'error', 5, function(){ console.log('Form something empty!'); });
        return false;
    }
    var m_navi = document.getElementById('add_navname').value;
    var m_link = document.getElementById('add_navlink').value;
    var reg = /^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/;
    if (!reg.test(m_link)) {
        alertify.notify('网址链接不符合HTTP规范，请使用“http://”或“https://”为前缀的标准URL。', 'error', 5, function(){ console.log('Get form infomation failed!'); });
        return false;
    }
    var m_jsondata = '{"m_navi":"' + m_navi + '","m_link":"' + m_link + '"}';
    ajax({
        url: "./api.php?c=index&a=operate",
        type: 'POST',
        data: {
            mod: 'navinfo',
            type: 'write',
            token: operator_id,
            data: m_jsondata
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                alertify.notify(message + '<br>页面将在3秒后自动重新载入！', 'success', 5, function(){ console.log('Navigation info create succeed!'); });
                var autoReload = window.setTimeout('location.reload()',3000);
                return true;
            }
            else {
                alertify.notify('提交失败，错误详情：' + message, 'error', 5, function(){ console.log('Navigation info update failed!'); });
                return true;
            }
        },
        fail: function (status) {
            alertify.notify('远程服务器忙碌！', 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status); });
            return false;
        }
    });
}

function submit_navi(type, nid) {
    if (type == 'save') {
        var m_navi = document.getElementById('navname_' + nid).innerText;
        var m_link = document.getElementById('navlink_' + nid).innerText;
        var odata = '{"m_navi":"' + m_navi + '","m_link":"' + m_link + '"}';
        var operation = '更新';
    }
    else if (type == 'del') {
        var odata = 'del';
        var operation = '移除';
    }
    else {
        alertify.notify('非法操作类型：' + type, 'error', 5, function(){ console.log('Illegal operation.'); });
    }
    if (!confirm('您确认继续' + operation + 'ID为：' + nid + '的导航信息么？')) {
        return false;
    }
    ajax({
        url: "./api.php?c=index&a=operate",
        type: 'POST',
        data: {
            mod: 'navinfo',
            type: 'write',
            nid: nid,
            token: operator_id,
            data: odata
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                alertify.notify(message + '<br>页面将在3秒后自动重新载入！', 'success', 2, function(){ console.log('Navigation info create succeed!'); });
                var autoReload = window.setTimeout('location.reload()',3000);
                return true;
            }
            else {
                alertify.notify(operation + '失败，错误详情：' + message, 'error', 5, function(){ console.log('Navigation info update failed!'); });
                return true;
            }
        },
        fail: function (status) {
            alertify.notify('远程服务器忙碌！', 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status); });
            return false;
        }
    });
}

function create_user() {
    postreg(operator_id);
}

function submit_user(type, uid) {
    if (type == 'save') {
        var m_user = document.getElementById('uname_' + uid).innerText;
        var m_pswd = document.getElementById('upass_' + uid).value;
        var m_mail = document.getElementById('umail_' + uid).innerText;
        var odata = '{"m_user":"' + m_user + '","m_pswd":"' + m_pswd + '","m_mail":"' + m_mail + '"}';
        var operation = '更新';
    }
    else if (type == 'del') {
        var odata = 'del';
        var operation = '移除';
    }
    else {
        alertify.notify('非法操作类型：' + type, 'error', 5, function(){ console.log('Illegal operation.'); });
    }
    if (!confirm('您确认继续' + operation + 'ID为：' + uid + '的用户信息么？')) {
        return false;
    }
    ajax({
        url: "./api.php?c=index&a=operate",
        type: 'POST',
        data: {
            mod: 'userinfo',
            type: 'write',
            uid: uid,
            token: operator_id,
            data: odata
        },
        dataType: "xml",
        async: false,
        success: function (response, xml) {
            //console.log(response);
            var authcode = xml.getElementsByTagName("code")[0].firstChild.nodeValue;
            var message = xml.getElementsByTagName("message")[0].firstChild.nodeValue;
            if (authcode == 200) {
                alertify.notify(message + '<br>页面将在3秒后自动重新载入！', 'success', 2, function(){ console.log('Navigation info create succeed!'); });
                var autoReload = window.setTimeout('location.reload()',3000);
                return true;
            }
            else {
                alertify.notify(operation + '失败，错误详情：' + message, 'error', 5, function(){ console.log('Navigation info update failed!'); });
                return true;
            }
        },
        fail: function (status) {
            alertify.notify('远程服务器忙碌！', 'error', 5, function(){ console.log('发生异常，系统无法正常请求远程服务器。请检查本地网络情况！如果网络一切正常，可能是由于远程服务器正在维护或处于忙碌状态，请稍候再次尝试或联系技术人员！错误信息：' + status); });
            return false;
        }
    });
}

/**
 * 原生JS AJAX封装
 * @param {any} options
 */
function ajax(options) {
    /**
     * 传入方式默认为对象
     * */
    options = options || {};
    /**
     * 默认为GET请求
     * */
    options.type = (options.type || "GET").toUpperCase();
    /**
     * 返回值类型默认为json
     * */
    options.dataType = options.dataType || 'json';
    /**
     * 默认为异步请求
     * */
    options.async = options.async || true;
    /**
     * 对需要传入的参数的处理
     * */
    var params = getParams(options.data);
    var xhr;
    /**
     * 创建一个 ajax请求
     * W3C标准和IE标准
     */
    if (window.XMLHttpRequest) {
        /**
         * W3C标准
         * */
        xhr = new XMLHttpRequest();
    } else {
        /**
         * IE标准
         * @type {ActiveXObject}
         */
        xhr = new ActiveXObject('Microsoft.XMLHTTP')
    }
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            var status = xhr.status;
            if (status >= 200 && status < 300) {
                options.success && options.success(xhr.responseText, xhr.responseXML);
            } else {
                options.fail && options.fail(status);
            }
        }
    };
    if (options.type == 'GET') {
        xhr.open("GET", options.url + '?' + params, options.async);
        xhr.send(null)
    } else if (options.type == 'POST') {
        /**
         *打开请求
         * */
        xhr.open('POST', options.url, options.async);
        /**
         * POST请求设置请求头
         * */
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        /**
         * 发送请求参数
         */
        xhr.send(params);
    }
}

/**
 * 对象参数的处理
 * @param data
 * @returns {string}
 */
function getParams(data) {
    var arr = [];
    for (var param in data) {
        arr.push(encodeURIComponent(param) + '=' + encodeURIComponent(data[param]));
    }
    //console.log(arr); //输出加入随机数参数之前的arr
    arr.push(('randomNumber=' + Math.random()).replace('.'));
    //console.log(arr); //输出加入随机数参数之后的arr
    return arr.join('&');
}
