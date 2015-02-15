<?php
$noteMap = array();
for($i = 0; $i < $settings['numNotes']; $i++)
{
	$noteMap[$settings['piano'][$i]] = 24+$i;
}
?>