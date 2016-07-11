<?php

/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/7/2016
 * Time: 2:07 PM
 */
class Keywords extends CI_Controller
{
    protected $CI;
    public function __construct(){
        parent::__construct();
        $this->CI = &get_instance();
    }

    //Get Google Keywords
    public function google(){
        $this->load->library('google');
        $this->load->helper('api');
//OAuth Info

        $oauth['client_id'] = $this->config->item('client_id');
        $oauth['client_secret'] = $this->config->item('client_secret');
        $oauth['refresh_token'] = $this->config->item('refresh_token');
        $user = new AdWordsUser(null,'Cm6HKYVm7uZ-hlqOF0RnMA','Asahi Technologies','131-752-3145',APPPATH.'settings.ini',$oauth);
// Generate callback URL
/*        $callbackUrl = "http://localhost/keyword-test/";
        $authUrl = $user->GetOAuth2AuthorizationUrl($callbackUrl, true);*/

        if(!empty(trim($_GET['keyword']))){
            $keyword = $_GET['keyword'];
            $this->session->set_userdata('keyword',$keyword);
            $result = GetKeywordIdeas($user,$keyword);
        }else {
            $data['error'] =  "<p>Keyword cannot be empty. Please enter a keyword to search</p>";
            $this->load->view('index/index',$data);
        }
        if(isset($result)){
            $this->load->view('google/index',array('data' => $result));
        }
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

    public function print_pre($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}