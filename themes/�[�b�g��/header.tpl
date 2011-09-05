<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ゼットンチェッカー</title>
<link rel="shortcut icon" href="{$relative_dir_to_top}/favicon.ico" />
<link rel="stylesheet" type="text/css" href="{$relative_dir_to_top}/css/checker.css" />
<meta name="keywords" content="{$site_title}" />
<meta name="description" content="{$site_title}" />
<script type="text/javascript" src="{$relative_dir_to_top}/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$relative_dir_to_top}/js/auto_notice.js"></script>
<script type="text/javascript" src="{$relative_dir_to_top}/js/cookie.js"></script>
{$additional_header}
<link rel="stylesheet" type="text/css" href="{$relative_dir_to_top}/css/menu-bar.css" />
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

