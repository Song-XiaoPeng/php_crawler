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
    set_time_limit(0);
    echo my_iconv('爬虫开始...') . "\n";
    /*********** 首先获取列表信息,循环20次  ***********/
    for ($i = 1; $i < 48; $i++) {
        echo iconv('utf-8', 'gbk', "开始爬取第{$i}页") . "\n";
        $url = "http://blog.jobbole.com/category/career/page/{$i}/";
        echo my_iconv("url为:") . $url . "\n";
        $list_rule = [
            'article_title' => ['#archive .archive-title', 'text'],
            'article_detail_url' => ['#archive .post-thumb > a:first-child', 'href'],
            'article_intro' => ['#archive .excerpt', 'text'],
            'article_thumb' => ['#archive .post-thumb > a > img', 'src'],
            'article_ctime' => ['#archive .post-meta > p:first-child', 'text', '-a'],
        ];
        $list_data = crawl_data($url, $list_rule);
        /*********** 在循环获取详情,并写入数据库  ***********/
        foreach ($list_data as $key => $value) {
//            echo my_iconv("开始获取<<{$list_data[$key]['article_title']}>>的详情...") . "\n";
            $deatil_rule = [
                'article_content' => ['.entry', 'html'],
            ];
            $detail_data = crawl_data($value['article_detail_url'], $deatil_rule);
            /*********** 组合数据  ***********/
            $db_data['article_title'] = $list_data[$key]['article_title'];
            $db_data['article_thumb'] = $list_data[$key]['article_thumb'];
            $db_data['article_intro'] = $list_data[$key]['article_intro'];
            $db_data['article_content'] = $detail_data[0]['article_content'];
            /*********** 从user表随机分配user_id  ***********/
            $db_data['article_uid'] = choose_a_uid();
            /*********** 使用正则过滤出日期  ***********/
            $db_data['article_ctime'] = find_date($list_data[$key]['article_ctime']);
            /*********** 随机生成浏览次数,min=100,max=1000  ***********/
            $db_data['article_views'] = rand(100, 1000);
            /*********** 写入数据库  ***********/
//            echo my_iconv("开始把<<{$list_data[$key]['article_title']}>>写入数据库...") . "\n";
            $res = $GLOBALS['database']->insert('article', $db_data);
            if ($res) {
//                echo my_iconv("<<{$list_data[$key]['article_title']}>>");
                echo my_iconv("写入数据库成功!") . "\n";
            } else {
//                echo my_iconv("<<{$list_data[$key]['article_title']}>>");
                echo my_iconv("写入数据库失败!") . "\n";
                var_dump(my_iconv('写入失败!'));
                die;
            }
        }
    }
    echo my_iconv("爬虫结束...") . "\n";
    var_dump(my_iconv('完成'));
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