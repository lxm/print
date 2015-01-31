<?php

// ===================================================================
// | FileName: 		FileController.class.php
// ===================================================================
// | Discription：	FileController 文件操作接口
//		<命名规范：>
// ===================================================================
// +------------------------------------------------------------------
// | 云印南开
// +------------------------------------------------------------------
// | Copyright (c) 2014 云印南开团队 All rights reserved.
// +------------------------------------------------------------------

/**
 * Class and Function List:
 * Function list:
 * - index()
 * - id()
 * Classes list:
 * - FileController extends RestController
 */

namespace API\Controller;
use Think\Controller\RestController;
class FileController extends RestController
{
	
	// REST允许的请求类型列表
	protected $allowMethod = array('get', 'delete', 'put', 'post',);
	protected $defaultType = 'json';
	
	// REST允许请求的资源类型列表
	protected $allowType   = array('xml', 'json',);
	
	/**
	 *index
	 *查询文件
	 * 支持操作get
	 *@return json,xml
	 *@author NewFuture
	 */
	public function index() 
	{
		$info        = auth();
		if ($info) 
		{
			switch ($info['type']) 
			{
			case C('PRINTER'):
				$where['pri_id']             = $info['id'];
				break;

			case C('STUDENT'):
				$where['use_id'] = $info['id'];
				break;

			default:
				$data['err'] = '不支持类型';
				break;
			}
			if (!isset($data)) 
			{
				$where['status']           = array('gt', 0);
				$start_id  = I('start', null, 'int');
				$page      = I('page', 1, 'int');
				if ($start_id) 
				{
					$where['id']           = array('gt', $start_id);
				}
				$cache_key = false;
				
				// cache_name('printer', $info['id']);
				$data['files']           = D('FileView')->where($where)->page($page, 10)->cache($cache_key, 10)->select();
			}
		} else
		{
			$data['err']           = '认证失败';
		}
		$type      = ($this->_type == 'xml') ? 'xml' : 'json';
		$this->response($data, $type);
	}
	
	/**
	 *id
	 *单个文件查看和修改
	 * 支持操作get，put
	 *@return json,xml
	 *		查询返回，详细信息列表
	 *		修改，返回操作结果msg
	 *		出错返回err
	 *@author NewFuture
	 */
	public function id($value = '') 
	{
		$info  = auth();
		if (!$info) 
		{
			$data['err']       = '认证失败';
		} else
		{
			
			//访问类型
			switch ($info['type']) 
			{
			case C('STUDENT'):
			case C('STUDENT_API'):
				$where['use_id']       = $info['id'];
				break;

			case C('PRINTER'):
			case C('PRINTER_WEB'):
				$where['pri_id'] = $info['id'];
				break;

			default:
				$data['err']      = '位置类型';
			}
			
			$fid  = I('get.id', null, 'intval');
			
			if (!$data && $fid) 
			{
				$where['id']      = $fid;
				$File = M('File');
				
				//操作类型
				switch ($this->_method) 
				{
				case 'get':
					$where['status']      = array('gt', '0');
					$data = $File->where($where)->find();
					break;

				case 'put':
				case 'post':
					if (!isset($where['pri_id'])) 
					{
						$data['err']             = '只允许打印店进行此操作';
					} else
					{
						$where['status']             = array('between', '1,4');
						$file        = $File->where($where)->field('status')->cache(false)->find();
						if (!$file) 
						{
							$data['err']             = '文件已删除或者已付款！';
						} else
						{
							$status      = I('status');
							switch ($status) 
							{
							case 'upload':
							case 1:
								$status_code = 1;
								break;

							case 'download':
							case 2:
								$status_code = 2;
								break;

							case 'printing':
							case 3:
								$status_code = 3;
								break;

							case 'printed':
							case 4:
								$status_code = 4;
								break;

							case 'payed':
							case 5:
								$status_code = 5;
								break;

							default:
								$data['err'] = '非法状态！';
								break;
							}
							if ($status_code <= $file['status']) 
							{
								$data['err'] = '不允许逆向设置！';
							} elseif ($File->where('id="%d"', $fid)->setField('status', $status_code)) 
							{
								$data['msg'] = '修改完成！';
								
								//删除缓存
								// S(cache_name('printer', $info['id']), null);
								// S(cache_name('user', $file['use_id']), null);
								
								
							} else
							{
								$data['err'] = '状态设置失败';
							}
						}
					}
					break;

				case 'delete':
					if (!isset($where['use_id'])) 
					{
						$data['err']      = '只允许上传用户删除';
					} else
					{
						$where['status']      = array(array('eq', 1), array('eq', 5), 'or');
						$file = $File->where($where)->field('url,pri_id')->find();
						if ($file) 
						{
							if (delete_file("./Uploads/" . $file['url'])) 
							{
								$save['status']      = 0;
								$save['url']      = '';
								if ($File->where('id="%d"', $fid)->save($save)) 
								{
									
									//删除缓存
									// S(cache_name('printer',$file['pri_id']),null);
									
									
								}
							} else
							{
								$data['err']      = '删除失败！';
							}
						} else
						{
							$data['err']      = '文件不存在！';
						}
					}
					break;
				}
			}
		}
		$type = ($this->_type == 'xml') ? 'xml' : 'json';
		$this->response($data, $type);
	}
}
