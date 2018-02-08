<?php

include('functions.php');
$html = file_get_contents('example.html');
save_data_key($html); exit;

// $html = file_get_html('example.html');


// preg_match_all('/data-cid="([^"]+)"/', $html, $arr, PREG_PATTERN_ORDER);

$divs = '/<div class="gs_r gs_or gs_scl"(.*)<\/div>/iU';
preg_match_all($divs, $html, $arr, PREG_PATTERN_ORDER);

var_dump($arr); exit;


foreach($html->find('div[id=gs_res_ccl_mid]') as $element) {
    var_dump($element->href);
    
}
    // echo $element . '<br>';
?>