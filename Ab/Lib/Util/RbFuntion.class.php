<?php
/*
 *作用：RB公用配置文件
 *研发：小七
 *时间：2017-06-11 16:59
 *版本：V1.0 
 */

class RbFuntion
{
	
	private $RB_mcd;
	public function __construct()
    {
        $memecached_ip = '192.168.10.106';
		$this->RB_mcd = new Memcached();
		$this->RB_mcd->addServer($memecached_ip, 11211, 100);
    }
	/*
	 *作用 ：判断是否拦截
	 *@para: mid int 赛事ID
	 *@para: type int 赛事类型 1足球；2篮球
	 *@para: addtype int 拦截种类 0展示拦截；1下注拦截
	 *@para: score_home int 主队比分 足球不需要
	 *@para: score_away int 客队比分 足球不需要
	 */
	public function is_intercept($mid,$type=1,$addtype=0,$score_home=0,$score_away=0)
	{
		$RB_match = $this->RB_mcd->get($mid);
		if($RB_match['mid']>0 and time()-$RB_match['system_time']<20)
		{
			if($type==1 and $RB_match['type']=='FT' and $RB_match['overdue']==1)
			{
				$this->add_intercept_times($RB_match,$addtype,$type);
				return TRUE;
			}
			else if($type==2 and $RB_match['type']=='BK' and ($RB_match['score_home']!=$score_home or $RB_match['score_away']!=$score_away))
			{
				$this->add_intercept_times($RB_match,$addtype,$type);
				return TRUE;
			}
		}
		return FALSE;
	}

	//将1天半的记录移到历史表中
	public function move_record_history()
	{
		$days_ago = date('Y-m-d h:i:s',time()-60*60*36);
		$history_sql = "insert into `RB_intercept_history_log`(`MID`,`type`,`overdue`,`score_home`,`score_away`,`event_time`,`system_time`,`Show_intercept_times`,`order_intercept_times`) select `MID`,`type`,`overdue`,`score_home`,`score_away`,`event_time`,`system_time`,`Show_intercept_times`,`order_intercept_times` from `RB_intercept_log` where `system_time`<'{$days_ago}'";
		mysql_query($history_sql);
		mysql_query("delete from `RB_intercept_log` where `system_time`<'{$days_ago}'");
		return true;
	}

	/*
	 *作用 ：增加拦截次数
	 *@para: RB_match arr 赛事信息
	 *@para: addtype int 拦截种类 0展示拦截；1下注拦截
	 *@para: type int 赛事类型 1足球；2篮球
	 */
	public function add_intercept_times($RB_match,$addtype=0,$type=1)
	{
		$type=$type+2;
		$addtype_arr = array('Show_intercept_times','order_intercept_times');
		$sql = 'select `MID` from `RB_intercept_log` where `MID`='.$RB_match['mid']; 
		$result = mysql_query($sql);
		$time_char = date('Y-m-d h:i:s',$RB_match['system_time']);
		if(mysql_num_rows($result))
		{
			$update_sql = "update `RB_intercept_log` set `overdue`='{$RB_match['overdue']}',`score_home`='{$RB_match['score_home']}',`score_away`='{$RB_match['score_away']}',`event_time`='{$RB_match['event_time']}',`system_time`='{$time_char}',`{$addtype_arr[$addtype]}`=`{$addtype_arr[$addtype]}`+1 where `MID`={$RB_match['mid']}";
			mysql_query($update_sql);
		}
		else
		{
			$insert_sql = "insert into `RB_intercept_log`(`MID`,`type`,`overdue`,`score_home`,`score_away`,`event_time`,`system_time`,`{$addtype_arr[$addtype]}`) values({$RB_match['mid']},{$type},'{$RB_match['overdue']}','{$RB_match['score_home']}','{$RB_match['score_away']}','{$RB_match['event_time']}','{$time_char}',1)";
			mysql_query($insert_sql);
		}
		$this->move_record_history();
		return true;
	}
}

?>