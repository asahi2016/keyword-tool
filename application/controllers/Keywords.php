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

    public function index(){

        $this->load->helper('api');
        $this->load->view('index');
    }
    //Get Google Keywords
    public function google()
    {
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
            $keyword = trim($_GET['keyword']);
            $this->session->set_userdata('keyword',$keyword);
            $result = GetKeywordIdeas($user,$keyword);
        }else {
            $data['error'] =  "<p>Keyword cannot be empty. Please enter a keyword to search</p>";
            $this->load->view('index',$data);
        }
        if(isset($result)){
            $this->load->view('google/index',array('data' => $result));
        }

    }

    //Get Youtube Keywords
    public function youtube()
    {
        $this->load->library('google');
        $this->load->helper('api');

        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {

            $keyword = trim($_GET['keyword']);

            $oauth['client_id'] = $this->config->item('client_id');
            $oauth['client_secret'] = $this->config->item('client_secret');
            $oauth['refresh_token'] = $this->config->item('refresh_token');
            $user = new AdWordsUser(null,'Cm6HKYVm7uZ-hlqOF0RnMA','Asahi Technologies','131-752-3145',APPPATH.'settings.ini',$oauth);

            $results = GetKeywordIdeas($user,$keyword);
            //print_pre($results,1);
            //Getting all suggested keywords
            $search_results = $this->getAllSuggestedKeywords();

            //Remove the duplicate values in the keywords
            $result['youtube']['result'] = array_values(array_unique($search_results));
            $result['youtube']['keyword'] = $keyword;
            $result['youtube']['count'] = count($result['youtube']['result']);

        }else{
            $data['error'] =  "<p>Keyword cannot be empty. Please enter a keyword to search</p>";
            $this->load->view('index',$data);
        }

        if(isset($result) && !empty($result)){
        $this->load->view('youtube/index', $result);
        }

    }

    //Get Bing Keywords
    public function bing()
    {

        $this->load->helper('api');

        //Generate the suggested keywords
        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {

            $keyword = trim($_GET['keyword']);
            $result = array();

            //Getting all suggested keywords
            $search_results = $this->getAllSuggestedKeywords();

            //Remove the duplicate values in the keywords
            $result['bing']['result'] = array_values(array_unique($search_results));
            $result['bing']['keyword'] = $keyword;
            $result['bing']['count'] = count($result['bing']['result']);

        }else {
            $data['error'] =  "<p>Keyword cannot be empty. Please enter a keyword to search</p>";
            $this->load->view('index',$data);
        }
            if(isset($result) && !empty($result)){
            $this->load->view('bing/index', $result);
        }
    }

    public function getApiResponse($keyword,$domain,$language){

        $keyword = urlencode($keyword);
        $result =array();

        $CI =& get_instance();
        $uri =  $CI->uri->segment(2);
        if($uri == 'bing'){
            $method = 'POST';
            $api_url = 'http://api.bing.com/osjson.aspx?query='.$keyword.'&mkt='.$language.'-'.$domain;//API url
            $response = callAPI($method, $api_url, true);

        }
        else {
            $method = 'GET';
            $api_url = 'http://www.google.com/complete/search?output=search&client=chrome&q=' . $keyword . '&hl='.$language.'&gl='.$domain.'&ds=yt';
            $response = callAPI($method, $api_url, true);
        }

        $response = json_decode($response);
        if(isset($response[1]) && !empty($response[1]) ){
        $result = array_slice($response[1], 0, 10, true);

        }
        return $result;
    }

    public function getAllSuggestedKeywords(){


        $keyword = trim($_GET['keyword']);
        $domain = trim($_GET['domain']);
        $language = trim($_GET['language']);
        $this->session->set_userdata('keyword',$keyword);
        $this->session->set_userdata('domain',$domain);
        $this->session->set_userdata('language',$language);


        $letters = range('a', 'z');
        $numbers = range('0', '9');

        //merge the letters and numbers
        $suggestions = array_merge($letters, $numbers);

        $search_results = array();
        foreach ($suggestions as $key => $val) {

            //Adding the suggestions(letters and numbers) before and after the keyword
            $keys = array('normal' => $_GET['keyword'],'space-front' => ' '. $_GET['keyword'],'space-back' => $_GET['keyword'].' ','front' => $_GET['keyword'] . ' ' . $val, 'back' => $val . ' ' . $_GET['keyword']);

            foreach ($keys as $k => $search) {
                //pass the keyword to api call
                $response = $this->getApiResponse($search,$domain,$language);
                foreach ($response as $order => $res_search) {
                    $removeurls = preg_match('/(http(s)?:\/\/)(www\.)?(.)*[\.](.)*$/i', $res_search);
                    if($removeurls == 1){
                        unset($res_search);
                    }else{
                        array_push($search_results, $res_search);//pushing the multiple array into one
                    }

                }
            }
        }

        return $search_results;
    }

}
?>
