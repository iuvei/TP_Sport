var ag = {};
ag.set = {};
ag.set.action = 'b';
ag.query = 0;
function gm() {
	if (ag.query == 1) {
		return;
	}
	$('#ref_img2').attr('src','/Public/images/refresh6.gif');
	ag.set.action = 'b';
	ag.query = 1;
	$.ajax({
		url: '/index.php/Ag/gm',
		type: 'POST',
		dataType: 'json',
		data: ag.set,
		success: function (ret) {
			if(ret.err!='1025'){
				try{
				$('#ag_money').html(ret.ag);
				$('#ag_money2').html(ret.ag);
				$('#hg_money').html(ret.hg);
				
				ag.set.token=ret.token;
				}
				catch(e){}
			}
			else{
				document.location.href='/';
			}
	$('#ref_img2').attr('src','/Public/images/refresh5.gif');
		},
		error: function () {
			
	$('#ref_img2').attr('src','/Public/images/refresh5.gif');
		}
	});
	ag.query = 0;
}

function cm(t1) {
	if (ag.query == 1) {
		return;
	}
	ag.set.action = 'a';
	ag.query = 1;
	ag.set.f = (t1 == 'ag' ? 'ag' : 'hg');
	ag.set.t = (t1 == 'ag' ? 'hg' : 'ag');
	ag.set.b=$('#gold').val();
	if(ag.set.b==''||ag.set.b==0){
		popup('请输入金额。');
		ag.query = 0;
		return false;
	}
	$.ajax({
		url: '/index.php/Ag/cm',
		type: 'POST',
		dataType: 'json',
		data: ag.set,
		success: function (ret) {
			if(ret.err=='1025'){
				document.location.href='/';
			}
			else {
				$('#gold').val('');
				ag.set.token=ret.token;
				gm();
				popup(ret.msg);
			}
		},
		error: function (ii,jj,kk) {
			
		}
	});
	ag.query = 0;
}

function tg(gameType) {
	if (ag.query == 1) {
		return;
	}
	ag.set.action = 'f';
	if (gameType)
	{
        ag.set.gameType = gameType;
	}
	ag.query = 1;

	$.ajax({
		url: '/index.php/Ag/tg',
		type: 'POST',
		dataType: 'json',
		data: ag.set,
		success: function (ret) {
			
			if(ret.err==0){
				document.location.href=ret.msg;
			}
			else if(ret.err=='1025'){
				document.location.href='/';
			}
			else{
				ag.set.token=ret.token;
			}
		},
		error: function (ii,jj,kk,ll) {
			var tm=1;
		}
	});
	ag.query = 0;
}