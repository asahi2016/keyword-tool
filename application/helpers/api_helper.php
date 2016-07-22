<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 4/7/2016
 * Time: 4:11 PM
 */

    // Perform API call
    function callAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            case "GET":
                curl_setopt($curl, CURLOPT_HTTPGET, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

function GetKeywordIdeas(AdWordsUser $user,$key) {
    // Get the service, which loads the required classes.
    $targetingIdeaService = $user->GetService('TargetingIdeaService', 'v201605');

    // Create selector.
    $selector = new TargetingIdeaSelector();
    $selector->requestType = 'IDEAS';
    $selector->ideaType = 'KEYWORD';
    $selector->requestedAttributeTypes = array('KEYWORD_TEXT', 'SEARCH_VOLUME','AVERAGE_CPC','COMPETITION','CATEGORY_PRODUCTS_AND_SERVICES','TARGETED_MONTHLY_SEARCHES');

    // Create seed keyword.
    $keyword = $key;
    // Create related to query search parameter.
    $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
    $relatedToQuerySearchParameter->queries = array($keyword);
    $selector->searchParameters[] = $relatedToQuerySearchParameter;

    // Create language search parameter (optional).
    // The ID can be found in the documentation:
    //   https://developers.google.com/adwords/api/docs/appendix/languagecodes
    // Note: As of v201302, only a single language parameter is allowed.
    $languageParameter = new LanguageSearchParameter();
    $english = new Language();
    $english->id = 1000;
    $languageParameter->languages = array($english);
    $selector->searchParameters[] = $languageParameter;

    // Create network search parameter (optional).
    $networkSetting = new NetworkSetting();
    $networkSetting->targetGoogleSearch = true;
    $networkSetting->targetSearchNetwork = false;
    $networkSetting->targetContentNetwork = false;
    $networkSetting->targetPartnerSearchNetwork = false;

    $networkSearchParameter = new NetworkSearchParameter();
    $networkSearchParameter->networkSetting = $networkSetting;
    $selector->searchParameters[] = $networkSearchParameter;

    // Set selector paging (required by this service).
    $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
    $result_array = array();
    // Make the get request.
    $page = $targetingIdeaService->get($selector);
    while ($page->totalNumEntries > $selector->paging->startIndex){

        // Display results.
        if (isset($page->entries)) {
            $i = 0;
            foreach ($page->entries as $targetingIdea) {
                $data = MapUtils::GetMap($targetingIdea->data);
                $keyword = $data['KEYWORD_TEXT']->value;
                $search_volume = isset($data['SEARCH_VOLUME']->value)? $data['SEARCH_VOLUME']->value : 0;
                $avg_CPC = isset($data['AVERAGE_CPC']->value)? $data['AVERAGE_CPC']->value : 0;
                $competition = $data['COMPETITION'];
                if ($data['CATEGORY_PRODUCTS_AND_SERVICES']->value === null) {
                    $categoryIds = '';
                } else {
                    $categoryIds =
                        implode(', ', $data['CATEGORY_PRODUCTS_AND_SERVICES']->value);
                }
                /*printf("Keyword idea with text '%s', category IDs (%s) and average "
                    . "monthly search volume '%s' was found.\n",
                    $keyword, $categoryIds, $search_volume);*/
                $result_array[$i]['keyword'] = $keyword;
                $result_array[$i]['volume'] = $search_volume;
                $result_array[$i]['avg_cpc'] = '$'.round($avg_CPC->microAmount/1000000,2);
                $result_array[$i]['competition'] = round($competition->value,2);
                $i++;
            }
        } else {
            print "No keywords ideas were found.\n";
        }

        // Advance the paging index.
        $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
    }
    return $result_array;
}

function getProvider(){

        $CI =& get_instance();
        $uri =  $CI->uri->segment(2);

        if(!$uri)
            $uri = 'google';

        return $uri;
}

function get_bing_country(){

    $country = array(
        "ar" => 'Argentina',
        "au" => 'Australia',
        "at" => 'Austria (Österreich)',
        "be" => 'Belgium (België)',
        "br" => 'Brazil (Brasil)',
        "ca" => 'Canada',
        "fr" => 'France',
        "de" => 'Germany (Deutschland)',
        "hk" => 'Hong Kong (香港)',
        "in" => 'India',
        "id" => 'Indonesia',
        "it" => 'Italy (Italia)' ,
        "jp" => 'Japan (日本)',
        "kr" => 'Korea (한국)',
        "my" => 'Malaysia',
        "mx" => 'Mexico (México)'  ,
        "nl" => 'Netherlands (Nederland)',
        "nz" => 'New Zealand',
        "no" => 'Norway (Norge)',
        "cn" => 'Peoples Republic of China',
        "pl" => 'Poland (Polska)' ,
        "ph" => 'Republic of the Philippines',
        "ru" => 'Russia (Россия)',
        "sa" => 'Saudi Arabia (المملكة العربية السعودية)',
        "za" => 'South Africa (Suid-Afrika)',
        "es" => 'Spain (España)',
        "se" => 'Sweden (Sverige)',
        "ch" => 'Switzerland (Schweiz)',
        "tw" => 'Taiwan (中華民國)',
        "tr" => 'Turkey (Türkiye)',
        "gb" => 'United Kingdom'  ,

    );

    return $country;
}

function get_bing_language(){

    $languages = array(
        "ar"  =>  'Arabic (العربية)',
        "zh"  =>  'Chinese (中国)',
        "nl"  =>  'Dutch (Nederlands)',
        "fr"  =>  'French (Français)',
        "de"  =>  'German (Deutsch)',
        "it"  =>  'Italian (Italiano)',
        "ja"  =>  'Japanese (日本語)',
        "ko"  =>  'Korean (한국어)',
        "nb"  =>  'Norwegian (Bokmål)',
        "pl"  =>  'Polish (Polski)',
        "pt"  =>  'Portuguese (Português)',
        "ru"  =>  'Russian (Русский)',
        "es"  =>  'Spanish (Español)',
        "sv"  =>  'Swedish (Svenska)',
        "tr"  =>  'Turkish (Türk)',
    );

    return $languages;
}