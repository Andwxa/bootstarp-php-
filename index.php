<?php
/*
 * @Date: 2022-10-30 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:10:14
 * Github:https://github.com/Andwxa
 * @Description: 首页
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'index';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>文化交流</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.8">
        <link rel="stylesheet" type="text/css" href="./bootstrap-3.4.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="./css/index.css">
        <script src="./bootstrap-3.4.1/dist/jq/jquery-3.6.0.min.js"></script>
        <script src="./bootstrap-3.4.1/dist/js/bootstrap3_3_7.main.js"></script>
        <script>
            $(document).ready(function (){
                $(document).ready(function (){
                    // 导航栏选中颜色
                    $("#tagIndex").addClass("active");
                });
            });
        </script> 
    </head>
    <body style="background-color: #FAFAFA;">
        <div class="container">
            <!-- 导航栏 -->
            <?php include_once('utility\navigator.php');?>
            <!--轮播图-->
            <div id="myCarousel" class="carousel slide">
                <!-- 轮播指标 -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                    <li data-target="#myCarousel" data-slide-to="4"></li>
                    <li data-target="#myCarousel" data-slide-to="5"></li>
                    <li data-target="#myCarousel" data-slide-to="6"></li>
                    <li data-target="#myCarousel" data-slide-to="7"></li>
                    <li data-target="#myCarousel" data-slide-to="8"></li>
                    <li data-target="#myCarousel" data-slide-to="9"></li>
                </ol>
                <!-- 轮播项目 -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="./img/index01.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index02.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index03.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index04.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index05.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index06.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index07.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index08.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index09.jpeg">
                    </div>
                    <div class="item">
                        <img src="./img/index10.jpeg">
                    </div>
                </div>
                <!-- 轮播导航 -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <!--主题-->
            <div class="teme">
                <div><h1>欢迎来到文化交流论坛</h1></div>
                <div><h3>思维的碰撞带来不同知识</h3></div>
                <div style="padding-top: 1px;"><h4>创建属于你的栏目吧</h4></div>
                <div class="scroll scroll-container">
                    <div><div>思想哲学</div></div>
                    <div><div>传统文学</div></div>
                    <div><div>饮食厨艺</div></div>
                </div>
            </div>
        </div>
        <!--共享底部-->
        <?php include_once('utility\sharedFoot.php');?>
    </body>
</html>