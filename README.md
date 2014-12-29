云印南开
=================
更方便的校园打印 [print.nkumstc.cn](http://print.nkumstc.cn)
----------------------------

## Bugs
* 不要用英文
* 现在下载是通过文件名处的连接，并不能将文件状态从1set到2
* 低版本浏览器适配
* 导航栏目

## Features
* 打印店方面需要显示份数和单双面
* 异步通知（无刷新）



## 团队主要人员及分工
#### 1.项目发起人
[李旭昇](https://github.com/jeffli678)
#### 2.指导和系统设计
[NewFuture](https://github.com/New-Future)
#### 3.数据库设计
[牛亮](https://github.com/wangxiaodiu) [梁崇军]()
#### 4.后端实现
[孙卓豪]() [牛亮](https://github.com/wangxiaodiu) [梁崇军]() [NewFuture](https://github.com/New-Future)
#### 5.前端实现
[杜晓唐]() [孙卓豪]() [王博]()
#### 6.打印店客户端
[宋剑超]() [陈路宽]()
#### 7.测试维护
[刘安]() [王雨晴]()
#### 8.服务器部署
[赵泽坤]()
#### 9.图形设计
[陈超]()

##框架目录
>
```
|─index.php       入口文件-->Print
|─api.php         api接口入口文件-->API
|─Common          后端公共模块目录
|   |─Common		        公共库目录
|	|	|─Urp.class.php        	urp验证文件
|   |	└─function.php       	公共函数文件
|   └─Conf        			公共配置目录
|		└─config.php        		公共配置文件
|
|─Print			  云印南开系统项目目录
|	|─Home		  	普通用户模块目录
|	|	|─Conf        	配置文件目录
|	|	|─Common      	公共函数目录
|	|	|─Controller  	控制器目录
|	|	|	|─IndexController.class.php		默认控制器（首页）
|	|	|	|─UserController.class.php		用户控制器
|	|	|	└─FileController.class.php		文件管理控制器
|	|	|─Model  		模型目录
|	|	|	|─...... 					各种模型
|	|	|	└─UserModel.class.php 		用户模型
|	|	└─View        	视图目录
|	|
|	└─Printer		  	打印店管理模块目录
|		|─Conf        	配置文件目录
|		|─Common      	公共函数目录
|		|─Controller  	控制器目录
|		|	|─PrinterController.class.php		打印店控制器
|		|	└─FileController.class.php			文件管理控制器
|		|─Model  		模型目录
|		└─View        	视图目录
|
|─API		  云印南开API模块
|	|─Conf        	配置文件目录
|	|─Common      	公共函数目录
|	|─Controller  	控制器目录
|	|	|─NotificationController.class.php	消息接口控制器
|	|	|─FileController.class.php			文件接口控制器
|	|	└─IndexController.class.php			认证和令牌管理制器
|	└─Model  		模型目录
|─Public          前端资源文件目录
|─Uploads         上传文件目录（可写，不同步）
|─Runtime         运行时缓存目录（可写，不同步）
└─ThinkPHP       框架目录(框架核心资源不用修改)
```
>>



## 仓库分支说明
	包含web端，客户端，数据 三个稳定分支
1. [master](https://github.com/nkumstc/print/tree/master) web端源码仓库分支
2. [DB](https://github.com/nkumstc/print/tree/DB) 	 数据库设计源码仓库分支
3. [printer](https://github.com/nkumstc/print/tree/printer) 打印店客户端和源码仓库分支

##其他

[API相关说明](https://github.com/nkumstc/print/blob/master/API.md)

本项目由南开大学学生发起，免费开源，同时欢迎所有人贡献代码和想法

项目起步阶段由[南开大学微软技术俱乐部](http://nkumstc.cn)提供支持