<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:09:15
 * Github:https://github.com/Andwxa
 * @Description: 文章管理
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'articleManagement';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得顺序和搜索的名称
$priority = isset($_GET['priority']) ? $_GET['priority'] : '';
$vague = isset($_GET['vague']) ? $_GET['vague'] : '';
// 当前页
$page = 0;
$page = $_GET['page'];
// 获得页总数
$allPage = $mysqls_interface->selectArticlePageMysql('',$uId,$vague);
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
                $("#articleManagement").addClass("active");
                $a = '<?php echo $priority?>';
                if ($a){
                    $("#<?php echo $priority?>").attr('checked','checked');
                }else {
                    $("#timeMax").attr('checked','checked');
                }
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
                <div class="jumbotron"><h1>文章管理</h1></div>
                <h2 class="sub-header">文章控制</h2>
                <form class="form-inline" method="get" action='articleManagement.php'>
                    <!-- 单选框 -->
                    <input type="radio" name="priority" id="hitsMax" value="hitsMax" /><span>观看次数降序</span>
                    <input type="radio" name="priority" id="hitsMin" value="hitsMin" /><span>观看次数升序</span>
                    <input type="radio" name="priority" id="replyMax" value="replyMax" /><span>回复次数降序</span>
                    <input type="radio" name="priority" id="replyMin" value="replyMin" /><span>回复次数升序</span>
                    <input type="radio" name="priority" id="timeMax" value="timeMax" /><span>发表时间降序</span>
                    <input type="radio" name="priority" id="timeMin" value="timeMin" /><span>发表时间升序</span>
                    <input type="text" class="form-control" name='vague' value="<?php echo $isVague = isset($_GET['vague']) ? $_GET['vague'] : '';?>" placeholder="模糊搜索文章名称">
                    <input type="submit" class="btn btn-default" value="确定">
                </form>
                <!--文章信息-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>文章名称</th>
                                <th>发布时间</th>
                                <th>浏览次数</th>
                                <th>回复次数</th>
                                <th>控制</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // 根据用户id获得用户文章
                            $articleData = $mysqls_interface->selectArticleByUIdOrTitleMysql($uId,$vague,$priority,$page*8);
                            if ($articleData->num_rows > 0) {
                                // 输出数据
                                while($article = $articleData->fetch_assoc()) {
                                    $articleId = $article['aId'];
                                    $articleUid = $article['uId'];
                                    $articleTitle =  $article['title'];
                                    $articleTime =  $article['time'];
                                    $articleHits =  $article['hits'];
                                    $articleReply =  $article['reply'];
                                    echo "
                            <tr>
                            <td>$articleId</td>
                            <td>$articleTitle</td>
                            <td>$articleTime</td>
                            <td>$articleHits</td>
                            <td>$articleReply</td>
                            <td>
                                <form method='post' action='./processing/process_deleteArticle.php?id=$articleId'>
                                    <input class='btn btn-default' type='submit' name='dele' value='删除'/>
                                </form>
                            </td>
                        </tr>
                                    ";
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                    <!--翻页组件-->
                    <div>
                        <?php
                            // 携带额外的信息
                            $carryInformation = "&priority=$priority&vague=$vague";
                            include_once('utility\pageComponent.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>