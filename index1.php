<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 9:58
 */
require_once "vendor/autoload.php";

use Medoo\Medoo;
use QL\QueryList;

$options = [
    'database_type' => 'mysql',
    'database_name' => 'crawl',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
];
global $database;
$database = new Medoo($options);

function index()
{
    header('content-type:text/html;charset=utf-8');
    set_time_limit(0);
    echo '爬虫开始...' . "<br/>";
    $i = $_GET['page'];
    /*********** 首先获取列表信息,循环20次  ***********/
    echo "开始爬取第{$i}页" . "<br/>";
    $url = "http://blog.jobbole.com/category/career/page/{$i}/";
    echo "url为:" . $url . "<br/>";
    $list_rule = [
        'article_title' => ['#archive .archive-title', 'text'],//
        'article_detail_url' => ['#archive .post-thumb > a:first-child', 'href'],
        'article_intro' => ['#archive .excerpt', 'text'],//
        'article_thumb' => ['#archive .post-thumb > a > img', 'src'],
        'article_ctime' => ['#archive .post-meta > p:first-child', 'text', '-a'],//
    ];
    $list_data = crawl_data($url, $list_rule);

    echo '<pre>';
    var_dump($list_data);

    echo "爬虫结束..." . "<br/>";
    var_dump('完成');
    die;
}

/**
 * 根据url爬取数据
 */
function crawl_data($url, $rule)
{
    $data = QueryList::Query($url, $rule)->data;
    return $data;
}

/**
 * 随机选取user_id
 */
function choose_a_uid()
{
    /*$datas = $GLOBALS['database']->select("user", ["user_id"]);
    $max_key = sizeof($datas) - 1;
    $min_key = 0;
    $key = rand($min_key, $max_key);
    $rand_user_id = $datas[$key]['user_id'];*/
    $i = 0;
    $rand_user_id = $i++;
    return $rand_user_id;
}

/**
 * 正则匹配日期
 */
function find_date($string)
{
    $result = preg_match('/\d{4}\/\d{1,2}\/\d{1,2}/', $string, $matches);
    if ($result) {
        return $matches[0];
    } else {
        return '2017/08/01';
    }
}

/**
 * 转码
 */
function my_iconv($str)
{
    return iconv('utf-8', 'gbk', $str);
}

index();