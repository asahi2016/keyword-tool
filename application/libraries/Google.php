<?php

/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/7/2016
 * Time: 3:06 PM
 */
class Google
{
    public function __construct(){
        require_once APPPATH. 'third_party/Google/Api/Ads/AdWords/Lib/AdWordsUser.php';
        require_once APPPATH. "third_party/Google/Api/Ads/AdWords/v201605/TargetingIdeaService.php";
    }

   /* public function loadThirdParty(){
        require_once APPPATH. "thirdparty/Google/Api/Ads/AdWords/Lib/AdWordsUser.php";
        require_once APPPATH. "thirdparty/Google/Api/Ads/AdWords/v201605/TargetingIdeaService.php";
    }*/
}