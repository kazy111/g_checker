var curr_time = new Date().getTime() / 1000;

function notice_process(data)
{
	var num = 0;
	var names = [];
	for(var i in data){
		if(data[i].live == 'online' && data[i].start > curr_time){
			names.push(data[i].name);
		}
	}
	if(names.length > 0){
		// notice
		if(names.length > 10)
			$('#notice').html(num+'件の配信が始まりました！');
		else{
			$('#notice').html(names.join('、')+'配信が始まりました！');
		}
		$('#notice').bind("click", function(){ location.reload(); })
		$('#notice').slideDown();
	}else{
		$('#notice').slideUp();
	}
}

function check_lives()
{
	$.ajax({
	type: "GET",
	url: "items.php",
	dataType: "script",
	success: function(json_txt){
	  var data = eval(json_txt);
	  notice_process(data);
	}
	});
}
function set_timer(func, interval)
{
	window.setTimeout(function(){ func();set_timer(func,interval) }, interval);
}

$(document).ready(function(){
	set_timer(check_lives, 60000);
});
