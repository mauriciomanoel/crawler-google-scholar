<?php
    set_time_limit(0);
    include('functions.php');

    try {
        if (defined('STDIN')) {
            $break_line     = "\r\n";
            $page           = (int) $argv[1];
            $file_name      = trim($argv[2]);
            $query_string   = urlencode(trim($argv[2]));            
        } else {
            $break_line     = "<br>";
            $query_string   = urlencode(trim($_GET['query']));
            $file_name      = trim($_GET['query']);
            $page           = (int) $_GET['page'];
        }

        if (empty($query_string)) {
            throw new Exception("Query String not found");
        }

        $file           = slug(trim($file_name)) . ".bib";
        $url            = getUrl(0, $query_string);
        $cookie         = getCookie($url);

        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);
        define('COOKIE', $cookie);
        define('FILE', $file);
        define('URL', $url);

        echo "Page: " . $page . $break_line;
        $url = getUrl($page, $query_string);
        progress_google($url, FILE, $break_line);
    } catch (Exception $e) {
        echo $e->getMessage() . $break_line;
    }
?>