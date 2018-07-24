<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NGame
 *
 * @author xingyimingyi
 */
class NGame {
    protected $mem_field;
    protected $config;
    protected $sql_drive;
    public function __construct($_config, $sql_drive) {
        $this->config = $_config;
        $this->sql_drive = $sql_drive;
    }

    public function log_status($id, $status,$err='',$model) {
        // $model = new Model();
        $log = '';
        if($err){
            $log=" ,log='{$err}' ";
        }
        $sql = "update log_money set suss={$status},{$log} where id={$id}";
        $model->query($sql);
    }

    public function log_money($username, $from, $to, $money, $subfix,$model) {
        $sql = "insert into log_money(usernmae,money,`from`,`to`,subfix) values('{$username}','{$money}','{$from}','{$to}','{$subfix}')";
        $model->query($sql);
        return mysql_insert_id();
    }

    /**
     *
     * @param int $id 会员的id
     * @param string $subfix 各个站得前缀
     * @return string 会员密码
     * @todo 会员密码生成规则,用于会员登录app展示给会员
     *
     */
    public function rule_psw($id, $subfix) {
        //global $ptsubfix;
        if (strlen($id) < 6) {
            $qq = '000000' . '' . $id;
            $fix = substr($qq, -6);
        } else {
            $fix = $id;
        }
        $yuan = strtoupper($subfix . $fix);
        return $this->addcoder($yuan);
    }

    public function addcoder($str) {
        $yuan = '05O28QD7N1U6PT4F9ZWHCSRIAVY3XKJBELMG';
        $jia = '95XN6IDMF0Y8BLTRJSCA2E1Z43UPKWOHVQ7G';
        if (strlen($str) == 0)
            return false;
        for ($i = 0; $i < strlen($str); $i++) {
            for ($j = 0; $j < strlen($yuan); $j++) {
                if ($str[$i] == $yuan[$j]) {
                    $results .= $jia[$j];
                    break;
                }
            }
        }
        return $results;
    }

    /**
     *
     * @param int $uid
     * @return boolean
     *
     */
    public function mem_save2db($uid) {
        $model = new Model();
        $sql = "update web_member_data set `{$this->mem_field}`=1 where id=" . $uid . " limit 1";
        $model->query($sql);
        return true;
    }

}
