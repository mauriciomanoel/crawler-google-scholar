<?php
    set_time_limit(0);
    
    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });

    try {
        $break_line     = "<br>";
        $query_string   = urlencode(trim($_GET['query']));
        $file_name      = trim(@$_GET['query']);
        $page           = (int) @$_GET['page'];
        $pages          = (int) @$_GET['pages'];

        if (empty($query_string)) {
            throw new Exception("Query String not found");
        } 
        if ( (!empty($page) && !empty($pages) ) || ( empty($page) && empty($pages) )) {
            throw new Exception("Only one parameter: page or pages");
        }

        $file           = Util::slug(trim($file_name)) . ".bib";
        $url            = GoogleScholar::getURL(0, $query_string);
        // $cookie         = "NID=123=MqBYpgCNuIuEVjmOL0xzcskpRwoU9MDvbyCYXhSs17w7BALwVUVf97M_o7AcLLqkbK-mvrBsSqcjWp0cdtpSOfxZV1UomeLcUbf65zgc-mBhLcFu_S5QWSOHqe9Ixc7T; GSP=LD=en:LR=lang_en:NR=20:DT=1:LM=1518494355:S=Kdp7k4AdCW6V0cLS";
        $cookie         = "NIP:123=RUM9bWq4VMC2jWsdpaJE-q7MlpEmzTUcYlE4o6pxOXiiqaPxnOZAeZY4QF-btOUOXhPzZkLsDdIAmuexpuv0UUB04mpFpTU6hycSrQ3MmtdfWLOmrzgB8XIV-xjO8Zl8;GSP:LM=1518495074:S=iHhczGSQumu8X9t9";
        // $cookie         = Util::getCookie($url);

        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);
        define('COOKIE', $cookie);
        define('FILE', $file);
        define('BREAK_LINE', $break_line);

        if (!empty($page)) {            
            Util::showMessage("Page: " . $page);
            $url = GoogleScholar::getURL($page, $query_string);
            GoogleScholar::progress($url);
            
        }  else if (!empty($pages)) {
            for($page=60; $page<=$pages; $page+=20) {
                Util::showMessage("Page: " . $page);
                $url = GoogleScholar::getURL($page, $query_string);
                GoogleScholar::progress($url);
                $sleep = rand(4,10);
                Util::showMessage("Wait for " . $sleep . " seconds before executing next page");
                Util::showMessage("");
                sleep($sleep);
            }
        }

    } catch (Exception $e) {
        echo $e->getMessage() . $break_line;
    }
?>