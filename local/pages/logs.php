<?php
output_header("Chat Logs");
output_block_start("Chat Logs");
?>
<style type="text/css">
td.left {
	width: 160px;
	background-color: rgba(107, 107, 107, 0.8);
	color: white;
	padding: 5px;
}
.left a {
	font-weight: bold;
	color: white;
}
#chat_table {
	background-color: rgba(107, 107, 107, 0.4);
	border: 1px solid #505050;
    margin-bottom: 10px;
}
#chat_table table {
	margin: 0;
	padding: 0;
}
#chat_table td {
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}
td.sendbar {
	background-color: #c0c0c0;
	padding: 5px;
}
td.spacer {
	height: 10px;
}
.char {
	float: left;
	margin-right: 1px;
}
</style>
<table width="100%" cellpadding="0" cellspacing="0" style="padding-top:10px;">
<tbody><tr>
<td>

<div id="chat_table">
<?php
define("IN_MYBB", true);
include("forum/global.php");
require_once('forum/inc/class_parser.php');
$parser = new postParser();
$url = '/logs';
$where = '';
if(!empty($_GET['search'])) {
    $search = trim(db_escape_string($_GET['search']));
    $result = db_query('SELECT uid FROM mybb_users WHERE username="'.$search.'"');
    if(mysqli_num_rows($result) == 0)
        $uid = -1;
    else {
        $uid = mysqli_fetch_array($result);
        $uid = $uid['uid'];
    }
    $where = " WHERE uid=$uid OR username LIKE \"%$search%\"OR message LIKE \"%$search%\"";
    $url .= '?search='.htmlspecialchars($search);
}
else
$search = '';


$perpage = 100;
if(isset($_GET['page']) && $_GET['page'] != "last") {
    $page = intval($_GET['page']);
}   
$count = mysqli_num_rows(db_query("SELECT id FROM mybb_ajaxchat$where"));
$pages = $count / $perpage;
$pages = ceil($pages);
if(isset($_GET['page']) && $_GET['page'] == "last") {
    $page = $pages;
}

if($page > $pages || $page <= 0) {
    $page = 1;
}

if($page) {
    $start = ($page-1) * $perpage;
}
else {
    $start = 0;
    $page = 1;
}
$upper = $start+$perpage;
$query = "SELECT * FROM mybb_ajaxchat$where LIMIT $start,$perpage";
$result = db_query($query);
while($row = mysqli_fetch_array($result)) {
    $date = date('n/j/y G:i', $row["date"]);
    $name = $row['username'] ? $row['username'] : 'Guest '.$row['uid'];
    $msg = $parser->parse_message($row["message"], array('filter_badwords'=>1, 'allow_mycode'=>1, 'allow_smilies'=>0, 'me_username'=>1, 'nl2br'=>0));
    echo '<table id="m'.$row['id'].'"><tr><td class="left">'.$name.'<br/><a href="logs?page='.(floor((($row['id']-12)/$perpage))+1).'#m'.$row['id'].'" target="_blank">&rarr; '.$date.'</a></td><td>'.$msg.'</td></tr></table>';
}
?>
</div>
<form action="logs" style="width: 250px; float: left;">
<input type="text" name="search" value="<?php echo $search; ?>" />
<input type="submit" value="Search" />
</form>
<?php
$multipage = multipage($count, $perpage, $page, $url);
if($count > $perpage) {
    echo $multipage;
}
?>
</td>
</tr>
</tbody></table>
<?php
output_block_end();
output_footer();
?>