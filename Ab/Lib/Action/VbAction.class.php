<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VbAction
 *
 * @author Administrator
 */
class VbAction extends SportAction {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->_sel_gt = 'VB';
        $this->assign('_sel_gt', ucfirst(strtolower($this->_sel_gt)));
    }
}