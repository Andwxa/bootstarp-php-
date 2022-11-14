<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:09:41
 * Github:https://github.com/Andwxa
 * @Description: 系统审核文章管理
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'articleReviewManagement';
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
                $("#articleReviewManagement").addClass("active");
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
                <div class="jumbotron"><h1>审核文章</h1></div>
                <!--评论信息-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>栏目</th>
                                <th>昵称</th>
                                <th>标题</th>
                                <th>控制</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // 获得要审核的文章
                            $reviewArticle = $mysqls_interface->selectReviewArticleByrAIdMysql();
                            if ($reviewArticle) {
                                // 如果有评论数据
                                if ($reviewArticle->num_rows > 0) {
                                    while ($row = $reviewArticle->fetch_assoc()) {
                                        // 文章rAId
                                        $rAId = $row['rAId'];
                                        // 栏目
                                        $cId = $row['cId'];
                                        $column = $mysqls_interface->selectColumnByIdOrNameMysql($cId);
                                        // 用户id
                                        $uId = $row['uId'];
                                        $nickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($uId);
                                        // 文章标题
                                        $title = $row['title'];
                                        echo "
                                        <tr>
                                        <td>$column</td>
                                        <td>$nickname</td>
                                        <td><a href='./articleReview.php?rAId=$rAId'>$title</a></td>
                                        <td>
                                            <form method='post' action='./processing/process_articleReview.php?rAId=$rAId'>
                                                <input class='btn btn-default' type='submit' name='dele' value='驳回'/>
                                                <input class='btn btn-default' type='submit' name='update' value='通过'/>
                                            </form>
                                        </td>
                                    </tr>
                                                ";
                                    }
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>