/**
 * by nacky.long
 * 创建自定义弹窗
 * @param param
 * 参数结构：
 param = {
            title:'提示',
            tips:"没有任何提示信息！",
            btnOk:'是',
            btnNo:'否',
            funcOk:function () {
            },
            funcNo:function () {
            }
        }
 */
function popup(content,mark,url) {
    if(!mark){
        param = {
            title:'友情提示',
            tips:content,
            btnOk:'确定',
            funcOk:function () {
                if(url){
                    window.location.href=url;
                }
            },
        }
    }else{
        param = {
            title:'友情提示',
            tips:content,
            btnOk:'确定',
            btnNo:'取消',
            funcOk:function () {
                if(url){
                    window.location.href=url;
                } 
            },
            funcNo:function () {
                return false;
            }
        }
    }
    var tipWinObj = document.createElement("DIV");
    // tipWinObj.id = uuid();
    tipWinObj.style.cssText = "position:fixed;z-index:9999;width:90%; height:180px; overflow:hidden;background-color:white; border:none;padding-bottom:10px;left:17px;";
    tipWinObj.style.top = '30%';

    var topDiv = document.createElement("DIV");
    topDiv.style.cssText = "height;45px; font-size:14px;background-color:#ac2e0e;color:white;line-height: 45px;";

    var titDiv = document.createElement("DIV");
    titDiv.style.cssText = "float:left; width:73%;text-align: center;letter-spacing: 10px;font-size: 18px;line-height: 45px; margin-left: 45px;font-weight: 700";
    titDiv.innerHTML = param.title;

    var cross = document.createElement("DIV");
    cross.style.cssText = "float:right;cursor:pointer;font-family: -webkit-body;margin-right:10px;margin-top: 7px;line-height:26px;color:white;width: 20px;height: 20px;text-align: center;font-size: 23px;";
    cross.innerHTML = 'X';

    var clearDiv = document.createElement("DIV");
    clearDiv.style.cssText = "clear:both";


     var contetDiv = document.createElement("DIV");
        contetDiv.style.cssText = "margin: 10px; line-height:90px;font-size: 16px;font-family:'Microsoft YaHei';color: rgb(89, 88, 88);";
        contetDiv.innerHTML = param.tips;

    var contentDiv = document.createElement("DIV");
    contentDiv.style.cssText = "height:145px; overflow:hidden;text-align:center;color:#595858;letter-spacing:1px;";
   

    var okBtn = document.createElement("BUTTON");
    okBtn.style.cssText = "position:absolute; width:70px; right:15px;cursor:pointer;bottom:10px;background-color: #ac2e0e;border: none;color: white;";
    okBtn.className = 'alert_btn';
    okBtn.innerHTML = param.btnOk;
    if(mark){
        var noBtn = document.createElement("BUTTON");
        noBtn.style.cssText = "float:right; width:70px;cursor:pointer;margin-right: 15px;";
        noBtn.innerHTML = param.btnNo;
    }

    topDiv.appendChild(titDiv);
    topDiv.appendChild(cross);
    topDiv.appendChild(clearDiv);
    contentDiv.appendChild(contetDiv);
    tipWinObj.appendChild(topDiv);
    tipWinObj.appendChild(contentDiv);
    if(mark){
        tipWinObj.appendChild(noBtn);
    }
    tipWinObj.appendChild(okBtn);

    //获取当前页面的第一个body节点对象,
    var body = document.getElementsByTagName("BODY")[0];
    body.appendChild(tipWinObj);

    //鎖屏DIV
    var bgObj = document.createElement("DIV");
    bgObj.style.cssText = "position:fixed;z-index: 9997;top: 0px;left: 0px;background: #000000;filter: alpha(Opacity=30); -moz-opacity:0.30;opacity:0.30;";
    bgObj.style.width = '100%';
    bgObj.style.height = '120%';
    body.appendChild(bgObj);

    cross.onclick = function () {
        body.removeChild(tipWinObj);
        body.removeChild(bgObj);
    };
    okBtn.onclick = function () {
        param.funcOk();
        body.removeChild(tipWinObj);
        body.removeChild(bgObj);
    };
    if(mark){
        noBtn.onclick = function () {
            param.funcNo();
            body.removeChild(tipWinObj);
            body.removeChild(bgObj);
        };
    }
}