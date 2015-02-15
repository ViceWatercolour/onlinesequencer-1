<?php
require('app/sequencer.php');
$autoplay = 0;
if(isset($_GET['random'])) {
    $id = db_result(db_query('SELECT id FROM sequences WHERE deleted=0 AND scale>=0 AND LENGTH(data) > 500 ORDER BY RAND() LIMIT 1'), 0);
    $autoplay=1;
}
if($id != 0) {
	$result = db_query('SELECT * FROM sequences WHERE deleted=0 AND id="'.e_mysql($id).'" LIMIT 1');
	if(mysqli_num_rows($result) == 0)
	{
		output_message('Error', 'Unknown sequence: '.e_html($id));
		exit;
	}
	$row = mysqli_fetch_array($result);
    
    $title = formatSequenceTitle($row);
	$nav_right = formatSequenceInfo($row);
}
if($id != 0)
    $cname = $id;
if($id == 0)
    $showFeatured = true;
output_header($id == 0 ? 'Make music online' : $title, false, $id);
?>
<style type="text/css">
#main {
    bottom: 0;
}
</style>
<div id="middle">
<div id="frame_wrapper">
<?php
show_sequencer($id, $autoplay);
?>
</div>
<div id="sidebar">
<div class="btn_side"><a href="/"><img src="/resources/add.png" width="16" height="16"><span>New Sequence</span></a></div>
<div class="btn_side" style="height:auto;">
<?php /* <div id="sort_featured"><span id="featured_arrow"></span><a id="featured_link" href="javascript:;" onclick="sort('featured')">Featured</a></div> */ ?>
<div id="sort_random"><span id="random_arrow">&raquo;</span><a id="random_link" href="javascript:;" onclick="sort('random')" style="font-weight:bold">Random</a></div>
<div id="sort_hot"><span id="popular_arrow"></span><a id="popular_link" href="javascript:;" onclick="sort('popular')">Popular</a></div>
</div>
<div id="sidebar_dynamic">
<?php
require('ajax/random.php');
//if($showFeatured)
//    echo '<script type="text/javascript">sort("featured")</script>';
?>
</div>
</div>
</div>
</div>
<?php
output_footer();
?>
