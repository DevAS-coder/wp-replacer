<?php

$connect = mysqli_connect('localhost', 'root', '', 'PFWP');

$result = ['0'];
$counter = 0;
$words = array();

global $wpdb;
while ($result != []){
    $sql_query = $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}wpreplacer LIMIT 1 OFFSET $counter", 
        $counter
    );
    $result = $wpdb->get_row($sql_query, ARRAY_A);

    if ($result != []){
        $oldword = $result['old_word'];
        $newword = $result['new_word'];
        
        $words[$oldword] = $newword;
     
        $counter++;    
    }

    else{
        break;
    }
}

function word_replacment($content){
    global $words;
    foreach ($words as $word => $replacement) {
        $content = str_replace($word, $replacement, $content);
     }

     return $content;
}

add_action('template_redirect', 'wrp_start_output_buffer');
function wrp_start_output_buffer() {
    ob_start('word_replacment');
}

?>