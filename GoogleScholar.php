<?php
class GoogleScholar {


    public static function getURL($page, $query, $includePatents = false) 
    {
        $as_sdt = "1,5";
        if ($includePatents) {
            $as_sdt = "0,5";
        }
        return "https://scholar.google.com/scholar?&hl=en&as_sdt=" . $as_sdt . "&&start=" . $page . "&q=" . $query . "&btnG=";
    }

    public static function getDataCID($html) {

        preg_match_all('/data-cid="([^"]+)"/', $html, $values, PREG_PATTERN_ORDER);

        if (!empty($values[1])) {
            return $values[1];
        }
        return array();
    }

    public static function save_data_bibtex($url) {
        $parameters = array();
        $content    = "";
        $parameters["host"] = "scholar.google.com.br";        
        $html = Util::loadURL($url, COOKIE, USER_AGENT, array(), $parameters);
        $parameters["host"] = "scholar.googleusercontent.com";
        $parameters["referer"] = $url;

        $values = Util::getHTMLFromClass($html, "gs_citi", "a");
        $urlBibtex = Util::getURLFromHTML(@$values[0]);

        $content = Util::loadURL($urlBibtex, COOKIE, USER_AGENT, array(), $parameters);

        // var_dump($urlBibtex); exit;
        // $dom = Util::getDOM($html);
        // foreach ($dom->getElementsByTagName('div') as $node) {
        //     if ($node->hasAttribute( 'id' )) {
        //         if ($node->getAttribute( 'id' ) == "gs_citi") {
        //             $child = $node->firstChild;
        //             $urlBibtex = trim($child->getAttribute( 'href' ));
        //             $content .= Util::loadURL($urlBibtex, COOKIE, USER_AGENT, array(), $parameters);
        //             break;
        //         }
        //     }
        // }

        return $content;
    }

    public static function progress($url) {
        $parameters["referer"]  = $url;
        $parameters["host"]     = "scholar.google.com.br";
        $html = Util::loadURL($url, COOKIE, USER_AGENT, array(), $parameters["referer"]);
        
        // Check Google Captcha
        if ( strpos($html, "gs_captcha_cb()") !== false || strpos($html, "sending automated queries") !== false ) {
            Util::showMessage("Captha detected"); exit;
        }

        $classname="gs_r gs_or gs_scl";
        $values = Util::getHTMLFromClass($html, $classname);        
        $bibtex_new = "";
        foreach($values as $value) {

            $data = self::get_data_google_scholar($value);
            Util::showMessage($data["title"]);
            $url_action = "https://scholar.google.com.br/scholar?q=info:" . $data["data_cid"] . ":scholar.google.com/&output=cite&scirp=0&hl=en";
            // echo $url_action . $break_line . $break_line;
            unset($data["title"]);
            unset($data["data_cid"]);
            $bibtex     = self::save_data_bibtex($url_action);
            if (!empty($bibtex)) {
                $bibtex_new .= Util::add_fields_bibtex($bibtex, $data);
                Util::showMessage("Download bibtex file OK.");
                Util::showMessage("");
            }
            sleep(rand(4,8)); // rand between 4 and 8 seconds

        }

        if (!empty($bibtex_new)) {
            file_put_contents(FILE, $bibtex_new, FILE_APPEND);
            Util::showMessage("File " . FILE . " saved successfully.");
            Util::showMessage("");
        }
    }

    

    public static function get_data_google_scholar($value) {

        $data_cid               = @self::getDataCID($value)[0];
        $html_pdf_article       = @Util::arrayToString(Util::getHTMLFromClass($value, "gs_or_ggsm"));
        $pdf_article            = @Util::getURLFromHTML($html_pdf_article);
        $html_link_article      = @Util::arrayToString(Util::getHTMLFromClass($value, "gs_rt"));
        $link_article           = @Util::getURLFromHTML($html_link_article);
        $html_options_article   = @Util::arrayToString(Util::getHTMLFromClass($value, "gs_fl"));
        $title_article          = trim(preg_replace("/\[(.*?)\]/i", "", strip_tags($html_link_article))); // remove [*]
        $cited_by               = @self::getCitedFromHTML($html_options_article);

        return array("title"=>$title_article, "data_cid"=> $data_cid, "pdf_file"=>$pdf_article, "link_google"=>$link_article, "cited_by"=>$cited_by);
    }  

    public static function getCitedFromHTML($html) {

        preg_match("'<a href=\"\/scholar\?cites(.*?)>(Cited by|Citado por) (.*?)</a>'si", $html, $match);
        if (!empty(@$match[3])) {
            return $match[3];
        }
        return "";
    }
}    
?>