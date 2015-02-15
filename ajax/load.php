<?php
if(!defined('IN_SITE'))
    require('../inc/init.php');
$id = intval($_GET['id']);
$row = mysqli_fetch_array(db_query('SELECT * FROM sequences WHERE id="'.$id.'"'));
db_query('UPDATE sequences SET accesscount=accesscount+1 WHERE id="'.$id.'"');

$dataString = str_replace(array('<', '>', '"', "'"), '', $row['data']);

header('Content-type: text/plain');
$data = array();
$data['title'] = $row['title'];
$data['windowTitle'] = formatSequenceTitle($row);
$data['data'] = $dataString;
$data['navRight'] = formatSequenceInfo($row);
echo json_encode($data);
?>