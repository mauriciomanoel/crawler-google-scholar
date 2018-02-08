<?php

    function loadURL($url, $cookie, $user_agent, $fields=array(), $parameters=array()) {
        
        $ch 		= curl_init($url);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt( $ch, CURLOPT_HEADER, 0 );

        if (empty($fields) && count($fields) ==0) {
            curl_setopt( $ch, CURLOPT_HTTPGET, 1 );
        } else {
            $fields_string = "";
            foreach($fields as $key => $value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        }

        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Accept-Charset: UTF-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3";
        $header[] = "Upgrade-Insecure-Requests: 1";
        $header[] = "Pragma: ";
        $header[] = "Cookie: " . $cookie;
        if (!empty($parameters["host"])) {
            $header[] = "Host: " . $parameters["host"];
        }

        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        if (!empty($parameters["referer"])) {
            curl_setopt( $ch, CURLOPT_REFERER, $parameters["referer"]);
        }
        curl_setopt( $ch, CURLOPT_ENCODING, "gzip, deflate, br");
        curl_setopt( $ch, CURLOPT_USERAGENT, $user_agent);
        $output 	= curl_exec($ch);
        curl_close( $ch );
        return $output;
    }

    function slug($string, $replacement = '_') {
        $transliteration = array(
            '/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
            '/Æ|Ǽ/' => 'AE',
            '/Ä/' => 'Ae',
            '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
            '/Ð|Ď|Đ/' => 'D',
            '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
            '/Ĝ|Ğ|Ġ|Ģ|Ґ/' => 'G',
            '/Ĥ|Ħ/' => 'H',
            '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|І/' => 'I',
            '/Ĳ/' => 'IJ',
            '/Ĵ/' => 'J',
            '/Ķ/' => 'K',
            '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
            '/Ñ|Ń|Ņ|Ň/' => 'N',
            '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
            '/Œ/' => 'OE',
            '/Ö/' => 'Oe',
            '/Ŕ|Ŗ|Ř/' => 'R',
            '/Ś|Ŝ|Ş|Ș|Š/' => 'S',
            '/ẞ/' => 'SS',
            '/Ţ|Ț|Ť|Ŧ/' => 'T',
            '/Þ/' => 'TH',
            '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
            '/Ü/' => 'Ue',
            '/Ŵ/' => 'W',
            '/Ý|Ÿ|Ŷ/' => 'Y',
            '/Є/' => 'Ye',
            '/Ї/' => 'Yi',
            '/Ź|Ż|Ž/' => 'Z',
            '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',
            '/ä|æ|ǽ/' => 'ae',
            '/ç|ć|ĉ|ċ|č/' => 'c',
            '/ð|ď|đ/' => 'd',
            '/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
            '/ƒ/' => 'f',
            '/ĝ|ğ|ġ|ģ|ґ/' => 'g',
            '/ĥ|ħ/' => 'h',
            '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|і/' => 'i',
            '/ĳ/' => 'ij',
            '/ĵ/' => 'j',
            '/ķ/' => 'k',
            '/ĺ|ļ|ľ|ŀ|ł/' => 'l',
            '/ñ|ń|ņ|ň|ŉ/' => 'n',
            '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
            '/ö|œ/' => 'oe',
            '/ŕ|ŗ|ř/' => 'r',
            '/ś|ŝ|ş|ș|š|ſ/' => 's',
            '/ß/' => 'ss',
            '/ţ|ț|ť|ŧ/' => 't',
            '/þ/' => 'th',
            '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
            '/ü/' => 'ue',
            '/ŵ/' => 'w',
            '/ý|ÿ|ŷ/' => 'y',
            '/є/' => 'ye',
            '/ї/' => 'yi',
            '/ź|ż|ž/' => 'z',
        );
        
        $quotedReplacement = preg_quote($replacement, '/');

        $merge = array(
            '/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/[\s\p{Zs}]+/mu' => $replacement,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        );

        $map = $transliteration + $merge;
        return preg_replace(array_keys($map), array_values($map), $string);
    }

    function getCookie($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // get headers too with this line
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        // get cookie
        // multi-cookie variant contributed by @Combuster in comments
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        $cookie = "";
        foreach($cookies as $key => $value) {
            $cookie .= $key . "=" . $value . ", ";
        }
        $cookie = rtrim($cookie, ", ");
        return $cookie;
    }

    function getUrl($page, $query, $includePatents = false) {
        $as_sdt = "1,5";
        if ($includePatents) {
            $as_sdt = "0,5";
        }
        return "https://scholar.google.com/scholar?&hl=en&as_sdt=" . $as_sdt . "&&start=" . $page . "&q=" . $query . "&btnG=";
    }

    function getDOM($value) {
        libxml_use_internal_errors(true) && libxml_clear_errors(); // for html5
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8'));
        $dom->preserveWhiteSpace = true;
        
        return $dom;
    }

    function getDataCID($html) {

        preg_match_all('/data-cid="([^"]+)"/', $html, $values, PREG_PATTERN_ORDER);

        if (!empty($values[1])) {
            return $values[1];
        }
        return array();

        // $values = array();
        // $dom = getDOM($html);
        // foreach ($dom->getElementsByTagName('div') as $node) {
        //     if ($node->hasAttribute( 'data-cid' )) {
               
        //             $data_cid = $node->getAttribute( 'data-cid' );
        //             $values[] = $data_cid;
        //     }
        // }
        // return $values;
    }

    function save_data_bibtex($url) {
        $parameters = array();
        $content    = "";
        $parameters["host"] = "scholar.google.com.br";        
        $html = loadURL($url, COOKIE, USER_AGENT, array(), $parameters);

        $parameters["host"] = "scholar.googleusercontent.com";
        $parameters["referer"] = $url;

        $dom = getDOM($html);
        foreach ($dom->getElementsByTagName('div') as $node) {
            if ($node->hasAttribute( 'id' )) {
                if ($node->getAttribute( 'id' ) == "gs_citi") {
                    $child = $node->firstChild;
                    $urlBibtex = trim($child->getAttribute( 'href' ));
                    $content .=  loadURL($urlBibtex, COOKIE, USER_AGENT, array(), $parameters);
                    break;
                }
            }
        }
        return $content;
    }

    function progress_google($url) {
        echo "<br>" . $url . "<br>";
        $parameters["referer"]  = $url;
        $parameters["host"]     = "scholar.google.com.br";
        $html = loadURL($url, COOKIE, USER_AGENT, array(), $parameters["referer"]);
        
        // Check Google Captcha
        if ( strpos($html, "gs_captcha_cb()") !== false || strpos($html, "sending automated queries") !== false ) {
            echo "Captha detected<br>";
            echo $html; exit;
        }

        $datacids = getDataCID($html);
        
        foreach($datacids as $data_cid) {
            while (@ ob_end_flush()); // end all output buffers if any
                $url_action = "https://scholar.google.com.br/scholar?q=info:" . $data_cid . ":scholar.google.com/&output=cite&scirp=0&hl=en";
                echo $url_action . "<br>";
                $bibtex = save_data_bibtex($url_action);
                echo $bibtex . "<br>";
            @ flush();
        }
         exit;
        
        return $html;     
    }

    function save_data_key($html) {
        $content        = "";
        $data_cid       = "";
        $link_pdf       = "";
        $link_article   = "";
        $title_article  = "";
        $cited_by       = "";
        
        $classname="gs_r gs_or gs_scl";
        $values = getHTMLFromClass($html, $classname);

        foreach($values as $value) {
            $html_article = getHTMLFromClass($value, "gs_or_ggsm");
            $html_link_article = getHTMLFromClass($value, "gs_rt");
            // var_dump($html_link_article); exit;

            // if (!empty($html_article[0]))
            //     var_dump(getURLPDF($html_article[0]));

            if (!empty($html_link_article[0]))
                var_dump(getURLFromHTML($html_link_article[0]));
        }
        // var_dump($values); 
        exit;
        
        var_dump($arr); exit;
        foreach($nodes as $node) {
            
           var_dump($dom->saveHTML($nodes[1])); exit;
        }
        exit;
        foreach ($dom->getElementsByTagName('div') as $node) {

            if ($node->hasAttribute( 'data-cid' )) {
                $data_cid = trim($node->getAttribute( 'data-cid' ));
            }
            if ($node->hasAttribute( 'class' )) {
                if ($node->getAttribute( 'class' ) == "gs_or_ggsm") {
                    $child = $node->firstChild;
                    $link_pdf = trim($child->getAttribute( 'href' ));
                }
                if ($node->getAttribute( 'class' ) == "gs_ri") {
                    $childsDiv = $node->childNodes;
                    $nodeTitle = $childsDiv->item(0);
                    if ($nodeTitle->getElementsByTagName('a')->length > 0) {
                        $link_article = trim($nodeTitle->getElementsByTagName('a')->item(0)->getAttribute( 'href' ));
                    }                    
                    $title_article = trim($nodeTitle->textContent);
                    
                    foreach($childsDiv as $child) {
                        //  var_dump($child);
                        $textContent = trim($child->textContent);
                        if (!empty($textContent) && (strpos($textContent, "Citado por") !== false || strpos($textContent, "Cited by") !== false)) {
                            preg_match('/\d+/', $textContent, $matches);
                            $cited_by = $matches[0];
                            break;
                        }
                    }
                }
            }
            if (!empty($data_cid) && !empty($title_article) && !empty($link_article)) {
                $replaces = array("[BOOK][B]", "[PDF][PDF]", "[LIVRO][B]", "[CITAÇÃO][C]", "[HTML][HTML]", "[CITATION][C]");
                $title_article = trim(str_replace($replaces, "", $title_article));
                // var_dump(mb_detect_encoding($title_article));
                $content .= $data_cid . "|" . slug($title_article) . "|" . $title_article . "|" . $link_article . "|". $link_pdf . "|". $cited_by . "\r\n";
                $data_cid = "";
                $link_pdf = "";
                $link_article = "";
                $title_article = "";
                $cited_by = "";
            }
        }
        
        if (!empty($content)) {
            $oldContent = @file_get_contents($file);
            $newContent = $oldContent . $content;
            file_put_contents($file, $newContent);
        }    
    }

    function getHTMLFromClass($html, $classname) {

        $dom = getDOM($html);
        $finder = new DomXPath($dom);
        $nodes = $finder->query("//*[contains(@class, '$classname')]");
        $values = array();

        foreach($nodes as $node) {
            $values[] = $dom->saveHTML($node);
        }
        
        return $values;
    }

    function getURLFromHTML($html) {
        preg_match_all('/href="([^"]+)"/', $html, $arr, PREG_PATTERN_ORDER);
        if (!empty($arr[1])) {
            return $arr[1];
        }
        return "";
    }

?>