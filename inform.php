<?php
/*
 * @Date: 2022-10-11 22:57:04
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:10:20
 * Github:https://github.com/Andwxa
 * @Description: 举报界面
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
$uId= $mysqls_interface->inspectionTime(true);
// 获得类型和id
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
// 获得返回的网址
$returnsUrl = $_SESSION['backtrack'];
// 举报的目标
$reportTarget;
// 判断要举报的表
switch ($type) {
    // 评论
    case 'comment':
        $rs = $mysqls_interface->selectCommentByCIdMysql($id);
        $rs = $rs->fetch_array();
        $reportTarget = '对评论内容-'.$rs['content'].'-进行举报';
        break;
    // 文章
    case 'article':
        $rs = $mysqls_interface->selectArticleByIdMysql($id);
        $rs = $rs->fetch_array();
        $reportTarget = '对文章-'.$rs['title'].'-进行举报';
        break;
    // 人物
    case 'userinfo':
        $rs = $mysqls_interface->selectUserAndUserInformationMysql($id);
        $rs = $rs->fetch_array();
        $reportTarget = '对昵称-'.$rs['nickname'].'-进行举报';
        break;
    default:
        $_SESSION['showInformation'] = [$returnsUrl, '3', 'error', '没有该类型'];
        die(header("Refresh:0;url=../showInformation.php"));
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
    </head>
    <body style="background-color: #FAFAFA;">
        <div class="container">
            <!--导航栏-->
            <?php include_once('utility\navigator.php');?>
            <!--面包屑-->
            <ul class="breadcrumb" style="margin-top: -20px;">
                <li><a href="<?php echo $returnsUrl?>">返回</a></li>
            </ul>
            <!--主体-->
            <div class="container">
                <div class="row">
                    <div class="row-xs-12 text-center">
                        <?php echo $reportTarget?>
                    </div>
                    <div class="row-xs-12" style="margin-top: 50px;">
                        <!--举报内容区域-->
                        <div class="bs-example" style="text-align: center;" id="commentArea">
                            <form class="form-inline" method="post" action='./processing/process_inform.php?type=<?php echo $type?>&id=<?php echo $id?>' onsubmit="return addComment()">
                                <textarea class="form-control" style="width: 400px;" rows="4" placeholder="举报内容不能大于100个字符！" id="comment" name="comment"></textarea><br>
                                <input type="submit" class="btn btn-default" style="margin: 5px;" value="--发起举报--">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--共享底部-->
        <?php include_once('utility\sharedFoot.php');?>
        <script>
            //发表举报检查
            function addComment() {
                if ($("#comment").val() == "") {
                    alert('举报内容不能为空！');
                    console.log("举报内容不能为空！");
                    return false;
                }
                else if ($("#comment").val().length > 100) {
                    alert('举报内容不能大于100个字符！');
                    console.log("举报内容不能大于100个字符！");
                    return false;
                }
                // 用户存在
                var cook = '<?php $cook = $mysqls_interface->selectUserByNameMysql($data); $cook = $cook->fetch_array(); echo $cook; ?>';
                if (cook != "") {
                        return true;
                }
                alert('发起举报需要登录！');
                console.log("发起举报需要登录！");
                return false;
            }
        </script>
    </body>
</html>