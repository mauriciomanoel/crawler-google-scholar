<?php
    set_time_limit(0);
    include('functions.php');

    try {
        $query_string   = urlencode(trim($_GET['query']));
        $page           = (int) $_GET['page'];
        
        if (empty($query_string)) {
            throw new Exception("Query String not found");
        }

        $file           = slug(trim($_GET['query'])) . ".bib";
        $url            = getUrl(0, $query_string);
        $cookie         = getCookie($url);

        define('USER_AGENT', $_SERVER["HTTP_USER_AGENT"]);
        define('COOKIE', $cookie);
        define('FILE', $file);
        define('URL', $url);

        echo "Page: " . $page . "<br>\n\r";
        $url = getUrl($page, $query_string);
        progress_google($url, FILE);
    } catch (Exception $e) {
        echo $e->getMessage() . "<br>\n\r";
    }
?>