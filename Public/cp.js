var cp = {};
cp.set = {};
cp.set.action = 'b';
cp.query = 0;
function gm_cp() {
    if (cp.query == 1) {
        return;
    }
    $('#ref_img3').attr('src', '/Public/images/refresh6.gif');
    cp.set.action = 'b';
    cp.query = 1;
    $.ajax({
        url: '/index.php/Lottery/getcp',
        type: 'POST',
        dataType: 'json',
        data: cp.set,
        success: function (ret) {
            if (ret.err != '1025') {
                try {
                    $('#cp_money').html(ret.cp);
                    cp.set.token = ret.token;
                } catch (e) {
                }
            } else {
                document.location.href = '/';
            }
            $('#ref_img3').attr('src', '/Public/images/refresh5.gif');
        },
        error: function () {
            $('#ref_img3').attr('src', '/Public/images/refresh5.gif');
        }
    });
    cp.query = 0;
}

function cm(t1) {
    if (cp.query == 1) {
        return;
    }
    cp.set.action = 'a';
    cp.query = 1;
    cp.set.f = (t1 == 'cp' ? 'cp' : 'hg');
    cp.set.t = (t1 == 'cp' ? 'hg' : 'cp');
    cp.set.b = $('#gold').val();
    if (cp.set.b == '' || cp.set.b == 0) {
        alert('璇疯緭鍏ラ噾棰濄€�');
        cp.query = 0;
        return false;
    }
    $.ajax({
        url: '/index.php/Lottery/cm',
        type: 'POST',
        dataType: 'json',
        data: cp.set,
        success: function (ret) {
            if (ret.err == '1025') {
                document.location.href = '/';
            } else {
                $('#gold').val('');
                cp.set.token = ret.token;
                gm_cp();
                alert(ret.msg);
            }
        },
        error: function (ii, jj, kk) {
            
        }
    });
    cp.query = 0;
}

function tg(gameId, groupId) {
    if (cp.query == 1) {
        return;
    }
    cp.set.action = 'f';
    cp.query = 1;
    $.ajax({
        url: '/index.php/Lottery/tg/gameId/' + gameId + "/groupId/" + groupId,
        type: 'POST',
        dataType: 'json',
        data: cp.set,
        success: function (ret) {
            //alert(ret);
            document.location.href = ret;
        },
        error: function (ii, jj, kk, ll) {
            var tm = 1;
        }
    });
    cp.query = 0;
}