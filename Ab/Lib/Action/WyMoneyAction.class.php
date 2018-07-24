<?php
/**
 * 彩票平台额度转换
 */
class WyMoneyAction extends Action{


    private $_host;
    private $_config;
    private $userinfo;
    private $arr_url=array();
    private $agentinfo=array();
    public function __construct() {
        parent::__construct();
        if(empty($_SESSION['uid']) || $_SESSION['uid'] == 'logout' )
        {
            echo '<script>top.location="/index.php/Index";</script>';
            exit();
        }
        $model=new Model();
        $result=$model->query("select ID,Oid,UserName,Agents,Money,World,Corprator,Super,Admin from web_member_data where Oid='{$_SESSION['uid']}' and Status=0");
        $user=  current($result);
        if(!$user){
            $vdata['code']='1001';
			$vdata['msg']='身份验证失败！';
			echo json_encode($data);exit;
        }
        $this->userinfo = $user;
        // $mcd = new Memcached();
        // $mcd->addServer('192.168.10.101', 11211, 100);
        // $agentinfo = $mcd->get('agentinfo');
        // if(!$agentinfo){
        	$row = $model->query("select * from cp_agent where status=0");
        	$agent_arr=array();
        	foreach ($row as $value) {
        		$agent_arr[$value['agent']]=$value;
        	}
        	// var_dump($agent_arr);exit;
        	if(!$agent_arr[$user['Agents']]){
        		echo '代理不存在';
	        	exit;
        	}

        // }else{
        	// $this->agentinfo = $agentinfo[$user['Agents']];
        	$this->agentinfo = $agent_arr[$user['Agents']];
        // 	if(!$agentinfo[$user['Agents']]){
	       //  	echo '代理不存在';
	       //  	exit;
	       //  }
        // }
    }
    //额度转换试图，并获取彩票体育余额
    public function index()
    {
    	$uid = $_SESSION['uid'];
    	$row = $this->getCpMoney($uid,$this->agentinfo['url']);
    	$cpmoney=$row['balance']?$row['balance']:0;
    	$this->assign('tymoney', $this->userinfo['Money']);
    	$this->assign('cpmoney', $cpmoney);
    	$this->display();
    }


    /**
     * 接收额度转换请求
     * @return [type] [description]
     */
    public function ajax_money(){

    	$data = $_REQUEST;
    	if(!$data['money']||!isset($data['type'])){
			$vdata['code']='1003';
			$vdata['msg']='参数有误！';
			echo json_encode($vdata);exit;
		}

        //验证金额必须为正数
        if ($data['money'] != abs($data['money']))
        {
            $vdata['code']='1013';
            $vdata['msg']='参数错误！';
            echo  json_encode($vdata);exit;
        }

		$api_url = $this->agentinfo['url'];
		if($data['type']=='0'){
			$res = $this->getTytoCp($data,$this->userinfo,$api_url);
			echo json_encode($res);exit;
		}else{
			$data = $this->getCpToTy($data,$this->userinfo,$api_url);
			echo json_encode($data);exit;
		}
    }



    /**
     * 获取当前用户信息
     * @return [type] [description]
     */
	public function getUserName(){
		$uid=$_SESSION['uid'];
 		$model=new Model();
        $result=$model->query("select ID,Oid,UserName,Agents,Money,World,Corprator,Super,Admin from web_member_data where Oid='{$uid}' and Status=0");
        $user=  current($result);
        if(1!=$user['num']){
            $data['code']='1002';
			$data['msg']='身份验证失败！';
			return $data;
        }
        return $user;
	}

	/**
	 * 体育转彩票
	 * @param  [type] $uid       [description]
	 * @param  [type] $money     [description]
	 * @param  [type] $useroinfo [description]
	 * @return [type]            [description]
	 */
	public function getTytoCp($data,$useroinfo,$apiurl){
		//判断金额
		if($data['money']>$useroinfo['Money']){
			$vdata['code']='1004';
			$vdata['msg']='转账金额小于体育账户余额！';
			return $vdata;
		}
		//体育转体育要减去当前
		$money = $data['money'] * -1;
		//体育账号减钱
		$this->setToMoney((int)$money,$useroinfo);

		$type_msg='体育转彩票';

		$row = $this->setTyMoney($money,$useroinfo,'T',$type_msg);
		if(!$row){
			$vdata['code']='2004';
			$vdata['msg']='额度转换失败2！';
			return $vdata;
		}
		//额度转换
		$cpbank = $this->getToMoney($data,$apiurl);
		if($cpbank['code']=='1'&&$cpbank['msg']=='2006'){
			$vdata['code']='1001';
			$vdata['msg']='额度转换成功。';
			$vdata['cpmoney']=$cpbank['cpBalance'];
			$vdata['tymoney']=$useroinfo['Money']-$data['money'];
			return $vdata;
			//添加额度转换记录
		}else{
			//额度转换失败则把减的钱再加回来
			$vdata['code']='2004';
			$vdata['msg']='额度转换失败3！';
			return $vdata;
		}
	}



	/**
	 * 获取彩票额度
	 * @return [type] [description]
	 */
	public function getCpMoney($oid,$apiurl){
		$apiurl=$apiurl."/user/getPersonalCenter";
		$row = $this->http_Curl(json_encode(['oid'=>$oid]),$apiurl);
		return $row;
	}



	/**
	 * 彩票转体育
	 * @param  [type] $uid       [description]
	 * @param  [type] $money     [description]
	 * @param  [type] $useroinfo [description]
	 * @return [type]            [description]
	 */
	public function getCpToTy($data,$useroinfo,$api_url){
		$cpinfo = $this->getCpMoney($_SESSION['uid'],$api_url);
		if(!$cpinfo){
			$vdata['code']='2002';
			$vdata['msg']='额度转换失败1';
			return $vdata;
		}
		if(empty($cpinfo['balance'])){
			$vdata['code']='2002';
			$vdata['msg']='额度转换失败2';
			return $vdata;
		}
		if((int)$cpinfo['balance']<(int)$data['money']){
			$vdata['code']='2003';
			$vdata['msg']='额度转换失败,您的余额不足！';
			return $vdata;
		}
		$cpbank = $this->getToMoney($data,$api_url);
		if($cpbank['code']=='1'&&$cpbank['msg']=='2006'){
			$vdata['code']='1001';
			$vdata['msg']='额度转换成功。';
			$vdata['cpmoney']=$cpbank['cpBalance'];
			$vdata['tymoney']=$useroinfo['Money']+$data['money'];
			$type_msg = '彩票转账到体育';
			$row = $this->setTyMoney(abs($data['money']),$useroinfo,'S',$type_msg,$cpbank['refId']);
			if($row['code']=='3001'){
				return $row;
			}
			$this->setToMoney($data['money'],$useroinfo);
		}else{
			$vdata['code']='2004';
			$vdata['msg']='额度转换失败2！';
			return $vdata;
		}
		return $vdata;
	}




	/**
	 * 额度转换请求
	 * @param  [type] $money     [description]
	 * @param  [type] $useroinfo [description]
	 * @return [type]            [description]
	 */
	public function getToMoney($data,$apiurl){
		$_postData = [
			'oid'=>$_SESSION['uid'],
			'transfer_io'=>$data['type'],
			'amount'=>$data['money'],
			'client_type'=>'1',
		];
		$apiurl = $apiurl."/user/conversionCash";

		$postDate = json_encode($_postData);

		//请求转换额度
		$res = $this->http_Curl($postDate,$apiurl);

		return $res;
	}




	/**
	 * 添加额度转换记录
	 * @param [type] $money    转换金额
	 * @param [type] $userinfo 用户信息
	 * @param [type] $type     转换方式
	 */
	public function setTyMoney($money,$userinfo,$type,$type_msg,$order_code=''){
		$golds = $userinfo['Money']+$money;
		$date = date('Y-m-d');
		$datetime = date('Y-m-d H:i:s');

		$sql = " insert into web_sys800_data set ";
		$sql.="Checked=1,";
		$sql.="Payway='W',";
		$sql.="Gold='".$money."',";
		$sql.="Golds='".$golds."',";
		$sql.="Goldf='".$userinfo['Money']."',";
		$sql.="AddDate='".$date."',";
		$sql.="Type='".$type."',";
		$sql.="UserName='".$userinfo['UserName']."',";
		$sql.="Admin='".$userinfo['Admin']."',";
		$sql.="Agents='".$userinfo['Agents']."',";
		$sql.="World='".$userinfo['World']."',";
		$sql.="Corprator='".$userinfo['Corprator']."',";
		$sql.="Super='".$userinfo['Super']."',";
		$sql.="CurType='RMB',";
		$sql.="Date='".$datetime."',";
		$sql.="Name='".$userinfo['UserName']."',";
		$sql.="User='Network',";
		$sql.="Order_Code='".$order_code."',";
		$sql.="Bank_Account='".$type_msg."',";
		$sql.="iscp='1',";
		$sql.="Cancel='0'";
		$model=new Model();
        $result=$model->execute($sql);
        if(!$result){
        	return false;
        }
        return true;

	}


	/**
	 * 加钱或者减钱
	 * @param [type] $money    [description]
	 * @param [type] $userinfo [description]
	 */
	public function setToMoney($money,$userinfo){
		$model=new Model();
        $result=$model->query("update web_member_data set money=money+'{$money}' where ID='{$userinfo['ID']}' and UserName='{$userinfo['UserName']}'");
	}

	/**
	 * curl 请求
	 * @param  [type] $post_data [description]
	 * @param  [type] $url       [description]
	 * @return [type]            [description]
	 */
	public function http_Curl($post_data,$url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($post_data)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch), true);
        $error  = curl_error($ch);
        curl_close($ch);
        return $result;
	}

	/**
	 * 获取额度转换记录
	 * @return [type] [description]
	 */
	public function saveList(){
        $date_start = date('Y-m-d H:i:s', time() - 14 * 86400);
        $date_end = date('Y-m-d H:i:s');
         $mysql = "select ID,Gold,Date,Name,Order_Code,Bank_Account from web_sys800_data where `UserName`='{$_SESSION['username']}' and `AddDate`>='$date_start' and `AddDate`<='$date_end' and iscp='1' order by id desc";
        $model = new Model();
        $rows = $model->query($mysql);
        $this->assign('rows', $rows);
        $this->display();
    }
}