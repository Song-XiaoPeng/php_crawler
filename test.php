<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 9:58
 */
//配置中国镜像，解决安装速度慢的问题 composer config -g repo.packagist composer https://packagist.phpcomposer.com

use Medoo\Medoo;
use QL\QueryList;

require_once "vendor/autoload.php";

//采集某页面所有的图片
/*
 * 3.* 版本用法
 * $data = QueryList::Query('http://study.163.com/course/introduction/1004317010.htm', array(
//采集规则库
//'规则名' => array('jQuery选择器','要采集的属性'),
    'image' => array('img', 'src')
))->data;*/
/*
 * 4.* 版本用法
 *
 * */
$url1 = 'http://cms.querylist.cc/bizhi/453.html';

/**
 *如访问一个不存在的网址会报一下错误
 * Fatal error: Uncaught GuzzleHttp\Exception\ClientException: Client error: `GET http://cms.querylist.cc/bizhi/453.html` resulted in a `404 Not Found` response: <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"> <html> <head><title>404 Not Found</title></head> <body bgcolor="wh (truncated...) in D:\htdocs\php_crawler\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php:113 Stack trace: #0 D:\htdocs\php_crawler\vendor\guzzlehttp\guzzle\src\Middleware.php(65): GuzzleHttp\Exception\RequestException::create(Object(GuzzleHttp\Psr7\Request), Object(GuzzleHttp\Psr7\Response)) #1 D:\htdocs\php_crawler\vendor\guzzlehttp\promises\src\Promise.php(203): GuzzleHttp\Middleware::GuzzleHttp\{closure}(Object(GuzzleHttp\Psr7\Response)) #2 D:\htdocs\php_crawler\vendor\guzzlehttp\promises\src\Promise.php(156): GuzzleHttp\Promise\Promise::callHandler(1, Object(GuzzleHttp\Psr7\Response), Array) #3 D:\htdocs\php_crawler\vendor\guzzlehttp\promises\src\TaskQueue.php(47): GuzzleHttp\Promise\Promise::GuzzleHttp\Promise\{closure}() #4 D:\ht in D:\htdocs\php_crawler\vendor\guzzlehttp\guzzle\src\Exception\RequestException.php on line 113
 */

$url2 = 'http://study.163.com/course/introduction/1004317010.htm';
$data = QueryList::get($url2)->find('img')->attrs('src');
echo '<pre>';
//打印结果
print_r($data->all());

$options = [
    'database_type' => 'mysql',
    'database_name' => 'test',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
];
$dao = new Medoo($options);
$res = $dao->select('users','*');
print_r($res);
