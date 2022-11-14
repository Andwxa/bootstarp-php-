<?php
/*
 * @Date: 2022-10-18 19:03:45
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:06:29
 * Github:https://github.com/Andwxa
 * @Description: 用户管理侧边栏
 */
?>
<div class="list-group col-xs-2" style="margin-top: 50px;margin-left: 10px;">
    <a href="userCenter.php" id="userCenter" class="list-group-item">
        <h4 class="list-group-item-heading">
            基本信息
        </h4>
    </a>
    <a href="messageCenter.php" id="messageCenter" class="list-group-item">
        <h4 class="list-group-item-heading">
            消息中心 <span class='badge'><?php echo $messageUnreadCount?></span>
        </h4>
    </a>
    <a href="articleManagement.php" id="articleManagement" class="list-group-item">
        <h4 class="list-group-item-heading">
            文章管理
        </h4>
    </a>
    <a href="commentManagement.php" id="commentManagement" class="list-group-item">
        <h4 class="list-group-item-heading">
            评论管理
        </h4>
    </a>
    <?php
        // 获得用户id
        $sidebarUId = $mysqls_interface->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
        // 获得用户身份
        $sidebarGroup = $mysqls_interface->selectUSerAcquireGroupById($sidebarUId);
        if ($sidebarGroup == 'admin') {
            echo '
        <a href="articleReviewManagement.php" id="articleReviewManagement" class="list-group-item">
            <h4 class="list-group-item-heading">
                系统管理
            </h4>
        </a>
            ';
        }
    ?>
</div>