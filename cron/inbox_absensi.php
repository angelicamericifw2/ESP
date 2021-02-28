<?php


$username = "sumberre";
$password = "PSBB2021Selesai!";
$pdo = new PDO('mysql:host=localhost;dbname=sumberre_esp', $username, $password);

/*$username = "root";
$password = "";
$pdo = new PDO('mysql:host=localhost;dbname=bond', $username, $password);*/

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'IND');

try {
	$pdo->beginTransaction();
	$jam_masuk = "";
	$jam_pulang ="";
	$hariIni = GetHariIni(null);
	if($hariIni == "MON"){
		$sql = "SELECT * FROM setting where nama = 'jam_masuk_sabtu'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$jam_masuk = $stmt->fetchColumn(2);

		$sql = "SELECT * FROM setting where nama = 'jam_pulang_sabtu'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$jam_pulang = $stmt->fetchColumn(2);

	} else {
		$sql = "SELECT * FROM setting where nama = 'jam_masuk'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$jam_masuk = $stmt->fetchColumn(2);

		$sql = "SELECT * FROM setting where nama = 'jam_pulang'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$jam_pulang = $stmt->fetchColumn(2);
	}

	$sql = "SELECT * FROM pemakai WHERE KODEJABATAN ='SALESMAN'";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	if($stmt->rowCount() > 0){
		$pemakai = $stmt->fetchAll(PDO::FETCH_CLASS);
		$array['tidak_absen'] = "Tidak Absen : <br>";
		$array['tidak_check_login'] = "Tidak Check Login : <br>";
		$array['tidak_check_logout'] = "Tidak Check Logout : <br>";
		$array['login_telat'] = "Absen Telat : <br>";
		$array['logout_awal'] = "Pulang Lebih Awal : <br>";
		foreach ($pemakai as $key => $value) {
			$days = 0;
			if($hariIni == 'TUE' || $hariIni == "WED" || $hariIni == "THU" || $hariIni == "FRI" || $hariIni == "SAT"){
				$days = 1;
			} else if ($hariIni == 'MON'){
				$days = 2;
			}

			$sql = "SELECT ma.waktu_login as waktu_login, ma.waktu_logout as waktu_logout FROM mabsensi ma  WHERE ma.userid =:userid AND tanggal =:tanggal";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				'userid'=>$value->USERID,
				'tanggal'=>date('Y-m-d', strtotime(' -'.$days.' days'))
			]);

			$sql2 = "SELECT * FROM mkaryawan WHERE KODEKARYAWAN = :KODEKARYAWAN";
			$stmt2 = $pdo->prepare($sql2);
			$stmt2->execute(['KODEKARYAWAN'=>$value->USERID]);
			$pemakai2 = $stmt2->fetch();

			if($stmt->rowCount() > 0){
				$pemakai = $stmt->fetch();
				if(!isset($pemakai['waktu_login'])){
					$array['tidak_check_login'] .= " - " . $pemakai2['NAMA'] . "<br>";
				} else if(!isset($pemakai['waktu_logout'])){
					$array['tidak_check_logout'] .= " - " . $pemakai2['NAMA'] . "<br>";
				} else {
					if(date('H:i',strtotime($pemakai['waktu_login'])) > $jam_masuk){
						$array['login_telat'] .= " - " . $pemakai2['NAMA'] . "<br>";
					} else if(date('H:i',strtotime($pemakai['waktu_logout'])) < $jam_pulang){
						$array['logout_awal'] .= " - " . $pemakai2['NAMA'] . "<br>";
					}
				}
			} else {
				$array['tidak_absen'] .= " - " . $pemakai2['NAMA'] ."<br>";
			}
		}

		
	}

	$isi = "Tanggal " . date('d-m-Y', strtotime(' -'.$days.' days')) . "<br>";
	$isi .= $array['tidak_absen'] . "<br>";
	$isi .= $array['tidak_check_login'] . "<br>";
	$isi .= $array['tidak_check_logout'] . "<br>";
	$isi .= $array['login_telat'] . "<br>";
	$isi .= $array['logout_awal'] . "<br>";
	$judul = "DAFTAR ABSENSI KEMARIN";
	$jenis = "cron_job_notification";
	$sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER'";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$array['KODEKARYAWAN'] = $stmt->fetchAll(PDO::FETCH_CLASS);
	if(isset($array['KODEKARYAWAN'])){
		$id = 0;
		foreach ($array['KODEKARYAWAN'] as $key => $value) {
			if(isset($value->GCM_ID) && $value->GCM_ID != ""){
				$array['GCM_ID_KARYAWAN'][$id] = $value->GCM_ID;
				$id++;
			}

			if($hariIni != "SUN"){

				$sql = "INSERT INTO inbox (penerima,title,jenis,isi) values(:penerima,:title,:jenis,:isi)";
				$stmt = $pdo->prepare($sql);
				$stmt->execute([
					'penerima'=>$value->USERID,
					'title'=>$judul,
					'jenis'=>$jenis,
					'isi'=>$isi
				]);
			}
		}
	}

	$content = array(
		"en" => $isi
	);  

	$headings = array(
		"en" =>$judul
	);

	$fields = array(
		'app_id' => "ec4cf440-afa6-4896-8350-d4e493082179",
		'include_player_ids' => $array['GCM_ID_KARYAWAN'],
		'data' => array("foo" => "bar"),
		'headings' => $headings,
		'contents' => $content
	);
	$fields = json_encode($fields);


	if($hariIni != "SUN"){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		curl_exec($ch);
		curl_close($ch);
	}
	$pdo->commit();
} catch(Exception $e){
	$pdo->rollBack();
	error_log("Hubungi Admin");
}


function GetHariIni($day){
	if(isset($day)){
		$tanggalHariIni = strtoupper(date('D',strtotime(date('Y-m-d',strtotime($day)))));
		return $tanggalHariIni;
	} else {
		$tanggalHariIni = strtoupper(date('D',strtotime(date('Y-m-d'))));
		return $tanggalHariIni;
	}
}

?>