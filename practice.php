<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 14:30
 */
header("content-type:text/html;charset=gbk");

$str = "宋sone";
//$str = iconv('utf-8', 'GB2312', $str);
//$str = iconv('utf-8', 'gbk', $str);
echo $str;