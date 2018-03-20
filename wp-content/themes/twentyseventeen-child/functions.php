<?php

include_once('inputtitle_submit_inc.php');

/*
add_action('wp_enqueue_scripts','ajax_test_enqueue_scripts');
funtion ajax_test_enqueue_scripts() {
   wp_enqueue_scripts('test',plugins_url('test.js',__FILE__),array('jquery'),'1.0',true);
}
*/


function FMAC_Test() {
   echo "This is the output from my test function";
}
?>
