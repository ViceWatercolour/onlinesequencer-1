<?php
$GLOBALS['pager_id'] = 0;
$GLOBALS['pager'] = array();
function pager_init($items_per_page) {
	global $pager, $pager_id;
	$pager[$pager_id] = array();
	$pager[$pager_id]['items_per_page'] = $items_per_page;
	$pager_id++;
	return $pager_id-1;
}
function pager_display($id, $query, $func) {
	global $pager;
	$pager[$id]['count'] = mysqli_num_rows(db_query($query));
	$pager[$id]['start'] = p_int('start'.pager_suffix($id), 0);
	$pager[$id]['stop'] = $pager[$id]['start'] + $pager[$id]['items_per_page'];
	$limit = ' LIMIT '.$pager[$id]['start'].', '.($pager[$id]['stop'] - $pager[$id]['start']);
	$result = db_query($query.$limit);
	while($row = mysqli_fetch_array($result))
	{
		$func($row);
	}
	pager_pagelinks($id);
}
function pager_suffix($id) {
	return $id == 0 ? '' : '_'.$id;
}
function pager_pagelinks($id) {
	global $pager;
	output_clear();
	if($pager[$id]['stop'] > $pager[$id]['items_per_page'])
		echo '<a class="linkbutton" style="margin: 10px;" href="'.(self(array('start'.pager_suffix($id) => ($pager[$id]['start'] - $pager[$id]['items_per_page'])))).'">&laquo; Previous Page</a>';
	if($pager[$id]['stop'] < $pager[$id]['count'])
		echo ' <a class="linkbutton" style="margin: 10px;" href="'.(self(array('start'.pager_suffix($id) => $pager[$id]['stop']))).'">Next Page &raquo;</a>';
}
?>