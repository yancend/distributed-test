<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dongyancen
 * Date: 13-12-3
 * Time: 下午8:02
 * To change this template use File | Settings | File Templates.
 */
error_reporting(0);
require_once 'config.php';
function saveConfigFile(){
    $rootPath = Config::$rootPath;
    $fileName = 'config.txt';

    $filePath =$rootPath.'src/'.$_POST['testSuiteName'];
    if(!file_exists($filePath)){
        mkdir($filePath);
    }
    //将ip地址和浏览器信息写入配置文件
//    $str = "REMOTE_ADDR:".$_SERVER["REMOTE_ADDR"]."\r\n";
//    $str .="_ENV['COMPUTERNAME']:".$_ENV['COMPUTERNAME']."\r\n";
    $str = '';
    foreach($_POST["browsers"] as $b){
        $str .=$b.",";
    }
    $str = substr($str,0,-1);
    $fh = fopen($filePath.'/'.$fileName, "w");
    fwrite($fh, $str);
    fclose($fh);
}
saveConfigFile();
