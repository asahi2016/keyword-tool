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

    //Get Youtueb Keywords
    public function youtube()
    {
        $this->load->helper('api');
        echo "Youtube";
    }

    //Get Bing Keywords
    public function bing()
    {

        $this->load->helper('api');

        //Generate the suggested keywords
        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {

            $domian = trim($_GET['domain']);
            $keyword = trim($_GET['keyword']);
            $language = trim($_GET['language']);
            $this->session->set_userdata('keyword',$keyword);
            $this->session->set_userdata('domain',$domian);
            $this->session->set_userdata('language',$language);

            $result = array();

            $letters = range('a', 'z');
            $numbers = range('0', '9');

            //merge the letters and numbers
            $suggestions = array_merge($letters, $numbers);

            $search_results = array();
            foreach ($suggestions as $key => $val) {

                //Adding the suggestions(letters and numbers) before and after the keyword
                $keys = array('normal' => $_GET['keyword'],'front' => $_GET['keyword'] . ' ' . $val, 'back' => $val . ' ' . $_GET['keyword']);

                foreach ($keys as $k => $search) {
                    //pass the keyword to api call
                    $response = $this->getApiResponse($search,$domian,$language);
                    foreach ($response as $order => $res_search) {
                        array_push($search_results, $res_search); //pushing the multiple array into one
                    }
                }
            }
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

        $api_url = 'http://api.bing.com/osjson.aspx?query=' . $keyword . '&mkt='.$language.'-'.$domain;//API url

        $method = 'POST';

        //API call to get the keywords
        $response = callAPI($method, $api_url, true);

        $response = json_decode($response);
        $result = array_slice($response[1],0,10,true);

        return $result;
    }

}
?>
