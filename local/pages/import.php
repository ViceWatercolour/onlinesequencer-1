<?php
mod('form');
$form = form_create('Continue', 'default', 'process_form', 'post');
function process_form() {
	global $form, $root, $userid;
	$f = $_FILES['file'];
    $name = $f['name'];
	$f['name'] = md5(rand(0, 1000000000));
	$path = $root.'/uploads/midi/'.$f['name'];
	if(!move_uploaded_file($f['tmp_name'], $path)) {
		form_add_error($form, 'Error uploading file.');
	}
	else {
		header('Location: /import2/'.$f['name'].'?title='.urlencode($name));
		exit;
	}
}
form_add_param($form, 'File', 'file', 'file;mid;20000000', '', 100, 14);
form_process();
output_header("Upload MIDI File");
output_block_start("Upload MIDI File");
echo '<p>Keep in mind that not all MIDI files will work well! Many MIDI files use features that are not supported by the sequencer; simple ones work best.</p>';
echo '<p>Examples: <a href="http://onlinesequencer.net/4679">Baba Yetu</a>, <a href="http://onlinesequencer.net/4680">Fangad aven korvring</a>, <a href="http://onlinesequencer.net/4681">Cliffs of Dover</a></p>';
form_display($form);
output_block_end();
output_footer();
?>