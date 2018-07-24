var mg = {};
mg.set = {};
mg.set.action = 'b';
mg.query = 0;
function gmmg() {
    if (mg.query == 1) {
        return;
    }
    mg.set.action = 'b';
    mg.query = 1;
    $.ajax({
        url: '/index.php/Mg/gm1',
        type: 'POST',
        dataType: 'json',
        data: mg.set,
        success: function (ret) {
            console.log(ret);
            if(ret.err==0){
                try{
                    $('#mg_money').html(ret.mg);
                    $('#hg_money').html(ret.hg);
                }
                catch(e){}
            } else if(ret.err==110){
                $('#mg_money').html(ret.msg);
            }else{
                document.location.href='/';
            }
        },
        error: function () {
            
        }
    });
    mg.query = 0;
}

function cmmg(t1) {
    if (mg.query == 1) {
        return;
    }
    mg.set.action = 'a';
    mg.query = 1;
    mg.set.f = (t1 == 'mgty' ? 'mg' : 'hg');
    mg.set.t = (t1 == 'mgty' ? 'hg' : 'mg');
    mg.set.b=$('#gold').val();
    if(mg.set.b==''||mg.set.b==0){
        popup('请输入金额。');
        mg.query = 0;
        return false;
    }
    $.ajax({
        url: '/index.php/Mg/cm1',
        type: 'POST',
        dataType: 'json',
        data: mg.set,
        success: function (ret) {
            if(ret.err=='1025'){
                document.location.href='/';
            }
            else {
                $('#gold').val('');
                cp.set.token=ret.token;
                ag.set.token=ret.token;
                mg.set.token=ret.token;
                gmmg();
                popup(ret.msg);
            }
        },
        error: function (ii,jj,kk) {
            
        }
    });
    mg.query = 0;
}