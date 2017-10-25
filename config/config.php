<?php

    $commonconf = array(

		'DB_TYPE'   => 'mysqli', 				// 数据库类型：本系统支持mysql和mysqli两种连接方式，php5.0以上版本均支持全新的mysqli方式
		'DB_HOST'   => '127.0.0.1', 			// 服务器地址
		'DB_USER'   => 'root', 					// 用户名
		'DB_PWD'    => 'dnw199611018', 			// 密码
		'DB_PORT'   => 3306, 					// 端口		
		'DB_NAME'   => 'album', 				// 数据库名
		'DB_PREFIX' => '', 						// 数据库表前缀

	    'URL_CASE_INSENSITIVE'  =>  true ,		//URL不区分大小写
		'SHOW_PAGE_TRACE' => true,				//开启页面Trace
	);

?>