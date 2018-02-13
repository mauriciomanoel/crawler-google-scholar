<?php
    set_time_limit(0);
    
    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });

    try {
        $break_line     = "<br>";
        $query_string   = urlencode(trim($_GET['query']));
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

        $file           = Util::slug(trim($file_name)) . ".bib";
        $url            = GoogleScholar::getURL(0, $query_string);        
        $cookie         = "GSP=LD=en:LR=lang_en:NR=20:CF=4:DT=1:LM=1518535910:S=mcL3AI6G-bZ4o8oJ; NID=123=CldAvABaPXy4tPVcUoWOAux2U6qqs8Uqo-akNMbARVoIA9TQilopkpVDtp9_XH_eAPe4CUe9ssPLDnJIJT2Fx3eTVP1HZ_Ttu3LhReU2Agr1M8eO4HpXYE5UihGCgGJL";
        // $cookie         = Util::getCookie($url);

        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);
        define('COOKIE', $cookie);
        define('FILE', $file);
        define('FILE_LOG', "log_" . Util::slug(trim($file_name)) . ".txt");
        define('BREAK_LINE', $break_line);
        
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
        echo $e->getMessage() . $break_line;
    }
?>