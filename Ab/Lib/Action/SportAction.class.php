<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SportAction
 *
 * @author Administrator
 */
class SportAction extends HloginAction {//这个类主要是为了方便以后扩展

    protected $_step;
    protected $_gt;
    protected $_sel_gt;
    protected $_m_date;

    public function __construct() {
        parent::__construct();
        $this->_m_date = date('Y-m-d');
        $this->_gt = array(
            'FT' => array(
                'S_Show', 'H_Show'
            ),
            'BK' => array(
                'S_Show', 'RQ_Show'
            )
        );

        $this->_step = array(
            'game' => '', //选择的球赛类型
            'leag' => '', //选择的联盟
            'team' => '', //选择的队伍
            'more' => '', //多种玩法
            'rate' => ''//最终选择的赔率
        );
        $this->_sel_gt = '';
    }

    public function alert_msg($code) {
        echo '<script>alert("赛事关闭 ' . $code . '");try{clearTimeout(odtimeout);clearTimeout(tentime);}catch(e){}</script>';
        exit;
    }

    protected function header($_action, $_model) {
        import("@.Util.Header");
        $header = new Header($_action, $_model);
        return $header->run();
    }

    public function gametype_count($sel, $model) {
        if ($sel == '') {
            return false;
        }
        $ft = $this->_gt[$sel];
        $sql = "select if(count(MID),count(MID),0) as RB_Show from match_sports where Type='{$sel}' and M_Start<now() and RB_Show=1 and Open=1 and Score=0";
        $re = $model->query($sql);
        if(strtoupper($sel) == 'FT')
        {
            $RbSql = "select if(count(MID),count(MID),0) as RB_WC_Show from match_sports where Type='FT' and M_League like '世界杯2018%' and M_Start<now() and RB_Show=1 and Open=1 and Score=0";
            $reWC = $model->query($RbSql);
            $re[0]['RB_WC_Show'] = $reWC[0]['RB_WC_Show'];
        }       
        if ($sel == 'BK') {
            $sql = "select if(sum(if(RQ_Show<>1,S_Show,0)),sum(if(RQ_Show<>1,S_Show,0)),0) as S_Show, if(sum(RQ_Show),sum(RQ_Show),0) as RQ_Show from match_sports where Type='BK' and M_Start>now() and M_Date='{$this->_m_date}' and RB_Show<>1 ";
            $sum = $model->query($sql);
        } else {
            $sum[0] = array('S_Show' => 0, 'H_Show' => 0,'WC_Show'=>0);
            $sql = "select M_League,MB_LetB_Rate,MB_Dime_Rate,MB_Win_Rate,S_Single_Rate,MB_LetB_Rate_H,TG_Dime_Rate_H,MB_Dime_Rate_H from match_sports where Type='FT' and M_Start>now() and M_Date='{$this->_m_date}' and RB_Show<>1 and S_Show=1 ";
            $sum1 = $model->query($sql);
            foreach ($sum1 as $row) {
                if (!empty($row['MB_LetB_Rate_H']) || !empty($row['MB_Win_Rate_H']) || !empty($row['MB_Dime_Rate_H'])) {
                    $sum[0]['H_Show'] ++;
                }
                if (!empty($row['MB_LetB_Rate']) || !empty($row['MB_Dime_Rate']) || !empty($row['MB_Win_Rate']) || !empty($row['S_Single_Rate'])) {
                    $sum[0]['S_Show'] ++;
                }
                if(strstr($row['M_League'],'世界杯2018') !== False)
                {
                    $sum[0]['WC_Show'] ++;
                }
            }
        }
        return array_merge($re[0], $sum[0]);
    }
    
    public function countwc() {
        $model = new Model();
        $RbSql = "select if(count(MID),count(MID),0) as re from match_sports where Type='FT' and M_League like '世界杯2018%' and M_Start<now() and RB_Show=1 and Open=1 and Score=0";
        $re = $model->query($RbSql);
        $TodaySql = "select count(MID) as today from match_sports where Type='FT' and M_League like '世界杯2018%' and M_Start>now() and M_Date='{$this->_m_date}' and RB_Show<>1 and S_Show=1";
        $today = $model->query($TodaySql);      
        echo json_encode(array('re'=>$re[0]['re'],'today'=>$today[0]['today']));
    }

    public function index() {
        $gametype = strtoupper($this->_sel_gt);
        $t = $_REQUEST['t'];
        if (!in_array($t, array('s', 'r', 'b'))) {

        }

        $_SESSION['_step']['more'] = $t;
        switch ($t) {
            case 's':
                if ($this->_sel_gt == 'FT') {
                    $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='FT' and M_Start>now() and `M_Date` ='{$this->_m_date}' and S_Show=1  group by M_League";
                } else {
                    $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='BK' and M_Start>now() and `M_Date` ='{$this->_m_date}' and S_Show=1 and RQ_Show<>1  group by M_League";
                }
                break;
            case 'b':
                if ($this->_sel_gt == 'FT') {
                    $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='FT' and M_Start>now() and `M_Date` ='{$this->_m_date}' and S_Show=1 and(MB_LetB_Rate_H<>'' or TG_Dime_Rate_H<>'' or MB_Dime_Rate_H<>'') group by M_League";
                } else {
                    $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='BK' and M_Start>now() and `M_Date` ='{$this->_m_date}' and S_Show=1  and RQ_Show=1  group by M_League";
                }
                break;
            case 'r':
                $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='{$gametype}' and RB_Show=1 and MB_Inball='' group by M_League";
                break;
        }
        $model = new Model();
        $leg = $model->query($mysql);
        $this->assign('legs', $leg);
        $money = current($model->query("select Money,UserName from web_member_data where UserName='{$_SESSION['username']}'"));
        $this->assign('money', $money);
        $this->display('Sport:leg');
    }

    public function gametype() {
        $this->assign('header', $this->header(ACTION_NAME, 'Game'));
        $t = strtoupper($this->_sel_gt);
        if ($t) {
            $_SESSION['_step']['game'] = $t;
            $count = $this->gametype_count($t);
            $this->assign('game', $count);
        }

        $this->display('Sport:' . $this->_sel_gt);
    }

    public function leg() {
        $this->assign('header', $this->header(ACTION_NAME, 'Game'));
        $tt = strtoupper($_REQUEST['tt']);
        if ($tt == 'RB') {
            $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='{$_SESSION['_step']['game']}' and M_Date='{$this->_m_date}' and RB_Show=1 and MB_Inball='' group by M_League";
        } else {
            if ($this->_sel_gt == 'BK' && $tt == 'S') {
                $conde = " and RQ_Show<>1 ";
            }
            $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='{$_SESSION['_step']['game']}' and M_Start>now( ) and `M_Date` ='{$this->_m_date}' and " . $tt . "_Show=1 {$conde}  group by M_League";
        }
        $_SESSION['_step']['more'] = $tt;
        //echo $mysql;
        $model = new Model();
        $leg = $model->query($mysql);
        $this->assign('legs', $leg);
        $this->display('Sport:leg');
    }

    public function games() {
        $this->assign('header', $this->header(ACTION_NAME, 'Game'));
        $g = rawurldecode($_REQUEST['g']);
        $tt = $_SESSION['_step']['more'];
        if ($this->_sel_gt == 'BK' && $_SESSION['_step']['more'] == 'S') {
            $conde = " and RQ_Show<>1 ";
        }
        if ($_SESSION['_step']['more'] == 'RB') {
            $mysql = "select MID,MB_Team,TG_Team from match_sports where M_League='{$g}' and `M_Date` ='{$this->_m_date}' and {$tt}_Show=1  and MB_Inball=''";
        } else {
            $mysql = "select MID,MB_Team,TG_Team from match_sports where M_League='{$g}' and M_Start>now() and `M_Date` ='{$this->_m_date}' and {$tt}_Show=1 {$conde}";
        }
        $_SESSION['_step']['leag'] = $g;
        //echo $mysql;
        $this->assign('ttyy', get_type($_SESSION['_step']['game'], $_SESSION['_step']['more']));
        $model = new Model();
        $teams = $model->query($mysql);
        $this->assign('teams', $teams);
        $this->display('Sport:games');
    }
    public function team() {
        $leg = $_REQUEST['g']; //联盟
        $m_date = date('Y-m-d');
        $date = date('m-d');
        import('@.Util.Team.Gamerate');
        $method = strtolower($this->_sel_gt) . '_rate';
        $_SESSION['_step']['team'] = $leg;
        $rates = call_user_func_array(array(new Gamerate(), $method), array($_SESSION['_step']));
        $this->assign('rates', $rates);
        $this->assign('leg', $leg);
        $this->assign('show_type', $_SESSION['_step']['more']);
        $this->display('Sport:team');
    }

    public function rate() {
        $this->assign('header', $this->header(ACTION_NAME, 'Game'));
        $t = strtoupper($_REQUEST['t']);
        $model = new Model();
        if ('ft' == strtolower($this->_sel_gt)) {
            $method = 'rate_' . $t;
        } else {
            $method = strtolower($this->_sel_gt) . '_rate_' . $t;
        }

        $M_Rate = $this->$method($model);
        if ($_REQUEST['ajax'] != 1) {
            $this->assign('reff', $_SERVER['HTTP_REFERER']);
            $this->display('Sport:' . ucfirst(strtolower($this->_sel_gt)) . '_' . $t);
        } else {
            echo json_encode(array('M_Rate' => $M_Rate));
        }
    }


    /**
     *
     * @param type $toptype 足球或者篮球
     * @param type $sor 单式或者滚球
     *
     */


    public function slist() {

        $leg = array('日本J1联赛' => '日职联', '日本J1联赛-特别投注' => '日职联特', '日本J3联赛' => '日丙', '日本J2联赛' => '日职乙', '澳洲昆士兰州国家超级联赛' => '澳昆超', '澳洲维多利亚国家超级联赛' => '澳维超', '南韩K挑战联赛' => '韩挑K联', '南韩K经典联赛' => '韩K联', '澳洲甲组联赛' => '澳洲甲', '英格兰超级联赛' => '英超', '英格兰超级联赛-特别投注' => '英超特', '德国甲组联赛' => '德甲', '德国甲组联赛-特别投注' => '德甲特', '西班牙甲组联赛' => '西甲', '西班牙甲组联赛-特别投注' => '西甲特', '意大利甲组联赛' => '意甲', '意大利甲组联赛-特别投注' => '意甲特', '德国乙组联赛' => '德乙', '意大利乙组联赛' => '意乙', '西班牙乙组联赛' => '西乙', '奥地利甲组联赛' => '奥甲', '澳洲甲组联赛' => '澳甲', '比利时甲组联赛-附加赛' => '比甲附', '比利时甲组联赛-附加赛-特别投注' => '比甲附特', '丹麦超级联赛' => '丹超', '丹麦超级联赛-特别投注' => '丹超特', '法國甲組聯賽' => '法甲', '法国甲组联赛-特别投注' => '法甲特', '荷兰甲组联赛' => '荷甲', '荷兰甲组联赛-特别投注' => '荷甲特', '挪威超级联赛' => '挪超', '挪威超级联赛-特别投注' => '挪超特', '美国职业大联盟' => '美职', '美国职业大联盟-特别投注' => '美职特', '俄罗斯超级联赛' => '俄超', '俄罗斯超级联赛-特别投注' => '俄超特', '中国超级联赛' => '中超', '中国超级联赛-特别投注' => '中超特', '瑞典超级联赛' => '瑞超', '瑞典超级联赛-特别投注' => '瑞超特', '苏格兰联赛挑战杯' => '苏挑杯', '德国丙组联赛' => '德丙', '德国北部联赛' => '德北', '德国东北部联赛' => '德东北', '德国西部联赛' => '德西', '德国西南部联赛' => '德西南', '巴西里约州甲组联赛' => '里约锦标', '巴西圣保罗州甲组一联赛' => '巴伊联附', '丹麦甲组联赛' => '丹甲', '挪威甲组联赛' => '挪甲', '葡萄牙超级联赛' => '葡超', '葡萄牙超级联赛-特别投注' => '葡超特', '葡萄牙甲组联赛' => '葡甲', '瑞典超级甲组联赛' => '瑞典甲', '瑞士甲组联赛' => '瑞士甲', '土耳其超级联赛' => '土超', '土耳其甲组联赛' => '土甲', '波兰甲组联赛' => '波甲', '阿根廷甲组联赛' => '阿甲', '阿根廷甲组联赛-特别投注' => '阿甲特', '捷克甲组联赛' => '捷甲', '墨西哥甲组联赛' => '墨甲', '智利甲组联赛' => '智甲', '克罗地亚甲组联赛' => '克甲', '希腊超级联赛' => '希超', '希腊超级联赛-特别投注' => '希超特', '罗马尼亚甲组联赛-附加赛' => '罗甲附', '澳洲维多利亚国家超级联赛' => '澳维超', '澳洲布里斯班超级联赛' => '澳里超', '澳洲新南威尔斯国家超级联赛' => '澳新超', '西澳洲国家超级联赛' => '西澳超', '阿根廷乙组全国联赛' => '阿乙', '意大利职业联赛' => '意丙1B', '西班牙乙组联赛-特别投注' => '西乙特', '英格兰冠军联赛' => '英冠', '法国乙组联赛' => '法乙', '爱尔兰超级联赛' => '爱超', '欧洲冠军杯' => '欧冠', '英格兰足总杯' => '英足总', '欧足联欧洲联赛' => '欧足联', '德国杯' => '德杯', '法国联赛杯' => '法杯', '意大利杯' => '意杯', '西班牙杯' => '西杯', '美洲国家百年纪念杯2016(在美国)' => '美洲杯', '欧洲足球锦标赛2016(在法国)' => '欧洲杯', '澳洲布里斯班后备超级联赛' => '澳布后', '哥斯达黎加甲组联赛' => '哥甲', '澳洲维多利亚国家超级联赛U20' => '澳维U20', 'NBA美国职业篮球联赛' => 'NBA', '东南澳洲女子篮球联赛' => '东南澳女', '篮网球-ANZ锦标赛' => 'ANZ', '捷克丙组联赛MSFL' => '捷丙', '中国甲组联赛' => '中甲', '俄罗斯联赛U21' => '俄联U21', '德国乙组联赛-特别投注' => '德乙特', '瑞士超级联赛' => '瑞士超', '瑞典乙组联赛' => '瑞典乙', '阿美尼亚超级联赛' => '阿美超', '比利时乙组联赛' => '比乙', '西班牙乙组联赛B' => '西乙B', '捷克国家联赛' => '捷联', '越南甲组联赛' => '越甲', '日本足球联赛' => '日足联');

        $type = isset($_REQUEST['tt'])?$_REQUEST['tt']:'FT';
        $sor = isset($_REQUEST['sr'])?$_REQUEST['sr']:'';
        $wordCup = isset($_REQUEST['wc'])?true:false;
        $model = new Model();
        if (session('username')) {
            $money = current($model->query("select Money,UserName from web_member_data where UserName='{$_SESSION['username']}'"));
            $this->assign('money', $money);
        }

            $model = new Model();
            $rel = $model->query("select * from web_weihu");
            $wh = $rel;

            if($wh->wapsport==1 or $wh->jinri == 1){
                redirect ('/index.php/Sport/weihu1');
            }

        $ft = $this->gametype_count('FT', $model);
        $bk = $this->gametype_count('BK', $model);
        $this->assign('ft', $ft);
        $this->assign('bk', $bk);
        $m_date = date('Y-m-d');
        $wordCupSql = $wordCup ? " AND `M_League` like '世界杯2018%' " : '';
        //$mysql="select MID,M_Time,MB_Team,TG_Team,M_League,Type,0 as Ror from match_sports where Type='{$_REQUEST['tt']}' and M_Start>now() AND M_Date='$m_date' and S_Show=1 and MB_Team<>'' and Open=1 order by M_Start,M_League";
        $mysql = "SELECT GROUP_CONCAT( MID ) as MID , M_Time, MB_Team, TG_Team, M_League, Type , 0 AS Ror FROM match_sports WHERE Type =  '{$type}' AND M_Start > NOW( )  AND M_Date =  '$m_date' AND S_Show =1 AND MB_Team <>  '' AND OPEN =1 {$wordCupSql} GROUP BY mb_team, m_league order by M_Start asc";
        //echo $mysql;
        $sports = $model->query($mysql);

        $mysql2 = "select GROUP_CONCAT( MID ) as MID ,now_play,M_League,MB_MID,TG_MID,MB_Team,TG_Team,MB_Ball,TG_Ball,Type,M_Time,1 as Ror from match_sports where RB_show=1 and type='{$type}' and Score=0 and Open=1 {$wordCupSql}  GROUP BY mb_team, m_league order by crown_order,m_start,m_league";

        $rbsports = $model->query($mysql2);
        $this->assign('leg', $leg);
        $this->assign('tt', $_REQUEST['tt']);
        if($_REQUEST['tt'] =="FT"){
            if($_REQUEST['sr']){
                if($wh['gunqiu']){
                    redirect ('/index.php/Sport/weihu1');
                }
                $this->assign('ttt', 2);
             }else{
               if($wh['jinri']){
                    redirect ('/index.php/Sport/weihu');
                }
                $this->assign('ttt', 1);
             }
        }

        if($_REQUEST['tt']=="BK" ){
            if($_REQUEST['sr']){
                if($wh['gunqiu']){
                    redirect ('/index.php/Sport/weihu1');
                }
                 $this->assign('ttt', 4);
             }else{
                if($wh['jinri']){
                    redirect ('/index.php/Sport/weihu');
                }
                $this->assign('ttt', 3);
             }
        }
        if( $_REQUEST['wc']==1){
            $this->assign('ttt', 5);
        }
        if($_REQUEST['wc']==1 && $_REQUEST['sr']==1){
            $this->assign('ttt', 6);
        }

        if(isset($_REQUEST['tt'])){
            if($sor==1){
                $this->assign('rbsports',$rbsports);
             }elseif($sor==0){
                $this->assign('sports', $sports);
             }

        }else{
            $this->assign('rbsports', $rbsports);
            $this->assign('sports', $sports);

        }

        $this->assign('sor', $sor);
        $this->display();

    }

    public function srate() {
        $id = $_REQUEST['id'];
        $ids = explode(',', $id);
        foreach ($ids as $idd) {
            $tmp[] = (int) $idd;
            if ((int) $idd <= 0) {//赛事不存在或者赛事关盘
                echo '<script>history.go(-1);</script>';
                exit();
            }
        }
        if (empty($tmp)) {
            echo '<script>history.go(-1);</script>';
            exit();
        }
        $id = implode(',', $tmp);
        $model = new Model();
        if (session('username')) {
            $money = current($model->query("select Money,UserName from web_member_data where UserName='{$_SESSION['username']}'"));
            $this->assign('money', $money);
        }
        $tt = strtolower($_REQUEST['tt']);
        if (!in_array($tt, array('ft', 'bk')))
            $tt = 'ft';
        $ror = $_REQUEST['ror'];
        if ($ror) {
            $more = 'r';
        } else {
            $more = 's';
        }
        import('@.Util.Team.Teamrate');
        $sport = call_user_func_array(array(new Teamrate(), $tt . '_rate'), array(array('id' => $id, 'more' => $more)));
        //var_dump($sport);
        if (empty($sport)) {
            echo '<script>alert("赛事关闭2。");history.go(-1);</script>';
            exit();
        }
        import('@.Util.RbFuntion');
        $RbFuntion = new RbFuntion();
        //正则替换将 O U去掉       
        foreach ($sport as $key => $value) {
            $ball_type = $tt == 'ft' ? 1 : 2;
            if (!$RbFuntion->is_intercept($value['MID'], $ball_type , 0, $value['MB_Ball'],$value['TG_Ball'])) {
                // $sport[$key]['MB_Dime_RB'] = ltrim($value['MB_Dime_RB'] ,"O");
                // $sport[$key]['TG_Dime_RB'] = ltrim($value['TG_Dime_RB'] ,"U");
                $sport[$key]['MB_Dime_RB'] = preg_replace("/[A-Z]/",'',$value['MB_Dime_RB']);
                $sport[$key]['MB_Dime_RB_H'] = preg_replace("/[A-Z]/",'',$value['MB_Dime_RB_H']);
                $sport[$key]['TG_Dime_RB'] = preg_replace("/[A-Z]/",'',$value['TG_Dime_RB']);
                $sport[$key]['TG_Dime_RB_H'] = preg_replace("/[A-Z]/",'',$value['TG_Dime_RB_H']);
                $sport[$key]['MB_Dime'] = preg_replace("/[A-Z]/",'',$value['MB_Dime']);
                $sport[$key]['TG_Dime'] = preg_replace("/[A-Z]/",'',$value['TG_Dime']);
                $sport[$key]['MB_Dime_H'] = preg_replace("/[A-Z]/",'',$value['MB_Dime_H']);
                $sport[$key]['TG_Dime_H'] = preg_replace("/[A-Z]/",'',$value['TG_Dime_H']);
                $sport[$key]['ratio_ouho'] = preg_replace("/[A-Z]/",'',$value['ratio_ouho']);
                $sport[$key]['ratio_ouhu'] = preg_replace("/[A-Z]/",'',$value['ratio_ouhu']);
                $sport[$key]['ratio_ouco'] = preg_replace("/[A-Z]/",'',$value['ratio_ouco']);
                $sport[$key]['ratio_oucu'] = preg_replace("/[A-Z]/",'',$value['ratio_oucu']);
                $sport[$key]['TG_P_Dime'] = preg_replace("/[A-Z]/",'',$value['TG_P_Dime']);
                $sport[$key]['MB_P_Dime'] = preg_replace("/[A-Z]/",'',$value['MB_P_Dime']);
            }
            else
            {
                $sport[$key]['MB_Dime_RB'] = '';
                $sport[$key]['MB_Dime_RB_H'] = '';
                $sport[$key]['TG_Dime_RB'] = '';
                $sport[$key]['TG_Dime_RB_H'] = '';
                $sport[$key]['MB_Dime'] = '';
                $sport[$key]['TG_Dime'] = '';
                $sport[$key]['MB_Dime_H'] = '';
                $sport[$key]['TG_Dime_H'] = '';
                $sport[$key]['ratio_ouho'] = '';
                $sport[$key]['ratio_ouhu'] = '';
                $sport[$key]['ratio_ouco'] = '';
                $sport[$key]['ratio_oucu'] = '';
                $sport[$key]['TG_P_Dime'] = '';
                $sport[$key]['MB_P_Dime'] = '';
            }
        }
        // echo '<pre>';
        // dump($sport);die;
        $this->assign('sport', $sport);
        $this->display();
    }
      public function weihu1() {
            $this->display();
        }

        public function weihu(){
            $this->display();
        }

        public function ajax_weihu(){
            $type = $_REQUEST['type'];
            $model = new Model();
            $rel = $model->query("select * from web_weihu");
            $wh = $rel;
            if($type=='jrss'){
                echo $wh->jinri;exit;
            }
            if($type=='gq'){
                echo $wh->gunqiu;exit;
            }
        }
}