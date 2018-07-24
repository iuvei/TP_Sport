<?php

// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    
    public function __construct()
    {
        parent::__construct();
     
    }
    /**
     * 不能直接访问提示错误
     */
    public function index() {
        $this->display('error');
    }

}
