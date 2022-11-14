<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:09:50
 * Github:https://github.com/Andwxa
 * @Description: 系统公告管理
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'bulletinManagement';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true,true);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>文化交流</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.8">
        <link rel="stylesheet" type="text/css" href="./bootstrap-3.4.1/dist/css/bootstrap.min.css">
        <script src="./bootstrap-3.4.1/dist/jq/jquery-3.6.0.min.js"></script>
        <script src="./bootstrap-3.4.1/dist/js/bootstrap3_3_7.main.js"></script>
        <script>
            $(document).ready(function (){
                // 导航栏和侧边栏选中颜色
                $("#tagUserCenter").addClass("active");
                $("#bulletinManagement").addClass("active");
            });
        </script>
    </head>
    <body style="background-color: #FAFAFA;">
        <!-- 导航栏 -->
        <?php include_once('utility\navigator.php');?>
        <!--主体-->
        <div class="row">
            <!--左侧-->
            <?php include_once('utility\adminSidebar.php');?>
            <!--右侧-->
            <div class="col-xs-9">
                <div class="jumbotron"><h1>发布公告</h1></div>
                <!--发表公告-->
                <div class="row text-center">
                    <form class="form-inline" method="post" action="./processing/process_bulletinManagement.php" onsubmit="return addBulletin()">
                        <div class="col-xs-6 text-right">
                            <select class="form-control" name="identity" id="identity">                                
                                <option selected="selected" value="user">全体用户</option>
                                <option value="only">单个用户</option>
                                <option value="admin">全体管理</option>
                                <option value="all">全体</option>                            
                            </select>
                        </div>
                        <div class="col-xs-6 text-left">
                            <input type="text" class="form-control" style="display: none;" id="userId" name="userId" value="" placeholder="用户id">
                        </div>
                        <div class="col-xs-12" style="margin-top: 20px;">
                            <textarea class="form-control" id="content" name="content" style="width: 400px;" rows="4" placeholder="如果为空则使用历史公告，如果不为空则使用新的公告，新公告内容不能大于100个字符！" ></textarea><br>
                        </div>
                        <input type="submit" class="btn btn-default" style="margin: 5px;" value="--发表公告--">
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        //发表公告检查
        function addBulletin() {
            if ($("#bulletin option:selected").val() == '0' && $("#content").val() == "") {
                alert('请选择历史公告或者创建新的公告！');
                console.log('请选择历史公告或者创建新的公告！');
                return false;
            }
            if ($("#content").val().length > 100) {
                alert('新的公告不能大于100个字符！');
                console.log("新的公告不能大于100个字符！");
                return false;
            }
            return true;
        }
        $(document).ready(function() {
            $("#identity").click(function(){  
                if ($("#identity option:selected").text()=='单个用户') {
                    document.getElementById('userId').style.display='inline';
                }else{
                    document.getElementById('userId').value ='';
                    document.getElementById('userId').style.display='none';
                }
            });
        });
         
      </script>
</html>