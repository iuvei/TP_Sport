<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gamesql
 *
 * @author Administrator
 */
class Gamerate {

    //put your code here
    private $_param;

    public function __construct($param = array()) {
        $this->_param = $param;
    }

    public function ft_rate($param = array()) {
        $this->_param = $param;
        $gmid = '';
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];
        if ($this->_param['more'] == 'r') {
			//echo 'qqqqq';
            $mysql = "select datasite,uid,uid_tw,uid_en from web_system_data where ID=1";
            $row = current($model->query($mysql));
            $site = $row['datasite'];
            $suid = $row['uid'];
            import('@.Util.Team.Curlhttp');
            $curl = &new Curlhttp();
            $curl->store_cookies("cookies.txt");
            $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            $curl->set_referrer("" . $site . "/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
            $html_data = $curl->fetch_url("" . $site . "/app/member/FT_browse/body_var.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
			//var_dump("" . $site . "/app/member/FT_browse/body_var.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
			//var_dump($html_data );
            $a = array("if(self == top)", "<script>", "</script>", "new Array()", "parent.GameFT=new Array();", "\n\n");
            $b = array("", "", "", "", "", "");
            unset($matches);
            unset($datainfo);
            $msg = str_replace($a, $b, $html_data);
            preg_match_all("/g\(\[(.+?)\]\);/is", $msg, $matches);
			//var_dump($matches);
            $cou = sizeof($matches[1]);
			//var_dump($cou);
            $page_count = floor($cou / 60) + 1;
            for ($i = 0; $i < $cou; $i++) {
                $messages = $matches[1][$i];
		        $messages=str_replace("g([","",$messages);
	            $messages=str_replace("'","",$messages);
	            $messages=str_replace("]);","",$messages);
                $datainfo = explode(",", $messages);
                //        var_dump($datainfo);

                $opensql = "select * from match_sports where  MID='$datainfo[0]' and Type='FT'";
                $openrow = current($model->query($opensql));
                //var_dump($openrow['Open']);
                if ($openrow['Open'] == 1) {
                    $sql = "update match_sports set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',MB_Win_Rate_RB='$datainfo[33]',TG_Win_Rate_RB='$datainfo[34]',M_Flat_Rate_RB='$datainfo[35]',MB_Win_Rate_RB_H='$datainfo[36]',TG_Win_Rate_RB_H='$datainfo[37]',M_Flat_Rate_RB_H='$datainfo[38]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and Type='FT'";
                    $model->execute($sql);
                    if ($datainfo[9] != '') {
                        $datainfo[9] = change_rate($open, $datainfo[9]);
                        $datainfo[10] = change_rate($open, $datainfo[10]);
                    }
                    if ($datainfo[13] != '') {
                        $datainfo[13] = change_rate($open, $datainfo[13]);
                        $datainfo[14] = change_rate($open, $datainfo[14]);
                    }
                    if ($datainfo[23] != '') {
                        $datainfo[23] = change_rate($open, $datainfo[23]);
                        $datainfo[24] = change_rate($open, $datainfo[24]);
                    }
                    if ($datainfo[28] != '') {
                        $datainfo[28] = change_rate($open, $datainfo[28]);
                        $datainfo[27] = change_rate($open, $datainfo[27]);
                    }
                    $datainfo[19] = $datainfo[19] + 0;
                    $datainfo[18] = $datainfo[18] + 0;

                    if ($gmid == '') {
                        $gmid = $datainfo[0];
                    } else {
                        $gmid = $gmid . ',' . $datainfo[0];
                    }
                    if ($this->_param['team'] == $datainfo[2]) {
                        //var_dump($datainfo);
                        if ($datainfo[7] == 'H') {
                            $MG_LetB_RB = $datainfo[8];
                            $TG_LetB_RB = '';
                        } else {
                            $MG_LetB_RB = '';
                            $TG_LetB_RB = $datainfo[8];
                        }
                        if ($datainfo[21] == 'H') {
                            $MG_LetB_RB_H = $datainfo[22];
                            $TG_LetB_RB_H = '';
                        } else {
                            $MG_LetB_RB_H = '';
                            $TG_LetB_RB_H = $datainfo[22];
                        }
                        $rate[] = array(
                            'MID'=>$datainfo[0],'M_M_M'=>$datainfo[1],'M_Time' => $datainfo[42], 'MB_Team' => $datainfo[5], 'TG_Team' => $datainfo[6], 'ShowTypeRB' => $datainfo[7], 'M_LetB_RB' => $datainfo[8], 'MB_LetB_Rate_RB' => $datainfo[9], 'TG_LetB_Rate_RB' => $datainfo[10], 'MB_Dime_RB' => $datainfo[11], 'TG_Dime_RB' => $datainfo[12], 'MB_Dime_Rate_RB' => $datainfo[14], 'TG_Dime_Rate_RB' => $datainfo[13], 'ShowTypeHRB' => $datainfo[21], 'M_LetB_RB_H' => $datainfo[22], 'MB_LetB_Rate_RB_H' => $datainfo[23], 'TG_LetB_Rate_RB_H' => $datainfo[24], 'MB_Dime_RB_H' => $datainfo[25], 'TG_Dime_RB_H' => $datainfo[26], 'MB_Dime_Rate_RB_H' => $datainfo[28], 'TG_Dime_Rate_RB_H' => $datainfo[27], 'MB_Ball' => $datainfo[18], 'TG_Ball' => $datainfo[19], 'MB_Card' => $datainfo[29], 'TG_Card' => $datainfo[30], 'MB_Red' => $datainfo[31], 'TG_Red' => $datainfo[32], 'MB_Win_Rate_RB' => $datainfo[33], 'TG_Win_Rate_RB' => $datainfo[34], 'M_Flat_Rate_RB' => $datainfo[35], 'MB_Win_Rate_RB_H' => $datainfo[36], 'TG_Win_Rate_RB_H' => $datainfo[37], 'M_Flat_Rate_RB_H' => $datainfo[38], 'Eventid' => $datainfo[39], 'Hot' => $datainfo[40], 'Play' => $datainfo[41], 'MG_LetB_RB' => $MG_LetB_RB, 'TG_LetB_RB' => $TG_LetB_RB, 'MG_LetB_RB_H' => $MG_LetB_RB_H, 'TG_LetB_RB_H' => $TG_LetB_RB_H);
                    }
                }
            }
            // $sql = "update match_sports set RB_Show=0 where RB_Show=1 and locate(MID,'$gmid')<1";
            // $model->execute($sql);
        } elseif ($this->_param['more'] == 's' || $this->_param['more'] == 'b') {
            $mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from match_sports where Type='FT' and M_Start>now() AND S_Show=1 and MB_Team!='' and Open=1 and M_League='{$this->_param['team']}'";
            $rows = $model->query($mysql);
            foreach ($rows as $row) {
                $MB_Win_Rate = num_rate($open, $row["MB_Win_Rate"]);
                $TG_Win_Rate = num_rate($open, $row["TG_Win_Rate"]);
                $M_Flat_Rate = num_rate($open, $row["M_Flat_Rate"]);
                $MB_LetB_Rate = change_rate($open, $row['MB_LetB_Rate']);
                $TG_LetB_Rate = change_rate($open, $row['TG_LetB_Rate']);
                $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
                $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);
                $S_Single_Rate = num_rate($open, $row['S_Single_Rate']);
                $S_Double_Rate = num_rate($open, $row['S_Double_Rate']);

                $MB_Win_Rate_H = num_rate($open, $row["MB_Win_Rate_H"]);
                $TG_Win_Rate_H = num_rate($open, $row["TG_Win_Rate_H"]);
                $M_Flat_Rate_H = num_rate($open, $row["M_Flat_Rate_H"]);
                $MB_LetB_Rate_H = change_rate($open, $row['MB_LetB_Rate_H']);
                $TG_LetB_Rate_H = change_rate($open, $row['TG_LetB_Rate_H']);
                $MB_Dime_Rate_H = change_rate($open, $row["MB_Dime_Rate_H"]);
                $TG_Dime_Rate_H = change_rate($open, $row["TG_Dime_Rate_H"]);

                if ($row['HPD_Show'] == 1 and $row['PD_Show'] == 1 and $row['T_Show'] == 1 and $row['F_Show'] == 1) {
                    $show = 4;
                } else if ($row['PD_Show'] == 1 and $row['T_Show'] == 1 and $row['F_Show'] == 1) {
                    $show = 3;
                } else {
                    $show = 0;
                }
                if ($row['M_Type'] == 1) {
                    $Running = "<br><font color=red>滚球</font>";
                } else {
                    $Running = "";
                }
                if ($S_Single_Rate == '' and $S_Double_Rate == '') {
                    $odd = '';
                    $even = '';
                } else {
                    $odd = "$o";
                    $even = "$e";
                }

                if ($row['ShowTypeR'] == 'H') {
                    $row['MG_LetB'] = $row['M_LetB'];
                    $row['TG_LetB'] = '';
                } else {
                    $row['MG_LetB'] = '';
                    $row['TG_LetB'] = $row['M_LetB'];
                }

                if ($row['ShowTypeHR'] == 'H') {
                    $row['MG_LetB_H'] = $row['M_LetB_H'];
                    $row['TG_LetB_H'] = '';
                } else {
                    $row['MG_LetB_H'] = '';
                    $row['TG_LetB_H'] = $row['M_LetB_H'];
                }

                $rate[] = array(
                    'MID'=>$row['MID'],
                    'M_Time' => $row['M_Time'],
                    'MB_Team' => $row['MB_Team'],
                    'TG_Team' => $row['TG_Team'],
                    'M_LetB' => $row['M_LetB'],
                    'MG_LetB' => $row['MG_LetB'],
                    'TG_LetB' => $row['TG_LetB'],
                    'MG_LetB_H' => $row['MG_LetB_H'],
                    'TG_LetB_H' => $row['TG_LetB_H'],
                    'MB_LetB_Rate' => $MB_LetB_Rate,
                    'TG_LetB_Rate' => $TG_LetB_Rate,
                    'MB_Dime_Rate' => $MB_Dime_Rate,
                    'TG_Dime_Rate' => $TG_Dime_Rate,
                    'MB_Win_Rate' => $MB_Win_Rate,
                    'TG_Win_Rate' => $TG_Win_Rate,
                    'M_Flat_Rate' => $M_Flat_Rate,
                    'S_Single_Rate' => $S_Single_Rate,
                    'S_Double_Rate' => $S_Double_Rate,
                    'M_LetB_H' => $row['M_LetB_H'],
                    'MB_LetB_Rate_H' => $MB_LetB_Rate_H,
                    'TG_LetB_Rate_H' => $TG_LetB_Rate_H,
                    'MB_Dime' => $row['MB_Dime'],
                    'TG_Dime' => $row['TG_Dime'],
                    'MB_Dime_H' => $row['MB_Dime_H'],
                    'TG_Dime_H' => $row['TG_Dime_H'],
                    'TG_Dime_Rate_H' => $TG_Dime_Rate_H,
                    'MB_Dime_Rate_H' => $MB_Dime_Rate_H,
                    'MB_Win_Rate_H' => $MB_Win_Rate_H,
                    'TG_Win_Rate_H' => $TG_Win_Rate_H,
                    'M_Flat_Rate_H' => $M_Flat_Rate_H
                );
            }
        }
        return $rate;
    }

    public function bk_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];
        if ($this->_param['more'] == 's') {
            $mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,Eventid,Hot,Play from match_sports where M_League='{$this->_param['team']}' and Type='BK' and  M_Start>now() AND S_Show=1 and RQ_Show!=1 and MB_Team!=''";
            $result = $model->query($mysql);
            foreach($result as $row){
            $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
            $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);
            $MB_LetB_Rate = change_rate($open, $row['MB_LetB_Rate']);
            $TG_LetB_Rate = change_rate($open, $row['TG_LetB_Rate']);
            $S_Single_Rate = num_rate($open, $row['S_Single_Rate']);
            $S_Double_Rate = num_rate($open, $row['S_Double_Rate']);
            if ($S_Single_Rate == '') {
                $Single = '';
            } else {
                $Single = $o;
            }
            if ($S_Double_Rate == '') {
                $Double = '';
            } else {
                $Double = $e;
            }
            if ($row['M_Type'] == 1) {
                $Running = '<br><font color=red>滚球</font>';
            } else {
                $Running = '';
            }
            if ($row['ShowTypeR'] == 'H') {
                $MG_LetB = $row['M_LetB'];
                $TG_LetB = '';
            } else {
                $MG_LetB = '';
                $TG_LetB = $row['M_LetB'];
            }
            $rate[] = array(
                'MID'=>$row['MID'],
                    'M_Time' => $row['M_Date'].' '.$row['M_Time'],
                    'MB_Team' => $row['MB_Team'],
                    'TG_Team' => $row['TG_Team'], 'MG_LetB' => $MG_LetB, 'TG_LetB' => $TG_LetB,
                    'MB_LetB_Rate' => $MB_LetB_Rate,
                    'TG_LetB_Rate' => $TG_LetB_Rate,
                    'MB_Dime_Rate' => $MB_Dime_Rate,
                    'TG_Dime_Rate' => $TG_Dime_Rate,
                    'S_Single_Rate' => $S_Single_Rate,
                    'S_Double_Rate' => $S_Double_Rate, 'MB_Dime' => $row['MB_Dime'], 'TG_Dime' => $row['TG_Dime']
                );
            }
        } else if ($this->_param['more'] == 'b') {
            $mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeHR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,Eventid,Hot,Play from match_sports where M_League='{$this->_param['team']}' and Type='BK' and M_Start>now() AND RQ_Show=1 and MB_Team!=''";
            $result = $model->query($mysql);
            foreach($result as $row){
            $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
            $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);
            $MB_LetB_Rate = change_rate($open, $row['MB_LetB_Rate']);
            $TG_LetB_Rate = change_rate($open, $row['TG_LetB_Rate']);
            $S_Single_Rate = num_rate($open, $row['S_Single_Rate']);
            $S_Double_Rate = num_rate($open, $row['S_Double_Rate']);
            if ($S_Single_Rate == '') {
                $Single = '';
            } else {
                $Single = $o;
            }
            if ($S_Double_Rate == '') {
                $Double = '';
            } else {
                $Double = $e;
            }
            if ($row['M_Type'] == 1) {
                $Running = '<br><font color=red>滚球</font>';
            } else {
                $Running = '';
            }
            $rate[] = array(
                'MID'=>$row['MID'],
                    'M_Time' =>$row['M_Date'].' '.$row['M_Time'],
                    'MB_Team' => $row['MB_Team'],
                    'TG_Team' => $row['TG_Team'], 'MG_LetB' => $MG_LetB, 'TG_LetB' => $TG_LetB,
                    'MB_LetB_Rate' => $MB_LetB_Rate,
                    'TG_LetB_Rate' => $TG_LetB_Rate,
                    'MB_Dime_Rate' => $MB_Dime_Rate,
                    'TG_Dime_Rate' => $TG_Dime_Rate,
                    'S_Single_Rate' => $S_Single_Rate,
                    'S_Double_Rate' => $S_Double_Rate, 'MB_Dime' => $row['MB_Dime'], 'TG_Dime' => $row['TG_Dime']
            );
            }
        } else if ($this->_param['more'] == 'r') {
            $mysql = "select datasite,Uid,Uid_tw,Uid_en from web_system_data where ID=1";
            $result = $model->query($mysql);
            $row = current($result);
            $site = $row['datasite'];
            $suid = $row['Uid'];

            import('@.Util.Team.Curlhttp');
            $curl = &new Curlhttp();
            $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            $curl->set_referrer("" . $site . "/app/member/BK_browse/index.php?rtype=re_all&uid=$suid&langx=$langx&mtype=4");
            $html_data = $curl->fetch_url("" . $site . "/app/member/BK_browse/body_var.php?rtype=re_all&uid=$suid&langx=zh-cn&mtype=4");

            $a = array("parent.GameFT=new ", "parent.GameHead = new ");
            $b = array("", "");
            unset($matches);
            unset($datainfo);
            $msg = str_replace($a, $b, $html_data);
            preg_match_all("/g\(\[(.+?)\]\);/is", $msg, $matches);
            $cou = sizeof($matches[1]);
            //var_dump($matches);
            for ($i = 0; $i < $cou; $i++) {
                $messages = $matches[1][$i];
		$messages=str_replace("g([","",$messages);
	$messages=str_replace("'","",$messages);
	$messages=str_replace("]);","",$messages);
                $datainfo = explode(",", $messages);

                $opensql = "select * from `match_sports` where Type='BK' and  MID='$datainfo[0]'";
                $openresult = $model->query($opensql);
                $openrow = current($openresult);
                if ($openrow['Open'] == 1) {
                    $sql = "update match_sports set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',T_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',TG_Dime_Rate_RB='$datainfo[13]',MB_Dime_Rate_RB='$datainfo[14]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and Type='BK'";
                    $model->execute($sql);
                    if ($datainfo[9] <> '') {
                        $datainfo[9] = change_rate($open, $datainfo[9]);
                        $datainfo[10] = change_rate($open, $datainfo[10]);
                    }
                    if ($datainfo[13] <> '') {
                        $datainfo[13] = change_rate($open, $datainfo[13]);
                        $datainfo[14] = change_rate($open, $datainfo[14]);
                    }
                    if ($datainfo[7] == 'H') {
                        $MG_LetB_RB = $datainfo[8];
                        $TG_LetB_RB = '';
                    } else {
                        $MG_LetB_RB = '';
                        $TG_LetB_RB = $datainfo[8];
                    }
                    if ($this->_param['team'] == $datainfo[2]) {
						if($datainfo[51]=="Y"){
							$jie = 	$datainfo[52] ;
						 }
						if((!preg_match('/NBA/is',str_replace(' ','',$openrow['M_League']) ) || !preg_match('/中国男子篮球职业联赛/is',str_replace(' ','',$openrow['M_League']) ) || !preg_match('/西班牙篮球甲级联赛/is',str_replace(' ','',$openrow['M_League']) ))&&($jie=="H2" or $jie=="Q3" or $jie=="Q4" or $jie=="OT")){
							$rate[] = array(
                            'MID'=>$datainfo[0],
                                'M_Time' =>  $datainfo[1],
                                'MB_Team' => $datainfo[5],
                                'TG_Team' => $datainfo[6],
                                'ShowTypeRB' => $datainfo[7],
                                'MG_LetB_RB' => '',
                                'TG_LetB_RB' => '',
                                'MB_LetB_Rate_RB' => 0,
                                'TG_LetB_Rate_RB' => 0,
                                'MB_Dime_RB' => '',
                                'TG_Dime_RB' => '',
                                'TG_Dime_Rate_RB' => 0,
                                'MB_Dime_Rate_RB' => 0
                            
                        );
						}else{
                        $rate[] = array(
                            'MID'=>$datainfo[0],
                                'M_Time' =>  $datainfo[1],
                                'MB_Team' => $datainfo[5],
                                'TG_Team' => $datainfo[6],
                                'ShowTypeRB' => $datainfo[7],
                                'MG_LetB_RB' => $MG_LetB_RB,
                                'TG_LetB_RB' => $TG_LetB_RB,
                                'MB_LetB_Rate_RB' => $datainfo[9],
                                'TG_LetB_Rate_RB' => $datainfo[10],
                                'MB_Dime_RB' => $datainfo[11],
                                'TG_Dime_RB' => $datainfo[12],
                                'TG_Dime_Rate_RB' => $datainfo[13],
                                'MB_Dime_Rate_RB' => $datainfo[14]
                            
                        );}
                    }
                }
            }
        }
        return $rate;
    }

    public function bs_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    public function tn_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    public function vb_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    public function op_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

}

?>