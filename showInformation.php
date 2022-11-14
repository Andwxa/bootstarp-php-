<?php
/*
 * @Date: 2022-10-03 22:09:09
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:12:28
 * Github:https://github.com/Andwxa
 * @Description: 信息显示
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
$v = $_SESSION['showInformation'];
if(empty($v)){
	header("Refresh:1;url=./index.php"); 
    echo '<div class="alert alert-danger text-center"><strong>出现错误，信息传递失败！</strong></div>';
    var_dump($v);
    echo $v[0].'<br>';
    echo $v[1].'<br>';
    echo $v[2].'<br>';
    echo $v[3].'<br>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="./bootstrap-3.4.1/dist/css/bootstrap.min.css">
<title>信息显示</title>
</head>
    <body>
        <div class="container" style="margin-top: 5%;" >
            <div class="jumbotron">
                <h1 class="text-center">信息提示</h1>
                <?php
                    if ($v[2] == "success")
                        echo '<div class="alert alert-success text-center" style="margin-top: 30px;"><strong>成功</strong>,'.$v[3].'</div>';
                    elseif($v[2] == "error") 
                        echo '<div class="alert alert-danger text-center" style="margin-top: 30px;"><strong>错误</strong>,'.$v[3].'</div>';
                    echo '<div class="alert alert-info text-center">'.$v[1].'秒后返回</div>';
                ?>
            </div>
        </div>
        <script src="./bootstrap-3.4.1/dist/jq/jquery-3.6.0.min.js"></script>
        <script src="./bootstrap-3.4.1/dist/js/bootstrap3_3_7.main.js"></script>
    </body>
</html>
<?php
    header("Refresh:".$v[1].";url=".$v[0]); 
    unset($_SESSION['showInformation']); //清除SESSION数据
?>