<?php
/*
 * @Date: 2022-10-01 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:10:30
 * Github:https://github.com/Andwxa
 * @Description: 文化话题 栏目
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'interchange';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId= $mysqls_interface->inspectionTime();
// 栏目筛选
$culture = isset($_GET['culture']) ? $_GET['culture'] : '';
// 文字模糊查询
$vague = isset($_GET['vague']) ? $_GET['vague'] : '';
// 当前页
$page = 0;
$page = $_GET['page'];
$allPage = $mysqls_interface->selectArticlePageMysql($culture,'',$vague);
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
        <style>
            .blog-post{
                background: #ffffff;
                border-radius: 3%;
                box-shadow: 0px 0px 0px 1px rgba(217,217,217,0.8);
            }
            .blog-post-title{
                padding-top: 5px;
                padding-left: 2px;
            }
            .blog-post-meta{
                padding-left: 4px;
            }
            @keyframes preAnimationIn{
                0%{height: 50px;}
                100%{height: 100px;}
            }
            @keyframes preAnimationOut{
                0%{height: 100px;}
                100%{height: 50px;}
            }
            pre{
                margin-left: 4px;
                margin-right: 4px;
                height: 50px; /* 高度根据多行文字一共占多少高度写 */
                line-height: 18px;
                font-size: 14px;
                overflow: hidden;
                -ms-text-overflow: ellipsis;
                text-overflow: ellipsis; /* 超出省略号 */
                display: -webkit-box;
                -webkit-line-clamp: 5; /* 控制行数 */
                -webkit-box-orient: vertical;
            }
            pre:hover{
                animation-name: preAnimationIn;
                animation-duration: 1s;
                animation-iteration-count: 1;
                animation-fill-mode: forwards;
            }
            pre{
                animation-name: preAnimationOut;
                animation-duration: 1s;
                animation-iteration-count: 1;
            }
            .padding_below{
                padding-bottom: 5px;
                padding-right: 4px;
            }
        </style>
        <script>
            $(document).ready(function (){
                // 导航栏选中颜色
                $("#tagActive").addClass("active");
            });
        </script>
    </head>
    <body style="background-color: #FAFAFA;">
        <div class="container">
            <!-- 导航栏 -->
            <?php include_once('utility\navigator.php');?>
            <!--主体-->
            <div class="container row">
                <!--头部-->
                <div class="blog-header">
                    <div style="padding-left: 20px;">
                        <h1 class="blog-title">中国的传统文化</h1>
                        <p class="lead blog-description">中国传统文化，是民族文明、风俗、精神的总称。</p>
                    </div>
                    <!--搜索框-->
                    <div class="col-xs-12 col-md-4">
                        <form class="form-inline" method="get" action='interchange.php'>
                            <div class="col-xs-5 col-sm-2 col-md-4">
                                <select class="form-control" name='culture'>
                                    <?php
                                        // 改变下拉框的首选项
                                        if ($culture) {
                                            $cultureScreening = $mysqls_interface->selectColumnByIdOrNameMysql($culture);
                                            echo "<option selected='selected' value='$culture'>$cultureScreening</option>";
                                            echo '<option value="">全部文化</option>';
                                        }else{
                                            echo '<option selected="selected" value="">全部文化</option>';
                                        }
                                    ?>
                                    <?php
                                    // 输出下拉框剩下的选项
                                    $categorData = $mysqls_interface->selectColumnMysql('');
                                    if ($categorData->num_rows > 0) {
                                        while($row = $categorData->fetch_assoc()) {
                                            $categorId =  $row['cId'];
                                            $categorName =  $row['name'];
                                            echo "<option value='$categorId'>$categorName</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xs-4 col-sm-3 col-md-6">
                                <input type="text" class="form-control" name='vague' value="<?php echo $isVague = isset($_GET['vague']) ? $_GET['vague'] : '';?>" placeholder="模糊搜索">
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <input type="submit" class="btn btn-default" value="筛选">
                            </div>
                        </form>
                    </div>
                </div>
                <!--内容-->
                <div style="margin-top: 60px;">
                    <!--左侧框-->
                    <div class='col-xs-7 blog-main'>
                    <?php
                    // 获得文章和栏目
                    $result = $mysqls_interface->selectArticleAllMysql($culture,$vague,$page*8);
                    if ($result->num_rows > 0) {
                        // 输出数据
                        while($row = $result->fetch_assoc()) {
                            $textId = $row['aId'];
                            $textTitle = $row['title'];
                            $textCategory = $row['name'];
                            $textTime = $row['time'];
                            $textContent = $row['content'];
                            $textHits = $row['hits'];
                            $textReply = $row['reply'];
                            echo "
                        <a href='./article.php?id=$textId'>
                        <div class='blog-post'>
                            <h2 class='blog-post-title'>$textTitle</h2>
                            <p class='blog-post-meta'>话题:$textCategory <br> 发布时间:$textTime </p>
                            <pre><code>$textContent</code></pre>
                            <p class='blog-post-meta text-right padding_below'>访问量-> $textHits 评论量-> $textReply</p>
                        </div>
                        </a>
                            ";
                        }
                    }
                    ?>
                    </div><!-- /.左侧内容 -->
                    <!--右侧框-->
                    <div class="col-xs-4 col-xs-offset-1 blog-sidebar">
                        <div class="sidebar-module sidebar-module-inset">
                            <h4>中国传统文化</h4>
                            <p>中国传统文化，是民族文明、风俗、精神的总称。“文化”的定义，往往是“仁者见仁，智者见智”。
                                简单地说，中国传统文化以儒佛道三家为主干。三者相互依存，相互渗透，相互影响，构筑中国传统文化的整体。
                                这三家传统文化之思想，在中国合称为“三教”。
                                中国传统文化，依据中国历史大系表顺序，经历了有巢氏、燧人氏、伏羲氏、神农氏炎帝、黄帝轩辕氏、尧、舜、禹等时代，
                                《先秦史》云：“吾国开化之迹，可征者始于巢、燧、羲、农。”；到夏朝建立。
                                之后绵延发展。中国传统文化中的儒家文化主张“积极进取、建功立业”，为历代儒客尊崇；而道家文化主张“顺其自然、自我完善”；
                                佛家文化主张“慈爱众生、无私奉献”。</p>
                        </div>
                        <div class="sidebar-module">
                            <h4>栏目的意义</h4>
                            <ol class="list-unstyled">
                                <li>思想哲学，比如：儒家、佛家等文化意识。</li>
                                <li>传统文学，比如：《诗经》、《楚辞》等。</li>
                                <li>饮食厨艺，比如：茶道、酒文化、中国菜、八大菜系等。</li>
                                <li>中华武术，比如：太极拳、咏春拳、武当拳等。</li>
                                <li>传统节日，比如中秋节、清明节、端午节等。</li>
                                <li>中国戏剧，比如：京剧、越剧、秦腔、潮剧等。</li>
                                <li>琴棋书画，比如：二胡、中国象棋、文房四宝、国画等。</li>
                                <li>中国建筑，比如：亭阁牌坊、园林寺院、亭台楼阁等</li>
                                <li>医药医学，比如：中医、中药、《针灸甲乙经》等。</li>
                                <li>民间工艺，比如剪纸、刺绣、中国结、泥人等。</li>
                            </ol>
                        </div>
                        <div class="sidebar-module">
                            <h4>超连接</h4>
                            <ol class="list-unstyled">
                                <li><a href="https://baike.baidu.com/item/%E4%B8%AD%E5%9B%BD%E4%BC%A0%E7%BB%9F%E6%96%87%E5%8C%96/6211">中国传统文化百度百科</a></li>
                                <li><a href="http://www.httpcn.com/">汉程网</a></li>
                                <li><a href="http://www.news.cn/culturepro/">新华网</a></li>
                            </ol>
                        </div>
                    </div><!-- /.右侧内容 -->
                    <!--翻页组件-->
                    <div class="col-xs-12 col-sm-7">
                        <?php
                            // 携带额外的信息
                            $carryInformation = "&vague=$vague&culture=$culture";
                            include_once('utility\pageComponent.php');
                        ?>
                    </div>
                </div><!-- /.内容 -->
            </div>
        </div>
        <!--共享底部-->
        <?php include_once('utility\sharedFoot.php');?>
    </body>
</html>