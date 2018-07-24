<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BsAction
 *
 * @author Administrator
 */
class BsAction extends SportAction {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->_sel_gt = 'BS';
        $this->assign('_sel_gt', ucfirst(strtolower($this->_sel_gt)));
    }
    
    public function bs_rate_R($model){
        
    }
}