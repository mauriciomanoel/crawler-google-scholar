<?php
    set_time_limit(0);
    include('functions.php');

    $query_string   = urlencode(trim($_GET['query']));
    $file           = slug(trim($_GET['query'])) . ".bib";
    $url            = getUrl(0, $query_string);
    $cookie         = getCookie($url);
    
    define('USER_AGENT', $_SERVER["HTTP_USER_AGENT"]);
    define('COOKIE', $cookie);
    define('FILE', $file);
    define('URL', $url);

    

    function save_data_key($dom, $file) {
        $content        = "";
        $data_cid       = "";
        $link_pdf       = "";
        $link_article   = "";
        $title_article  = "";
        $cited_by       = "";
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
        
    $page = 0;
    $url = getUrl(0, $query_string);
    $html = progress_google($url);
    var_dump($html);

?>