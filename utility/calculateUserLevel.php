<?php 
/*
 * @Date: 2022-10-02 15:22:27
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-10-30 21:05:13
 * Github:https://github.com/Andwxa
 * @Description: 计算用户等级和经验值
 */
    // 获得用户id
    $calculateUId = $mysqls_interface->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
    // 获得用户经验
    $exp = $mysqls_interface->selectUserInformationByuIdMysql($calculateUId);
    $level = 1; // 当前等级
    $maximumExperienceValue = 100; // 最大经验值
    $minimumExperienceValue = 0; // 当前经验值
    $percentageOfExperience = '0%'; //经验值比例 
    if($exp == 0) $exp = 1;
    switch($exp)
    {
        case $exp<100:
            $minimumExperienceValue = $exp;
            break;
        case $exp<220:
            $level = 2;
            $minimumExperienceValue = $exp - 100; 
            $maximumExperienceValue = 110;
            break;
        case $exp<430:
            $level = 3;
            $minimumExperienceValue = $exp - 220; 
            $maximumExperienceValue = 220;
            break;
        case $exp<860:
            $level = 4;
            $minimumExperienceValue = $exp - 430; 
            $maximumExperienceValue = 430;
            break;
        case $exp<1720:
            $level = 5;
            $minimumExperienceValue = $exp - 860; 
            $maximumExperienceValue = 860;
            break;
        case $exp<3440:
            $level = 6;
            $minimumExperienceValue = $exp - 1720; 
            $maximumExperienceValue = 1720;
            break;
        case $exp<6880:
            $level = 7;
            $minimumExperienceValue = $exp - 3440; 
            $maximumExperienceValue = 3440;
            break;
        case $exp<13760:
            $level = 8;
            $minimumExperienceValue = $exp - 6880; 
            $maximumExperienceValue = 6880;
            break;
        default:
            $level = 'max';
            $minimumExperienceValue = $exp - 13760; 
            $maximumExperienceValue = 9999;
    }
    // 计算百分比
    $percentageOfExperience = number_format(($minimumExperienceValue / $maximumExperienceValue) * 100,2);
    $percentageOfExperience = $percentageOfExperience .'%';
?>