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

    public function GetKeywordIdeasExample(AdWordsUser $user,$key) {
        // Get the service, which loads the required classes.
        $targetingIdeaService = $user->GetService('TargetingIdeaService', 'v201605');

        // Create selector.
        $selector = new TargetingIdeaSelector();
        $selector->requestType = 'IDEAS';
        $selector->ideaType = 'KEYWORD';
        $selector->requestedAttributeTypes = array('KEYWORD_TEXT', 'SEARCH_VOLUME',
            'CATEGORY_PRODUCTS_AND_SERVICES');

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
                    $search_volume = isset($data['SEARCH_VOLUME']->value)
                        ? $data['SEARCH_VOLUME']->value : 0;
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
                    $result_array[$i]['categoryid'] = $categoryIds;
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
}