<?php
/*
 * @Date: 2022-10-01 22:36:57
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:11:44
 * Github:https://github.com/Andwxa
 * @Description: 发布文章
 */
// 取消警告
error_reporting(0);
// 引入数据库
require('deploy/after_end.php');
// 网页的名称
$webpage = 'publish';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie时间
$uId = $mysqls_interface->inspectionTime(true);
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
    <script type="text/javascript" charset="utf-8" src="./utf8-php/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="./utf8-php/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="./utf8-php/lang/zh-cn/zh-cn.js"></script>
    <script>
        $(document).ready(function (){
            $(document).ready(function (){
                // 导航栏选中颜色
                $("#tagPublish").addClass("active");
            });
        });
    </script>
</head>
<body style="background-color: #FAFAFA;">
    <div class="container">
        <!-- 导航栏 -->
        <?php include_once('utility\navigator.php');?>
        <!--主体-->
        <div>
            <form method="post" action='./processing/process_publishArticle.php' onsubmit="return checkPublishForm()">
                <input type="text" class="form-control" id="title" name="title" placeholder="文章标题">
                <select class="form-control" name='culture' style="margin-top: 5px">
                    <?php
                        $categorData = $mysqls_interface->selectColumnMysql('');
                        if ($categorData->num_rows > 0) {
                            // 输出数据
                            while($row = $categorData->fetch_assoc()) {
                                $categorId =  $row['cId'];
                                $categorName =  $row['name'];
                                echo "<option value='$categorId'>$categorName</option>";
                            }
                        }
                    ?>
                </select>
                <script id="editor" name="content" type="text/plain" style="height:500px;margin-top: 5px"></script>
                <div class="text-center" style="margin-top: 5px">
                    <input class="btn btn-default" id="submit" type="submit" value="提交文章"/>
                </div>
            </form>
        </div>
    </div>
    <!--共享底部-->
    <?php include_once('utility\sharedFoot.php');?>
    <script type="text/javascript">
        var ue = UE.getEditor('editor');
    </script>
    <script>
        function checkPublishForm() {
            var content = UE.getEditor('editor').hasContents();
            if ($("#title").val() == "") {
                alert('标题不能为空！');
                return false;
            } else if (content == false) {
                alert('内容不能为空！');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>