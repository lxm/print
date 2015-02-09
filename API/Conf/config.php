<?php
// ===================================================================
// | FileName: 	/API/Conf/config.php api配置
// ===================================================================
// +------------------------------------------------------------------
// | 云印南开
// +------------------------------------------------------------------
// | Copyright (c) 2014 云印南开团队 All rights reserved.
// +------------------------------------------------------------------

return array(
	
	//'配置项'=>'配置值'
	'URL_ROUTER_ON' => true,
	
	'URL_ROUTE_RULES' => array(
		'File/:id' => 'File/id',
		'Notification/:id' => 'Notification/id',
		'User/:id' => 'User/id',
		'Printer/:id' => 'Printer/id',
		'Token'=>array('Token/create','',array('method'=>'post')),
	) ,

	'API_VERSION'=>1.2,
);

