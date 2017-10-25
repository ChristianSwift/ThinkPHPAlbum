<?php

    $commonconf = array(

		'DB_TYPE'   => 'mysqli', // 数据库类型：本系统支持mysql和mysqli两种连接方式，php5.0以上版本均支持全新的mysqli方式
		'DB_HOST'   => '127.0.0.1', // 服务器地址
		'DB_USER'   => 'root', // 用户名
		'DB_PWD'    => 'dnw199611018', // 密码
		'DB_PORT'   => 3306, // 端口		
		'DB_NAME'   => 'album', // 数据库名
		'DB_PREFIX' => '', // 数据库表前缀
      
        /*
        极限验证API尚未完成集成，此处仅为预留参数区
        */
      	//'GEETEST_AK' => 'fbbc37ecbab479cc41a0076606ddd695', //极验滑动验证应用公钥
      	//'GEETEST_SK' => '4195cbb0741c7529e2d7ab8a2f487f4b', //极验滑动验证应用私钥
      
      	//'DEFAULT_TIMEZONE' => 'PRC', //系统时区配置，TP貌似会自动检测，如果自动检测失败，可以手动指定。。。
      
      	'myalbum_name' => '个人相册', //相册名称与标题配置
      	'myalbum_introduction' => '这是XXXXX的个人相册，欢迎您的访问。如果您对我有什么建议，请通过右侧的留言板给我留言！', //相册详细介绍配置
      	'myalbum_twitter' => 'http://twitter.com/XXXXX', //相册社交-推特配置
      	'myalbum_facebook' => 'http://www.facebook.com/XXXXX', //相册社交-脸书配置
      	'myalbum_github' => 'http://github.com/XXXXX', //相册社交-github配置
      	'myalbum_author' => 'David Ding', //相册作者信息配置
      	'myalbum_bgm' => 'http://filecache.cdn.xxx.com/musics/name.mp3', //相册背景音乐配置
      	'myalbum_copyright' => 'Anonymous', //相册版权信息配置
      
      	'myalbum_ssosrv' => 'false', //单点登录支持模式，如果为false则说明关闭sso。此功能为私有功能，需要配合小丁工作室SSO服务端！
	    
	    'URL_CASE_INSENSITIVE'  =>  true //URL不区分大小写
	
	);

?>