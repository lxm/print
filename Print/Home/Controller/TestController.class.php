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
	    var_dump(C(UPLOAD_SITEIMG_QINIU));
    }

    public function getfunc()
	{
	    echo(encode('bla_bla+/++---___'));
    }
    
    public function getsplit()
    {
            $condition['use_id']            = 1;
            $condition['status']            = array('between', '1,5');
            $File       = D('FileView');
            $count      = $File->where($condition)->count();
            $Page       = new \Think\Page($count,5);
            $show       = $Page->show();
            $list= $File->where($condition)->order('file.id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);
            $this->display();

    }
    
    public function postupyun()
    {
        $setting=array ( 
                'maxSize' => 10 * 1024 * 1024,//文件大小
                'rootPath' => './',
                'saveName' => array ('uniqid', ''),
                'driver' => 'Upyun',
                'driverConfig' => array (
                        'host' => 'http://v0.api.upyun.com',
                        // 空间名称
                        'bucket' => '',
                        // 操作员名称
                        'username' => '',
                        // 密码
                        'password' => ''
                        )
            );
        $Upload = new \Think\Upload($setting);
        $info = $Upload->upload($_FILES);
	    var_dump($Upload);
        var_dump($info);
	
    }
    
	public function posttest()
	{
	    mb_internal_encoding("UTF-8");	    
	    $uid=1;
        $sid=M('User')->cache(true)->getFieldById($uid,'student_number');
	    $fid=0;
	    $pid = 1;//I('post.pri_id',0,'int');
	    foreach ($_FILES as $file)
        {
            if($fid<5&&$file['error']==0)
            {
            }
            else
            {
                $this->error('Upload error');
            }
            $fid++;
        }

	            $setting=array ( 
                'maxSize' => 10 * 1024 * 1024,//文件大小
                'rootPath' => './',
//                'saveName' => array ('uniqid', ''),
                'driver' => 'Qiniu',
                'driverConfig' => array (
                        'secrectKey' => 'Tnpd01budFmsrY562mCeHyKJD5WAMltmPxsynAZg', 
                        'accessKey' => 'fhGmHO1_QHq01QVKuAyKQoWklslb88Uxd0rJLcko',
                        'domain' => 'nkumstc.qiniudn.com',
                        'bucket' => 'nkumstc'
                        )
                );
                $Upload = new \Think\Upload($setting);
                $Upload->exts = array('doc', 'docx', 'pdf', 'wps', 'ppt', 'pptx');
                $info = $Upload->upload($_FILES);
                $fid = 0;
        foreach($info as $file)
        {
                $copies=I('post.copies_'.$fid,0,'int');
                $double=I('post.double_side_'.$fid,0,'int');
                $savepath= str_replace('/','_',$info['file_'.$fid]['savepath']);
                $url = 'http://7vihnm.com1.z0.glb.clouddn.com/'.$savepath.$info['file_'.$fid]['savename'];
                $find = array('+', '/');
                $replace = array('-', '_');
                $duetime = NOW_TIME + 10086400;//下载凭证有效时间
                $DownloadUrl = $url . '?e=' . $duetime;
                $Sign = hash_hmac ( 'sha1', $DownloadUrl, $setting ["driverConfig"] ["secrectKey"], true );
                $EncodedSign = str_replace($find, $replace, base64_encode($Sign));
                $Token = $setting ["driverConfig"] ["accessKey"] . ':' . $EncodedSign;
                $RealDownloadUrl = $DownloadUrl . '&token=' . $Token;
                echo($RealDownloadUrl);
                echo('<br>');
                $name=$_FILES['file_'.$fid]['name'];
                	if(mb_strlen($name)>62)
                	{
                		$name=mb_substr($name,0,58).'.'.$file['ext'];
                	}
                $data['name']                      = $name;
                $data['pri_id']                    = $pid;
                $data['url']                      = $savepath.$info['file_'.$fid]['savename'];
                $data['status']                      = 1;
                $data['use_id']                      = $uid;
                $data['copies']                      = $copies;
                $data['double_side']                      = $double;

                    $File                 = M('File');
                    $result               = $File->cache(true)->add($data);
                    if ($result) 
                    {
                        $Notification         = M('Notification');
                        $Notification->fil_id = $result;
                        $Notification->to_id  = $data['pri_id'];
                        $Notification->type   = 1;
                        $Notification->add();
                        S(cache_name('user',$uid),null);
                        S(cache_name('printer',$pid),null);
                    } else
                    {
                        echo('BAD UPLOAD');
                    }
                $fid++;
        }
    }
}
