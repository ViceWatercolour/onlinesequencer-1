<?php
if(!defined('NOT_IN_FORUM'))
{
	define('NOT_IN_FORUM', 1);
	require_once('../inc/init.php');
	
	include($GLOBALS['templates_path'].'/'.$GLOBALS['settings']['template'].'/main.php');
	echo '<link rel="stylesheet" href="/resources/style.css"/>';
}
global $settings, $cname;
if(isset($cname)) {
    echo '<link rel="canonical" href="'.$settings['domain'].'/'.$cname.'" />'."\n";
    echo '<meta property="og:url" content="'.$settings['domain'].'/'.$cname.'" />'."\n";
    echo '<meta property="og:image" content="'.$settings['domain'].'/preview.php?v=2&id='.$cname.'" />'."\n";
} else {
    echo '<meta property="og:image" content="'.$settings['domain'].'/resources/logo.png" />';
}
?>
<meta charset="utf-8"> 
<meta name="description" content="OnlineSequencer.net is an online music sequencer. Make tunes in your browser and share them with friends!" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Online Sequencer" />
<meta property="og:description" content="OnlineSequencer.net is an online music sequencer. Make tunes in your browser and share them with friends!" />
<meta property="fb:app_id" content="1512952002290893" />
<style id="css_vars">
.note {

}
.key, .key_sharp, .key div, .key_sharp div {

}
.sequencer_key {
    background-image:url(/app/sequencer1.gif);
}

</style>

<?php
global $headhtml;
if(isset($headhtml))
	echo $headhtml;
?>