<body>
<noscript><div class="sitemessage">This site requires JavaScript to be enabled in your browser settings to work properly.</div></noscript>
	<div id="container">
		<div id="header">
            <?php /*<div id="banner">
                <!-- Project Wonderful Ad Box Code -->
                <div id="pw_adbox_73955_1_0"></div>
                <script type="text/javascript"></script>
                <noscript><map name="admap73955" id="admap73955"><area href="http://www.projectwonderful.com/out_nojs.php?r=0&c=0&id=73955&type=1" shape="rect" coords="0,0,468,60" title="" alt="" target="_blank" /></map>
                <table cellpadding="0" cellspacing="0" style="width:468px;border-style:none;background-color:#ffffff;"><tr><td><img src="http://www.projectwonderful.com/nojs.php?id=73955&type=1" style="width:468px;height:60px;border-style:none;" usemap="#admap73955" alt="" /></td></tr><tr><td style="background-color:#ffffff;" colspan="1"><center><a style="font-size:10px;color:#0000ff;text-decoration:none;line-height:1.2;font-weight:bold;font-family:Tahoma, verdana,arial,helvetica,sans-serif;text-transform: none;letter-spacing:normal;text-shadow:none;white-space:normal;word-spacing:normal;" href="http://www.projectwonderful.com/advertisehere.php?id=73955&type=1" target="_blank">Ads by Project Wonderful!  Your ad here, right now: $0</a></center></td></tr></table>
                </noscript>
                <!-- End Project Wonderful Ad Box Code -->
            </div> */ ?>
			<div id="logo"><a href="/"></a></div>
<div style="position: absolute; top: 0; left: 0; width: 100%; text-align:center; "><span style="background-color: rgba(0, 0, 0, 0.8); padding: 8px; padding-bottom: 5px;"><a href="http://buildism.net/mc" target="_blank">Minecraft Capture the Flag!</a></span></div>
<div id="login">
<?php
$count = db_result(db_query('SELECT COUNT(id) FROM sequences'), 0);
echo 'Hosting '.number_format($count).' sequences since 2013 &middot; <a href="http://reddit.com/r/onlinesequencer" target="_blank" style="background-color:rgba(255, 255, 255, 0.3); padding: 3px;">Find us on reddit!</a>';
?>
</div>
<div id="nav">
<div>
Professional sequencing software: <a href="http://www.amazon.com/gp/product/B00CHZG1FE/ref=as_li_tf_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=B00CHZG1FE&linkCode=as2&tag=onlinseque-20">FL Studio</a><img src="http://ir-na.amazon-adsystem.com/e/ir?t=onlinseque-20&l=as2&o=1&a=B00CHZG1FE" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
<span id="nav_right"><?php if(isset($nav_right)) echo $nav_right; ?></span></div>
				<ul>
<?php
if(!function_exists('showNavLink'))
{
function showNavLink($url, $name, $extra="")
{
	$class = stristr($_SERVER["REQUEST_URI"], $url) ? ' class="active"' : '';
	echo '<li'.$class.'><a href="'.$url.'"'.$extra.'>'.$name.'</a></li>';
}
}
showNavLink('/sequences', 'All Sequences');
showNavLink('/import', 'Import MIDI');
showNavLink('/app/sequencer.php?frame=1&id='.(isset($id)?$id:'0'), 'Full Screen View');
showNavLink('javascript:;', 'Chat', ' onclick="showChat();"');
showNavLink('/forum/', 'Forum');
showNavLink('/experiments', 'Experiments');
?>
</ul>
			</div>
		</div>
		<div id="main">
		<div id="page_bg"></div>
			<div id="page" <?php if(!function_exists('show_left')) echo ' style="width: 100%"'; ?>>
			<?php if(function_exists('show_left')) { ?>
			<div id="page_left">
			<?php show_left(); ?>
			</div>
			<?php } ?>
			<div id="page_right"<?php if(!function_exists('show_left')) echo ' style="width: 100%;"'; ?>>
<?php if(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) { ?>
<div class="message">
Internet Explorer's audio implementation is very slow! Use Chrome or Firefox for much better results.
</div>
<?php } ?>