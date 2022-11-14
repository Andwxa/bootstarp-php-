<?php
/*
 * @Date: 2022-10-24 08:07:53
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:11:54
 * Github:https://github.com/Andwxa
 * @Description: 系统举报管理
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
$uId = $mysqls_interface->inspectionTime(true,true);
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
                $("#reportManagement").addClass("active");
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
                <div class="jumbotron"><h1>举报管理</h1></div>
                <!--评论信息-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>举报目标</th>
                                <th>多少人举报</th>
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // 获得举报数据
                            $rs = $mysqls_interface->selectInformMysql();
                            $num = 1;
                            // 循环
                            $cyclic = 1;
                            if ($rs) {
                                // 如果有举报数据
                                if ($rs->num_rows > 0) {
                                    while ($row = $rs->fetch_assoc()) {
                                        // type
                                        $type = $row['type'];
                                        // id
                                        $id = $row['id'];
                                        // 路径
                                        switch ($type) {
                                            case 'article':
                                                $target = "./article.php?id=$id";
                                                break;
                                            case 'userinfo':
                                                $target = "./userinfo.php?uId=$id";
                                                break;
                                            default:
                                                $target = '';
                                                break;
                                        }
                                        // time
                                        $time = $row['title'];
                                        // complete
                                        $complete = $row['complete'];
                                        // com
                                        $com = 0;
                                        switch ($complete) {
                                            case '0':
                                                $complete = '未审核';
                                                $com = '0';
                                                break;
                                            case '1':
                                                $complete = '正在审核';
                                                $com = '1';
                                                break;
                                            case '2':
                                                $complete = '举报成功';
                                                $com = '2';
                                                break;
                                            case '3':
                                                $complete = '已经驳回';
                                                $com = '3';
                                                break;
                                            default:
                                                $complete = '未能获得数据';
                                                break;
                                        }
                                        if ($com == '2' or $com == '3') {
                                            continue;
                                        }else{
                                            $complete = "<a class='btn btn-default' href='./reportView.php?type=$type&id=$id'>$complete</a>";
                                            // 去除第一次
                                            if ($id1 != '') {
                                                // 如果 当前的 和 上一次 一样
                                                if ($type == $type1 and $id == $id1) {
                                                    $num++;
                                                }
                                                // 如果 当前的 和 上一次 不一样 输出上一次的内容 并且不能是审核完的
                                                if($type != $type1 or $id != $id1){
                                                    echo "
                                                    <tr>
                                                        <td><a href='$target1'>$type1 of $id1<a></td>
                                                        <td>$num</td>
                                                        <td>$complete1</td>
                                                    </tr>";
                                                    $num = 1;
                                                // 如果是最后一次循环，输出本次内容 并且不能是审核完的
                                                }elseif ($rs->num_rows == $cyclic) {
                                                    echo "
                                                    <tr>
                                                        <td><a href='$target'>$type of $id<a></td>
                                                        <td>$num</td>
                                                        <td>$complete</td>
                                                    </tr>";
                                                }
                                                
                                            }
                                            // 循环
                                            $cyclic++;
                                            // 记录当前
                                            $type1 = $type;
                                            $id1 = $id;
                                            $target1 = $target;
                                            $complete1 = $complete;
                                        }
                                    }
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>