<?php
mod("pager");
mod("form");

$gp = pager_init(66);

$searchform = form_create('Search', 'inline', null, 'get'); 
form_add_param($searchform, 'Value', 'search', 'string', '', 100, 12);

$search = p_string('search');
$basedon = p_int('basedon', 0);
$sort = p_string('sort', 1);
$date = p_string('date', empty($search) ? 1 : 4);

if(isset($_GET['deleted']))
	$where = ' WHERE deleted=1 ';
else if($basedon)
    $where = ' WHERE basedon="'.$basedon.'" ';
else if(!empty($_GET["search"]))
	$where = ' WHERE deleted=0 AND (title LIKE "%'.e_mysql($search).'%") ';
else
	$where = ' WHERE deleted=0 ';
switch($sort) {
	case 2:
		$order = "accesscount DESC";
	break;
	case 4:
		$order = "RAND()";
	break;
	case 3:
		$order = "datalength DESC";
	break;
	case 1:
		$order = "date DESC";
	break;
}
switch($date) {
	case 1:
		$where .= 'AND date > UNIX_TIMESTAMP() - 86400';
	break;
	case 2:
		$where .= 'AND date > UNIX_TIMESTAMP() - 604800';
	break;
	case 3:
		$where .= 'AND date > UNIX_TIMESTAMP() - 2592000';
	break;
	case 4:
	
	break;
}

function showSortLink($id, $name) {
	global $search, $sort, $left;
    $active = ($sort == $id) ? ' class="active"' : '';
	echo '<li><a'.$active.' href="'.self(array('sort'=>$id)).'">'.$name.'</a></li>';
	echo "\n";
}

function showDateLink($id, $name) {
	global $search, $date, $left;
    $active = ($date == $id) ? ' class="active"' : '';
	echo '<li><a'.$active.' href="'.self(array('date'=>$id)).'">'.$name.'</a></li>';
	echo "\n";
}

function show_left() {
	global $searchform;
	output_block_start('Search Titles');
	form_display($searchform);
	output_block_end();
	output_block_start('Sort by');
	echo '<ul>';
	showSortLink(1, "Newest");
	showSortLink(2, "Popular");
	showSortLink(3, "Longest");
	echo '</ul><ul>';
	showDateLink(1, "Today");
	showDateLink(2, "This week");
	showDateLink(3, "This month");
	showDateLink(4, "All time");
	echo '</ul>';
	output_block_end();
}

function display_seq($row) {
	echo '<div class="game"><a href="/'.$row['id'].'">'.preview($row['id'], $row['title']).'</a></div>';
}

output_header('Sequences');
output_block_start('Sequences');
pager_display($gp, "SELECT * FROM sequences$where ORDER BY $order", 'display_seq');
output_clear();
output_block_end();
output_footer();
?>
