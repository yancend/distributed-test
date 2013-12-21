<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dongyancen
 * Date: 13-12-4
 * Time: 下午5:18
 * To change this template use File | Settings | File Templates.
 */
error_reporting(0);
require_once 'config.php';
$rootPath = Config::$rootPath.'pics/'.$_POST['suiteName'];
//去掉字符串结尾的逗号
function removeLastComma($str){
    if(eregi(",$", $str))
        $str = substr($str,0,-1);
    return $str;
}
//根据指定路径和图片名称生成该路径下所有图片的url
function getImages($path,$imageNames){
    $imageUrls = '';
    foreach($imageNames as $n){
        if(eregi(implode("$|", Config::$imageTypes)."$", $n)){
            $imageUrls .= $path.'/'.$n.',';
        }
    }
    $imageUrls = removeLastComma($imageUrls);
    return $imageUrls;
}
function sortByDay($a, $b)
{

    $timeA = strtotime($a["date"]);
    $timeB = strtotime($b["date"]);

    if ($timeA == $timeB) return 0;
    return ($timeA < $timeB) ? 1 : -1;
}
function getReturn($dirsNow){
    global $rootPath;
    $imgUrls = array();
    $dirsInfo =array();

    foreach($dirsNow as $d){

        if($d=='.'||$d=='..')continue;


        $dirsInfo[$d] = array("urls"=>getImages($rootPath.'/'.$d,scandir($rootPath.'/'.$d)),"day"=>date("Y-m-d",filemtime($rootPath.'/'.$d)),"name"=>$d,"date"=>date("Y-m-d h:i:s",filemtime($rootPath.'/'.$d)));

    }
    usort($dirsInfo, "sortByDay");
    foreach($dirsInfo as $key => $value){

        if(!array_key_exists($dirsInfo[$key]["day"],$imgUrls)){
            $imgUrls[$dirsInfo[$key]["day"]]= array("day"=>$dirsInfo[$key]["day"],"caseImgUrls"=>array());
        }
        $imgUrls[$dirsInfo[$key]["day"]]["caseImgUrls"][$dirsInfo[$key]["name"]] = $dirsInfo[$key]["urls"];
    }


    //$strReturn 保存现有的所有路经(用于更新路径信息),和本次新增的所有路径及路径下的图片url
    $strReturn = '{"dirs":"'.implode(',', $dirsNow).'","imgInfo":{';
    foreach($imgUrls as $key => $value){

        $strReturn .= '"'.$key.'":{';
        foreach($value["caseImgUrls"] as $caseName => $url){
            $strReturn .='"'.$caseName.'":"'.$url.'",';
        }
        $strReturn .='},';
    }
    $strReturn = removeLastComma($strReturn).'}}';
    echo $strReturn;
}
function checkNewDir(){
    global $rootPath;
    if(!file_exists($rootPath))
    {
        echo "false";
        return ;
    }
    $dirsOld = explode(",", $_POST['dirs']);//路径下原有的文件夹
    $dirsNow = scandir($rootPath);//路径下现有的所有文件夹
//如果有新增的路径,才读取内容
    $flag = false;
    foreach($dirsNow as $d){
        if(!in_array($d,$dirsOld)){
            $flag = true;
            break;
        }
    }
    if($flag){
        getReturn($dirsNow);
    }else
    {
        echo "false";
    }
}
checkNewDir();




