<?php

/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/7/2016
 * Time: 2:07 PM
 */
class Keywords extends CI_Controller
{

    public function __construct(){
        
    }

    //Get Google Keywords
    public function google(){
        $this->load->helper('api');
        echo "Google";
    }

    //Get Youtueb Keywords
    public function youtube(){
        $this->load->helper('api');
        echo "Youtube";
    }

    //Get Bing Keywords
    public function bing(){
        $this->load->helper('api');
        echo "Bing";
    }
}