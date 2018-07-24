<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Game
 *
 * @author Administrator
 */
class Game {
    //put your code here
    private $_script;
    public function __construct() {
        //$this->_script='<script>_$("uc_center").onclick=user;if(_$("uc_sport")){_$("uc_sport").onclick=sport;}</script>';
    }
    public function index(){
        return '<div class="location"><a href="/index.php/Sport" class="topbtn1">回球类</a>&nbsp;<span>选择游戏</span>&nbsp;<a href="/index.php/User" class="topbtn2">回主菜单</a></div> '.$this->_script;
    }
    
    public function leg(){
        return '<div class="location"><a href="/index.php/Sport" class="topbtn1">回球类</a>&nbsp;<span>选择联盟</span>&nbsp;<a href="/index.php/User" class="topbtn2">回主菜单</a></div> '.$this->_script;
    }
    
    public function gametype(){
        return '<div class="location"><a href="/index.php/Sport" class="topbtn1">回球类</a>&nbsp;<span>多种玩法</span>&nbsp;<a href="/index.php/User" class="topbtn2">回主菜单</a></div> '.$this->_script;
    }
    public function games(){
        return '<div class="location"><a href="/index.php/Sport" class="topbtn1">回球类</a>&nbsp;<span>比赛队伍</span>&nbsp;<a href="/index.php/User" class="topbtn2">回主菜单</a></div> '.$this->_script;
    }
    
    public function team(){
        return '<div class="location"><a href="/index.php/Sport" class="topbtn1">回球类</a>&nbsp;<span>赔率显示</span>&nbsp;<a href="/index.php/User" class="topbtn2">回主菜单</a></div> '.$this->_script;
    }
    public function rate(){
        return '<div class="location"><a href="/index.php/Sport" class="topbtn1">回球类</a>&nbsp;<span>下注资料</span>&nbsp;<a href="/index.php/User" class="topbtn2">回主菜单</a></div> '.$this->_script;
    }
}

?>
