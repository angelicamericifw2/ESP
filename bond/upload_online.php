<?php
$namaFile = $_POST['namaFile'];
error_log($_FILES['photo']['tmp_name']);
if($_FILES['photo']['name']){

	if(!$_FILES['photo']['error']){
		move_uploaded_file($_FILES['photo']['tmp_name'],"gambar_karyawan/".$namaFile.'.jpg');
		$message='CONGRATS YOUR FILE ACCEPTED.';
	} else {
		$message='OOPS! your upload triggered the followring error:'.$_FILES['photo']['error'];
		error_log($message);
	}

} else {
    die('You did not select any file!'); 
    error_log("tidak masuk");
    
}


?>
