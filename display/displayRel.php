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
    <style type="text/css">
        body {
            font-family: 'Courier New', 'Heiti SC', 'Microsoft Yahei';
            font-weight: bold;
            font-size: 15px;
        }
        .tdClass{
        }
        .imgClass{
            border: 1px solid #DDD;
            /*padding: 10px;*/

        }
        .imgNameClass{
        }
        .imgsDiv{
           margin-left:  8px;

        }
    </style>
</head>
<body>
<table id="displayTable" style="width:100% ;display: none" ><tbody></tbody></table>
</body>
<?php
echo  "<script type='text/javascript'>var urlsStr = '".$_GET['urls']."';</script>";

?>

<script type="text/javascript">
    var urls = urlsStr.split(",");
    function addTr(startIndex){
        var tr = $("<tr></tr>").attr('width','100%');
        for(var j= 0;j<3&&(startIndex+j<urls.length);j++){
            var img = $("<img class=\"imgClass\" src=\""+urls[startIndex+j]+"\"  />" );
            var imgName = urls[startIndex+j].split("/").slice(-1)[0];

            imgName = imgName.substr(0,imgName.length-4);
            tr.append($("<td class='tdClass' valign='top'></td>").attr('width',tdWidth).append($("<div class='imgsDiv'></div>").append($("<p class='imgNameClass'>"+imgName+":</p>")).append(img).append($("<br/>"))));

        }
        $("#displayTable").append(tr);
    }
        var tdWidth = Math.floor(document.documentElement.scrollWidth/3);
        var trNum = Math.ceil(urls.length/3);
        for(var i= 0;i<trNum;i++){
            addTr(i*3);
        }
    $(window).load(function(){
        var imgs = $("img");
        for(var p =0;p<imgs.length;p++){
            $(imgs[p]).css("width","100%");
        }
        $("#displayTable").attr('style','width:100% ;display: block');
    });
</script>

</html>