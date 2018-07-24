<?php
// 本类由系统自动生成，仅供测试用途
class LoginAction extends Action {

    public function index(){
       // $this->show();
    }

    public function __construct()
    {
        parent::__construct();

    }


    public function setLogin(){
        $uid = $_REQUEST['uid'];
        if(!empty($uid)){
            $_SESSION['uid']=$uid;
            $_SESSION['username']=$this->getUserName($uid);
            if(isset($_REQUEST['type'])){
                $_SESSION['type'] = $_REQUEST['type'];
            }

            $referrer_url = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->getCpUrl($uid);
            $_SESSION['referrer_url'.$uid] = $this->getRealUrl($referrer_url);
            header('location:/index.php/Main');
        }
    }
	public function getCpUrl($uid){
        $model = new Model();
        $user = current($model->query("select Url from web_member_data where Oid='{$uid}'"));
        return $user['Url'];
    }

    public function getRealUrl($url){
        $fix = substr($url,0,7);
        if($fix=='http://' || $fix=='https:/'){
            $resUrl = $url;
        }else{
            $resUrl = 'http://'.$url;
        }
        $resUrl = rtrim($resUrl,'/');
        return $resUrl;
    }

    public function getUserName($uid){
        $model = new Model();
        $user = current($model->query("select UserName from web_member_data where Oid='{$uid}'"));
        if(!$user['UserName']){
            echo "用户获取失败！";
            exit;
        }
        return $user['UserName'];
    }

}