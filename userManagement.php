<?php
/*
 * @Date: 2022-10-01 19:26:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:33:45
 * Github:https://github.com/Andwxa
 * @Description: 用户管理
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'userManagement';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true,true);
// 获得顺序和搜索的名称
$priority = isset($_GET['priority']) ? $_GET['priority'] : '';
$select = isset($_GET['select']) ? $_GET['select'] : '';
// 只能昵称
$vague = isset($_GET['vague']) ? $_GET['vague'] : '';
if ($select == 'selectUId') {
    // id转昵称
    $vague = $mysqls_interface->selectUSerInformationAcquireNicknameById($vague);
}
// 当前页
$page = 0;
$page = $_GET['page'];
$allPage = $mysqls_interface->selectUserPageMysql('',$vague);
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
            $("#userManagement").addClass("active");
            // 页尾
            $(".blog-footer") .load('./utility/SharedFoot.php');
            $a = '<?php echo $priority?>';
                console.log($a);
                if ($a){
                    $("#<?php echo $priority?>").attr('checked','checked');
                }else {
                    $("#timeMax").attr('checked','checked');
                }
            $b = '<?php echo $select?>';
            console.log($b);
            if ($b){
                $("#<?php echo $select?>").attr('checked','checked');
            }else {
                $("#selectUId").attr('checked','checked');
            }
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
    <?php include_once('utility\adminSidebar.php');?>
    <!--右侧-->
    <div class="col-xs-9">
        <div class="jumbotron"><h1>用户控制</h1></div>
        <h2 class="sub-header">用户控制</h2>
        <form class="form-inline" method="get" action='userManagement.php'>
                    <!-- 单选框 -->
                    <input type="radio" name="priority" id="timeMax" value="timeMax" /><span>注册时间降序</span>
                    <input type="radio" name="priority" id="timeMin" value="timeMin" /><span>注册时间升序</span>
                    <input type="radio" name="select" id="selectUId" value="selectUId" /><span>id</span>
                    <input type="radio" name="select" id="selectNickname" value="selectNickname" /><span>模糊昵称</span>
                    <input type="text" class="form-control" name='vague' value="<?php echo $isVague = isset($_GET['vague']) ? $_GET['vague'] : '';?>" placeholder="搜索">
                    <input type="submit" class="btn btn-default" value="确定">
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>用户id</th>
                    <th>用户昵称</th>
                    <th>用户身份</th>
                    <th>账号</th>
                    <th>用户邮箱</th>
                    <th>创建时间</th>
                    <th>封印时间</th>
                    <th>控制</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $userData = $mysqls_interface->selectUserAndUserInformationMysql('',$vague,$priority,$page*8);
                if ($userData->num_rows > 0) {
                    // 输出数据
                    while($user = $userData->fetch_assoc()) {
                        $userId =  $user['uId'];
                        $userGroup =  $user['group'];
                        $userName =  $user['name'];
                        $userEmail=  $user['email'];
                        $userTime =  $user['time'];
                        $userEmployTime = $user['employTime'];
                        if ($userEmployTime) {
                            $userEmployTime = date('Y-m-d H:i:s', $userEmployTime);
                        }else{
                            $userEmployTime = '正常状态';
                        }
                        $nickname = $user['nickname'];
                        echo "
                        <tr>
                            <td>$userId</td>
                            <td>$nickname</td>
                            <td>$userGroup</td>
                            <td>$userName</td>
                            <td>$userEmail</td>
                            <td>$userTime</td>
                            <td>$userEmployTime<td>
                            <td>
                                <form method='post' class='form-inline' action='./processing/process_userManagement.php'>
                                    <input style='display: none;' type='text' name='id' value='$userId'/>
                                    <input class='btn btn-default' type='submit' name='dele' value='删除'/>
                                    <input id='day' name='day' class='form-control' width='30px' type='text' placeholder='天'>
                                    <input id='hour' name='hour' class='form-control' width='30px' type='text' placeholder='小时'>
                                    <input id='minute' name='minute' class='form-control' width='30px' type='text' placeholder='分钟'>
                                    <input class='btn btn-default' type='submit' id='seal' name='seal' width='30px' value='封号'/>
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
                    $carryInformation = "&priority=$priority&vague=$vague&select=$select";
                    include_once('utility\pageComponent.php');
                ?>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    
</script>
</html>