<?php

    $commonconf = array(
		'DB_TYPE'   => 'mysqli',	// 数据库类型：本系统支持mysql和mysqli两种连接方式，php5.0以上版本均支持全新的mysqli方式
		'DB_HOST'   => 'ddns1.dingcloud.xyz',	// 服务器地址
		'DB_USER'   => 'album',	// 用户名
		'DB_PWD'    => 'album_passwd',	// 密码
		'DB_PORT'   => 3306,	// 端口		
		'DB_NAME'   => 'album',	// 数据库名
		'DB_PREFIX' => '',	// 数据库表前缀

		'URL_MODEL'	=>	0, //URL模式设置，用于服务器伪静态处理

		'STATIC_SRV'	=>	'http://ouralbum-demo.test.upcdn.net', //仅在启用云存储后有效，如又拍云驱动。（末尾无需加斜杠，存库时会自动添加）
		'FILE_UPLOAD_TYPE' => 'Local', //配置上传驱动，目前支持：本地、又拍云、FTP等存储
		'UPLOAD_TYPE_CONFIG' => array(
			//又拍云host，默认即可
			'host' => 'v0.api.upyun.com',
			//又拍云创建的空间名
			'bucket' => 'ouralbum-demo', 
			//对应空间的操作员名称
			'username' => 'ouralbum',
			//操作员口令
			'password' => 'ouralbum20171226'
		),
		'URL_CASE_INSENSITIVE'  =>  true	//URL不区分大小写
	);

?>