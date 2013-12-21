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
    </head>
    <body>
    <div style="left:300px; position:relative;  width: 1000px"><p>请输入要查询的测试集名称:
            <input id="testSuite"
                   name="testSuiteName" type="text"/><button onclick="quireResult()"> 点击查询结果</button></p>
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
    function rmRel(msgStr){
        rels = [];
        if($("#displayResluts").length){
            $("#displayResluts")[0].parentNode.removeChild($("#displayResluts")[0]);
        }
        $("#noRels").text(msgStr);
    }

    function displayResult(newDir,urlsStr){

        var singleResultDiv = $("<div class='singleRel'></div>");
        //本页的展示变成可收放的div
        //每个用例名字后面加一个链接,链接的页面展示这个用例的所有图片
        var index = rels.length ;
        var p = $("<p></p>").text(newDir+' :').append($("<button onclick=\"rels["+index+"].slideToggle(\'slow\');\">折叠/展开结果</button>"));
        singleResultDiv.append(p);
        var casesDiv = $("<div class='casesDiv'></div>");
        rels.push(casesDiv);
        //默认只展示最近一天的结果,其它隐藏
        casesDiv.attr('style','display: '+($(".singleRel").length?'none':'block'))  ;

        //把该case的放到对应div
        for(var d in urlsStr){
            casesDiv.append($("<div></div>").append($("<div style='width : 100px;'></div>").text(d+' :')).append($("<a href=\"./displayRel.php?urls="+urlsStr[d]+"\" target=\"_blank\" >在新页面展示结果</a>")));
        }

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