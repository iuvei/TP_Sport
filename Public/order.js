function _$(i){
    return document.getElementById(i);
}
function user(){window.location.href="/index.php/User";}
function sport(){window.location.href="/index.php/Sport";}
function close_bet(){
    _$('Submit').disabled=true;
}
function open_bet(havemoney,betgold){
    _$('restsinglecredit').value=Number(parseFloat(_$('restsinglecredit').value)+parseFloat(betgold)).toFixed(2);
    _$('restcredit').value=havemoney;
    _$('credit').innerHTML=havemoney;
    document.all.gold.readOnly=false;
    _$('pc').innerHTML=0;
    _$('gold').value='';
    _$('gold').focus();
    _$('Submit').disabled=false;
}

function CheckKey(obj){
    obj.value = obj.value.replace(/[^\d.]/g,"");
    obj.value = obj.value.replace(/^\./g,"");
    obj.value = obj.value.replace(/\.{2,}/g,".");
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
}

function SubChk(){
    _$('Submit').disabled=true;
    if(document.all.gold.value==''){
    _$('Submit').disabled=false;
        document.all.gold.focus();
        alert("請輸入下注金額!!");
        return false;
    }
    if(isNaN(document.all.gold.value) == true){
    _$('Submit').disabled=false;
        document.all.gold.focus();
        alert("只能輸入數字!!");
        return false;
    }

    if(eval(document.all.gold.value*1) < eval(document.all.gmin_single.value)){
    _$('Submit').disabled=false;
        document.all.gold.focus();
        alert("下注金額不可小於最低下注金額!!");
        return false;
    }
    if(eval(document.all.gold.value*1) > eval(document.all.gmax_single.value)){
    _$('Submit').disabled=false;
        document.all.gold.focus();
        alert("對不起,本場有下注金額最高: "+document.all.gmax_single.value+" 元限制!!");
        return false;
    }
    if (document.all.pay_type.value!='1'){
        if(eval(document.all.gold.value*1) > eval(document.all.singleorder.value)){
            document.all.gold.focus();
            alert("下注金額不可大於單注限額!!");
    _$('Submit').disabled=false;
            return false;
        }
        if((eval(document.all.restsinglecredit.value)+eval(document.all.gold.value*1)) > eval(document.all.singlecredit.value)){
            document.all.gold.focus();
            if (eval(document.all.restsinglecredit.value)==0){
                alert("下注金額已超過單場最高限額!!");
            }else{
                alert("本場累計下注共: "+document.all.restsinglecredit.value+"\n下注金額已超過單場限額!!");
            }
    _$('Submit').disabled=false;
            return false;
        }
    }
    if(eval(document.all.gold.value*1) > eval(document.all.restcredit.value)){
        document.all.gold.focus();
        alert("下注金額不可大於可用額度!!");
    _$('Submit').disabled=false;
        return false;
    }
    document.all.Submit.disabled = true;
    document.all.gold.readOnly=true;
    _$('bet_iframe_sub').submit();
}
function CountWinGold(){
    if(document.all.gold.value==''){
        document.all.gold.focus();
        document.all.pc.innerHTML="0";
        alert('未輸入下注金額!!!');
        return false;
    }else{
        var tmpior =document.all.ioradio_r_h.value;
        if(document.all.odd_f_type.value == "E") tmpior -=1;
        var tmp_var=document.all.gold.value * ((tmpior < 0)? 1 : tmpior);
        tmp_var=Math.round(tmp_var*100);
        tmp_var=tmp_var/100;
        document.all.pc.innerHTML=tmp_var;
        count_win=true;
    }
}
function CountWinGold1(){
    if(document.all.gold.value==''){
        document.all.gold.focus();
        document.all.pc.innerHTML="0";
    }else{
        var tmp_var=document.all.gold.value * document.all.ioradio_r_h.value;
        tmp_var=tmp_var-document.all.gold.value;
        tmp_var=Math.round(tmp_var*100);
        tmp_var=tmp_var/100;
        document.all.pc.innerHTML=tmp_var;
        count_win=true;
    }
}