<?php

/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/7/2016
 * Time: 2:07 PM
 */
class Keywords extends CI_Controller
{
    /*public function index()
    {

        //$this->load->view('index/index');
    }*/

    //Get Google Keywords
    public function google()
    {
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
            $keyword = trim($_GET['keyword']);
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
    public function youtube()
    {
        $this->load->helper('api');
        echo "Youtube";
    }

    //Get Bing Keywords
    public function bing()
    {
        //$this->load->view('bing/index');

        if(isset($_GET['bing-keyword']) && !empty($_GET['bing-keyword'])) {

            $result = array();
            $this->load->helper('api');

            $letters = range('a', 'z');
            $numbers = range('0', '9');

            $suggestions = array_merge($letters, $numbers);

            $search_results = array();
            foreach ($suggestions as $key => $val) {

                $keys = array('front' => $_GET['bing-keyword'] . ' ' . $val, 'back' => $val . ' ' . $_GET['bing-keyword']);

                foreach ($keys as $k => $search) {

                    $response = $this->getApiResponse($search);
                    foreach ($response as $order => $res_search) {
                        array_push($search_results, $res_search);
                    }
                }
            }

            $result['bing']['result'] = array_values(array_unique($search_results));

            //print_pre($result['bing']['result'],1);

            $this->load->view('bing/index', $result);
        }
        else{

            $this->load->view('bing/index');
        }
    }

    public function getApiResponse($keyword){
        $keyword = urlencode($keyword);

        $front_url = 'http://api.bing.com/osjson.aspx?query=' . $keyword . '&mkt=en-in';

        $method = 'POST';

        $response = callAPI($method, $front_url, true);

        $response = json_decode($response);

        return $response[1];
    }
}
?>
