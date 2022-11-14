<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:10:56
 * Github:https://github.com/Andwxa
 * @Description: 消息中心
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'messageCenter';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true);
$rs = $mysqls_interface->selectUserByUIdMysql($uId);
$rs = $rs ->fetch_array();
$userGroup = $rs['group'];
// 消息条数
$rowsUIdCount = $mysqls_interface->selectMessageCountByuIdMysql($uId,1);
// 组公告条数
$rowsGroupCount = $mysqls_interface->selectMessageCountByGroupMysql($userGroup);
// 系统公告条数
$rowsAllCount = $mysqls_interface->selectMessageByAllCountMysql();
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
                $("#messageCenter").addClass("active");
            });
        </script>
    </head>
    <body style="background-color: #FAFAFA;">
        <!-- 导航栏 -->
        <?php include_once('utility\navigator.php');?>
        <!--主体-->
        <div class="row">
            <!--左侧-->
            <?php include_once('utility\sidebar.php');?>
            <!--右侧-->
            <div class="col-xs-9">
                <div class="jumbotron"><h1>消息中心</h1></div>
                <div>
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active"><a href="#uId" data-toggle="tab">消息 <span class='badge'><?php echo $rowsUIdCount?></span></a></li>
                        <li><a href="#group" data-toggle="tab">组消息 <span class='badge'><?php echo $rowsGroupCount?></span></a></li>
                        <li><a href="#all" data-toggle="tab">公告 <span class='badge'><?php echo $rowsAllCount?></span></a></li>
                    </ul>
                </div>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade in active" id="uId">
                        <div><a class="text-right" href="./processing/process_messageCenter.php">读取全部</a></div>
                        <div class="table-responsive" style="height: 500px;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>内容</th>
                                        <th>发布时间</th>
                                        <th>控制</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $rowsUId = $mysqls_interface->selectMessageByuIdMysql($uId,1);
                                if ($rowsUId->num_rows > 0) {
                                    while($row = $rowsUId->fetch_assoc()) {
                                        $mId = $row['mId'];
                                        $read = $row['read'];
                                        $mcId = $row['mcId'];
                                        $time = $row['time'];
                                        $content =  $mysqls_interface->selectMessageContentMysql($mcId);
                                        $content = $content->fetch_array();
                                        $content = $content['content'];
                                        if ($read==1) 
                                            $isRead = '已读';
                                        else
                                            $isRead = '未读';
                                        echo "
                                    <tr>
                                        <td>$content</td>
                                        <td>$time</td>
                                        <td>
                                            <form method='post' action='./processing/process_messageCenter.php?id=$mId'><input class='btn btn-default' type='submit' name='read' value='$isRead'/></form>
                                        </td>
                                    </tr>";

                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="group">
                        <div class="table-responsive" style="height: 500px;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>内容</th>
                                        <th>发布时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            $rowsGroup = $mysqls_interface->selectMessageByGroupMysql($userGroup);
                            if ($rowsGroup->num_rows > 0) {
                                while($row = $rowsGroup->fetch_assoc()) {
                                    $mcId = $row['mcId'];
                                    $time = $row['time'];
                                    $content =  $mysqls_interface->selectMessageContentMysql($mcId);
                                    $content = $content->fetch_array();
                                    $content = $content['content'];
                                    echo "
                                    <tr>
                                        <td>$content</td>
                                        <td>$time</td>
                                    </tr>";
                                }
                            }
                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="all">
                        <div class="table-responsive" style="height: 500px;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>内容</th>
                                        <th>发布时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            $rowsAll = $mysqls_interface->selectMessageByAllMysql();
                            if ($rowsAll->num_rows > 0) {
                                while($row = $rowsAll->fetch_assoc()) {
                                    $mcId = $row['mcId'];
                                    $time = $row['time'];
                                    $content =  $mysqls_interface->selectMessageContentMysql($mcId);
                                    $content = $content->fetch_array();
                                    $content = $content['content'];
                                    echo "
                                    <tr>
                                        <td>$content</td>
                                        <td>$time</td>
                                    </tr>";
                                }
                            }
                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>