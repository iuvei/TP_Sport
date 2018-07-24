<?php
// 本类由系统自动生成，仅供测试用途
class GameAction extends Action {
    /**
     * @todo 显示帐号明细
     * @param null
     */
    private $_header;
    private $_m_date;
    private $_gt;
    private $_step;
    public function __construct() {
        parent::__construct();
        $this->_header='';
        $this->_m_date=date('Y-m-d');
        $this->_gt=array(
            'FT'=>array(
                'S_Show','PD_Show','HPD_Show','T_Show','F_Show','P3_Show'
            ),
            'BK'=>array(
                'S_Show','RQ_Show','PR_Show'
            )
        );
        
        $this->_step=array(
            'game'=>'',//选择的球赛类型
            'leag'=>'',//选择的联盟
            'team'=>'',//选择的队伍
            'more'=>'',//多种玩法
        );
    }
    /**
     * @todo 显示所有可以下注的内容 
     */
    public function index(){
        $_SESSION['_step']=$this->_step;
        $this->assign('header',$this->header(ACTION_NAME,'Game'));
        $this->display();
    }
    /**
     * @todo 显示联盟
     */
    public function leg(){
        $tt=strtoupper($_REQUEST['tt']);
        if($tt=='RB'){
            $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='{$_SESSION['_step']['game']}' and M_Date='{$this->_m_date}' and RB_Show=1 and MB_Inball='' group by M_League";
        }
        else{
            $mysql = "select M_League,count(MID) as teamnum FROM match_sports WHERE Type='{$_SESSION['_step']['game']}' and M_Start>now( ) and `M_Date` ='{$this->_m_date}' and ".$tt."_Show=1  group by M_League";
        }
        $_SESSION['_step']['more']=$tt;
        //echo $mysql;
        $model=new Model();
        $model->query('set names latin1');
        $leg=$model->query($mysql);header("Content-Type: text/html; charset=utf-8"); 
        echo '<ul>';
        foreach($leg as $row=>$ary){
            echo '<li><a href="/index.php/Game/games/g/'.rawurlencode($ary['M_League']).'/tt/'.$tt.'">'.$ary['M_League'].'('.$ary['teamnum'].')</a></li>';
        }
        echo '</ul>';
    }
	
    /**
     * @todo 显示赛事明细
     */
    public function games(){
        $g=rawurldecode($_REQUEST['g']);
        $tt=$_SESSION['_step']['more'];
        $mysql="select MB_Team,TG_Team from match_sports where M_League='{$g}' and M_Start>now() and `M_Date` ='{$this->_m_date}' and {$tt}_Show=1";
        $_SESSION['_step']['leag']=$g;
        //echo $mysql;
        $model=new Model();
        $model->query('set names latin1');
        $leg=$model->query($mysql);header("Content-Type: text/html; charset=utf-8"); 
        echo '<ul>';
        $teams=$model->query($mysql);
        foreach($teams as $k=>$team){
            echo '<li>'.$team['MB_Team'].'   V.S   '.$team['TG_Team'].'</li>';
        }
        echo '</ul>';
    }
    
    public function team(){
        $team_id=$_REQUEST['tid'];
        $_SESSION['_step']['team']=$team_id;
        $m_date=date('Y-m-d');
        $date=date('m-d');
        import("@.Util.Team#Gamesql");
        $game_rate=new Gamerate();
        $rate=$game_rate->get_rate($_SESSION['_step']);
        $team_sql="select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `match_sports` where MID={$team_id} and `Type`='FT' and `M_Start`>now() AND `M_Date` ='$m_date' and `S_Show`=1 and MB_Team!='' and `Open`=1";
        
    }
    /**
     * @todo 显示有赛事的分类
     * @param $_REQUEST['t'] 
     */
    public function gametype(){
        $t=strtoupper($_REQUEST['t']);
        if($t){
            $_SESSION['_step']['game']=$t;
            $count=$this->gametype_count($t);
            $this->assign('game',$count);
        }
        
        $this->display();
    }
    
    private function header($_action,$_model){
        import("@.Util.Header");
        $header=new Header($_action, $_model);
        return $header->run();
    }
    
    public function gamelist(){
        $server = $_SERVER['SERVER_ADMIN'];
        $model=new Model();
        $money=current($model->query("select Money,UserName from web_member_data where UserName='{$_SESSION['username']}'"));
        $this->assign('money',$money);
        if($_SESSION['referrer_url'.$_SESSION['uid']]){
            $this->assign('referrer_url', $_SESSION['referrer_url'.$_SESSION['uid']]);
        }
        $ft=$this->gametype_count('FT',$model);
        $bk=$this->gametype_count('BK',$model);
        $this->assign('ft', $ft);
        $this->assign('bk', $bk);
        $this->assign('server', $server);
        $this->display();
    }

    public function gametype_count($sel, $model) {
        if ($sel == '') {
            return false;
        }
        $ft = $this->_gt[$sel];
        $sql = "select if(count(MID),count(MID),0) as RB_Show from match_sports where Type='{$sel}' and M_Start<now() and RB_Show=1 and Open=1 and Score=0";
		//echo $sql;
        //$model = new Model();
        $re = $model->query($sql);
        if($sel=='BK'){
            $sql="select if(sum(if(RQ_Show<>1,S_Show,0)),sum(if(RQ_Show<>1,S_Show,0)),0) as S_Show, if(sum(RQ_Show),sum(RQ_Show),0) as RQ_Show from match_sports where Type='BK' and M_Start>now() and M_Date='{$this->_m_date}' and RB_Show<>1 ";
			//echo $sql;
            $sum = $model->query($sql);
        }
        else{
            $sum[0]=array('S_Show'=>0,'H_Show'=>0);
            $sql="select MB_LetB_Rate,MB_Dime_Rate,MB_Win_Rate,S_Single_Rate,MB_LetB_Rate_H,TG_Dime_Rate_H,MB_Dime_Rate_H from match_sports where Type='FT' and M_Start>now() and M_Date='{$this->_m_date}' and RB_Show<>1 and S_Show=1 ";
            $sum1=$model->query($sql);
            foreach($sum1 as $row){
                if(!empty($row['MB_LetB_Rate_H']) || !empty($row['MB_Win_Rate_H']) || !empty($row['MB_Dime_Rate_H'])){
                    $sum[0]['H_Show']++;
                }
                if(!empty($row['MB_LetB_Rate']) || !empty($row['MB_Dime_Rate']) || !empty($row['MB_Win_Rate']) || !empty($row['S_Single_Rate'])){
                    $sum[0]['S_Show']++;
                }
            }
        }
        return array_merge($re[0], $sum[0]);
    }
}