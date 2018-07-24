<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Header
 *
 * @author Administrator
 */
class Header {
    //put your code here
    private $_action,$_model;
    public function __construct($_action,$_model) {
        $this->_action=$_action;
        $this->_model=$_model;
    }
    
    public function run(){
        import("@.Util.{$this->_model}");
        return call_user_func_array(array(new $this->_model,$this->_action),array());
    }
}

?>
