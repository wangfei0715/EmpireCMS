<?php
require("../../class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
$myuserid=(int)getcvar('mluserid');
$mhavelogin=0;
if($myuserid)
{
	include("../../class/db_sql.php");
	include("../../class/user.php");
	include("../../data/dbcache/MemberLevel.php");
	$link=db_connect();
	$empire=new mysqlquery();
	$mhavelogin=1;
	//数据
	$myusername=RepPostVar(getcvar('mlusername'));
	$myrnd=RepPostVar(getcvar('mlrnd'));
	$r=$empire->fetch1("select ".$user_userid.",".$user_username.",".$user_group.",".$user_userfen.",".$user_money.",".$user_userdate.",".$user_havemsg.",".$user_checked." from ".$user_tablename." where ".$user_userid."='$myuserid' and ".$user_rnd."='$myrnd' limit 1");
	if(empty($r[$user_userid])||$r[$user_checked]==0)
	{
		EmptyEcmsCookie();
		$mhavelogin=0;
	}
	//会员等级
	if(empty($r[$user_group]))
	{$groupid=$user_groupid;}
	else
	{$groupid=$r[$user_group];}
	$groupname=$level_r[$groupid]['groupname'];
	//点数
	$userfen=$r[$user_userfen];
	//余额
	$money=$r[$user_money];
	//天数
	$userdate=0;
	if($r[$user_userdate])
	{
		$userdate=$r[$user_userdate]-time();
		if($userdate<=0)
		{$userdate=0;}
		else
		{$userdate=round($userdate/(24*3600));}
	}
	//是否有短信息
	$havemsg="";
	if($r[$user_havemsg])
	{
		$havemsg="<a href='".$public_r['newsurl']."e/member/msg/' target=_blank><font color=red>您有新信息</font></a>";
	}
	//$myusername=$r[$user_username];
	db_close();
	$empire=null;
}
if($mhavelogin==1)
{
?>
document.write("&raquo;&nbsp;<font color=red><b><?=$myusername?></b></font>&nbsp;&nbsp;<a href=\"/ecms66/e/member/my/\" target=\"_parent\"><?=$groupname?></a>&nbsp;<?=$havemsg?>&nbsp;<a href=\"/ecms66/e/space/?userid=<?=$myuserid?>\" target=_blank>我的空间</a>&nbsp;&nbsp;<a href=\"/ecms66/e/member/msg/\" target=_blank>短信息</a>&nbsp;&nbsp;<a href=\"/ecms66/e/member/fava/\" target=_blank>收藏夹</a>&nbsp;&nbsp;<a href=\"/ecms66/e/member/cp/\" target=\"_parent\">控制面板</a>&nbsp;&nbsp;<a href=\"/ecms66/e/enews/?enews=exit&ecmsfrom=9\" onclick=\"return confirm(\'确认要退出?\');\">退出</a>");
<?
}
else
{
?>
document.write("<form name=login method=post action=\"/ecms66/e/enews/index.php\">    <input type=hidden name=enews value=login>    <input type=hidden name=ecmsfrom value=9>    用户名：<input name=\"username\" type=\"text\" class=\"inputText\" size=\"16\" />&nbsp;    密码：<input name=\"password\" type=\"password\" class=\"inputText\" size=\"16\" />&nbsp;    <input type=\"submit\" name=\"Submit\" value=\"登陆\" class=\"inputSub\" />&nbsp;    <input type=\"button\" name=\"Submit2\" value=\"注册\" class=\"inputSub\" onclick=\"window.open(\'/ecms66/e/member/register/\');\" /></form>");
<?
}
?>