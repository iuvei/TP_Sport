<?php

// 本类由系统自动生成，仅供测试用途
class MainAction extends HloginAction {

    /**
     * @todo 显示帐号明细
     * @param null
     */
    public function index() {
      
        $model = new Model();
        $result = current($model->query("select wap_sport,sport_msg from web_weihu"));
        if($result['wap_sport']==1){
            redirect ('/index.php/Main/weihu');
        }
        $money = current($model->query("select Money,UserName,md5psw,Agents from web_member_data where UserName='{$_SESSION['username']}'"));
        $this->assign('money', $money);
        $uid = $_SESSION['uid'];
        //$marquee = current($model->query("SELECT Message FROM  `web_marquee_data` where Level='MEM' or FIND_IN_SET('{$_SESSION['username']}',Pmem) order by id desc "));
        $this->assign('marquee', array('Message'=>''));
        $this->assign('referrer_url',  $_SESSION['referrer_url'.$uid]);
        $this->display();
    }

    public function logout() {
        unset($_SESSION);
        header('location:/');
    }

    public function info() {
        $user = M('member_data');
        $info = current($user->where(array('uid' => session('uid'), 'UserName' => session('username')))->select());
        $this->assign('info', $info);
        $this->display();
    }

     public function weihu() {
        $model = new Model();
        $result = current($model->query("select sport,sport_msg from web_weihu"));
        $sport_msg = $result['sport_msg'];
        $this->assign('sport_msg',$sport_msg);
            $this->display();
        }

        public function ajax_wh(){

                $model = new Model();
                $result = $model->query("select * from web_weihu");
                $data=$result->wap_sport;

            echo $data;exit();
        }


        public function cp_index() {
   
            $model = new Model();
            if ($_SESSION['username']) {
                $money = current($model->query("select Money,UserName,md5psw from web_member_data where UserName='{$_SESSION['username']}'"));

                $this->assign('money', $money);
                $_SESSION['token'] = md5(time() . $_SESSION['username']);
            } else {
                $_SESSION['token'] = md5(time() . mt_rand(0, 99999999));
            }

            if($_SESSION['referrer_url'.$_SESSION['uid']]){
                $this->assign('referrer_url', $_SESSION['referrer_url'.$_SESSION['uid']]);
            }
            $this->display();
        }
}
