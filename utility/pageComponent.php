<?php
/*
 * @Date: 2022-10-09 22:04:35
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-10-09 22:19:38
 * Github:https://github.com/Andwxa
 * @Description: 翻页组件
 */
    // 下一页
    if ($page < $allPage-1)
        $nextPage = $page + 1;
    else
        $nextPage = $page;
    // 上一页
    if ($page > 0)
        $previousPage = $page -1;
    else
        $previousPage = $page;
    // 首页
    $showNowPage = $nowadayPage+1;
    // 当前页
    $currentPage = $page+1;
    // 尾页
    $lastPage = $allPage-1;
    // 如果总页数不等于0/1
    if ($allPage != 1 and $allPage != 0)
    echo "
    <nav>
        <ul class='pager'>
            <li><a href='$webpage.php?page=0$carryInformation'>首页</a></li>
            <li><a href='$webpage.php?page=$previousPage$carryInformation'>上一页</a></li>
            <span>$currentPage / $allPage</span>
            <li><a href='$webpage.php?page=$nextPage$carryInformation'>下一页</a></li>
            <li><a href='$webpage.php?page=$lastPage$carryInformation'>尾页</a></li>
        </ul>
    </nav>";
?>