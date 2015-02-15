<?php
require('inc/init.php');
require_once('inc/solvemedialib.php');
mod('js');
$fail = 0;
$success = 0;
if(isset($_POST['adcopy_response'])) {
    $solvemedia_response = solvemedia_check_answer($settings['k_p'],
    $_SERVER["REMOTE_ADDR"],
    $_POST["adcopy_challenge"],
    $_POST["adcopy_response"],
    $settings['k_h']);
    if (!$solvemedia_response->is_valid && $_POST['adcopy_response'] != 1337)
        $fail = 1;
    else
        $success = 1;
}
?>
<html>
<head>
<?php
show_css(array('/resources/style'));
?>
<style type="text/css">
body {
    background: transparent;
    width: 310px;
    height: 310px;
    color: black;
    margin: 10px;
    padding: 0;
    position: relative;
}
p {
    margin: 0;
    margin-bottom: 10px;
}
input {
    margin-top: 10px;
}
input[type="text"] {
    color: black !important;
    background: white !important;
}
#adcopy-outer {
    position: absolute;
    left: 0;
    top: 0;
}
#captcha_container {
    width:300px; 
    height: 200px; 
    position: relative;
}
#captcha_alternate {
    width: 100%; 
    height: 100%; 
    line-height: 200px; 
    text-align: center;
}
</style>
</head>
<body>
<?php if($success) { ?>
<script type="text/javascript">
var d = new Date();
d.setTime(d.getTime()+(24*60*60*1000));
var expires = "expires="+d.toGMTString();
document.cookie = "captcha=1; " + expires;
window.parent.save();
</script>
<?php } else {?>
<p>To continue, please verify that you're a human and not a computer:</p>
<form action="" method="post">
<div id="captcha_container">
<div id="captcha_alternate">
1330 + 7 = <input type="text" name="adcopy_response" value="" />
</div>
<?php
echo solvemedia_get_html($settings['k_c']);
?>
</div>
<input type="submit" value="Save" />
</form>
<?php } ?>
</html>