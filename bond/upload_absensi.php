<?php

$namaFile = $_POST['namaFile'];
$namaFileHistory = $_POST['namaFileHistory'];
if($_FILES['photo']['name']){
	if(!$_FILES['photo']['error']){
		$path = "gambar_absensi/".$namaFile.'.jpg';
		move_uploaded_file($_FILES['photo']['tmp_name'],$path);

		copy($path,"gambar_absensi/".$namaFileHistory.'.jpg');
		$message='CONGRATS YOUR FILE ACCEPTED.';
	} else {
		$message='OOPS! your upload triggered the followring error:'.$_FILES['photo']['error'];
	}

	if(!$_FILES['photo']['error']){
		$message='CONGRATS YOUR FILE ACCEPTED.';
	} else {
		$message='OOPS! your upload triggered the followring error:'.$_FILES['photo']['error'];
	}

} else { die('You did not select any file!'); }

?>
