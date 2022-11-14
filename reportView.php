<?php
/*
 * @Date: 2022-10-01 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:12:07
 * Github:https://github.com/Andwxa
 * @Description: 举报内容显示
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'reportView';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
// 获得类型
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$type or !$id) {
    $_SESSION['showInformation'] = ["./reportManagement.php",'3','error','get数据传入失败，请联系管理人员！'];
    die(header("Refresh:0;url=./showInformation.php"));
}
$mysqls_interface->updateInforOfCompletemMysql($type,$id,1);
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
                // 导航栏选中颜色
                $("#tagUserCenter").addClass("active");
            });
        </script>
        <style>
            .autoImg img{width: 100%;height: auto;}
        </style>
    </head>
    <body style="background-color: #FAFAFA;">
        <div class="container">
            <!--导航栏-->
            <?php include_once('utility\navigator.php');?>
            <!--面包屑-->
            <ul class="breadcrumb" style="margin-top: -20px;">
                <li><a href="./reportManagement.php">返回举报管理</a></li>
            </ul>
            <!--头部-->
            <div class="text-center">
                <form method="post" action="./processing/process_reportView.php?<?php echo 'type='.$type.'&id='.$id ?>">
                    <input type="submit" class="btn btn-default" name="success" value="成功">
                    <input type="submit" class="btn btn-default" name="failure" value="驳回">
                </form>
            </div>
            <!--中部-->
            <div class="row">
                <!-- 内容 -->
                <div class="col-sm-12 blog-main">
                    <div class="blog-post">
                        <!--文章内容-->
                        <div class="autoImg">
                            <?php
                            $rs = $mysqls_interface->selectInformMysql($type,$id);
                            while ($row = $rs->fetch_assoc()) {
                                $InformUId = $row['uId'];
                                $InformNickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($InformUId);
                                $InformTime = $row['time'];
                                $InformCommint = $row['commint'];
                                echo "<blockquote>
                                    <p>举报昵称:$InformNickname</p>
                                    <p>举报时间:$InformTime</p>
                                    <p>举报内容:$InformCommint</p>
                                </blockquote>";
                            }
                            ?>
                        </div>       
                    </div><!-- /.blog-post -->
                </div><!-- /.内容 -->
            </div><!-- /.中部 -->
        </div>
        <!--共享底部-->
        <?php include_once('utility\sharedFoot.php');?>
    </body>
</html>