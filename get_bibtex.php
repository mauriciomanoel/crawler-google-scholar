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
        $cookie         = "NID=123=mMrtBajM_kUclM_qqJDlkz8Z1nINDA4T6Aal3L45MvCkxf7DdvQuxgbXFcITO2qPY-IytP5MEoDmQo-USBJwGB2TdvBeXyqz2-FkPeleQ8bj5q1YbNO4IAoiRoi97hutVN8dREzN7j6WcIewUniOsoTtx1DEMvdWhU_Frv0tyXyFzjLCg1Sm; 1P_JAR=2018-2-12-20; GSP=LD=en:LR=lang_en:CR=0:NR=20:CF=4:DT=1:A=tJV8Ig:CPTS=1518483080:LM=1518483080:S=JuAMUv1I7edDPwk2";
        $cookie         = (empty(@$cookie)) ? Util::getCookie($url) : $cookie;

        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);
        define('COOKIE', $cookie);
        define('FILE', $file);
        define('BREAK_LINE', $break_line);
        define('URL', $url);        

        if (!empty($page)) {            
            Util::showMessage("Page: " . $page);
            $url = GoogleScholar::getURL($page, $query_string);
            GoogleScholar::progress($url);
            
        }  else if (!empty($pages)) {
            for($page=60; $page<=$pages; $page+=20) {
                Util::showMessage("Page: " . $page);
                $url = GoogleScholar::getURL($page, $query_string);
                GoogleScholar::progress($url);
                $sleep = rand(4,7);
                Util::showMessage("Wait for " . $sleep . " seconds before executing next page");
                Util::showMessage("");
                sleep($sleep);
            }
        }

    } catch (Exception $e) {
        echo $e->getMessage() . $break_line;
    }
?>