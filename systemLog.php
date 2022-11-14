<?php
/*
 * @Date: 2022-10-24 08:07:53
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:12:39
 * Github:https://github.com/Andwxa
 * @Description: 系统日志
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'systemLog';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true,true);
// 当前页
$page = 0;
$page = $_GET['page'];
// 获得页总数
$allPage = $mysqls_interface->selectJournalCountMysql();
$allPage = ceil($allPage / 12);
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
                $("#systemLog").addClass("active");
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
                <div class="jumbotron"><h1>系统日志</h1></div>
                <!--评论信息-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>昵称</th>
                                <th>事件</th>
                                <th>时间</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // 获得举报数据
                            $rs = $mysqls_interface->selectJournalMysql('DESC','','','',$page*12);
                            if ($rs) {
                                // 如果有举报数据
                                if ($rs->num_rows > 0) {
                                    while ($row = $rs->fetch_assoc()) {
                                        // incident
                                        $incident = $row['incident'];
                                        // uId
                                        $uId = $row['uId'];
                                        $nickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($uId);
                                        // time
                                        $time = $row['time'];
                                        echo "
                                        <tr>
                                            <td>$nickname</td>
                                            <td>$incident</td>
                                            <td>$time</td>
                                        </tr>";
                                    }
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                    <!--翻页组件-->
                    <div>
                        <?php
                            // 携带额外的信息
                            $carryInformation = "";
                            include_once('utility\pageComponent.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>