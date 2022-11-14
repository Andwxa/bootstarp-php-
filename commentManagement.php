<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:10:07
 * Github:https://github.com/Andwxa
 * @Description: 评论管理
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'commentManagement';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得条件和搜索的id
$priority = isset($_GET['priority']) ? $_GET['priority'] : '';
$vague = isset($_GET['vague']) ? $_GET['vague'] : '';
// 当前页
$page = 0;
$page = $_GET['page'];
// 获得页总数
if ($priority=='selectAId') {
    $allPage = $mysqls_interface->selectCommentPageEightMysql('',$vague,$uId );
}elseif($priority=='selectCId'){
    $allPage = $mysqls_interface->selectCommentPageEightMysql($vague,'',$uId );
}else{
    $allPage = $mysqls_interface->selectCommentPageEightMysql('','',$uId );
}
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
                $("#commentManagement").addClass("active");
                // 单选按钮
                $a = '<?php echo $priority?>';
                if ($a){
                    $("#<?php echo $priority?>").attr('checked','checked');
                }else {
                    $("#selectAll").attr('checked','checked');
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
                <div class="jumbotron"><h1>评论管理</h1></div>
                <form class="form-inline" id="select" method="get" action='commentAllManagement.php'>
                    <!-- 单选框 -->
                    <input type="radio" name="priority" id="selectAll" value="selectAll" /><span>全部</span>
                    <input type="radio" name="priority" id="selectCId" value="selectCId" /><span>评论</span>
                    <input type="radio" name="priority" id="selectAId" value="selectAId" /><span>文章</span>
                    <input type="text" class="form-control" style="display: none;" id="vague" name='vague' value="<?php echo $isVague = isset($_GET['vague']) ? $_GET['vague'] : '';?>" placeholder="id">
                    <input type="submit" class="btn btn-default" value="确定">
                </form>
                <!--评论信息-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>评论的id</th>
                                <th>文章的标题</th>
                                <th>评论的昵称</th>
                                <th>评论的内容</th>
                                <th>评论的时间</th>
                                <th>控制</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // 获得该用户文章aId数组
                            $aId = $mysqls_interface->selectArticleByUIdMysql($uId);
                            if ($aId) {
                                // 输出文章id
                                foreach ($aId as $key) {
                                    if($priority=='selectCId'){
                                        // 根据文章id评论id输出评论
                                        $comment = $mysqls_interface->selectCommentAllEightMysql($vague,$key,'',$page*8);
                                    }elseif($priority=='selectUId'){
                                        // 根据文章id用户id输出评论
                                        $comment = $mysqls_interface->selectCommentAllEightMysql('',$key,$vague,$page*8);
                                    }else{
                                        // 根据文章id输出评论
                                        $comment = $mysqls_interface->selectCommentAllEightMysql('',$key,'',$page*8);
                                    }
                                    // 如果有评论数据
                                    if ($comment->num_rows > 0) {
                                        while ($row = $comment->fetch_assoc()) {
                                            // cId
                                            $commentCId = $row['cId'];
                                            // 文章标题
                                            $articleTitle = $mysqls_interface->selectArticleAcquireTitleByIdMysql($key);
                                            $commentUId = $row['uId'];
                                            // 评论昵称
                                            $reviewNickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($commentUId);
                                            // 评论内容
                                            $commentContent = $row['content'];
                                            // 评论时间
                                            $commentTime = $row['time'];
                                            echo "
                                            <tr>
                                            <td>$commentCId</td>
                                            <td>$articleTitle</td>
                                            <td>$reviewNickname</td>
                                            <td>$commentContent</td>
                                            <td>$commentTime</td>
                                            <td>
                                                <form method='post' action='./processing/process_deleteComment.php?reply=$commentCId&aId=$key'>
                                                    <input class='btn btn-default' type='submit' name='dele' value='删除'/>
                                                </form>
                                            </td>
                                        </tr>
                                                    ";
                                        }
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
                            $carryInformation = "&priority=$priority&vague=$vague";
                            include_once('utility\pageComponent.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(document).ready(function() {
            $("input[type='radio']").on("click",function () {
                if($("input[id='selectAll']:checked").val()=='selectAll'){
                    document.getElementById('vague').value ='';
                    document.getElementById('vague').style.display='none';
                }else{
                    document.getElementById('vague').style.display='inline';
                }
            })
        });
    </script>
</html>