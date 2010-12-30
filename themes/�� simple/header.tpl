<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$site_title}</title>
<link rel="shortcut icon" href="./favicon.ico" />
<link rel="stylesheet" type="text/css" href="./css/checker.css" />
<meta name="keywords" content="{$site_title},獄卒ch,獄卒ちゃんねる" />
<meta name="description" content="獄卒ch配信者の配信状況などをチェックするサイト" />
{$additional_header}
<script type="text/javascript" src="{$site_url}/themes/玲/tab_change.js"></script>
<script type="text/javascript" src="{$site_url}/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$site_url}/js/auto_notice.js"></script>
<link rel="stylesheet" type="text/css" href="./css/menu-bar.css" />
<script type="text/javascript">

$(document).ready(function(){
	$('#navi').css('display', 'block');
	$("#main-nav li a.main-link").hover(function(){
		$("#main-nav li a.close").fadeIn();
		$("#main-nav li a.main-link").removeClass("active");
		$(this).addClass("active");
		$("#sub-link-bar").animate({
			height: "35px"
		});
		$(".sub-links").hide();
		$(this).siblings(".sub-links").fadeIn();
	});
	$("#main-nav li a.close").click(function(){
		$("#main-nav li a.main-link").removeClass("active");
		$(".sub-links").fadeOut();
		$("#sub-link-bar").animate({
			height: "10px"
		});
		$("#main-nav li a.close").fadeOut();
	});
});
</script>
</head>
<body>

{include file="$file_path/themes/default/link_menu.tpl"}
