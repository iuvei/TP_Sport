<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HloginAction
 *
 * @author Administrator
 * 
 */
class HloginAction extends Action {
    //put your code here
    public function __construct() {
        parent::__construct();
		$model=new Model();
        mysql_query("set sql_safe_updates = 1");
        if(empty($_SESSION['uid']) ||
            $_SESSION['uid'] == 'logout' )
        {
            echo '<script>top.location="/index.php/Index";</script>';
            exit();
        }else{//sim��������ʱ��
			$uid=$_SESSION['uid'];
			$datetime=date('Y-m-d H:i:s',time());
			$model->query("update web_member_data set Online=1,OnlineTime='$datetime' where Oid='$uid' and Oid!='logout'");
		}
        
        $result=$model->query("select count(*) as num from web_member_data where Oid='{$_SESSION['uid']}' and Status=0");
        $user=  current($result);
        if(1!=$user['num']){
            echo '<script>top.location="/index.php/Index";</script>';
            exit();
        }
    }
    
    protected function assigns($arr) {
        foreach ($arr as $key11 => $val11) {
            if (in_array($key11, array('model', 'havesql', 'sql', 'mysql', 'haverow'))) {
                
            } else {
                $this->assign($key11, $val11);
            }
        }
    }

}