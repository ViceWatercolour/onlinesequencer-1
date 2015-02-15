<?php
$featured = array(
    array(67179, "Gracious Intervention", "Wafels"),
    array(67844, "Angel Beats", "Sora"),
    array(55030, "TechnoTechnoTechnoooo!", "hmyj"),
    array(66964, "The Legend of Zelda Overworld Theme", ""),
    array(64836, "No Game No Life", "MiniRunaway"),
    array(44487, "The Lord of the Rings Melody", "zoyd11"),
    array(34334, "8 Awesome Angles of Youtube! Part One", ""),
    /*array(, "", ""),
    array(, "", ""),
    array(, "", ""),
    array(, "", ""),
    array(, "", ""),
    array(, "", ""),
    array(, "", ""),
    array(, "", ""),*/
);
if(!defined('IN_SITE'))
    require('../inc/init.php');
$limit = 12;
/*echo '<div id="featured" style="display:none">';
for($i = 0; $i < 4; $i++) {
    $seq = $featured[rand(0, count($featured)-1)];
    echo '<a href="/'.$seq[0].'" onclick="return onSequenceLinkClick(event, '.$seq[0].')"><img width="131" height="131" src="/preview.php?v=2&id='.$seq[0].'&title='.urlencode($seq[1]).'"/>';
}
echo '</div>';*/
echo '<div id="random">';
$result = db_query('SELECT *
  FROM sequences WHERE deleted=0 AND '.randomSequences($limit).' LIMIT '.$limit);
while($row = mysqli_fetch_array($result))
{
	echo '<a href="/'.$row['id'].'" onclick="return onSequenceLinkClick(event, '.$row['id'].')">'.preview($row['id'], $row['title']).'</a>';
}
echo '</div>
<div id="popular" style="display:none">';
$result = db_query('SELECT *
  FROM sequences WHERE deleted=0 AND '.time().'-date < 2628000 ORDER BY accesscount DESC LIMIT '.$limit);
while($row = mysqli_fetch_array($result))
{
	echo '<a href="/'.$row['id'].'" onclick="navigate('.$row['id'].'); return false;">'.preview($row['id'], $row['title']).'</a>';
}
echo '</div>';
$currentId = isset($id) ? $id : intval($_GET['id']);
echo '<div id="like_button">
<iframe src="//www.facebook.com/plugins/like.php?href='.$settings['domain'].'/'.($currentId == 0 ? '' : $currentId).'&amp;width=131&amp;layout=button_count&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=21&amp;appId=502727419751398" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:131px; height:21px;" allowTransparency="true"></iframe>
</div>';
?>