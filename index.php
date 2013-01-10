<?php
error_reporting(0);
session_start();
include("class/Lego.php");
$mc = microtime();
$lg = new Lego();
$lg->data_base();
$_CONFIG = $lg->get_config();
//$lg->new_mod();
//$lg->check_mod();
$lg->mod_inc();
//echo '<hr>'.(microtime()-$mc);

?>
