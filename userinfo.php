<?php
/*
 * @Date: 2022-10-04 23:43:44
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:29:38
 * Github:https://github.com/Andwxa
 * @Description: 用户信息界面
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入数据库
require('deploy/after_end.php');
//网页的名称
$webpage = 'userinfo';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$userId = $mysqls_interface->inspectionTime();
// 获得用户id
$uId = isset($_GET['uId']) ? $_GET['uId'] : '';
// 获得返回的网址
$returnsUrl = $_SESSION['backtrack'];
// 判断需要的值是否传入
if (!$uId) {
    $_SESSION['showInformation'] = ['./index.php','3','error','用户数据传输失败，请联系管理人员！'];
    die(header("Refresh:0;url=./showInformation.php"));
}
$rs = $mysqls_interface->selectUserAndUserInformationMysql($uId);
$rs = $rs ->fetch_array();
$userAvatar = $rs['avatar'];
$nickname = $rs['nickname'];
$phone = $rs['phone'];
$qq = $rs['QQ'];
$wechat = $rs['wechat'];
$unit = $rs['unit']; //单位
$integral = $rs['integral']; //积分
$usingIntegral = $rs['usingIntegral']; // 已使用积分
$privacy = $rs['privacy']; // 是否开启隐私
if ($privacy == 1) {
    $phone = '隐私保护';
    $qq = '隐私保护';
    $wechat = '隐私保护';
    $unit = '隐私保护';
}
// 算出可使用的积分
$usedIntegral = $integral - $usingIntegral;
// 获得顺序和搜索的名称
$priority = isset($_GET['priority']) ? $_GET['priority'] : '';
$vague = isset($_GET['vague']) ? $_GET['vague'] : '';
// 当前页
$page = 0;
$page = $_GET['page'];
$allPage = $mysqls_interface->selectArticlePageMysql($culture,'',$vague);
// 引入计算等级经验 需要uId
include_once('utility\calculateUserLevel.php');
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
                // 表格单选按钮
                $a = '<?php echo $priority?>';
                console.log($a);
                if ($a){
                    $("#<?php echo $priority?>").attr('checked','checked');
                }else {
                    $("#timeMax").attr('checked','checked');
                }
            });
        </script>
    </head>
    <body style="background-color: #FAFAFA;">
        <div class="container">
            <!--导航栏-->
            <?php include_once('utility\navigator.php');?>
            <!--面包屑-->
            <ul class="breadcrumb" style="margin-top: -20px;">
                <li><a href="<?php echo $returnsUrl?>">返回</a></li>
                <?php if($userId)echo "<li><a href='inform.php?type=userinfo&id=$uId'>举报</a></li>"?>
            </ul>
            <!--主体-->
            <div class="container">
                <div class="row">
                    <!--第一部分-->
                    <h1 class="page-header">基本信息</h1>
                    <div class="row">
                        <!--第一部分 左边-->
                        <div class="col-xs-12 col-sm-6 text-center">
                            <!--头像和等级和积分-->
                            <div class="row">
                                <div class="col-xs-6">
                                    <img src="<?php echo $userAvatar?>" id="headPortrait" width="200" height="200" alt="头像">
                                </div>
                                <div class="col-xs-4">
                                    <div class="progress" title="<?php echo $minimumExperienceValue.'/'.$maximumExperienceValue?>">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="140" aria-valuemin="0" aria-valuemax="<?php echo $maximumExperienceValue;?>"  style="width:<?php echo $percentageOfExperience;?>">
                                            <?php echo $percentageOfExperience;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <?php echo $level?>级
                                </div>
                                <div class="col-xs-6 text-left">
                                        <h4>积分栏</h4>
                                        <hr style="margin-top: -5px;">
                                        <div style="margin-top: -16px;" title="星光积分"> <?php echo $usedIntegral;?><img src="img/integral/0.png" alt="星光积分" style="margin-top: -4px; height: 20px; width: 20px;"></div>
                                </div>
                            </div>
                        </div>
                        <!--第一部分 右边-->
                        <div class="col-xs-12 col-sm-6" >
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td>昵称</td>
                                        <td><?php echo $nickname;?></td>
                                    </tr>
                                    <tr>
                                        <td>手机号</td>
                                        <td><?php echo $phone;?></td>
                                    </tr>
                                    <tr>
                                        <td>QQ</td>
                                        <td><?php echo $qq;?></td>
                                    </tr>
                                    <tr>
                                        <td>微信</td>
                                        <td><?php echo $wechat;?></td>
                                    </tr>
                                    <tr>
                                        <td>单位</td>
                                        <td><?php echo $unit;?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 第二部分-->
                    <h2 class="page-header">关于文章</h2>
                    <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-hover">
                            <thead>
                                <tr>
                                    <th>文章名称</th>
                                    <th>发布时间</th>
                                    <th>浏览次数</th>
                                    <th>回复次数</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                // 根据用户id获得用户文章
                                $articleData = $mysqls_interface->selectArticleByUIdOrTitleMysql($uId,$vague,$priority,$page*8);
                                if ($articleData->num_rows > 0) {
                                    // 输出数据
                                    while($article = $articleData->fetch_assoc()) {
                                        $articleTitle =  $article['title'];
                                        $articleTime =  $article['time'];
                                        $articleHits =  $article['hits'];
                                        $articleReply =  $article['reply'];
                                        echo "
                                <tr>
                                <td>$articleTitle</td>
                                <td>$articleTime</td>
                                <td>$articleHits</td>
                                <td>$articleReply</td>
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
                                $carryInformation = "&uId=$uId";
                                include_once('utility\pageComponent.php');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <!--共享底部-->
        <?php include_once('utility\sharedFoot.php');?>
    </body>
</html>