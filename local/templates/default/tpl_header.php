<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if(isset($html_attributes)) echo $html_attributes; ?>>
<head>
<title>Online Sequencer - <?php echo $title; ?></title>
<?php if(isset($html_head)) echo $html_head."\n";
include("header_head.php");?>
<?php
show_css(array('resources/tooltipster',
'resources/fonts',
'resources/style',
'app/sequencer')); 
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?php
show_js(array('resources/jquery.tooltipster.min',
'resources/main',
'/forum/jscripts/ajaxchat_index',
'app/audioSystem',
'app/lib',
'app/synth',
'app/sequencer'));
?>
</head>
<body>
<?php
include("header_body.php"); 
?>
