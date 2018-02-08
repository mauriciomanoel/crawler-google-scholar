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

    $page = 0;
    $url = getUrl(0, $query_string);
    $html = progress_google($url);
    var_dump($html);

?>