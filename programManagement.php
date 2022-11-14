<?php
/*
 * @Date: 2022-10-01 19:27:48
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:18:37
 * Github:https://github.com/Andwxa
 * @Description: 栏目管理
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'programManagement';
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
                $(document).ready(function (){
                // 导航栏和侧边栏选中颜色
                $("#tagUserCenter").addClass("active");
                $("#programManagement").addClass("active");
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
                <div class="jumbotron"><h1>栏目控制</h1></div>
                <h2 class="sub-header">数值越大优先度越高</h2>
                <div class="table-responsive">
                    <table class="table table-hover" id="tatble">
                        <thead>
                            <tr>
                                <th>栏目ID</th>
                                <th>栏目名称</th>
                                <th>栏目优先级</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                $result = $mysqls_interface->selectColumnMysql('');
                                if ($result->num_rows > 0) {
                                    // 输出数据
                                    while($row = $result->fetch_assoc()) {
                                        $categoryId = $row['cId'];
                                        $categoryName =  $row['name'];
                                        $categorySort =  $row['sort'];
                                        echo "
                            <form method='post' action='./processing/process_programManagement.php'>
                                <tr>
                                    <td><input type='text' name='id' class='form-control' value='$categoryId' readonly/></td>
                                    <td><input type='text' name='name' class='form-control' value='$categoryName' /></td>
                                    <td><input type='text' name='sort' class='form-control' value='$categorySort' /></td>
                                    <td><input styly='margin-right: 5px;' class='btn btn-default' name='update' type='submit' value='更新' /><input class='btn btn-default' name ='dele' type='submit' value='删除' /></td>
                                </tr>
                            </form>
                                        ";
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h3>增加栏目</h3>
                    <form method='post' action='./processing/process_programManagement.php'>
                        <input type='text' name='name' class='form-control' placeholder="栏目名称" />
                        <input type='text' name='sort' class='form-control' placeholder="栏目顺序"/>
                        <input type="submit" class="btn btn-default" value="添加" name="add"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>