<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:13:02
 * Github:https://github.com/Andwxa
 * @Description: 用户中心
 */
// 清除缓存区
ob_clean();
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'userCenter';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true);
$rs = $mysqls_interface->selectUserByUIdMysql($uId);
$rs = $rs ->fetch_array();
$userName = $rs['name'];
$userEmail =  $rs['email'];
$userAvatar = $rs['avatar'];
$rs = $mysqls_interface->selectUSerInformationById($uId);
$rs = $rs ->fetch_array();
$nickname = $rs['nickname'];
$phone = $rs['phone'];
$qq = $rs['QQ'];
$wechat = $rs['wechat'];
$card = $rs['card']; //身份证
$unit = $rs['unit']; //单位
$integral = $rs['integral']; //积分
$usingIntegral = $rs['usingIntegral']; // 已使用积分
// 算出可使用的积分
$usedIntegral = $integral - $usingIntegral;
// 引入计算等级经验
include_once('utility\calculateUserLevel.php');
// 检查任务
$mysqls_interface->checkUseerTask($webpage);
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
                $(document).ready(function (){
                    // 导航栏和侧边栏选中颜色
                    $("#tagUserCenter").addClass("active");
                    $("#userCenter").addClass("active");
                });
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
                <!--第一部分-->
                <h1 class="page-header">基本信息</h1>
                <div class="row">
                    <!--第一部分 左边-->
                    <div class="col-xs-12 col-sm-4 placeholder text-center" style="padding-bottom: 10px;" >
                        <img src="<?php echo $userAvatar?>" id="headPortrait" width="200" height="200" alt="请添加图片">
                        <h4 style="padding-top: 10px;">跟换头像</h4>
                        <form method="post" action='./processing/process_portrait.php' enctype='multipart/form-data' style="padding-top: 30px;">
                            <input type="button" value="更改头像" id="button" class="btn btn-default" style="width: 80px;"/>
                            <input type="submit" value="保存头像" id="submit" class="btn btn-default" style="width: 80px;"/>
                            <input type="file" id="file" name="pic" style="display: none;"/>
                        </form>
                    </div>
                    <!--第一部分 右边-->
                    <div class="col-xs-12 col-sm-5 placeholder" >
                        <form class='form-horizontal' action='./processing/process_userDetails.php' method="post">
                            <div class='form-group'>
                                <label for='inputNickName' class='col-xs-4 control-label'>昵称</label>
                                <div class='col-xs-8'>
                                    <input type='text' class='form-control' name='inputNickName' id='inputNickName' placeholder='昵称'value='<?php echo $nickname;?>'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputPhone' class='col-xs-4 control-label'>手机号</label>
                                <div class='col-xs-8'>
                                    <input type='text' class='form-control' name='inputPhone' id='inputPhone' placeholder='手机号' value='<?php echo $phone;?>'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputQq' class='col-xs-4 control-label'>QQ</label>
                                <div class='col-xs-8'>
                                    <input type='text' class='form-control' name='inputQq' id='inputQq' placeholder='QQ' value='<?php echo $qq;?>'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputWechat' class='col-xs-4 control-label'>微信</label>
                                <div class='col-xs-8'>
                                    <input type='text' class='form-control' name='inputWechat' id='inputWechat' placeholder='微信' value='<?php echo $wechat;?>'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputCard' class='col-xs-4 control-label'>身份证</label>
                                <div class='col-xs-8'>
                                    <input type='text' class='form-control' name='inputCard' id='inputCard' placeholder='身份证' value='<?php echo $card;?>'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputUnit' class='col-xs-4 control-label'>单位</label>
                                <div class='col-xs-8'>
                                    <input type='text' class='form-control' name='inputUnit' id='inputUnit' placeholder='单位' value='<?php echo $unit;?>'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class="col-xs-12 text-center">
                                    <input class="btn btn-default" style="width: 80px; margin: auto;" type='submit' class="form-control" value='更新'>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- 第二部分-->
                <h2 class="page-header">更多信息</h2>
                <div class="row">
                    <!--经验条和积分-->
                    <div class="col-xs-12 col-sm-5">
                        <div class="col-xs-8">
                            <div class="progress" title="<?php echo $minimumExperienceValue.'/'.$maximumExperienceValue?>">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="140" aria-valuemin="0" aria-valuemax="<?php echo $maximumExperienceValue;?>"  style="width:<?php echo $percentageOfExperience;?>">
                                    <?php echo $percentageOfExperience;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <?php echo $level?>级
                        </div>
                        <div class="col-xs-12 col-sm-10">
                                <h4>积分栏</h4>
                                <hr style="margin-top: -5px;">
                                <div style="margin-top: -16px;" title="星光积分"> <?php echo $usedIntegral;?><img src="img/integral/0.png" alt="星光积分" style="margin-top: -4px; height: 20px; width: 20px;"></div>
                        </div>
                    </div>
                    <!--签到任务栏-->
                    <div class="col-xs-12 col-sm-7">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>任务名称</th>
                                    <th>奖励积分</th>
                                    <th>任务状态</th>
                                </tr>
                            </thead>
                            <?php
                            // 提交按钮
                            $submitButton = '';
                            // 输出所有任务
                            $result = $mysqls_interface->selectTaskMysql();
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $taskId = $row['taskId'];
                                    $taskName = $row['taskName'];
                                    $taskDetail = $row['taskDetail'];
                                    $taskIntegral = $row['taskIntegral'];
                                    $taskNumber = $row['taskNumber'];
                                    try {
                                        $rs = $mysqls_interface->selectTaskListByTidUidMysql($taskId,$uId);
                                        $complete = $rs['complete'];
                                        if ($complete == 3) 
                                            $submitButton = "<a class='btn btn-default' href='#'>已完成</a>";
                                        elseif($complete == 2)
                                            $submitButton = "<a class='btn btn-default' href='processing/process_submitTask.php?id=$taskId'>领取奖励</a>";
                                        elseif($complete == 1)
                                            $submitButton = "<a class='btn btn-default' href='#'>进行中</a>";
                                        elseif($complete == 0)
                                            $submitButton = "<a class='btn btn-default' href='processing/process_submitTask.php?id=$taskId'>领取任务</a>";
                                    } catch (\Throwable $th) {
                                        $submitButton = "<a class='btn btn-default' href='processing/process_submitTask.php?id=$taskId'>领取任务</a>";
                                    }
                                    echo "
                                <tr>
                                    <form action='processing/process_submitTask.php' method='post'>
                                        <th style='float: none;vertical-align:middle' title='$taskDetail'>$taskName</th>
                                        <th style='float: none;vertical-align:middle'>$taskIntegral</th>
                                        <th>$submitButton</th>
                                    </form>   
                                </tr> 
                                    ";
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#file').change(function(){
                var objUrl = window.URL.createObjectURL(this.files[0]);
                $('#headPortrait').attr("src",objUrl);
            });
            $('#button').click(function(){
                $('#file').click();
            });
        </script>
    </body>
</html>
