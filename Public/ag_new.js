var ag = {};
ag.set = {};
ag.set.action = 'b';
ag.query = 0;
function gm() {
	if (ag.query == 1) {
		return;
	}
	ag.set.action = 'b';
	ag.query = 1;
	$.ajax({
		url: '/index.php/Ag/gm1',
		type: 'POST',
		dataType: 'json',
		data: ag.set,
		success: function (ret) {
			if(ret.err!='1025'){
				try{
				$('#ag_money').html(ret.ag);
				$('#hg_money').html(ret.hg);
				}
				catch(e){}
			}
			else{
				document.location.href='/';
			}
		},
		error: function () {

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
		url: '/index.php/Ag/cm1',
		type: 'POST',
		dataType: 'json',
		data: ag.set,
		success: function (ret) {
			if(ret.err=='1025'){
				document.location.href='/';
			}else {
				$('#gold').val('');
				cp.set.token=ret.token;
                ag.set.token=ret.token;
                mg.set.token=ret.token;
				get_money();
				popup(ret.msg);
			}
		},
		error: function (ii,jj,kk) {
			
		}
	});
	ag.query = 0;
}

function tg() {
	if (ag.query == 1) {
		return;
	}
	ag.set.action = 'f';
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
			}else{
				cp.set.token=ret.token;
                ag.set.token=ret.token;
                mg.set.token=ret.token;
			}
		},
		error: function (ii,jj,kk,ll) {
			var tm=1;
		}
	});
	ag.query = 0;
}