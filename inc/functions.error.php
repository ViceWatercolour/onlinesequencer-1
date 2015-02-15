<?php
	function error_page($e) {
        clear_buffer();
		?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Online Sequencer - Error</title>
<meta charset="utf-8"> 
<meta name="Description" content="OnlineSequencer.net is an online music sequencer. Make tunes in your browser and share them with friends!" />
<link id="css_resources/fonts" rel="stylesheet" href="/resources/fonts.css" />
<link id="css_resources/style" rel="stylesheet" href="/resources/style.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
</head>
<body>
<body>
<noscript><div class="sitemessage">This site requires JavaScript to be enabled in your browser settings to work properly.</div></noscript>
	<div id="container">
		<div id="header">
            			<div id="logo"><a href="/"></a></div>
<div id="login">
<a href="http://reddit.com/r/onlinesequencer" target="_blank" style="background-color:rgba(255, 255, 255, 0.3); padding: 3px;">Find us on reddit!</a></div>
<div id="nav">
<div>
Professional sequencing software: <a href="http://www.amazon.com/gp/product/B00CHZG1FE/ref=as_li_tf_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=B00CHZG1FE&linkCode=as2&tag=onlinseque-20">FL Studio</a><img src="http://ir-na.amazon-adsystem.com/e/ir?t=onlinseque-20&l=as2&o=1&a=B00CHZG1FE" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
</div>
				<ul>
<li><a href="/sequences">All Sequences</a></li><li><a href="/import">Import MIDI</a></li><li><a href="/app/sequencer.php?0">Full Screen View</a></li></ul>
			</div>
		</div>
		<div id="main">
		<div id="page_bg"></div>
			<div id="page"  style="width: 100%">
						<div id="page_right" style="width: 100%;">
<div class="block">
<div class="btitle">Error</div>
<div class="bcontents">
<div class="bcontents_t">
<div class="bcontents_text">
<?php echo $e; ?></div></div><div class="bcontents_b"></div></div>
</div><div id="space"></div>
			</div>
			<div class="clear"></div>
<!-- Start of StatCounter Code -->
<script type="text/javascript">
var sc_project=8586779; 
var sc_invisible=1; 
var sc_security="5041619c"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="click tracking"
href="http://statcounter.com/" target="_blank"><img
class="statcounter"
src="http://c.statcounter.com/8586779/0/5041619c/1/"
alt="click tracking"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
<!-- End of StatCounter Code for Default Guide -->
<!-- Piwik -->
<script type="text/javascript"> 
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://buildism.net/stats//";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 2]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://buildism.net/stats/piwik.php?idsite=2" style="border:0" alt="" /></p></noscript>
			</div>
		</div>
	</div>
<br/><br/>
</body>
</html>		<?php
        die();
	}
?>