<?php

// 声明接口
interface mysqls_interface
{
    function linkMysql();
    function addUserMysql($name,$password,$email);
    function deleUserMysql($id);
    function updateUserAvatarMysql($id,$url);
    function updateUserEmployTimeMysql($id,$employTime);
    function updateUserPasswordMysql($user,$password);
    function selectUserByNameMysql($name);
    function selectUserByUIdMysql($id);
    function selectUserEmployTimeMysql($id);
    function selectUserByNameExistMysql($name);
    function selectUserByuIdExistMysql($id);
    function checkUserNamePasswordMatches($name,$password);
    function checkUserNameEmailMatches($name,$email);
    function selectUserAndUserInformationMysql($id='',$nickname='',$priority='',$initial='');
    function addUserInformationMysql($id);
    function deleUserInformationMysql($id);
    function updateUserInformationOfIntegralMysql($id,$integral,$isIntegral);
    function updateUserInformationMysql($id,$nickname,$phone,$qq,$wechat,$card,$unit);
    function selectUSerInformationById($id);
    function selectUSerInformationAcquireNicknameById($id);
    function selectUSerAcquireGroupById($id);
    function selectUserPageMysql($id,$name);
    function selectUserInformationByuIdMysql($uid);
    function selectTaskMysql($taskId='',$webpageName='',$employ=1);
    function addTaskListMysql($taskId,$uId);
    function updateTaskListByTidUidMysql($taskId,$uId,$number='',$complete);
    function selectTaskListByTidUidMysql($tid,$uid);
    function checkUseerTask($webpage);
    function addIdentityMysql($key,$expores,$uId);
    function deleIdentityMysql($id,$name);
    function updateIdentityExpiresMysql($key,$expires,$id);
    function selectIdentityAcquireUIdMysql($key);
    function selectIdentityAcquireExpiresMysql($key);
    function issetIdentityByKeyMysql($key);
    function issetIdentityUIdMysql($id);
    function inspectionTime($prevent=false,$admin=false);
    function addCommentMysql($aId,$uId,$content);
    function deleCommentByAIdMysql($aId);
    function deleCommentByRIdMysql($id);
    function selectCommentPageMysql($aId,$uId);
    function selectCommentAllMysql($aId,$initial);
    function selectCommentByCIdMysql($cId);
    function selectCommentAllEightMysql($cId='',$aId='',$uId='',$initial='');
    function selectCommentPageEightMysql($cId='',$aId='',$uId='');
    function addArticleMysql($cId,$uId,$title,$content);
    function deleArticleMysql($id);
    function updateArticleHitsMysql($id);
    function updateArticleReplyMysql($id);
    function selectArticleByUIdMysql($uid='');
    function selectArticleByIdMysql($id);
    function selectArticleAcquireTitleByIdMysql($id);
    function selectArticleAcquireUIdByIdMysql($id);
    function selectArticlePageMysql($cId='',$uId='',$title='');
    function selectArticleByUIdOrTitleMysql($uid,$title,$priority,$initial);
    function selectArticleAllMysql($category,$title,$initial);
    function addColumnMysql($name,$sort);
    function deleColumnByIdMysql($id);
    function updateColumnMysql($id,$name,$sort);
    function selectColumnMysql($order=false);
    function selectColumnByIdOrNameMysql($id);
    function addReviewArticleMysql($cId,$uId,$title,$content);
    function deleReviewArticleMysql($id);
    function duplicateJudgmentValue($table,$column,$value);
    function addInformMysql($uId,$type,$id,$commint);
    function deleInformMysql($id);
    function updateInforOfCompletemMysql($type,$id,$complete);
    function selectInformMysql($type = '',$id='');
    function addJournalMysql($incident,$uId);
    function deleJournalMysql($id);
    function deleJournalLastDataMysql();
    function selectJournalMysql($time='DESC',$jId = '',$incident='',$uId='',$initial='');
    function selectJournalCountMysql();
    function addMessageContentMysql($count);
    function deleMessageContentMysql($id);
    function selectMessageContentMysql($mcId='');
    function addMessageMysql($group,$uId,$mcId);
    function deleMessageMysql($id);
    function updateMessageMysql($id='',$uId='');
    function selectMessageByAllMysql();
    function selectMessageByAllCountMysql();
    function selectMessageByGroupMysql($group);
    function selectMessageCountByGroupMysql($group);
    function selectMessageByuIdMysql($uId,$read=0);
    function selectMessageCountByuIdMysql($uId);
}

// 实现接口
class mysqls implements mysqls_interface
{
    // 保存数据库连接
    private $link;
    // 连接数据库
    public function linkMysql()
    {
        $link = new mysqli('localhost', 'root', 'root', 'traditional_culture_exchange_network', '3306');
        $link->query("set names utf8");
        if ($link->connect_error)
            die('连接数据库失败!错误信息：' . $link->connect_error);
        $this->link = $link;
    }
    /**
     * ------------------------------------------------------------
     * fun_user tbale    用户表
     * fun_user add    用户增加语句
     * ------------------------------------------------------------
     */
    // 增加 fun_user 账号密码邮箱
    public function addUserMysql($name,$password,$email){
        if ($name and $password and $email) {
            $sql = "insert into `fun_user` (`group`,`name`,`password`,`email`,`salt`,`avatar`) values ('user','$name','$password','$email','$password','./img/headPortrait/UserAvatar.jpeg')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user tbale    用户表
     * fun_user dele    用户删除语句
     * ------------------------------------------------------------
     */
    // 根据uId删除 fun_user 所有数据
    public function deleUserMysql($id){
        if($id){
            $sql = "DELETE FROM `fun_user` WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user tbale    用户表
     * fun_user update    用户修改语句
     * ------------------------------------------------------------
     */
    // 根据uId修改 fun_user 头像的路径
    public function updateUserAvatarMysql($id,$url){
        if ($id and $url){
            $sql = "UPDATE `fun_user` SET `avatar`='$url' WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据uId修改 fun_user employTime
    public function updateUserEmployTimeMysql($id,$employTime){
        if ($id){
            $sql = "UPDATE `fun_user` SET `employTime`='$employTime' WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据name修改 fun_user 用户密码
    public function updateUserPasswordMysql($user,$password){
        if ($user and $password) {
            $sql = "UPDATE `fun_user` SET `password`='$password' WHERE `name` = '$user'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user tbale    用户表
     * fun_user select    用户查询语句
     * ------------------------------------------------------------
     */    
    //根据name查询 fun_user 所有数据
    public function selectUserByNameMysql($name){
        if ($name){
            $sql = "select `uId`,`group`,`name`,`email`,`avatar`,`time` from `fun_user` WHERE `name` = '$name'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    //根据uId查询 fun_user 所有数据
    public function selectUserByUIdMysql($id){
        if ($id){
            $sql = "select `uId`,`group`,`name`,`email`,`avatar`,`time` from `fun_user` WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据uId查询 fun_user employTime
    public function selectUserEmployTimeMysql($id){
        if ($id){
            $sql = "select `employTime` from fun_user where `uId`='$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['employTime'];
        }
        return $result;
    }
    // 根据name判断 fun_user 是否存在name返回uId
    public function selectUserByNameExistMysql($name){
        if ($name){
            $sql = "select `uId` from fun_user where `name`='$name'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['uId'];
        }
        return $result;
    }
    // 根据uId判断 fun_user 是否存在uId返回账号
    public function selectUserByuIdExistMysql($id){
        if ($id){
            $sql = "select `name` from fun_user where `uId`='$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['name'];
        }
        return $result;
    }
    // 根据name password判断 fun_user 用户和密码是否一致
    public function checkUserNamePasswordMatches($name,$password){
        $result = false;
        if ($name and $password) {
            $sql = "select 1 from fun_user where `name`='$name' AND `password`='$password'";
            $rs = $this->link->query($sql);
            $rs = $rs->fetch_array();
            $rs = $rs['1'];
            if($rs)
                $result = true;
        }
        return $result;
    }
    // 根据name email判断 fun_user 账号和邮箱是否一致
    public function checkUserNameEmailMatches($user,$email){
        $result= false;
        if ($user and $email) {
            $sql = "select 1 from fun_user where `name`='$user' and `email`='$email'";
            $rs = $this->link->query($sql);
            $rs = $rs->fetch_array();
            $rs = $rs['1'];
            if($rs)
                $result = true;
        }
        return $result;
    }
    //根据uId查询 fun_user group
    public function selectUSerAcquireGroupById($id){
        if ($id) {
            $sql = "select `group` from `fun_user` WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['group'];
        }
        return $result;
    }
    //根据uId OR nickname fun_user fun_user_information 除了passowrd salt card的所有数据
    public function selectUserAndUserInformationMysql($id='',$nickname='',$priority='',$initial=''){
        if ($id) 
            $id = "And fun_user.uId = '$id'";
        if ($nickname)
            $nickname = "And fun_user_information.nickname LIKE '%$nickname%'";
        if ($priority){
            if ($priority == 'timeMax') 
                $priority = 'ORDER BY `time` DESC';
            else
                $priority = 'ORDER BY `time` ASC';
        }else
            $priority = "ORDER BY `time` DESC";
        if ($initial)
            $initial = "LIMIT $initial,8";
        else
            $initial = "LIMIT 0,8";
        $sql = "SELECT fun_user.`uId`,`group`,`name`,`nickname`,`email`,`avatar`,`phone`,`QQ`,`wechat`,`unit`,`integral`,`usingIntegral`,`privacy`,`time`,`employTime` FROM fun_user LEFT JOIN fun_user_information ON fun_user.`uId` = fun_user_information.`uId` WHERE 1=1 $id $nickname $priority $initial";
        $result = $this->link->query($sql);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user_information tbale    用户信息表
     * fun_user_information add    用户信息增加语句
     * ------------------------------------------------------------
     */
    // 添加 fun_user_information id nickname
    public function addUserInformationMysql($id){
        if ($id){
            $sql = "insert into `fun_user_information` (`uId`,`nickname`) values ('$id','无名氏$id')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user_information tbale    用户信息表
     * fun_user_information delect    用户信息删除语句
     * ------------------------------------------------------------
     */
    // 根据uId删除 fun_user_information 所有数据
    public function deleUserInformationMysql($id){
        if($id){
            $sql = "DELETE FROM fun_user_information WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user_information tbale    用户信息表
     * fun_user_information update    用户信息修改语句
     * ------------------------------------------------------------
     */
    // 根据uId修改 fun_user_information 总积分
    public function updateUserInformationOfIntegralMysql($id,$count,$isIntegral){
        if ($id and $count) {
            if ($isIntegral) 
                $isIntegral = 'integral';
            else
                $isIntegral = 'usingIntegral';
            // 查询原来的积分
            $rs = $this->selectUSerInformationById($id);
            $rs = $rs->fetch_array();
            $count1 = $rs[$isIntegral];
            // 合并
            $count = $count + $count1;
            // 执行修改
            $sql = "UPDATE `fun_user_information` SET `$isIntegral`='$count' WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据uId修改 fun_user_information 所有的信息
    public function updateUserInformationMysql($id,$nickname,$phone,$qq,$wechat,$card,$unit){
        if ($id and $nickname and $phone and $qq and $wechat and $card and $unit) {
            $sql = "UPDATE `fun_user_information` SET `name`='$nickname',`phone`='$phone',`QQ`='$qq',`wechat`='$wechat',`card`='$card',`unit`='$unit' WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_user_information tbale    用户信息表
     * fun_user_information select    用户信息查询语句
     * ------------------------------------------------------------
     */
    //根据uId查询 fun_user_information 用户的详细数据
    public function selectUSerInformationById($id){
        if ($id) {
            $sql = "select * from `fun_user_information` WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    //根据uId查询 fun_user_information nickname
    public function selectUSerInformationAcquireNicknameById($id){
        if ($id) {
            $sql = "select `nickname` from `fun_user_information` WHERE `uId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['nickname'];
        }
        return $result;
    }
    // 根据昵称查询 用户详情 条数
    public function selectUserPageMysql($id,$name){
        if ($id) 
            $id = "And `uId` = $id";
        if ($name) 
            $name = "And `nickname` LIKE '%$name%'";
        //拼接SQL语句
        $sql = "select count(*) from `fun_user_information` WHERE 1=1 $id $name";
        //执行SQL语句
        $result = $this->link->query($sql);
        $result = $result ->fetch_array();
        $result =  $result['count(*)'];
        $result = ceil($result / 8);
        return $result;
    }
    // 根据uId查询 fun_user_information 总经验
    public function selectUserInformationByuIdMysql($uid){
        if ($uid){
            $sql = "SELECT `integral` FROM `fun_user_information` WHERE `uId`= '$uid';";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $count = $result['integral'];
        }
        return $count;
    }
    /**
     * ------------------------------------------------------------
     * fun_task tbale    任务表
     * fun_task select    任务查询语句
     * ------------------------------------------------------------
     */
    // 查询所有任务
    public function selectTaskMysql($taskId='',$webpageName='',$employ=1){
        if ($taskId) 
            $taskId = "and `taskId`='$taskId'";
        if ($webpageName)
            $webpageName = "and `webpageName` = '$webpageName'";
        if ($employ)
            $employ = "and `employ`='$employ'";
        $sql = "SELECT * FROM `fun_task` where 1=1 $taskId $webpageName $employ";
        $result = $this->link->query($sql);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_task_list tbale    任务列表表
     * fun_task_list add    任务列表增加语句
     * ------------------------------------------------------------
     */
    // 添加任务列表
    public function addTaskListMysql($taskId,$uId){
        if ($taskId and $uId) {
            $sql = "insert into `fun_task_list` (`taskId`,`uId`,`complete`) values ('$taskId','$uId',1)";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_task_list tbale    任务列表表
     * fun_task_list update    任务列表修改语句
     * ------------------------------------------------------------
     */
    // 根据taskId uId修改 fun_task_list 状态
    public function updateTaskListByTidUidMysql($taskId,$uId,$number='',$complete){
        if ($taskId and $uId and $complete) {
            if ($number) {
                $number = ",`number`=$number";
            }
            if ($taskId and $uId and $complete) {
                $sql = "UPDATE `fun_task_list` SET `complete`='$complete' $number WHERE `taskId` = '$taskId' and `uId` = '$uId'";
                $result = $this->link->query($sql);
            }
            return $result;
        }
    }
    /**
     * ------------------------------------------------------------
     * fun_task_list tbale    任务列表表
     * fun_task_list select    任务列表查询语句
     * ------------------------------------------------------------
     */
    // 根据uId和taskId判断 fun_task_list 如果是任务完成并且时间过了一天就删除
    public function selectTaskListByTidUidMysql($tid,$uid){
        if ($tid and $uid) {
            $sql = "SELECT * FROM `fun_task_list` WHERE `taskId`= '$tid' and `uId` = '$uid'";
            $result = $this->link->query($sql);
            // 如果数据存在判断任务时间和今天有没有不同和状态是否完成
            if ($result) {
                $rs = $result ->fetch_array();
                $time = date_create($rs['time']);
                $complete = $rs['complete'];
                if (date("Y-m-d")!=date_format($time,"Y-m-d") and $complete == 3) {
                    $sql = "DELETE FROM `fun_task_list` WHERE `taskId`= '$tid' and `uId` = '$uid'";
                    $this->link->query($sql);
                    $rs = '';
                }
            }
            return $rs;
        }
    }
    // 判断用户是否有该任务，如果有就任务次数加一，并且判断任务次数是否达标，如果任务状态未完成有变成带领取任务奖励状态
    public function checkUseerTask($webpage){
        if ($webpage){
            // 查询用户id
            $uId = $this->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
            if ($this->selectUserByuIdExistMysql($uId)) {
                // 检查此页面是否有任务
                $rs = $this->selectTaskMysql('',$webpage,'');
                $rs = $rs -> fetch_array();
                $taskId = $rs['taskId'];
                // 查询用户是否有该任务
                $rs = $this->selectTaskListByTidUidMysql($taskId,$uId);
                if ($rs) {
                    // 查询任务次数
                    $number = $rs['number'];
                    // 查询任务状态
                    $complete = $rs['complete'];
                    // 任务状态未完成
                    if ($complete != 3) {
                        // 如果任务次数达到目标，改变任务状态
                        $rs = $this->selectTaskMysql($taskId);
                        $rs = $rs->fetch_array();
                        $targe = $rs['taskNumber'];
                        $determineNumber = $number + 1;
                        if ($determineNumber >= $targe)
                            $this->updateTaskListByTidUidMysql($taskId,$uId,++$number,2);
                        else
                            $this->updateTaskListByTidUidMysql($taskId,$uId,++$number,1);
                    }
                }
            }
        }
    }
    /**
     * ------------------------------------------------------------
     * fun_identity tbale    身份表
     * fun_identity add    身份增加语句
     * ------------------------------------------------------------
     */
    // 增加 fun_identity 所有信息
    public function addIdentityMysql($key,$expores,$uId){
        if ($key and $expores and $uId) {
            $sql = "insert into `fun_identity` (`key`,`expires`,`uId`) values ('$key','$expores','$uId')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_identity tbale    身份表
     * fun_identity add    身份删除语句
     * ------------------------------------------------------------
     */
    // 根据key or uId删除 fun_identity 所有信息
    public function deleIdentityMysql($key, $id){
        $result = false;
        if ($key){
            $key = "and `key` = '$key'";
        }
        if ($id){
            $id = "and `uId` = '$id'";
        }
        if ($key or $id) {
            $sql = "DELETE FROM fun_identity WHERE 1 = 1 $key $id";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_identity tbale    身份表
     * fun_identity update    身份更改语句
     * ------------------------------------------------------------
     */
    // 根据key or uId修改 fun_identity expires
    public function updateIdentityExpiresMysql($key,$expires,$id){
        if ($key or $id and $expires) {
            if ($key) 
                $key = "and `key` = '$key'";
            elseif($id)
                $id = "and `uId` = '$id'";
            $sql = "UPDATE `fun_identity` SET `expires` = '$expires' WHERE 1=1 $key $id";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_identity tbale    身份表
     * fun_identity select    身份查询语句
     * ------------------------------------------------------------
     */
    // 根据key查询 fun_identity uId
    public function selectIdentityAcquireUIdMysql($key){
        if ($key){
            $sql = "select * from `fun_identity` where `key`= '$key'";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =  $result["uId"];
        }
        return $result;
    }
    // 根据key查询 fun_identity expires
    public function selectIdentityAcquireExpiresMysql($key){
        $result = false;
        if ($key){
            //拼接SQL语句
            $sql = "select `expires` from `fun_identity` where `key`='$key'";
            //执行SQL语句
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =  $result["expires"];
        }
        //返回
        return $result;
    }
    // 判断 fun_identity key是否存在
    public function issetIdentityByKeyMysql($key){
        $result = false;
        if ($key) {
            $sql = "select 1 from `fun_identity` where `key`='$key'";
            $result = $this->link->query($sql);
            if ($result) {
                $result = true;
            }
        }
        return $result;
    }
    // 判断 fun_identity uId是否存在
    public function issetIdentityUIdMysql($id){
        if ($id) {
            $sql = "select 1 from fun_identity where `uId`='$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 与网页的cookie中的身份和数据库的身份进行核对,判断用户是否被封印
    public function inspectionTime($prevent=false,$admin=false){
        session_start();
        $time = time();
        $key = $_COOKIE['funLoginCookie'];
        // 只需要核对cookie时间
        if ($key) {
            // 查找fun_identity表是否有这个id
            $data = $this->selectIdentityAcquireUIdMysql($key);
            // 如果没有这个id
            if (!$data) {
                setcookie("funLoginCookie", '', $time-3600,'/');
                $_SESSION['showInformation'] = ['./index.php','3','error','登录凭证不存在！']; 
                die(header("Refresh:0;url=./showInformation.php"));
            }
            // 查询fun_identity表的该id的过期时间
            $overdueTime = $this->selectIdentityAcquireExpiresMysql($key);
            // 如果现在的时间大于fun_identity表时间
            if ($time > $overdueTime){
                $this->deleIdentityMysql($key,'');
                setcookie("funLoginCookie", '', $time-3600,'/');
                $_SESSION['showInformation'] = ['./index.php','3','error','登录凭证已过期，请重新登录！']; 
                die(header("Refresh:0;url=./showInformation.php"));
            }
            // 如果剩余时间小于十分钟则再加十分钟
            if ($time > ($overdueTime+600)) {
                setcookie("funLoginCookie", $key,$time+600,'/');
                $this->updateIdentityExpiresMysql($key,$time+600,'');
            }
        }
        // 需要登录
        if ($prevent) {
            // 查询用户身份
            $group = $this->selectUSerAcquireGroupById($data);
            if ($group) {
                // 查询封印时间
                $employTime = $this->selectUserEmployTimeMysql($data);
                // 如果封印存在
                if ($employTime) {
                    // 如果封印时间大于现在时间
                    if ($employTime > $time) {
                        setcookie("funLoginCookie", '', $time-3600,'/');
                        $this->deleIdentityMysql($key,'');
                        $employTime = date('Y-m-d H:i:s', $employTime);
                        $_SESSION['showInformation'] = ['./index.php','10','error',"您被封印到 $employTime 才能解除!"]; 
                        die(header("Refresh:0;url=./showInformation.php"));
                    }else{
                        $this->updateUserEmployTimeMysql($data,'');
                    }
                }
                // 需要管理员身份
                if ($admin) {
                    // 如果身份不是管理员
                    if ($group != 'admin') {
                        $_SESSION['showInformation'] = ['./index.php','3','error','需要管理员身份！']; 
                        die(header("Refresh:0;url=./showInformation.php"));
                    }
                }
            }else{
                $_SESSION['showInformation'] = ['./index.php','3','error','登录凭证不存在！']; 
                die(header("Refresh:0;url=./showInformation.php"));
            }
        }
        return $data;
    }
    /**
     * ------------------------------------------------------------
     * fun_comment tbale    评论表
     * fun_comment add    评论增加语句
     * ------------------------------------------------------------
     */
    // 增加 fun_comment 数据
    public function addCommentMysql($aId,$uId,$content){
        if ($aId and $uId and $content) {
            $sql = "insert into `fun_comment` (`aId`,`uId`,`content`) values ('$aId','$uId','$content')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_comment tbale    评论表
     * fun_comment delete    评论删除语句
     * ------------------------------------------------------------
     */
    // 根据aId删除 fun_comment 数据
    public function deleCommentByAIdMysql($aId){
        if ($aId) {
            $sql = "DELETE FROM `fun_comment` WHERE `aId` = '$aId'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据cId删除 fun_comment 数据
    public function deleCommentByRIdMysql($rId){
        if ($rId) {
            $sql = "DELETE FROM `fun_comment` WHERE `cId` = '$rId'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_comment tbale    评论表
     * fun_comment select    评论查询语句
     * ------------------------------------------------------------
     */
    // 根据aId or uId查询 fun_comment 总数
    public function selectCommentPageMysql($aId,$uId){
        if($aId){
            $aId = "And `aId` = $aId";
        }
        if($uId){
            $uId = "And `uId` = $uId";
        }
        if ($aId or $uId) {
            $sql = "select count(*) from `fun_comment` WHERE 1=1 $aId $uId";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =  $result['count(*)'];
            $result = ceil($result / 4);
        }
        return $result;
    }
    // 根据aId查询 fun_comment 数据
    public function selectCommentAllMysql($aId,$initial){
        if ($aId){
            $aId = "And `aId` = '$aId'";
        }
        if ($initial){
            $initial = "LIMIT $initial,4";
        }else{
            $initial = "LIMIT 0,4";
        }
        $sql = "select * from `fun_comment` WHERE 1 = 1 $aId $initial";
        $result = $this->link->query($sql);
        return $result;
    }
    // 根据cId查询 fun_comment 数据
    public function selectCommentByCIdMysql($cId){
        if ($cId){
            $sql = "select * from `fun_comment` WHERE `cId` = $cId";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据aId查询 fun_comment 数据
    public function selectCommentAllEightMysql($cId='',$aId='',$uId='',$initial=''){
        if($cId)
            $cId = "And `cId` = $cId";
        if($aId)
            $aId = "And `aId` = $aId";
        if($uId)
            $uId = "And `uId` = $uId";
        if ($initial)
            $initial = "LIMIT $initial,7";
        else
            $initial = "LIMIT 0,7";
        $sql = "select * from `fun_comment` WHERE 1=1 $cId $aId $uId $initial";
        $result = $this->link->query($sql);
        return $result;
    }
    // 根据aId or uId查询 fun_comment 总数
    public function selectCommentPageEightMysql($cId='',$aId='',$uId=''){
        if($cId)
            $cId = "And `cId` = $cId";
        if($aId)
            $aId = "And `aId` = $aId";
        if($uId)
            $uId = "And `uId` = $uId";
        $sql = "select count(*) from `fun_comment` WHERE 1=1 $cId $aId $uId";
        $result = $this->link->query($sql);
        $result = $result ->fetch_array();
        $result =  $result['count(*)'];
        $result = ceil($result / 9);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_article tbale    文章表
     * fun_article add    文章增加语句
     * ------------------------------------------------------------
     */
    // 增加文章
    public function addArticleMysql($cId,$uId,$title,$content){
        if ($cId and $uId and $title and $content){
            $sql = "insert into `fun_article` (`cId`,`uId`,`title`,`content`,`hits`,`reply`) values ('$cId','$uId','$title','$content','0','0')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_article tbale    文章表
     * fun_article delect    文章删除语句
     * ------------------------------------------------------------
     */
    // 根据aId删除 fun_article 数据
    public function deleArticleMysql($id){
        if ($id) {
            $sql = "DELETE FROM `fun_article` WHERE aId = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_article tbale    文章表
     * fun_article update    文章修改语句
     * ------------------------------------------------------------
     */
    // 根据aId修改 fun_article 访问量
    public function updateArticleHitsMysql($id){
        if ($id) {
            $sql = "select `hits` from `fun_article` WHERE `aId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $count = $result['hits'];
            $count += 1;
            $sql = "UPDATE `fun_article` SET `hits` = '$count' WHERE `aId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
   // 根据aId修改 fun_article 回复条数
   public function updateArticleReplyMysql($id){
        if ($id) {
            $sql = "select count(*) from `fun_comment` WHERE 1=1 AND `aId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $count =  $result['count(*)'];
            $sql = "UPDATE `fun_article` SET `reply` = '$count' WHERE `aId` = '$id'";
            $result = $this->link->query($sql);
        }
    return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_article tbale    文章表
     * fun_article select    文章查询语句
     * ------------------------------------------------------------
     */
    // 根据uId查询 fun_article 用户发布文章由观看数从高到低  
    public function selectArticleByUIdMysql($uId=''){
        $rs = array();
        if ($uId) 
            $uId = " WHERE`uId` = '$uId'";
        $sql = "SELECT `aId` FROM `fun_article` $uId ORDER BY `hits` DESC";
        $result = $this->link->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($rs,$row['aId']);
            }
        }
        return $rs;
    }
    // 根据aId查询 fun_article 数据
    public function selectArticleByIdMysql($id){
        if ($id){
            $sql = "select * from `fun_article` WHERE `aId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 根据aId查询 fun_article title
    public function selectArticleAcquireTitleByIdMysql($id){
        if ($id){
            $sql = "select `title` from `fun_article` WHERE `aId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['title'];
        }
        return $result;
    }
    // 根据aId查询 fun_article uId
    public function selectArticleAcquireUIdByIdMysql($id){
        if ($id){
            $sql = "select `uId` from `fun_article` WHERE `aId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['uId'];
        }
        return $result;
    }
    // 根据cId or title or   查询 fun_article 总页数
    public function selectArticlePageMysql($cId='',$uId='',$title=''){
        if ($cId) 
            $cId = "And `cId` = '$cId'";
        if ($uId) 
            $uId = "And `uId` = '$uId'";
        if ($title) 
            $title = "And `title` LIKE '%$title%'";
        $sql = "select count(*) from `fun_article` WHERE 1=1 $cId $uId $title";
        $result = $this->link->query($sql);
        $result = $result ->fetch_array();
        $result =  $result['count(*)'];
        $result = ceil($result / 8);
        return $result;
    }
    // 根据cId or title查询 fun_article 用户文章
    public function selectArticleByUIdOrTitleMysql($uid,$title,$priority,$initial){
        if ($uid)
            $uid = "and `uId`='$uid'";
        if ($title)
            $title = "And `title` LIKE '%$title%'";
        if ($priority){
            if ($priority == 'timeMax') 
                $priority = '`time` DESC';
            elseif($priority == 'timeMin')
                $priority = '`time` ASC';
            elseif ($priority == 'replyMax') 
                $priority = '`reply` DESC';
            elseif($priority == 'replyMin')
                $priority = '`reply` ASC';
            elseif ($priority == 'hitsMax') 
                $priority = '`hits` DESC';
            elseif($priority == 'hitsMin')
                $priority = '`hits` ASC';
            else
                $priority = '`time` ASC';
            $priority = "ORDER BY $priority";
        }else{
            $priority = "ORDER BY `time` DESC";
        }
        if ($initial)
            $initial = "LIMIT $initial,8";
        else
            $initial = "LIMIT 0,8";
        $sql = "SELECT * FROM `fun_article` where 1=1 $uid $title $priority $initial";
        $result = $this->link->query($sql);
        return $result;
    }
    // 根据cId or title and initial查询 fun_article 用户文章
    public function selectArticleAllMysql($category,$title,$initial){
        if ($category){
            $category = "And fun_column.`cId` = '$category'";
        }
        if ($title){
            $title = "And `title` LIKE '%$title%'";
        }
        if ($initial){
            $initial = "LIMIT $initial,8";
        }else{
            $initial = "LIMIT 0,8";
        }
        $sql = "SELECT fun_article.aId,fun_article.cId,fun_article.uId,fun_article.title,fun_article.content,fun_article.time,fun_article.hits,fun_article.reply,fun_column.name,fun_column.sort FROM `fun_article` JOIN `fun_column` ON fun_article.cId = fun_column.cId where 1=1 $category $title ORDER BY fun_column.sort DESC,fun_article.time ASC $initial";
        $result = $this->link->query($sql);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_column tbale    栏目表
     * fun_column add    栏目增加语句
     * ------------------------------------------------------------
     */
    // 增加 fun_column 数据
    public function addColumnMysql($name,$sort){
        if ($name and $sort){
            $sql = "insert into `fun_column` (`name`,`sort`) values ('$name','$sort')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_column tbale    栏目表
     * fun_column delete    栏目删除语句
     * ------------------------------------------------------------
     */
    // 根据cId删除 fun_column 数据
    public function deleColumnByIdMysql($id){
        if ($id){
            $sql = "DELETE FROM fun_column WHERE cId = '$id'";
            $deleSql = "DELETE FROM fun_article WHERE cId = '$id'";
            $result = $this->link->query($sql);
            $result1 = $this->link->query($deleSql);
            if ($result and $result1)
                $result = true;
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_column tbale    栏目表
     * fun_column update    栏目修改语句
     * ------------------------------------------------------------
     */
    // 根据cId修改 fun_column 数据
    public function updateColumnMysql($id,$name,$sort){
        if ($id and $name and $sort) {
            $sql = "UPDATE `fun_column` SET `sort`='$sort',`name`='$name' WHERE `cId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_column tbale    栏目表
     * fun_column select    栏目查询语句
     * ------------------------------------------------------------
     */
    //输出 fun_column 数据,默认sort从大到小
    public function selectColumnMysql($order=false){
        if ($order)
            $sql = "SELECT * FROM `fun_column` ORDER BY `sort` ASC";
        else
            $sql = "SELECT * FROM `fun_column` ORDER BY `sort` DESC";
        $result = $this->link->query($sql);
        return $result;
    }
    // 根据cId查询 fun_column 栏目的名称
    public function selectColumnByIdOrNameMysql($id){
        if ($id){
            $sql = "select `name` from `fun_column` WHERE `cId` = '$id'";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =  $result['name'];
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_review_article tbale    审核文章表
     * fun_review_article add    审核文章增加语句
     * ------------------------------------------------------------
     */
    // 增加 fun_review_article 数据
    public function addReviewArticleMysql($cId,$uId,$title,$content){
        if ($cId and $uId and $title and $content){
            $sql = "insert into `fun_review_article` (`cId`,`uId`,`title`,`content`) values ('$cId','$uId','$title','$content')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_review_article tbale    审核文章表
     * fun_review_article delete    审核文章删除语句
     * ------------------------------------------------------------
     */
    // 根据id删除 fun_review_article 数据
    public function deleReviewArticleMysql($id){
        if ($id){
            $sql = "DELETE FROM `fun_review_article` WHERE `rAId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_review_article tbale    审核文章表
     * fun_review_article select    审核文章查询语句
     * ------------------------------------------------------------
     */
    //根据rAId查询 fun_review_article 数据
    public function selectReviewArticleByrAIdMysql($rAId = ''){
        if ($rAId)
            $rAId = "and `rAId` = '$rAId'";
        $sql = "SELECT * FROM `fun_review_article` where 1=1 $rAId";
        $result = $this->link->query($sql);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * * tbale    多种表
     * * select    多种查询语句
     * ------------------------------------------------------------
     */
    // 判断某个值是否重复
    public function duplicateJudgmentValue($table,$column,$value){      
        if ($table and $column and $value) {
            $sql = "select count(*) FROM $table WHERE $column = '$value'";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =$result['count(*)'];
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_inform tbale    举报章表
     * fun_inform add    举报增加语句
     * ------------------------------------------------------------
    */
    // 增加 fun_inform 数据
    public function addInformMysql($uId,$type,$id,$commint){
        if ($type and $id and $commint){
            $sql = "insert into `fun_inform` (`uId`,`type`,`id`,`commint`) values ('$uId','$type','$id','$commint')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_inform tbale    举报章表
     * fun_inform delete    举报删除语句
     * ------------------------------------------------------------
    */
    public function deleInformMysql($id){
        if ($id){
            $sql = "DELETE FROM `fun_inform` WHERE `iId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_inform tbale    举报表
     * fun_inform update    举报修改语句
     * ------------------------------------------------------------
     */
    // 根据iId修改 fun_inform complete
    public function updateInforOfCompletemMysql($type,$id,$complete){
        if ($type and $id and $complete) {
            $sql = "UPDATE `fun_inform` SET `complete`='$complete' WHERE `type` = '$type' and `id` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_inform tbale    举报表
     * fun_inform select    举报查询语句
     * ------------------------------------------------------------
    */
    //根据type or id查询 fun_inform 数据
    public function selectInformMysql($type = '',$id=''){
        if ($type)
            $type = "and `type` = '$type'";
        if ($id)
            $id = "and `id` = '$id'";
        $sql = "SELECT * FROM `fun_inform` where 1=1 $type $id ORDER BY `type`,`id` DESC";
        $result = $this->link->query($sql);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_journal tbale    日志表
     * fun_journal add    日志增加语句
     * ------------------------------------------------------------
    */
    // 增加 fun_journal 数据
    public function addJournalMysql($incident,$uId){
        if ($incident and $uId){
            $sql = "insert into `fun_journal` (`incident`,`uId`) values ('$incident','$uId')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_journal tbale    日志表
     * fun_journal delete    日志删除语句
     * ------------------------------------------------------------
    */
    // 删除 fun_journal 数据
    public function deleJournalMysql($id){
        if ($id){
            $sql = "DELETE FROM `fun_journal` WHERE `jId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 删除 fun_journal 时间降序100条后的数据
    public function deleJournalLastDataMysql(){
        // 查询条数
        $count = $this->selectJournalCountMysql();
        if ($count >= 100) {
            // 查询大于100的最后一位id
            $result = $this->selectJournalMysql('');
            $result = $result->fetch_array();
            $jId = $result['jId'];
            // 删除
            $result = $this->deleJournalMysql($jId);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_journal tbale    日志表
     * fun_journal select    日志查询语句
     * ------------------------------------------------------------
    */
    //根据jId or incident or uId查询 fun_journal 数据
    public function selectJournalMysql($time='DESC',$jId = '',$incident='',$uId='',$initial=''){
        if ($jId)
            $jId = "and `jId` = '$jId'";
        if ($incident)
            $incident = "and `incident` = '$incident'";
        if ($uId)
            $uId = "and `uId` = '$uId'";
        if ($time)
            $time = "ORDER BY `time` DESC";
        else 
            $time = "ORDER BY `time` ASC";
        if ($initial)
            $initial = "LIMIT $initial,12";
        else
            $initial = "LIMIT 0,12";
        $sql = "SELECT * FROM `fun_journal` where 1=1 $jId $incident $uId $time $initial";
        $result = $this->link->query($sql);
        return $result;
    }
    // 查询 fun_journal 条数
    public function selectJournalCountMysql($time='DESC',$jId = '',$incident='',$uId=''){      
        if ($jId)
            $jId = "and `jId` = '$jId'";
        if ($incident)
            $incident = "and `incident` = '$incident'";
        if ($uId)
            $uId = "and `uId` = '$uId'";
        if ($time)
            $time = "ORDER BY `time` DESC";
        else 
            $time = "ORDER BY `time` ASC";
        $sql = "select count(*) FROM `fun_journal` where 1=1 $jId $incident $uId $time";
        $result = $this->link->query($sql);
        $result = $result ->fetch_array();
        $result =$result['count(*)'];
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_message_content tbale    消息内容表
     * fun_message_content add    消息内容语句
     * ------------------------------------------------------------
    */
    // 增加 fun_message_content 数据
    public function addMessageContentMysql($content){
        if ($content){
            $sql = "insert into `fun_message_content` (`content`) values ('$content')";
            $result = $this->link->query($sql);
            $sql = "select `mcId` FROM `fun_message_content` WHERE `content`='$content'";
            $result = $this->link->query($sql);
            $result = $result->fetch_array();
            $result = $result['mcId'];
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_message_content tbale    消息内容表
     * fun_message_content delete    消息内容删除语句
     * ------------------------------------------------------------
    */
    // 删除 fun_message_content 数据
    public function deleMessageContentMysql($id){
        if ($id){
            $sql = "DELETE FROM `fun_message_content` WHERE `mcId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_message_content tbale    消息内容表
     * fun_message_content select    消息内容查询语句
     * ------------------------------------------------------------
    */
    // 查询 fun_message_content 数据
    public function selectMessageContentMysql($mcId=''){
        if ($mcId) {
            $mcId = "AND `mcId` = '$mcId'";
        }
        $sql = "SELECT * FROM `fun_message_content` WHERE 1 = 1 $mcId";
        $result = $this->link->query($sql);
        return $result;
    }
        /**
     * ------------------------------------------------------------
     * fun_message tbale    消息表
     * fun_message add    消息语句
     * ------------------------------------------------------------
    */
    // 增加 fun_message 数据
    public function addMessageMysql($group,$uId,$mcId){
        if ($group and $mcId){
            $sql = "insert into `fun_message` (`group`,`uId`,`mcId`) values ('$group','$uId','$mcId')";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_message tbale    消息表
     * fun_message delete    消息删除语句
     * ------------------------------------------------------------
    */
    // 删除 fun_message 数据
    public function deleMessageMysql($id){
        if ($id){
            $sql = "DELETE FROM `fun_message` WHERE `mId` = '$id'";
            $result = $this->link->query($sql);
        }
        return $result;
    }
            /**
     * ------------------------------------------------------------
     * fun_message tbale    消息表
     * fun_message update    消息修改语句
     * ------------------------------------------------------------
     */
    // 根据mId修改 fun_message read
    public function updateMessageMysql($id='',$uId=''){
        if ($id) 
            $id = "AND `mId` = '$id'";
        if ($uId) {
            $uId = "AND `uId` = '$uId'";
        }
        $sql = "UPDATE `fun_message` SET `read`='1' WHERE 1=1 $id $uId";
        $result = $this->link->query($sql);
        return $result;
    }
    /**
     * ------------------------------------------------------------
     * fun_message tbale    消息表
     * fun_message select    消息查询语句
     * ------------------------------------------------------------
    */
    // 查询 fun_message all组数据
    public function selectMessageByAllMysql(){
        $sql = "SELECT * FROM `fun_message` WHERE `group` = 'all' ORDER BY `read` ASC,`time` DESC";
        $result = $this->link->query($sql);
        return $result;
    }
    // 查询 fun_message all组数据条数
    public function selectMessageByAllCountMysql(){
        $sql = "SELECT count(*) FROM `fun_message` WHERE `group` = 'all' ORDER BY `read` ASC,`time` DESC";
        $result = $this->link->query($sql);
        $result = $result ->fetch_array();
        $result =  $result['count(*)'];
        return $result;
    }
    // 查询 fun_message 用户或管理员组数据
    public function selectMessageByGroupMysql($group){
        if ($group) {
            $sql = "SELECT * FROM `fun_message` WHERE `group` = '$group' ORDER BY `read` ASC,`time` DESC";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 查询 fun_message 用户或管理员组数据条数
    public function selectMessageCountByGroupMysql($group){
        if ($group) {
            $sql = "SELECT count(*) FROM `fun_message` WHERE `group` = '$group' ORDER BY `read` ASC,`time` DESC";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =  $result['count(*)'];
        }
        return $result;
    }
    // 查询 fun_message 指向数据
    public function selectMessageByuIdMysql($uId,$read=0){
        // 如果0或不存在只显示未读的部分
        if (!$read)
            $read = "AND `read` = '0'";
        else
            $read = '';
        if ($uId) {
            $sql = "SELECT * FROM `fun_message` WHERE `uId` = '$uId' $read ORDER BY `read` ASC,`time` DESC";
            $result = $this->link->query($sql);
        }
        return $result;
    }
    // 查询 fun_message 指向未读数据条数
    public function selectMessageCountByuIdMysql($uId,$read=0){
        // 如果0或不存在只显示未读的部分
        if (!$read)
            $read = "AND `read` = '0'";
        else
            $read = '';
        if ($uId) {
            $sql = "SELECT count(*) FROM `fun_message` WHERE `uId` = '$uId' AND `read` = '0' ORDER BY `read` ASC,`time` DESC";
            $result = $this->link->query($sql);
            $result = $result ->fetch_array();
            $result =  $result['count(*)'];
        }
        return $result;
    }
}
