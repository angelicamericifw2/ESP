<?php

$namaFile = $_POST['namaFile'];
if($_FILES['photo']['name']){


	if(!$_FILES['photo']['error']){
		$path = "gambar_pengeluaran/nomor_plat/".$namaFile.'.jpg';
		move_uploaded_file($_FILES['photo']['tmp_name'],$path);

		$message='CONGRATS YOUR FILE ACCEPTED.';
	} else {
		$message='OOPS! your upload triggered the followring error:'.$_FILES['photo']['error'];
		error_log($FILES);
	}


} else { die('You did not select any file!'); }

?>
