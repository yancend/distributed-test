<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dongyancen
 * Date: 13-12-12
 * Time: 下午5:17
 * To change this template use File | Settings | File Templates.
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

    <title></title>
    <script type="text/javascript" src="lib/jquery-1.5.1.js"></script>
</head>
<body>
</body>
<?php
echo  "<script type='text/javascript'>var urlsStr = '".$_GET['urls']."';</script>";

?>

<script type="text/javascript">
    var urls = urlsStr.split(",");
//    function addTr(startIndex){
//        var tr = $("<tr></tr>");
//        $("#displayTable").append(tr);
//    }
    //    var tdWidth = document.documentElement.scrollHeight/3;
    //    var trNum = Math.floor(urls.length/3);
    //    for(var i= 0;i<trNum;i++){
    //        addTr(i*3);
    //    }

    var singleResultDiv = $("<div class='singleRel' style='text-align: center'></div>");


    var imgsDiv = $("<div class='imgsDiv'></div>");

    //把该case的图片放到对应div
    for(var i=0;i< urls.length;i++){
        imgsDiv.append($("<img src=\""+urls[i]+"\"/>"));
        imgsDiv.append($("<br/>"));
    }
    singleResultDiv.append(imgsDiv);
    //加到总的展示div
    document.body.appendChild(singleResultDiv[0]);


    /*var  s = "";
     s += "\r\n网页可见区域宽："+ document.body.clientWidth;
     s += "\r\n网页可见区域高："+ document.body.clientHeight;
     s += "\r\n网页可见区域宽："+ document.body.offsetWidth  +" (包括边线和滚动条的宽)";
     s += "\r\n网页可见区域高："+ document.body.offsetHeight +" (包括边线的宽)";
     s += "\r\n网页正文全文宽："+ document.body.scrollWidth;
     s += "\r\n网页正文全文高："+ document.body.scrollHeight;
     s += "\r\n网页被卷去的高："+ document.body.scrollTop;
     s += "\r\n网页被卷去的左："+ document.body.scrollLeft;
     s += "\r\n网页正文部分上："+ window.screenTop;
     s += "\r\n网页正文部分左："+ window.screenLeft;
     s += "\r\n屏幕分辨率的高："+ window.screen.height;
     s += "\r\n屏幕分辨率的宽："+ window.screen.width;
     s += "\r\n屏幕可用工作区高度："+ window.screen.availHeight;
     s += "\r\n屏幕可用工作区宽度："+ window.screen.availWidth;
     s += "\r\n你的屏幕设置是 "+ window.screen.colorDepth +" 位彩色";
     s += "\r\n你的屏幕设置 "+ window.screen.deviceXDPI +" 像素/英寸";*/
</script>
<table id="displayTable" style="width:100%"><tbody></tbody></table>
</html>