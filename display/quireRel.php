<?php
/**
 * Created by PhpStorm.
 * User: dongyancen
 * Date: 13-12-20
 * Time: 下午7:14
 */
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

        <title></title>
        <script type="text/javascript" src="lib/jquery-1.5.1.js"></script>
        <style>
            body {
                font-family: 'Courier New', 'Heiti SC', 'Microsoft Yahei';


            }
            .pTextClass{
                font-weight: bold;
                font-size: 15px;

            }
            button {
                border: 1px solid #666;
                margin-right: 50px;
                padding: 5px 15px;
                -moz-border-radius: 8px;
                -webkit-border-radius: 8px;
                cursor: pointer;
                font-size: 12px;
                font-family: 'Courier New', 'Heiti SC', 'Microsoft Yahei';
                font-weight: bold;
            }
        </style>
    </head>
    <body>
<!--    left:400px; position:relative;-->
    <div id="main" style="left:400px; position:relative; "><p  class="pTextClass">请输入要查询的测试集名称:
            <input id="testSuite"
                   name="testSuiteName" type="text"/><button style="margin-left: 8px" onclick="quireResult()"> 点击查询结果</button></p>
        <div id='testResult'>
            <p id='noRels'> </p>
        </div></div>

    </body>
    <script type="text/javascript">
        var suiteName;
        var dirs = '.,..,config.txt';
        var rels = [];
        var response;

    </script>
<?php
echo  "<script type='text/javascript'> suiteName = '".$_GET['defaultTestSuite']."';if(suiteName){document.getElementById('testSuite').value = suiteName; }</script>";

?>
<script>
    $('#main').width($('#main').width()-450);
    function rmRel(msgStr){
        rels = [];
        if($("#displayResluts").length){
            $("#displayResluts")[0].parentNode.removeChild($("#displayResluts")[0]);
        }
        $("#noRels").text(msgStr);
    }
    function changeText(b,rel){
        if(rel.css('display')=='block')
            $(b).text('展开结果');
        else
            $(b).text('折叠结果');

    }
    function displayResult(newDir,urlsStr){

        var singleResultDiv = $("<div class='singleRel'></div>");
        //本页的展示变成可收放的div
        //每个用例名字后面加一个链接,链接的页面展示这个用例的所有图片
        var index = rels.length ;
        var p = $("<p class='pTextClass'></p>").text(newDir+' :').append($("<button onclick=\"changeText(this,rels["+index+"]);rels["+index+"].slideToggle(\'slow\');\">折叠/展开结果</button>"));
        singleResultDiv.append(p);
        var casesDiv = $("<div class='casesDiv'></div>");
        rels.push(casesDiv);
        //默认只展示最近一天的结果,其它隐藏
        casesDiv.attr('style','text-align:left; display: '+($(".singleRel").length?'none':'block'))  ;
        casesDiv.attr('width','100%')  ;
        singleResultDiv.find("button").attr('display',$(".singleRel").length?'none':'block')  ;
        singleResultDiv.find("button").text(($(".singleRel").length?'展开结果':'折叠结果'));

        //把该case的放到对应div
        for(var d in urlsStr){
            casesDiv.append($("<div></div>").append($("<div style='width : 100px;'></div>").text(d+' :')).append($("<p style='text-indent: 4em;margin-top: 0px'>").append($("<a  style='' href=\"./displayRel.php?urls="+urlsStr[d]+"\" target=\"_blank\" >在新页面展示结果</a>"))));
        }
        casesDiv.append($("<hr style='float:left;' width='500px' />")).append($("<br/>"));
        singleResultDiv.append(casesDiv);
        //加到总的展示div
        $("#displayResluts").append(singleResultDiv);
    }
    function displayResults(newDirs){
// 刷新时先把原来的div删掉 ,rels清空
        $("#testResult").attr('style',"display: block");
        rmRel('');
        $("#testResult").append($("<div id='displayResluts'></div>"));

        for(var newDir in newDirs){
            displayResult(newDir,newDirs[newDir]);
        }
    }
    function checkResult(ifFirstQ){

        $.ajax({
                url:'checkDir.php',
                type:'post',
                data:{'dirs':dirs,'suiteName':suiteName},
                success:function (msg) {
                    if(!msg||!eval("("+msg+")")){//没有返回结果或者返回false,清空展示区
                        if(ifFirstQ){rmRel("没有返回查询结果");}
                        return;
                    }
                    response = eval("("+msg+")");
                    dirs = response['dirs'];
                    displayResults(response['imgInfo']);
                },
                error:function (xhr, msg) {
                    alert("error 2");
                }
            });
    }
    function quireResult(){
        //清空记录
        dirs = '.,..,config.txt';
        suiteName = document.getElementById('testSuite').value ;
        if(suiteName){
            checkResult(true);
            window.setInterval(function(){
                checkResult(false);
            },5000);
        }else{
            rmRel('没有返回查询结果');
        }
    }
</script>