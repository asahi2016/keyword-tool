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

function get_youtube_country()
{

    $country = array(
        "af" => 'Afghanistan (افغانستان)',
        "al" => 'Albania (Shqipëria)',
        "dz" => 'Algeria (الجزائر)',
        "as" => 'American Samoa (Amerika Sāmoa)',
        "ad" => 'Andorra',
        "ao" => 'Angola',
        "ai" => 'Anguilla',
        "aq" => 'Antarctica',
        "ag" => 'Antigua and Barbuda',
        "ar" => 'Argentina',
        "am" => 'Armenia (Hayastán)',
        "aw" => 'Aruba',
        "au" => 'Australia',
        "at" => 'Austria (Österreich)',
        "az" => 'Azerbaijan (Azərbaycan)',
        "bs" => 'Bahamas',
        "bh" => 'Bahrain (البحرين)',
        "bd" => 'Bangladesh (বাংলাদেশ)',
        "bb" => 'Barbados',
        "by" => 'Belarus (Беларусь)',
        "ed=" => 'elected" value="be">Belgium (België)',
        "bz" => 'Belize',
        "bj" => 'Benin (Bénin)',
        "bm" => 'Bermuda',
        "bt" => 'Bhutan (འབྲུག་ཡུལ)',
        "bo" => 'Bolivia',
        "ba" => 'Bosnia and Herzegovina (Bosna i Hercegovina)',
        "bw" => 'Botswana',
        "bv" => 'Bouvet Island',
        "br" => 'Brazil (Brasil)',
        "io" => 'British Indian Ocean Territory',
        "bn" => 'Brunei (بروني)',
        "bg" => 'Bulgaria',
        "bf" => 'Burkina Faso',
        "bi" => 'Burundi',
        "kh" => 'Cambodia (Kampuchea)',
        "cm" => 'Cameroon (Cameroun)',
        "ca" => 'Canada',
        "cv" => 'Cape Verde (Cabo Verde)',
        "ky" => 'Cayman Islands',
        "cf" => 'Central African Republic (République Centrafricaine)',
        "td" => 'Chad (Tchad)',
        "cl" => 'Chile',
        "cn" => 'China (中国)',
        "cx" => 'Christmas Island',
        "cc" => 'Cocos (Keeling) Islands',
        "co" => 'Colombia',
        "km" => 'Comoros (جزر القمر)',
        "cg" => 'Congo',
        "cd" => 'Congo - Democratic Republic of',
        "ck" => 'Cook Islands',
        "cr" => 'Costa Rica',
        "ci" => 'Cote d\'Ivoire',
        "hr" => 'Croatia (Hrvatska)',
        "cy" => 'Cyprus (Kypros)',
        "cz" => 'Czech Republic (Česká Republika)',
        "dk" => 'Denmark (Danmark)',
        "dj" => 'Djibouti (جيبوتي)',
        "dm" => 'Dominica',
        "do" => 'Dominican Republic (República Dominicana)',
        "tl" => 'Timor-Leste',
        "ec" => 'Ecuador',
        "eg" => 'Egypt (مصر)',
        "sv" => 'El Salvador',
        "gq" => 'Equatorial Guinea (Guinea Ecuatorial)',
        "er" => 'Eritrea (إرتريا)',
        "ee" => 'Estonia (Eesti)',
        "et" => 'Ethiopia (Ityop\'ia)',
        "fk" => 'Falkland Islands (Islas Malvinas)',
        "fo" => 'Faroe Islands (Føroyar)',
        "fj" => 'Fiji',
        "fi" => 'Finland (Suomi)',
        "fr" => 'France',
        "gf" => 'French Guiana (Guyane)',
        "pf" => 'French Polynesia (Polynésie Française)',
        "tf" => 'French Southern Territories',
        "ga" => 'Gabon',
        "gm" => 'Gambia',
        "ge" => 'Georgia (Sak\'art\'velo)',
        "de" => 'Germany (Deutschland)',
        "gh" => 'Ghana',
        "gi" => 'Gibraltar',
        "gr" => 'Greece (Hellas)',
        "gl" => 'Greenland (Kalaallit Nunaat)',
        "gd" => 'Grenada',
        "gp" => 'Guadeloupe',
        "gu" => 'Guam (Guåhån)',
        "gt" => 'Guatemala',
        "gn" => 'Guinea (Guinée)',
        "gw" => 'Guinea-Bissau (Guiné-Bissau)',
        "gy" => 'Guyana',
        "ht" => 'Haiti (Haïti)',
        "hm" => 'Heard Island and McDonald Islands',
        "hn" => 'Honduras',
        "hk" => 'Hong Kong (香港)',
        "hu" => 'Hungary (Magyarország)',
        "is" => 'Iceland (Ísland)',
        "in" => 'India',
        "id" => 'Indonesia',
        "iq" => 'Iraq',
        "ie" => 'Ireland (Éire)',
        "il" => 'Israel (إسرائيل)',
        "it" => 'Italy (Italia)',
        "jm" => 'Jamaica',
        "jp" => 'Japan (日本)',
        "jo" => 'Jordan',
        "kz" => 'Kazakhstan (Қазақстан)',
        "ke" => 'Kenya',
        "ki" => 'Kiribati',
        "kw" => 'Kuwait (الكويت)',
        "kg" => 'Kyrgyzstan (Кыргызстан)',
        "la" => 'Laos (Lao)',
        "lv" => 'Latvia (Latvija)',
        "lb" => 'Lebanon (لبنان)',
        "ls" => 'Lesotho',
        "lr" => 'Liberia',
        "ly" => 'Libya',
        "li" => 'Liechtenstein',
        "lt" => 'Lithuania (Lietuva)',
        "lu" => 'Luxembourg (Lëtzebuerg)',
        "mo" => 'Macao',
        "mk" => 'Macedonia (Makedonija)',
        "mg" => 'Madagascar (Madagasikara)',
        "mw" => 'Malawi',
        "my" => 'Malaysia',
        "mv" => 'Maldives (Dhivehi Raajje)',
        "ml" => 'Mali',
        "mt" => 'Malta',
        "mh" => 'Marshall Islands',
        "mq" => 'Martinique',
        "mr" => 'Mauritania (Muritan)',
        "mu" => 'Mauritius (Maurice)',
        "yt" => 'Mayotte',
        "mx" => 'Mexico (México)',
        "fm" => 'Micronesia - Federated States of',
        "md" => 'Moldova',
        "mc" => 'Monaco',
        "mn" => 'Mongolia (Mongol Uls)',
        "ms" => 'Montserrat',
        "ma" => 'Morocco (Amerruk)',
        "mz" => 'Mozambique (Moçambique)',
        "na" => 'Namibia',
        "nr" => 'Nauru',
        "np" => 'Nepal (Nepāla)',
        "nl" => 'Netherlands (Nederland)',
        "nc" => 'New Caledonia (Nouvelle-Calédonie)',
        "nz" => 'New Zealand',
        "ni" => 'Nicaragua',
        "ne" => 'Niger',
        "ng" => 'Nigeria',
        "nu" => 'Niue',
        "nf" => 'Norfolk Island',
        "mp" => 'Northern Mariana Islands',
        "no" => 'Norway (Norge)',
        "om" => 'Oman',
        "pk" => 'Pakistan (پاکستان)',
        "pw" => 'Palau (Belau)',
        "ps" => 'West Bank',
        "pa" => 'Panama (Panamá)',
        "pg" => 'Papua New Guinea',
        "py" => 'Paraguay',
        "pe" => 'Peru (Perú)',
        "ph" => 'Philippines (Pilipinas)',
        "pn" => 'Pitcairn',
        "pl" => 'Poland (Polska)',
        "pt" => 'Portugal',
        "pr" => 'Puerto Rico',
        "qa" => 'Qatar (قطر)',
        "re" => 'Reunion (Réunion)',
        "ro" => 'Romania (România)',
        "ru" => 'Russia (Россия)',
        "rw" => 'Rwanda',
        "kn" => 'Saint Kitts and Nevis',
        "lc" => 'Saint Lucia',
        "vc" => 'Saint Vincent and the Grenadines',
        "ws" => 'Samoa',
        "sm" => 'San Marino',
        "st" => 'Sao Tome and Principe (São Tomé e Príncipe)',
        "sa" => 'Saudi Arabia (المملكة العربية السعودية)',
        "sn" => 'Senegal (Sénégal)',
        "sc" => 'Seychelles (Sesel)',
        "sl" => 'Sierra Leone',
        "sg" => 'Singapore',
        "sk" => 'Slovakia (Slovensko)',
        "si" => 'Slovenia (Slovenija)',
        "sb" => 'Solomon Islands',
        "so" => 'Somalia (Soomaaliya)',
        "za" => 'South Africa (Suid-Afrika)',
        "gs" => 'South Georgia and the South Sandwich Islands',
        "kr" => 'South Korea (한국)',
        "es" => 'Spain (España)',
        "lk" => 'Sri Lanka (Sri Lankā)',
        "sh" => 'Saint Helena',
        "pm" => 'Saint Pierre and Miquelon',
        "sr" => 'Suriname',
        "sj" => 'Svalbard and Jan Mayen',
        "sz" => 'Swaziland',
        "se" => 'Sweden (Sverige)',
        "ch" => 'Switzerland (Schweiz)',
        "tw" => 'Taiwan (中華民國)',
        "tj" => 'Tajikistan (Тоҷикистон)',
        "tz" => 'Tanzania',
        "th" => 'Thailand (ประเทศไทย)',
        "tg" => 'Togo',
        "tk" => 'Tokelau',
        "to" => 'Tonga',
        "tt" => 'Trinidad and Tobago',
        "tn" => 'Tunisia (Tunes)',
        "tr" => 'Turkey (Türkiye)',
        "tm" => 'Turkmenistan (Türkmenistan)',
        "tc" => 'Turks and Caicos Islands',
        "tv" => 'Tuvalu',
        "ug" => 'Uganda',
        "ua" => 'Ukraine (Україна)',
        "ae" => 'United Arab Emirates',
        "uk" => 'United Kingdom',
        "um" => 'United States Minor Outlying Islands',
        "uy" => 'Uruguay (República Oriental del Uruguay)',
        "uz" => 'Uzbekistan (Ўзбекистон)',
        "vu" => 'Vanuatu',
        "va" => 'Holy See (Vatican City State) (Città del Vaticano)',
        "ve" => 'Venezuela',
        "vn" => 'Vietnam (Việt Nam)',
        "vg" => 'British Virgin Islands',
        "vi" => 'United States Virgin Islands',
        "wf" => 'Wallis and Futuna (Wallis-et-Futuna)',
        "eh" => 'Western Sahara',
        "ye" => 'Yemen (اليمن)',
        "zm" => 'Zambia',
        "zw" => 'Zimbabwe'

    );

    return $country;
}


function get_youtube_languages(){

    $languages = array(

        "ar"  => 'Arabic (العربية)',
        "bg"  =>  'Bulgarian (Български)',
        "ca"  =>  'Catalan (Català)',
        "zh_CN"  =>  'Chinese - Simplified (中国 - 简体)',
        "zh_TW"  =>  'Chinese - Traditional (中文 - 繁體)',
        "hr"  =>  'atian (Hrvatski)',
        "cs"  =>  'ch (Čeština)',
        "da"  =>  'ish (Dansk)',
        "et"  =>  'onian (Eesti)',
        "nl"  =>  'ch (Nederlands)',
        "fi"  =>  'nish (Suomi)',
        "fr"  =>  'nch (Français)',
        "de"  =>  'man (Deutsch)',
        "el"  =>  'ek (ελληνικά)',
        "iw"  =>  'rew (עברית)',
        "hi"  =>  'di (हिंदी)',
        "hu"  =>  'garian (Magyar)',
        "is"  =>  'landic',
        "id"  =>  'onesian (Bahasa Indonesia)',
        "it"  =>  'lian (Italiano)',
        "ja"  =>  'anese (日本語)',
        "ko"  =>  'ean (한국어)',
        "lv"  =>  'vian (Latviešu Valoda)',
        "lt"  =>  'huanian (Lietuvių Kalba)',
        "no"  =>  'wegian (Norsk)',
        "pl"  =>  'ish (Polski)',
        "pt"  =>  'tuguese (Português)',
        "ro"  =>  'anian (Român)',
        "ru"  =>  'sian (Русский)',
        "sr"  =>  'bian (Cрпски)',
        "sk"  =>  'vak (Slovenský)',
        "sl"  =>  'venian (Slovenščina)',
        "es"  =>  'nish (Español)',
        "sv"  =>  'dish (Svenska)',
        "tl"  =>  'alog',
        "th"  =>  'i (ภาษาไทย)',
        "tr"  =>  'kish (Türk)',
        "uk"  =>  'ainian (Українська)',
        "ur"  =>  'u (اُردُو‎)    ',
        "vi"  =>  'tnamese (Việt)',

    );
    return $languages;

}