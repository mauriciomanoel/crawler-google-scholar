<?php
    set_time_limit(0);
    
    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });

    $break_line     = "<br>";
    define('BREAK_LINE', $break_line);

    try {        
        $query_string   = urlencode(trim(@$_GET['query']));
        $file_name      = trim(@$_GET['query']);
        $start          = (int) @$_GET['start'];
        $page           = (int) @$_GET['page'];
        $results        = (int) @$_GET['results'];

        if (empty($query_string)) {
            throw new Exception("Query String not found");
        }
        if ($start < 0) {
            throw new Exception("Start not found");
        } 
        if ( (!empty($page) && !empty($results) )) {
            throw new Exception("Only one parameter: page or results");
        }

        $file           = "bibtex/" . Util::slug(trim($file_name)) . ".bib";
        $url            = GoogleScholar::getURL(0, $query_string);        
        $cookie         = "1P_JAR=2018-2-14-13; GSP=LD=en:LR=lang_en:NR=20:CF=4:LM=1518616634:S=LBxaKc2xijQOMEC5; NID=123=P4UMpBFf6u-cTBVs6Zpe-SnsraXypD3iU4CL7Gc55QBMKM_U6HcqafQToUU0VyOX_W32GsOOYwUSuTfaDhqrVxJs1LZQCqMjmPJswq5utxoLZ1QfNxOUkqZ4YHwvgQi0";
        // $cookie         = Util::getCookie($url);

        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);
        define('COOKIE', $cookie);
        define('FILE', $file);
        define('FILE_LOG', "log_" . Util::slug(trim($file_name)) . ".txt");        
        
        if (isset($_GET['page'])) {         
            Util::showMessage("Page: " . $page);
            $url = GoogleScholar::getURL($page, $query_string);
            GoogleScholar::progress($url);                        
        }  else if (isset($_GET['results'])) {
            for($page=$start; $page<=$results; $page+=20) {
                Util::showMessage("Page: " . $page);
                $url = GoogleScholar::getURL($page, $query_string);
                GoogleScholar::progress($url);
                $sleep = rand(9,15);
                Util::showMessage("Wait for " . $sleep . " seconds before executing next page");
                Util::showMessage("");
                sleep($sleep);
            }
        }
        

    } catch (Exception $e) {
        Util::showMessage($e->getMessage());
    }
?>