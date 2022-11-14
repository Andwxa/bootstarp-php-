<?php
/*
 * @Date: 2022-10-05 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:09:29
 * Github:https://github.com/Andwxa
 * @Description: 显示文章
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'articleReview';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
// 获得文章id
$rAId = isset($_GET['rAId']) ? $_GET['rAId'] : '';
// 根据文章id获得文章信息
$rs = $mysqls_interface->selectReviewArticleByrAIdMysql($rAId);
$rs = $rs ->fetch_array();
$textTitle = $rs['title'];
$textCategory = $rs['cId'];
$textUid = $rs['uId'];
$textTime = $rs['time'];
$textContent = $rs['content'];
// 根据文章主用户id获得用户信息
$rs = $mysqls_interface->selectUserAndUserInformationMysql($textUid);
$rs = $rs ->fetch_array();
$articleAvatar = $rs['avatar'];
$articleName = $rs['nickname'];
$articlePhone = $rs['phone'];
$articleQq = $rs['QQ'];
$articleWechat = $rs['wechat'];
$articleUnit = $rs['unit'];
// 获得栏目名称
$textCategory = $mysqls_interface->selectColumnByIdOrNameMysql($textCategory);
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
                <li><a href="./articleReviewManagement.php">审核</a></li>
                <li><?php echo $textCategory ?>主题</li>
                <li class="active"><?php echo $textTitle?></li>
            </ul>
            <!--头部-->
            <div class="container">
                <div class="blog-header">
                    <?php
                    echo "
                    <h1 class='blog-title'>$textTitle</h1>
                    <p class='lead blog-description'>发布时间:$textTime</p>
                    ";
                    ?>
               </div>
            </div>
            <!--中部-->
            <div class="row">
                <!-- 内容 -->
                <div class="col-sm-8 blog-main">
                    <div class="blog-post">
                        <!--文章内容-->
                        <div class="autoImg">
                            <blockquote><p><?php echo $textContent ?></p></blockquote>
                        </div>       
                    </div><!-- /.blog-post -->
                </div><!-- /.内容 -->
                <!-- 右侧框 -->
                <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
                    <!--文章发布人-->
                    <div class="sidebar-module sidebar-module-inset" style="background-color:#f5f5f5;">
                        <h4 style="padding: 3px 3px;">发布人</h4>
                        <div class="text-center" style = "margin-bottom: 5px;">
                            <p><a href='userinfo.php?uId=<?php echo $textUid;?>'><img style='width: 100px;height: 100px; margin-top: 10px; border: 1px solid #e7e7e7;' src="<?php echo $articleAvatar ?>"/></a></p>
                            <p>昵称：<?php echo $articleName ?></p>
                            <p>电话：<?php echo $articlePhone ?></p>
                            <p>QQ：<?php echo $articleQq ?></p>
                            <p>微信：<?php echo $articleWechat ?></p>
                            <p>单位：<?php echo $articleUnit ?></p>
                        </div">
                    </div>
                    <!--发布者的相关文章-->
                    <div class="sidebar-module" style="background-color:#f5f5f5;">
                        <h4 style="padding: 3px 3px;">有关文章</h4>
                        <div class="text-center">
                        <?php 
                            // 获得发布者的文章由观看数从高到低
                            $publishPublish = $mysqls_interface->selectArticleByUIdMysql($textUid);
                            // 获得前5个
                            $publishPublish= array_slice($publishPublish,0,5);
                            foreach($publishPublish as $v){
                                // 根据文章id获得文章数据
                                $publishTitle = $mysqls_interface->selectArticleByIdMysql($v);
                                $publishTitle = $publishTitle->fetch_array();
                                echo "<a href='article.php?id=$v'>".$publishTitle['title'].'</a><br>';
                            }
                        ?>
                        </div>
                    </div>
                </div><!-- /.右侧框 -->
            </div><!-- /.中部 -->
        </div>
        <!--共享底部-->
        <?php include_once('utility\sharedFoot.php');?>
    </body>
</html>