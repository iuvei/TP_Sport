<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author Administrator
 */
class Otset {

    //put your code here
    private $_db;

    public function __construct($model) {
        //var_dump($dbhost,$dbuser,$dbpass,$dbname);
        $this->_db = $model;
    }

    public function user_add($userid, $table) {
        $this->web_member($userid);
    }

    public function web_member($userid) {
        $table = "web_member_data";
        $sql = "select * from {$table} where ID={$userid}";
        $arr = current($this->_db->query($sql));
        //$arr=mysql_fetch_assoc($res);
        //var_dump($res,$arr);
        $asql = "select id from ssc_user where username='{$arr['Admin']}'";
        $aarr = current($this->_db->query($asql));
        $a = $aarr['id'];
        $bsql = "select id from ssc_user where username='{$arr['Super']}'";
        $barr = current($this->_db->query($bsql));
        $b = $barr['id'];
        $csql = "select id from ssc_user where username='{$arr['Corprator']}'";
        $carr = current($this->_db->query($csql));
        $c = $carr['id'];
        $dsql = "select id from ssc_user where username='{$arr['World']}'";
        $darr = current($this->_db->query($dsql));
        $d = $darr['id'];
        $esql = "select id from ssc_user where username='{$arr['Agents']}'";
        $earr = current($this->_db->query($esql));
        $e = $earr['id'];
        $parentid = $e;
        $parentname = $arr['Agents'];
        if (empty($e) || empty($d) || empty($c) || empty($b) || empty($a)) {
            return false;
        }

        $query = "insert into ssc_user(username,realname,userpsw,parentid,usertype,currency,lcurrency,accounting,parentname,power,game,gameopen,a,b,c,d,e,status,stopuid,position,realpsw)values('{$arr['UserName']}','{$arr['LoginName']}',md5('{$arr['PassWord']}'),{$parentid},0,0,0,0,'{$parentname}','','2,3,45,47,51','2,3,45,47,51',{$a},{$b},{$c},{$d},{$e},0,0,'A','{$arr['PassWord']}')";
        $this->_db->query($query);
        $userid = $this->_db->getLastInsID();
        $setingsql = "INSERT INTO `ssc_usersetting` ( `userid`, `a`, `b`, `c`, `d`, `e`, `gamecode`, `gametype`, `ratea`, `rateb`, `ratec`, `positiona`, `positionb`, `positionc`, `gamemin`, `gamemax`) select {$userid},{$a},{$b},{$c},{$d},{$e},`gamecode`, `gametype`, `ratea`, `rateb`, `ratec`, `positiona`, `positionb`, `positionc`, `gamemin`, `gamemax` from  ssc_usersetting where userid={$parentid}";
        $this->_db->query($setingsql);

        $sixsql = "select id,kauser,adm_id,adm,com_id,com,guanid,guan,zongid,zong,a_point,b_point,c_point,d_point from ka_guan where kauser='{$arr['Agents']}'";
        $sixarr = current($this->_db->query($sixsql));
        $danid = $sixarr['id'];
        $adm_id = $sixarr['adm_id'];
        $adm = $sixarr['adm'];
        $com_id = $sixarr['com_id'];
        $com = $sixarr['com'];
        $guanid = $sixarr['guanid'];
        $guan = $sixarr['guan'];
        $zongid = $sixarr['zongid'];
        $zong = $sixarr['zong'];
        $dagu_zc = $sixarr['a_point'];
        $guan_zc = $sixarr['b_point'];
        $zong_zc = $sixarr['c_point'];
        $dan_zc = $sixarr['d_point'];
        $text = date("Y-m-d H:i:s");
        $query = "INSERT INTO  ka_mem set kapassword=MD5('{$arr['PassWord']}'),kauser='{$arr['UserName']}',xm='{$arr['UserName']}',cs='0',ts='0',adm='{$adm}',com='{$com}',guan='{$guan}',zong='{$zong}',dan='{$sixarr['kauser']}',stat='0',xy='10',adm_id={$adm_id},com_id={$com_id},guanid='" . $guanid . "',zongid='" . $zongid . "',danid='" . $danid . "',look=0,adddate='" . $text . "',slogin='" . $text . "',zlogin='" . $text . "',sip='" . $ip . "',zip='" . $ip . "',abcd='A',dan_zc='" . $dan_zc . "',guan_zc='" . $guan_zc . "',zong_zc='" . $zong_zc . "',dagu_zc='" . $dagu_zc . "' ,ops='',opd='',opp='' ";
        $this->_db->query($query);
        $SoftID = $this->_db->getLastInsID();

        $result = $this->_db->query("select * from  ka_quota where lx=0 and userid=" . $danid . " and flag=0 order by id ");
        $t = 0;
        foreach ($result as $image) {
            $yg = $image['yg'];
            $exe = $this->_db->query("INSERT INTO ka_quota Set yg='" . $yg . "',ygb=0,ygc=0,ygd=0,xx='" . $image['xx'] . "',xxx='" . $image['xxx'] . "',username='{$arr['UserName']}',userid='{$SoftID}',lx=0,flag=1,adm_id='{$adm_id}',com_id='{$com_id}',guanid='" . $guanid . "',zongid='" . $zongid . "',danid='" . $danid . "',memid='" . $SoftID . "',ds='" . $image['ds'] . "',abcd='A',style='" . $image['style'] . "'");
        }
        return true;
    }

}

?>
