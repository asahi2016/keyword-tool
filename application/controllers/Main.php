<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/7/16
 * Time: 5:01 PM
 */

class Main extends CI_Controller
{
    public function index(){
        if(!empty($_GET)){
            $keyword = $_GET['keyword'];
            
        }else{
            $this->load->view('index/index');
        }
    }
}