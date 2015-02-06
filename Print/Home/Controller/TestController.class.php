<?php

// ===================================================================
// | FileName:      EmptyController.class.php
// ===================================================================
// | Discription：   404 用户控制器
//      <命名规范：>
// ===================================================================
// +------------------------------------------------------------------
// | 云印南开
// +------------------------------------------------------------------
// | Copyright (c) 2014 云印南开团队 All rights reserved.
// +------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +------------------------------------------------------------------
/**
 * Class and Function List:
 * Function list:
 * - index()
 * Classes list:
 * - EmptyController extends Controller
 */
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller
{

	public function gettest()
	{
            $this->display();
	}
    public function getsetting()
	{
	var_dump(C(MAX_TRIES));
	    var_dump(C('UPLOAD_SITEIMG_QINIU'));
    }

    
    public function getfunc()
	{
	    echo(qiniu_encode('bla_bla+/++---___'));
    }
	public function posttest()
	{
	/*
        $setting=array ( 
                'maxSize' => 10 * 1024 * 1024,//文件大小
                'rootPath' => './',
                'saveName' => array ('uniqid', ''),
                'driver' => 'Upyun',
                'driverConfig' => array (
                        'host' => 'http://v0.api.upyun.com',
                        // 空间名称
                        'bucket' => 'nkumstc',
                        // 操作员名称
                        'username' => '605527108',
                        // 密码
                        'password' => 'w1e9s9t3W1O0O1D3'
                        )
            );
        var_dump($setting);
        $Upload = new \Think\Upload($setting);
        $info = $Upload->upload($_FILES);
	*/
	
	
	
	$setting=array ( 
                'maxSize' => 10 * 1024 * 1024,//文件大小
                'rootPath' => './',
                'saveName' => array ('uniqid', ''),
                'driver' => 'Qiniu',
                'driverConfig' => array (
                        'secrectKey' => 'Tnpd01budFmsrY562mCeHyKJD5WAMltmPxsynAZg', 
                        'accessKey' => 'fhGmHO1_QHq01QVKuAyKQoWklslb88Uxd0rJLcko',
                        'domain' => 'nkumstc.qiniudn.com',
                        'bucket' => 'nkumstc'
                )
            );
    $Upload = new \Think\Upload($setting);
    $info = $Upload->upload($_FILES);
    var_dump($Upload);
    var_dump($info);
    $savepath= str_replace('/','_',$info['image']['savepath']);
    $url = 'http://7vihnm.com1.z0.glb.clouddn.com/'.$savepath.$info['image']['savename'];
    echo($url.'\n');
    $find = array('+', '/');
    $replace = array('-', '_');
    $duetime = NOW_TIME + 10086400;//下载凭证有效时间
    $DownloadUrl = $url . '?e=' . $duetime;
    echo($DownloadUrl.'\n');
    $Sign = hash_hmac ( 'sha1', $DownloadUrl, $setting ["driverConfig"] ["secrectKey"], true );
    $EncodedSign = str_replace($find, $replace, base64_encode($Sign));
    $Token = $setting ["driverConfig"] ["accessKey"] . ':' . $EncodedSign;
    $RealDownloadUrl = $DownloadUrl . '&token=' . $Token;
    echo($RealDownloadUrl);
    }
}
