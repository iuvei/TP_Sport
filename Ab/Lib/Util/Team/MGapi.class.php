<?php
class MGapi extends NGame {

    /**
     *
     * @param array $_config
     * @param Mysql $sql_drive
     *
     */
    public function __construct($_config, $sql_drive) {
        parent::__construct($_config, $sql_drive);
        $this->mem_field='mgopen';
    }

    /**
     *
     * @param array $ret
     * @return boolean
     *
     */
    public function ag_save2db($ret) {
        $model = new Model();
        $sql = "update mg_config set ag_lst_login=" . time() . ",ag_lst_id='{$ret['id']}',ag_lst_token='{$ret['token']}' where id=1";
        $model->query($sql);
        //$sql_drive->query($sql);
        return true;
    }


    /**
     * @todo 登录到api
     * @param type $name Description：$_username-登录用户   $_pwd-密码
     * 返回值：errcode-错误代码（0：成功  其它:失败）
     *      id-网络ID
     *      token-授权令牌，API调用使用
     *      msg-错误消息
     */
    public function login_to_api() {
        $ret = array();
        $ch = curl_init();
        $url = "https://{$this->config['ag_url']}/lps/j_spring_security_check";
        $post_string = "j_username={$this->config['ag_usr']}&j_password={$this->config['ag_psw']}";
        $header[] = "";
        $header[] = "X-Requested-With:X-Api-Client";
        $header[] = "X-Api-Call:X-Api-Client";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["msg"] = "登录API失败，请联系客服.{$code}";
            $ret["errcode"] = 1;
        } else {
            //var_dump($response);
            $json = json_decode($response, true);
            if ($response&&isset($json["message"])) {
                $ret["errcode"] = 1;
                $ret["msg"] = "登录API失败，信用无效".$response;
            } else {
                $ret["errcode"] = 0;
                $ret["token"] = $json["token"];
                $ret["id"] = $json["id"];
                $this->ag_save2db($ret);
            }
        }
        curl_close($ch);
        return $ret;
    }


    /**
     * 
     * @param type $username
     * @param type $userid
     * @return string
     * 
     */
    public function member_create($username, $userid) {
        $ret = array();
        $ch = curl_init();
        $url = "https://{$this->config['ag_url']}/lps/secure/network/{$this->config['ag_neid']}/downline"; 
		//$url = "http://www.google.com"; 
		//echo $url ;
        //$method = "PUT";
        $post_string = array(
            "crId" => $this->config['ag_lst_id'],
            "neId" => $this->config['ag_neid'],
            "tarType" => "m",
            "username" => $this->config['subfix'] . $username,
            "name" => $this->config['subfix'] . $username,
            "password" => $this->rule_psw($userid, $this->config['subfix']),
            "confirmPassword" => $this->rule_psw($userid, $this->config['subfix']),
            "currency" => $this->config['currency_code'],
            "language" => $this->config['language'],
            "email" => "",
            "mobile" => "",
            "casino" => array("enable" => true), "poker" => array("enable" => false));
        $post_string = json_encode($post_string);
		//echo "<br>1111111<hr>";
		//var_dump($post_string);  
		//exit;

        $header[] = "";
        $header[] = "X-Requested-With:X-Api-Client";
        $header[] = "X-Api-Call:X-Api-Client";
        $header[] = "X-Api-Auth:{$this->config['ag_lst_token']}";
        $header[] = "Content-type:application/json";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
		//var_dump($response);
		//var_dump($ch);
		//exit ; 
        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["errcode"] = 1;
            $ret["msg"] = "405错误，请联系客服";
			//echo "cuowu " ;
			//exit ;
        } else {
            $arr = json_decode($response, true);
			//var_dump($arr);
			//echo "tttt";
			//exit ;
            if ($arr["success"]==true) { 
                $ret["errcode"] = 0;
                $ret["member_id"] = $arr["id"];
                $ret["member_code"] = $arr["code"];
                $this->mem_save2db($userid);
            } else {
                $ret["errcode"] = 1;
                $ret["msg"] = "创建会员失败....";
            }
        }
        curl_close($ch);
        return $ret;
    }

    /*
     * 功能：会员登录
     * 参数：$_parm-会员登录信息
     * 返回值：errcode-错误代码（0：成功  其它:失败）
     *      token-会员授权令牌，运行游戏时用
     *      msg-错误消息
     */

    public function member_login($username, $userid) {
        $ret = array();
        $url = "https://{$this->config['mem_url']}/member-api-web/member-api";
        $clientip = $_SERVER["REMOTE_ADDR"];

        $post_str = '<mbrapi-login-call timestamp="' . date("Y-m-d H:i:s", time() + 3600 * 4) . ' UTC" apiusername="' . $this->config['api_usr'] . '" apipassword="' . $this->config['api_psw'] . '" username="' . $this->config['subfix'] . $username . '" password="' . $this->rule_psw($userid, $this->config['subfix']) . '" ipaddress="' . $clientip . '" partnerId="' . $this->config['partner_id'] . '" currencyCode="' . $this->config['currency_code'] . '" />';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["errcode"] = 1;
            $ret["msg"] = "405错误，请联系客服";
        } else {
            $response_xml = simplexml_load_string($response);
            $attributes = $response_xml[0]->attributes();

            if ($attributes['status'] == "0") {
                $ret["errcode"] = 0;
                $ret["token"] = $attributes["token"];
            } else {
                $ret["errcode"] = 1;
                $ret["msg"] = "登录MG失败，代码：" . $attributes["status"];
            }
        }

        curl_close($ch);
        return $ret;
    }

    /*
     * 功能：获取游戏启动地址
     * 参数：$_demomode-游戏模式（true-试玩模式 false-真钱模式）
      $_parm-游戏运行信息
     * 返回值：errcode-错误代码（0：成功  其它:失败）
     *       msg-错误消息
     */

    public function lunchgame($gameid, $_demomode = false, $username = '', $userid = 0) {
        $ret = array();
        $tret = $this->member_login($username, $userid);
        if ($tret['errcode'] != 0) {
            $ret["errcode"] = 1;
            $ret["msg"] = "登录错误：请联系客服";
            return $ret;
        }
        $url = "https://{$this->config['mem_url']}/member-api-web/member-api";
        $post_str = '<mbrapi-launchurl-call ' .
                'timestamp="' . date("Y-m-d H:i:s") . ' UTC" ' .
                'apiusername="' . $this->config['api_usr'] . '" ' .
                'apipassword="' . $this->config['api_psw'] . '" ' .
                'token="' . $tret['token'] . '" ' .
                'language="zh" ' .
                'gameId="' . $gameid . '" ' .
                'bankingUrl="" ' .
                'lobbyUrl="" ' .
                'logoutRedirectUrl="" ' .
                'demoMode="' . $_demomode . '" ' .
                '/>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["errcode"] = 1;
            $ret["msg"] = "405错误，请联系客服";
        } else {
            $response_xml = simplexml_load_string($response);
            $attributes = $response_xml[0]->attributes();

            if ($attributes['status'] == "0") {
                $ret["errcode"] = 0;
                $ret["token"] = $attributes["token"];
                $ret["launchurl"] = $attributes["launchUrl"]; //游戏启动地址
            } else {
                $ret["errcode"] = 1;
                $ret["msg"] = "获取MG地址XML失败，请联系客服";
            }
        }

        curl_close($ch);
        return $ret;
    }

    /*
     * 功能：获取会员详细资料
     * 参数：$_demomode-游戏模式（true-试玩模式 false-真钱模式）
      $_parm-游戏运行信息
     * 返回值：errcode-错误代码（0：成功  其它:失败）
     *       msg-错误消息
     */

    public function member_blance($token) {
        $post_str = '<?xml version="1.0"?>' .
                '<mbrapi-account-call ' .
                'timestamp="' . date("Y-m-d H:i:s") . ' UTC" ' .
                'apiusername="' . $this->config['api_usr'] . '" ' .
                'apipassword="' . $this->config['api_psw'] . '" ' .
                'token="' . $token . '" />';
        $ret = array();
        $ch = curl_init();
        $url = "https://{$this->config['mem_url']}/member-api-web/member-api";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["errcode"] = 1;
            $ret["msg"] = "http错误：错误代码" . $status . "，请联系客服";
        } else {
            $response_xml = simplexml_load_string($response);
            $attributes = $response_xml[0]->attributes();
            $arr = (array) $response_xml->wallets;
            //$account=(array)$arr['account-wallet'][0]->attributes();

            if ($attributes['status'] == "0") {
                foreach ($arr['account-wallet'][0]->attributes() as $key => $val) {
                    $ret[$key] = (int) $val;
                }
                $ret["errcode"] = 0;
            } else {
                $ret["errcode"] = 1;
                $ret["msg"] = "MG失败，请联系客服" . $response;
            }
        }
        //var_dump($ret);
        curl_close($ch);
        return $ret;
    }

    /**
     * @todo 会员存款
     * 参数：$_demomode-游戏模式（true-试玩模式 false-真钱模式）
      $_parm-游戏运行信息
     * 返回值：errcode-错误代码（0：成功  其它:失败）
     *       msg-错误消息
     */
    public function member_deposit($amount, $orderid, $username, $userid) {
        $ret = array();
        $tret = $this->member_login($username, $userid);
        if ($tret['errcode'] != 0) {
            $ret["errcode"] = 1;
            // $ret["msg"] = "登录错误：请联系客服";
            $ret["msg"] = $tret['msg'];
            return $ret;
        }
        $url = "https://{$this->config['mem_url']}/member-api-web/member-api";
        $post_str = '<mbrapi-changecredit-call ' .
                'timestamp="' . date("Y-m-d H:i:s") . ' UTC" ' .
                'apiusername="' . $this->config['api_usr'] . '" ' .
                'apipassword="' . $this->config['api_psw'] . '" ' .
                'token="' . $tret["token"] . '" ' .
                'product="casino" ' .
                'operation="topup" ' .
                'amount="' . $amount . '" ' .
                'tx-id="' . $orderid . '" ' .
                '/>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["errcode"] = 1;
            $ret["msg"] = "http错误：错误代码" . $code . "，请联系客服";
        } else {
            $response_xml = simplexml_load_string($response);
            $attributes = $response_xml[0]->attributes();

            if ($attributes['status'] == "0") {
                $ret["errcode"] = 0;
                $ret["token"] = $data["token"];
            } else {
                $ret["errcode"] = 1;
                $ret["msg"] = "MG失败，请联系客服";
            }
        }
        curl_close($ch);
        return $ret;
    }

    /*
     * 功能：会员提款
     * 参数：$_amount-提款金额 $_parm-其它参数
     * 返回值：errcode-错误代码（0：成功  其它:失败）
     *      token-令牌
     *      msg-错误消息
     */

    public function member_withdrawal($amount, $orderid, $username, $userid) {
        $ret = array();
        $tret = $this->member_login($username, $userid);
        if ($tret['errcode'] != 0) {
            $ret["errcode"] = 1;
            $ret["msg"] = "登录错误：请联系客服";
            return $ret;
        }
        $url = "https://{$this->config['mem_url']}/member-api-web/member-api";
        $post_str = '<mbrapi-changecredit-call ' .
                'timestamp="' . date("Y-m-d H:i:s") . ' UTC" ' .
                'apiusername="' . $this->config['api_usr'] . '" ' .
                'apipassword="' . $this->config['api_psw'] . '" ' .
                'token="' . $tret["token"] . '" ' .
                'product="casino" ' .
                'operation="withdraw" ' .
                'amount="' . $amount . '" ' .
                'tx-id="' . $orderid . '" ' .
                '/>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $code = curl_errno($ch);
            $ret["errcode"] = 1;
            $ret["msg"] = "http错误：错误代码" . $code . "，请联系客服";
        } else {
            $response_xml = simplexml_load_string($response);
            $attributes = $response_xml[0]->attributes();

            if ($attributes['status'] == "0") {
                $ret["errcode"] = 0;
                $ret["token"] = $data["token"];
            } else {
                $ret["errcode"] = 1;
                $ret["msg"] = "MG错误：请联系客服";
            }
        }
        curl_close($ch);
        return $ret;
    }

    /*
     * 功能：解析XML
     * 参数：$xml-xml字符串
     * 返回：解析后的数组
     */

    protected function parsexml($xml, &$ret) {
        try {
            $p = xml_parser_create();
            xml_parse_into_struct($p, $res, $ret, $index);
            xml_parser_free($p);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
