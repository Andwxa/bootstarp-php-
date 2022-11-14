<?php
/*
 * @Date: 2022-10-01 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:08:45
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
$webpage = 'article';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime();
// 获得文章id
$aId = isset($_GET['id']) ? $_GET['id'] : '';
// 根据文章id获得文章信息
$rs = $mysqls_interface->selectArticleByIdMysql($aId);
$rs = $rs ->fetch_array();
$textTitle = $rs['title'];
$textCategory = $rs['cId'];
$textUid = $rs['uId'];
$textTime = $rs['time'];
$textContent = $rs['content'];
$textHits = $rs['hits'];
$textReply = $rs['reply'];
//根据文章主用户id获得用户信息
$rs = $mysqls_interface->selectUserAndUserInformationMysql($textUid);
$rs = $rs ->fetch_array();
$articleAvatar = $rs['avatar'];
$articleName = $rs['nickname'];
$articlePrivacy = $rs['privacy'];
if ($articlePrivacy != 1) {
    $articlePhone = $rs['phone'];
    $articleQq = $rs['QQ'];
    $articleWechat = $rs['wechat'];
    $articleUnit = $rs['unit'];
}else{
    $articlePhone = '隐私保护';
    $articleQq = '隐私保护';
    $articleWechat = '隐私保护';
    $articleUnit = '隐私保护';
}
// 获得栏目名称
$textCategory = $mysqls_interface->selectColumnByIdOrNameMysql($textCategory);
try {
    // 获得当前用户的用户组和id
    $user = $mysqls_interface->selectUserByUIdMysql($uId);
    $user = $user ->fetch_array();
    $userGroup = $user['group'];
    // 判断是否属于管理人员或者文章发布者
    if ($userGroup == 'admin' or $textUid == $uId) {
        $issDele = 'true';
    }
    //访问自增
    $uprs = $mysqls_interface->updateArticleHitsMysql($aId);
} catch (\Throwable $th) {
    // 是没登录的
}
// 当前页
$page = 0;
$page = $_GET['page'];
// 分成总页
$allPage = $mysqls_interface->selectCommentPageMysql($aId,'');
// 检查任务
$mysqls_interface->checkUseerTask($webpage);
// session记录返回的网址
$_SESSION['backtrack'] = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
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
                $("#tagActive").addClass("active");
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
                <li><a href="interchange.php">话题</a></li>
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
                        <!--发表评论区 只有登录才能看到-->
                        <?php
                        if ($uId) {
                            echo "
                        <div class='bs-example' style='text-align: center;' id='commentArea'>
                            <form class='form-inline' method='post' action='./processing/process_withComments.php?aId=$aId' onsubmit='return addComment()'>
                                <textarea class='form-control' style='width: 400px;' rows='4' placeholder='评论内容不能大于40个字符！' id='comment' name='comment'></textarea><br>
                                <input type='submit' class='btn btn-default' style='margin: 5px;' value='--发表评论--'>
                            </form>
                        </div>                            
                            ";
                        }
                        ?>
                        <!--评论-->
                        <div>
                            <?php
                                $ReplyDate = $mysqls_interface->selectCommentAllMysql($aId,$page*4);
                                if ($ReplyDate->num_rows > 0) {
                                    // 输出数据
                                    while($row = $ReplyDate->fetch_assoc()) {
                                        // 获得评论数据
                                        $ReplyId =  $row['cId'];
                                        $ReplyUid =  $row['uId'];
                                        $ReplyTime =  $row['time'];
                                        $ReplyContent =  $row['content'];
                                        // 获得评论的人的头像和昵称
                                        $comments = $mysqls_interface->selectUserAndUserInformationMysql($ReplyUid);
                                        $comments = $comments ->fetch_array();
                                        $avatar = $comments['avatar'];
                                        $nickname = $comments['nickname'];
                                        echo "<pre><div style='width:200px;height:80px;float:left;'><a href='userinfo.php?uId=$ReplyUid'><img src='$avatar' style='width:50px;height:50px;'/></a><br>$nickname<br>$ReplyTime</div><div style='width:200px;height:80px;float:left;word-break'>$ReplyContent<div>";if ($issDele == 'true')echo "<div style='width:60px;height:30px;float:left;'><a href='./processing/process_deleteComment.php?reply=$ReplyId&aId=$aId'>删除评论</a></div>";if ($uId)echo "<a href='inform.php?type=comment&id=$ReplyId'>举报</a>";echo'</pre>';
                                    }
                                }
                            ?>
                        </div>
                        <!--打赏举报区域-->
                        <?php
                        if ($uId) {
                            echo "
                        <div class='row'>
                            <form method='post' action='processing/process_reward.php?textUid=$textUid' onsubmit='return checkExceptional()'>
                                <div class='col-sm-offset-3 col-sm-3 col-xs-offset-1 col-xs-5 text-right' style='padding-right: 20px;'>
                                    <div class='input-group'>
                                        <input id='integral' name='integral' class='form-control' type='text' maxlength='5' value='1' >
                                        <span class='input-group-addon'>积分</span>
                                    </div>
                                </div>
                                <div class='col-xs-2 text-right' style='margin-left: -25px;'>
                                    <input class='btn btn-default' type='submit' value='打赏'>
                                </div>
                                <div class='col-xs-1 text-left' style='margin-left: -20px;'>
                                    <a class='btn btn-default' href='inform.php?type=article&id=$aId'>举报</a>
                                </div>
                            </form>
                        </div>
                            ";
                        }
                        ?>
                        <!--翻页组件-->
                        <div>
                            <?php
                                // 携带额外的信息
                                $carryInformation = "&id=$aId#commentArea";
                                include_once('utility\pageComponent.php');
                            ?>
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
        <script>
            //发表评论检查
            function addComment() {
                if ($("#comment").val() == "") {
                    alert('评论内容不能为空！');
                    console.log("评论内容不能为空！");
                    return false;
                }
                else if ($("#comment").val().length > 40) {
                    alert('评论内容不能大于40个字符！');
                    console.log("评论内容不能大于40个字符！");
                    return false;
                }
                // 用户存在
                var cook = '<?php echo $uId; ?>';
                if (cook != "") {
                        return true;
                }
                alert('发表评论需要登录！');
                console.log("发表评论需要登录！");
                return false;
            }
            function checkExceptional(){
                var reward;
                reward = document.getElementById("integral").value;
                if (isNaN(reward) || reward < 1 || reward >99999) {
                    alert('打赏的积分必须是数字且不能为空');
                }else{
                    return true;
                }
                return false;
            }
        </script>
    </body>
</html>