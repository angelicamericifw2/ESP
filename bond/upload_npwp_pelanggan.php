<?php

$namaFile = $_POST['namaFile'];
if($_FILES['photo']['name']){
	

	if(!$_FILES['photo']['error']){
		move_uploaded_file($_FILES['photo']['tmp_name'],"gambar_pelanggan/npwp/".$namaFile.'.jpg');
		$message='CONGRATS YOUR FILE ACCEPTED.';
	} else {
		$message='OOPS! your upload triggered the followring error:'.$_FILES['photo']['error'];
	}

} else { die('You did not select any file!'); }

?>
