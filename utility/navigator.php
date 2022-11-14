 <?php
 /*
 * @Date: 2022-10-02 15:09:04
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-10-30 21:19:24
 * Github:https://github.com/Andwxa
 * @Description: 导航栏 
 */
 ?>
 <!-- 导航栏 -->
 <nav class="navbar navbar-default .col-xs-12" role="navigation">
    <div class="container-fluid">
        <!--主题-->
        <div class="navbar-header"><a class="navbar-brand" href="index.php">文化交流</a></div>
        <!--内容-->
        <div>
            <!--向右对齐-->
            <ul class="nav navbar-nav navbar-right">
                <li id="tagIndex"><a href="index.php">首页</a></li>
                <li id="tagActive"><a href="interchange.php">话题</a></li>
                <?php
                if (isset($_COOKIE['funLoginCookie'])) {
                    echo '<li id="tagPublish"><a href="./publish.php">发表文章</a></li>';
                }else {
                    echo '<li><a href="loginAndRegister.html">登录后可以发表文章</a></li>';
                }
                ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">操作<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        if (isset($_COOKIE['funLoginCookie'])) {
                            // 获得用户id 获得未读信息的条数
                            $navigatorUId = $mysqls_interface->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
                            $messageUnreadCount =$mysqls_interface->selectMessageCountByuIdMysql($navigatorUId,0);
                            echo "<li id='tagUserCenter'><a href='./userCenter.php'>用户中心 <span class='badge'>$messageUnreadCount</span></a></li>";
                            echo "<li><a href='./processing/process_deleteState.php'>注销当前用户</a></li>";
                        }else {
                            echo '<li><a href="loginAndRegister.html">未登录</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">更多<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="mini_game.html">小游戏</a></li>
                        <li><a href="service.php">客服</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>