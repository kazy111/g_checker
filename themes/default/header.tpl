<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$site_title}</title>
<link rel="shortcut icon" href="{$relative_dir_to_top}/favicon.ico" />
<link rel="stylesheet" type="text/css" href="{$relative_dir_to_top}/css/checker.css" />
<link rel="stylesheet" type="text/css" href="{$relative_dir_to_top}/css/dot-luv/jquery-ui-1.10.3.custom.min.css" />
<link rel="alternate" type="application/rss+xml" href="{$relative_dir_to_top}/rss.php" title="RSS 2.0" />
<meta name="keywords" content="{$keywords}" />
<meta name="description" content="{$site_title}" />
<script type="text/javascript" src="{$relative_dir_to_top}/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="{$relative_dir_to_top}/js/jquery-ui-1.10.3.custom.min.js"></script>
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

	var tmp = ReadCookie('playsound');
	var n = document.getElementById('playsound');
	if(tmp != 'false') $("#playsound").attr('checked', "checked");
	$("#playsound").click(function(e){
		WriteCookie('playsound', (n.checked ? 'true' : 'false'),90);
	});
});

</script>
</head>
<body>

{include file="$file_path/themes/default/link_menu.tpl"}

