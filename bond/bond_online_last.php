<?php 

$servername ="localhost";
$username ="sumberre";
$password ="MarinatamaG12!";
/*
$username ="ecatering";
$password ="surabaya2017";
*/
//$db ="ecatering";

//$db ="ecatering_ta";
$db ="sumberre_esp";

//$ipGambar = "http://192.168.43.183:280/TugasAkhir/gambar_menu/";
//$ipGambar = "http://localhost:280/TugasAkhir/gambar_menu/";


//$ipGambar = "http://ecatering.shop/TugasAkhir/gambar_menu/";


/*if (isset($_GET['act'])) {
        // tampung passing GET ACT ke var act
        $act = $_GET['act'];
    }*/

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}	

$conn = new mysqli($servername,$username,$password,$db);
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pdo = new PDO('mysql:host=localhost;dbname=sumberre_esp', $username, $password);

if(isset($_POST["case"])){
    $act = $_POST["case"];	
}
else
{
    $act = "tampilanNota";
}
date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'IND');


switch($act){
    /*case "namaToko":
    $sql = "call spCoba";
    $result = mysqli_query($conn,$sql);
    $array = array();
    $i=0;

    while($row=mysqli_fetch_object($result)){
        $array['id'][$i] = $row->id;
        $array['nama'][$i] = $row->nama;
        $i++;
    }

    echo json_encode($array);
    break;

    case "prospekToko":
    $sql = "SELECT * from laporan_kunjungan";
    $result = mysqli_query($conn,$sql);
    $array = array();
    $i = 0;
    while($row=mysqli_fetch_object($result)){
        $array['prospek_toko'][$i] = $row->prospek_toko;
        $i++;
    }

    echo json_encode($array);
    break;*/

    case "TAMPIL_CABANG_SESUAI_USERID":

    $USERID = $_POST['USERID'];
    $sql = "SELECT p.KODECABANG as KODECABANG FROM pemakai p WHERE BINARY p.USERID=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        if($row['KODECABANG'] == "SEMUA"){
            $stmt = $pdo->prepare("SELECT * FROM cabang WHERE KODECABANG !=:KODECABANG");
            $stmt->execute(['KODECABANG'=>$row['KODECABANG']]); 
            while($cabang = $stmt->fetch()){
                $array['KODECABANG'][$id] = $cabang['KODECABANG'];
                $id++;
            }
            
        } else {
            $array['KODECABANG'][$id] = $row['KODECABANG'];
            $id++;
            while($row = $stmt->fetch()){
                $array['KODECABANG'][$id] = $row['KODECABANG'];
                $id++;
            }
        }
        
    } else {
        $array['hasil'] = "tidakada";
        $sql = "SELECT * FROM cabang WHERE KODECABANG !='SEMUA'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch()){
            $array['KODECABANG'][$id] = $row['KODECABANG'];
            $id++;
        }
    }

    echo json_encode($array);
    break;

    case "HOME_LOGIN":
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM pemakai WHERE BINARY USERID=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID' => $USERID]);
    if($row = $stmt->fetch()){
        $array['login']='berhasil';
        $array['KODEJABATAN'] = $row['KODEJABATAN'];
        $sql = "SELECT u.TAMBAH as TAMBAH, u.UBAH as UBAH, u.HAPUS as HAPUS, u.CETAK as CETAK, u.POSTING as POSTING, u.CARI as CARI, m.MENU as MENU, m.ICON as ICON, m.TAG as TAG FROM pemakai p INNER JOIN usermenu u on(u.USERID = p.USERID) INNER JOIN menu m on(m.SCREEN = u.SCREEN) WHERE BINARY p.USERID=:USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID]);
        $id = 0;
        if($row = $stmt->fetch()){
            $array['HASIL_USERMENU'] = "ada";
            $array['MENU'][$id] = $row['MENU'];
            $array['ICON'][$id] = $row['ICON'];
            $array['TAG'][$id] = $row['TAG'];

            $array['TAMBAH'][$id] = $row['TAMBAH'];
            $array['UBAH'][$id] = $row['UBAH'];
            $array['HAPUS'][$id] = $row['HAPUS'];
            $array['CETAK'][$id] = $row['CETAK'];
            $array['POSTING'][$id] = $row['POSTING'];
            $array['CARI'][$id] = $row['CARI'];

            $id++;
            while($row = $stmt->fetch()){
                $array['MENU'][$id] = $row['MENU'];
                $array['ICON'][$id] = $row['ICON'];
                $array['TAG'][$id] = $row['TAG'];

                $array['TAMBAH'][$id] = $row['TAMBAH'];
                $array['UBAH'][$id] = $row['UBAH'];
                $array['HAPUS'][$id] = $row['HAPUS'];
                $array['CETAK'][$id] = $row['CETAK'];
                $array['POSTING'][$id] = $row['POSTING'];
                $array['CARI'][$id] = $row['CARI'];
                $id++;
            }
        } else {
            $array['HASIL_USERMENU'] = "tidakada";
        }
    }



    echo json_encode($array);
    break;

    case "LOGIN":
    $USERID = $_POST['USERID'];
    $PASSWORD = $_POST['PASSWORD'];
    $array = array();
    $sql = "SELECT * FROM pemakai WHERE BINARY USERID=:USERID AND BINARY PASSWORD =:PASSWORD";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID,'PASSWORD'=>$PASSWORD]);
    if($row = $stmt->fetch()){
        $array['login']='berhasil';
        $array['KODEJABATAN'] = $row['KODEJABATAN'];
        $sql = "SELECT u.TAMBAH as TAMBAH, u.UBAH as UBAH, u.HAPUS as HAPUS, u.CETAK as CETAK, u.POSTING as POSTING, u.CARI as CARI, m.MENU as MENU, m.ICON as ICON, m.TAG as TAG FROM pemakai p INNER JOIN usermenu u on(u.USERID = p.USERID) INNER JOIN menu m on(m.SCREEN = u.SCREEN) WHERE BINARY p.USERID=:USERID AND p.PASSWORD =:PASSWORD";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID,'PASSWORD'=>$PASSWORD]);
        $id = 0;
        if($row = $stmt->fetch()){
            $array['HASIL_USERMENU'] = "ada";
            $array['MENU'][$id] = $row['MENU'];
            $array['ICON'][$id] = $row['ICON'];
            $array['TAG'][$id] = $row['TAG'];

            $array['TAMBAH'][$id] = $row['TAMBAH'];
            $array['UBAH'][$id] = $row['UBAH'];
            $array['HAPUS'][$id] = $row['HAPUS'];
            $array['CETAK'][$id] = $row['CETAK'];
            $array['POSTING'][$id] = $row['POSTING'];
            $array['CARI'][$id] = $row['CARI'];

            $id++;
            while($row = $stmt->fetch()){

                $array['MENU'][$id] = $row['MENU'];
                $array['ICON'][$id] = $row['ICON'];
                $array['TAG'][$id] = $row['TAG'];

                $array['TAMBAH'][$id] = $row['TAMBAH'];
                $array['UBAH'][$id] = $row['UBAH'];
                $array['HAPUS'][$id] = $row['HAPUS'];
                $array['CETAK'][$id] = $row['CETAK'];
                $array['POSTING'][$id] = $row['POSTING'];
                $array['CARI'][$id] = $row['CARI'];
                $id++;

            }
        } else {
            $array['HASIL_USERMENU'] = "tidakada";
        }

        if(isset($GCM_ID) && $GCM_ID != ""){
            $sql = "UPDATE pemakai set GCM_ID = :GCM_ID WHERE USERID = :USERID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID'=>$USERID,
                'GCM_ID'=>$GCM_ID
            ]);
        }

    } else {
        $array['login']='tidakberhasil';
    }


    echo json_encode($array);
    break;


    case "UBAH_PASSWORD":
    $USERID = $_POST['USERID'];
    $PASSWORDLAMA = $_POST['PASSWORDLAMA'];
    $PASSWORDBARU = $_POST['PASSWORDBARU'];
    $PASSWORDLAMA = mysqli_escape_string($conn,$PASSWORDLAMA);
    $PASSWORDBARU = mysqli_escape_string($conn,$PASSWORDBARU);
    $sql = "SELECT * FROM pemakai WHERE BINARY USERID=:USERID AND BINARY PASSWORD =:PASSWORD";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID,'PASSWORD'=>$PASSWORDLAMA]);

    $array = array();
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $sql = "UPDATE pemakai SET PASSWORD = :PASSWORD WHERE USERID =:USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['PASSWORD'=>$PASSWORDBARU,'USERID'=>$USERID]);
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_JABATAN":

    $sql = "SELECT * FROM mjabatan";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['STATUS'][$id] = $row['STATUS'];
        $array['KODECABANG'][$id] = $row['KODECABANG'];
        $id++;
        while($row = $stmt->fetch()){
            $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['STATUS'][$id] = $row['STATUS'];
            $array['KODECABANG'][$id] = $row['KODECABANG'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "INPUT_MASTER_JABATAN":
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $NAMA = $_POST['NAMA'];
    $KODECABANG = $_POST['KODECABANG'];

    $sql = "SELECT * FROM mjabatan WHERE KODEJABATAN = :KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEJABATAN'=>$KODEJABATAN]);
    $array = array();

    if($row = $stmt->fetch()){
        $array['hasil'] = 'ada';
    } else {
        $array['hasil'] = 'tidakada';
        $sql = "INSERT INTO mjabatan (KODEJABATAN,NAMA,USERID,STATUS,KODECABANG) values (:KODEJABATAN,:NAMA,:USERID,:STATUS,:KODECABANG)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODEJABATAN' =>$KODEJABATAN,
            'NAMA'=>$NAMA,
            'USERID'=>$USERID,
            'STATUS'=>'OPEN',
            'KODECABANG'=>$KODECABANG
        ]);

    }
    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_KATEGORI_PELANGGAN":
    $sql = "SELECT * FROM mkategoripelanggan";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()) {
        $array['hasil'] = "ada";
        $array['KODEKATEGORIPELANGGAN'][$id] = $row['KODEKATEGORIPELANGGAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['STATUS'][$id] = $row['STATUS'];
        $id++;
        while($row = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['KODEKATEGORIPELANGGAN'][$id] = $row['KODEKATEGORIPELANGGAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['STATUS'][$id] = $row['STATUS'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_POPUP_JABATAN":
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT * FROM mjabatan WHERE KODECABANG = :KODECABANG AND STATUS = 'CLOSE'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['STATUS'][$id] = $row['STATUS'];
        $array['KODECABANG'][$id] = $row['KODECABANG'];
        $id++;
        while($row = $stmt->fetch()){
            $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['STATUS'][$id] = $row['STATUS'];
            $array['KODECABANG'][$id] = $row['KODECABANG'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_PELANGGAN":
    $KODECABANG = $_POST['KODECABANG'];
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM pemakai WHERE USERID = :USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $row = $stmt->fetch();
    $KODEJABATAN = $row['KODEJABATAN'];


    if($KODEJABATAN == "OWNER" || $KODEJABATAN == "ADMIN" || $KODEJABATAN == "FINANCE"){
        $sql = "SELECT * FROM mpelanggan WHERE KODECABANG = :KODECABANG";
    } else {
        $sql = "SELECT distinct mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA, mp.ALAMAT as ALAMAT, mp.KOTA as KOTA, mp.TELPON as TELPON,mp.FAX as FAX, mp.KREDITLIMIT as KREDITLIMIT, mp.SYARATPIUTANG as SYARATPIUTANG, mp.SYARATPIUTANGMAX as SYARATPIUTANGMAX, mp.USERID as USERID, mp.TERM as TERM, mp.KATEGORIPELANGGAN as KATEGORIPELANGGAN, mp.ALAMATKIRIM as ALAMATKIRIM FROM mpelanggan mp inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE mp.KODECABANG = :KODECABANG AND (dp1.CP1 = '".$USERID."' OR mp.USERID='".$USERID."')";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['USERID'][$id] = $row['USERID'];
        $array['TERM'][$id] = $row['TERM'];
        $array['KATEGORIPELANGGAN'][$id] = $row['KATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $id++;
        while ($row = $stmt->fetch()) {
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['USERID'][$id] = $row['USERID'];
            $array['TERM'][$id] = $row['TERM'];
            $array['KATEGORIPELANGGAN'][$id] = $row['KATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_PELANGGAN_TANPA_PENGECUALIAN":
    $KODECABANG = $_POST['KODECABANG'];
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM pemakai WHERE USERID = :USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $row = $stmt->fetch();
    $KODEJABATAN = $row['KODEJABATAN'];


    $sql = "SELECT * FROM mpelanggan WHERE KODECABANG = :KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['USERID'][$id] = $row['USERID'];
        $array['TERM'][$id] = $row['TERM'];
        $array['KATEGORIPELANGGAN'][$id] = $row['KATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $id++;
        while ($row = $stmt->fetch()) {
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['USERID'][$id] = $row['USERID'];
            $array['TERM'][$id] = $row['TERM'];
            $array['KATEGORIPELANGGAN'][$id] = $row['KATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "DETAIL_PELANGGAN":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];

    $stmt = $pdo->prepare("SELECT mk.KODEKATEGORIPELANGGAN as KODEKATEGORIPELANGGAN FROM mpelanggan mp inner join mkategoripelanggan mk on(mk.KODEKATEGORIPELANGGAN = mp.KATEGORIPELANGGAN) WHERE mp.KODEPELANGGAN =:KODEPELANGGAN");
    $stmt->execute(['KODEPELANGGAN' =>$KODEPELANGGAN]);
    $pelanggan = $stmt->fetch();
    $KODEKATEGORIPELANGGAN = $pelanggan['KODEKATEGORIPELANGGAN'];

    $sql = "SELECT p.LATITUDE as LATITUDE, p.LONGITUDE as LONGITUDE,p.SYARATPIUTANGMAX as SYARATPIUTANGMAX,p.TERM as TERM, p.SYARATPIUTANG as SYARATPIUTANG, p.KREDITLIMIT as KREDITLIMIT,kp.KODEKATEGORIPELANGGAN as KODEKATEGORIPELANGGAN, p.KODEAREA as KODEAREA, p.status as STATUS,p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.NPWP as NPWP, p.KTP as KTP,kp.NAMA as NAMA FROM mkategoripelanggan kp inner join mpelanggan p on(p.KATEGORIPELANGGAN = kp.KODEKATEGORIPELANGGAN) WHERE KODEKATEGORIPELANGGAN=:KODEKATEGORIPELANGGAN AND KODEPELANGGAN = :KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN,'KODEPELANGGAN'=>$KODEPELANGGAN]);
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['NAMA'] = $row['NAMA'];
        $array['NAMAFAKTUR'] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'] = $row['ALAMATFAKTUR'];
        $array['NPWP'] = $row['NPWP'];
        $array['KTP'] = $row['KTP'];
        $array['STATUS'] = $row['STATUS'];
        $array['KODEAREA'] = $row['KODEAREA'];
        $array['KODEKATEGORIPELANGGAN'] = $row['KODEKATEGORIPELANGGAN'];
        $array['SYARATPIUTANGMAX'] = $row['SYARATPIUTANGMAX'];
        $array['SYARATPIUTANG'] = $row['SYARATPIUTANG'];
        $array['TERM'] = $row['TERM'];
        $array['LATITUDE'] = $row['LATITUDE'];
        $array['LONGITUDE'] = $row['LONGITUDE'];
        $array['KREDITLIMIT'] = 'Rp. '.number_format($row['KREDITLIMIT'],0,",",".");
    }

    $sql = "SELECT * FROM dpelanggan1 dp1 inner join mkaryawan mk on(mk.KODEKARYAWAN = dp1.CP1) WHERE dp1.KODEPELANGGAN=:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['CP1'][$id] = $row['NAMA'];
        $array['KJ1'][$id] = $row['KJ1'];
        $array['HP1'][$id] = $row['HP1'];
        $array['WA1'][$id] = $row['WA1'];
        $array['EMAIL1'][$id] = $row['EMAIL1'];
        $array['TANGGALLAHIR'][$id]=$row['TANGGALLAHIR'];
        $array['KETERANGAN'][$id]=$row['KETERANGAN'];
        $id++;
        while($row = $stmt->fetch()){
            $array['CP1'][$id] = $row['NAMA'];
            $array['KJ1'][$id] = $row['KJ1'];
            $array['HP1'][$id] = $row['HP1'];
            $array['WA1'][$id] = $row['WA1'];
            $array['EMAIL1'][$id] = $row['EMAIL1'];
            $array['TANGGALLAHIR'][$id]=$row['TANGGALLAHIR'];
            $array['KETERANGAN'][$id]=$row['KETERANGAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }  


    $sql = "SELECT * FROM dpelanggan2 WHERE KODEPELANGGAN = :KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil2'] = "ada2";
        $array['CP2'][$id] = $row['CP1'];
        $array['KJ2'][$id] = $row['KJ1'];
        $array['HP2'][$id] = $row['HP1'];
        $array['WA2'][$id] = $row['WA1'];
        $array['EMAIL2'][$id] = $row['EMAIL1'];
        $array['TANGGALLAHIR2'][$id]=$row['TANGGALLAHIR'];
        $array['KETERANGAN2'][$id]=$row['KETERANGAN'];
        $id++;
        while($row = $stmt->fetch()){
            $array['CP2'][$id] = $row['CP1'];
            $array['KJ2'][$id] = $row['KJ1'];
            $array['HP2'][$id] = $row['HP1'];
            $array['WA2'][$id] = $row['WA1'];
            $array['EMAIL2'][$id] = $row['EMAIL1'];
            $array['TANGGALLAHIR2'][$id] = $row['TANGGALLAHIR'];
            $array['KETERANGAN2'][$id] = $row['KETERANGAN'];
            $id++;
        }
    } else {
        $array['hasil2'] = "tidakada2";
    }

    $sql = "SELECT * FROM gambarpelanggan WHERE JENIS ='TOKO' AND KODEPELANGGAN = :KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $id=0;
    while($row = $stmt->fetch()){
        $array['hasilfototoko'] = "ada";
        $array['GAMBARTOKO'][$id] = $row['KODEGAMBAR'];
        $id++;
    }

    $sql = "SELECT * FROM gambarpelanggan WHERE JENIS ='KTP' AND KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($row = $stmt->fetch()){
        $array['hasilktp'] = "ada";
        $array['GAMBARKTP'] = $row['KODEGAMBAR'];
    } else {
        $array['hasilktp'] = "tidakada";
    }

    $sql = "SELECT * FROM gambarpelanggan WHERE JENIS ='NPWP' AND KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($row = $stmt->fetch()){
        $array['hasilnpwp'] = "ada";
        $array['GAMBARNPWP'] = $row['KODEGAMBAR'];
    } else {
        $array['hasilnpwp'] = "tidakada";
    }


    $sql = "SELECT * FROM tmkunjungan WHERE NOMORBUKTI = :NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($row = $stmt->fetch()){
        $array['hasilkunjungan'] = "ada";
        $array['TANGGALKUNJUNGAN'] = DATE("Y-m-d",strtotime($row['DATETRANSACTION']));
        $array['JAMMASUK'] = DATE("H:i:s",strtotime($row['WAKTUMASUK']));
        $array['JAMKUNJUNGAN'] = DATE("H:i:s",strtotime($row['DATETRANSACTION']));
        $array['KETERANGAN'] = $row['KETERANGAN'];
        $array['PERMINTAANKHUSUS'] = $row['PERMINTAANKHUSUS'];
        $array['KESIMPULAN'] = $row['KESIMPULAN'];
        $array['KODESALESMAN'] = $row['KODESALESMAN'];
    } else {
        $array['hasilkunjungan'] = "tidakada";
    }

    $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI.'%']);
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasilfotokunjungan'] = "ada";
        $array['NAMAGAMBAR'][$id] = $row['NAMAGAMBAR'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $row['NAMAGAMBAR'];
            $id++; 
        }
    } else {
        $array['hasilfotokunjungan'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_MENU":
    $sql = "SELECT * FROM menu";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['MENU'][$id] = $row['MENU'];
        $array['SCREEN'][$id] = $row['SCREEN'];
        $array['NOMOR'][$id] = $row['NOMOR'];
        $id++;
        while($row = $stmt->fetch()){
            $array['MENU'][$id] = $row['MENU'];
            $array['SCREEN'][$id] = $row['SCREEN'];
            $array['NOMOR'][$id] = $row['NOMOR'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }



    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_RENCANA_KUNJUNGAN":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT count(NOMORBUKTI) as JUMLAHOUTLET, DATETRANSACTION as DATETRANSACTION FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE KODESALESMAN =:USERID AND mp.KODECABANG =:KODECABANG AND NOMORBUKTI LIKE 'RK%' GROUP BY DATETRANSACTION ORDER BY DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID,'KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
        //$array['DATETRANSACTION'][$id] = date('d F Y',strtotime($row->DATETRANSACTION));
        //$array['DATETRANSACTION'][$id] = strftime("%e %B %Y", strtotime($row->DATETRANSACTION));
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){ 
            $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
            //$array['DATETRANSACTION'][$id] = date('d F Y',strtotime($row->DATETRANSACTION));
            //$array['DATETRANSACTION'][$id] = strftime("%e %B %Y", strtotime($row->DATETRANSACTION));
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_RENCANA_KUNJUNGAN_PARAMETER":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $STAFFDARI = $_POST['STAFFDARI'];
    $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
    $TANGGALDARI = $_POST['TANGGALDARI'];
    $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];

    $sql = "SELECT count(NOMORBUKTI) as JUMLAHOUTLET, DATETRANSACTION as DATETRANSACTION FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG AND NOMORBUKTI LIKE 'RK%' AND ";

    if($STAFFDARI){
        $sql .= " tm.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
    }

    if($STAFFSAMPAI){
        $sql .= " tm.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
    }

    if($TANGGALDARI){
        $sql .= " tm.DATETRANSACTION >= '" . date('Y-m-d',strtotime($TANGGALDARI)) ."' AND ";
    }

    if($TANGGALSAMPAI){
        $sql .= " tm.DATETRANSACTION <= '" . date('Y-m-d',strtotime($TANGGALSAMPAI)) ."' AND ";
    }

    

    $tampungSql = substr($sql,-5);
    if($tampungSql == " AND " || $tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .= " GROUP BY DATETRANSACTION ORDER BY DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){ 
            $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_RENCANA_KIRIMAN_COLLECTOR":
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $HALAMAN = $_POST['HALAMAN'];
    if($HALAMAN == "RENCANA_KIRIMAN"){
        $HALAMAN = "RF%";
    } else {
        $HALAMAN = "RC%";
    }
    if($KODEJABATAN == "OWNER"){
        $sql = "SELECT count(NOMORBUKTI) as JUMLAHOUTLET, DATETRANSACTION as DATETRANSACTION FROM tmrencanakunjungan WHERE NOMORBUKTI LIKE '".$HALAMAN."' GROUP BY DATETRANSACTION ORDER BY DATETRANSACTION DESC";
    } else {
        $sql = "SELECT count(NOMORBUKTI) as JUMLAHOUTLET, DATETRANSACTION as DATETRANSACTION FROM tmrencanakunjungan WHERE NOMORBUKTI LIKE '".$HALAMAN."' AND KODESALESMAN ='".$USERID."' GROUP BY DATETRANSACTION ORDER BY DATETRANSACTION DESC";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){ 
            $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_SEMUA_OUTLET_RENCANA_KUNJUNGAN":
    $USERID = $_POST['USERID'];
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID, t.NOMORBUKTI as NOMORBUKTI, t.KETERANGAN as KETERANGAN, p.NAMA as NAMA FROM tmrencanakunjungan t inner join mpelanggan p on(p.KODEPELANGGAN = t.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE t.DATETRANSACTION =:DATETRANSACTION AND t.KODESALESMAN =:USERID AND p.KODECABANG =:KODECABANG";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),'USERID'=>$USERID,'KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] ="ada";
        $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['TERM'][$id] = $row['TERM'];
        $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
        $array['KODEAREA'][$id] = $row['KODEAREA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['KETERANGAN'][$id] = $row['KETERANGAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['TERM'][$id] = $row['TERM'];
            $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
            $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
            $array['KODEAREA'][$id] = $row['KODEAREA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['KETERANGAN'][$id] = $row['KETERANGAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_SEMUA_OUTLET_RENCANA_KUNJUNGAN_PARAMETER":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $DATETRANSACTION = $_POST['DATETRANSACTION'];

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['STAFFDARI'])){
        $STAFFDARI = $_POST['STAFFDARI'];
    } else { $STAFFDARI = null; }

    if(isset($_POST['STAFFSAMPAI'])){
        $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
    } else { $STAFFSAMPAI = null; }
    
    $sql = "SELECT t.KODESALESMAN AS KODESALESMAN, p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID, t.NOMORBUKTI as NOMORBUKTI, t.KETERANGAN as KETERANGAN, p.NAMA as NAMA FROM tmrencanakunjungan t inner join mpelanggan p on(p.KODEPELANGGAN = t.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE p.KODECABANG='".$KODECABANG."' AND t.NOMORBUKTI LIKE 'RK%' AND t.DATETRANSACTION ='".date('Y-m-d',strtotime($DATETRANSACTION))."' AND ";
    if($STAFFDARI){
        $sql .= " t.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
    }

    if($STAFFSAMPAI){
        $sql .= " t.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
    }

    $tampungSql = substr($sql,-5);
    if($tampungSql == " AND " || $tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] ="ada";
        $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['TERM'][$id] = $row['TERM'];
        $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
        $array['KODEAREA'][$id] = $row['KODEAREA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
        $array['KETERANGAN'][$id] = $row['KETERANGAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['TERM'][$id] = $row['TERM'];
            $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
            $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
            $array['KODEAREA'][$id] = $row['KODEAREA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
            $array['KETERANGAN'][$id] = $row['KETERANGAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;


    case "TAMPIL_SEMUA_OUTLET_KUNJUNGAN":
    /*$DATETRANSACTION = date('Y-m-d',strtotime($_POST['DATETRANSACTION']));*/
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    /*$sql = "SELECT k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN = '".$USERID."' AND k.NOMORBUKTI LIKE 'KS%' GROUP BY k.NOMORBUKTI";*/

    if($KODEJABATAN == "OWNER"){
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN)  WHERE k.DATETRANSACTION >=:DATETRANSACTION AND k.DATETRANSACTION <:DATETRANSACTIONSAMPAI AND k.NOMORBUKTI LIKE 'KS%' GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN, k.DATETRANSACTION";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'DATETRANSACTION'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
            'DATETRANSACTIONSAMPAI'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))
        ]);
    } else {
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.DATETRANSACTION >=:DATETRANSACTION AND k.DATETRANSACTION <:DATETRANSACTIONSAMPAI AND k.NOMORBUKTI LIKE 'KS%' AND (dp1.CP1 =:USERID OR k.KODESALESMAN = :USERID) GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN, k.DATETRANSACTION";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'DATETRANSACTION'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
            'DATETRANSACTIONSAMPAI'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
            'USERID' => $USERID
        ]);

    }
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $row['NAMA'];
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['TERM'][$id] = $row['TERM'];
        $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
        $array['KODEAREA'][$id] = $row['KODEAREA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
        $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NAMA'][$id] = $row['NAMA'];
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['TERM'][$id] = $row['TERM'];
            $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
            $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
            $array['KODEAREA'][$id] = $row['KODEAREA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
            $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;


    case "INPUT_KATEGORI_PELANGGAN":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $NAMA = $_POST['NAMA'];
    $USERID = $_POST['USERID'];

    $sql = "SELECT * FROM mkategoripelanggan WHERE KODEKATEGORIPELANGGAN =:KODEKATEGORIPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN]);
    $array = array();

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
    } else {
        $array['hasil'] = "tidakada";
        $sql = "INSERT INTO mkategoripelanggan (KODEKATEGORIPELANGGAN,NAMA,USERID,STATUS) values(:KODEKATEGORIPELANGGAN,:NAMA,:USERID,:STATUS)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN,
            'NAMA'=>$NAMA,
            'USERID'=>$USERID,
            'STATUS'=>'OPEN'
        ]);

    }

    echo json_encode($array);
    break;

    case "INPUT_MASTER_MENU":
    $NOMOR = $_POST['NOMOR'];
    $SCREEN = $_POST['SCREEN'];
    $MENU = $_POST['MENU'];
    $ICON = $_POST['ICON'];
    $TAG = $_POST['TAG'];

    $sql = "SELECT * FROM menu WHERE NOMOR =:NOMOR OR SCREEN = :SCREEN OR MENU =:MENU";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'NOMOR'=>$NOMOR,
        'SCREEN'=>$SCREEN,
        'MENU'=>$MENU
    ]);
    $array = array();

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
    } else {
        $array['hasil'] = "tidakada";
        $tampungMenu ="";
        if(strlen($SCREEN)>1){
            for($i = 1; $i<strlen($SCREEN); $i++){
                $MENU = '     '.$MENU;           
            }
            $sql = "INSERT INTO menu (NOMOR,SCREEN,MENU,ICON,TAG) VALUES (:NOMOR,:SCREEN,:MENU,:ICON,:TAG)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'NOMOR'=>$NOMOR,
                'SCREEN'=>$SCREEN,
                'MENU'=>$MENU,
                'ICON'=>$ICON,
                'TAG'=>$TAG
            ]);
        } else {
            $sql = "INSERT INTO menu (NOMOR,SCREEN,MENU,ICON,TAG) VALUES (:NOMOR,:SCREEN,:MENU,:ICON,:TAG)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'NOMOR'=>$NOMOR,
                'SCREEN'=>$SCREEN,
                'MENU'=>$MENU,
                'ICON'=>$ICON,
                'TAG'=>$TAG
            ]);
        }
    }

    echo json_encode($array);
    break;

    case "EDIT_JABATAN":
    $NAMA = $_POST['NAMA'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $sql = "UPDATE mjabatan set NAMA =:NAMA WHERE KODEJABATAN =:KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'NAMA'=>$NAMA,
        'KODEJABATAN'=>$KODEJABATAN
    ]);
    break;


    case "TAMPIL_MASTER_HAK_AKSES":
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT * FROM pemakai WHERE KODECABANG ='".$KODECABANG."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['USERID'][$id] = $row['USERID'];
        $array['KODECABANG'][$id] = $row['KODECABANG'];
        $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
        $id++;
        while($row = $stmt->fetch()){
            $array['USERID'][$id] = $row['USERID'];
            $array['KODECABANG'][$id] = $row['KODECABANG'];
            $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;



    case "TAMPIL_HAK_AKSES_USERMENU":
    $USERID = $_POST['USERID'];
    $sql = "SELECT m.SCREEN as SCREEN, m.MENU as MENU, m.NOMOR as NOMOR FROM pemakai p INNER JOIN usermenu um on(um.USERID = p.USERID) INNER JOIN menu m on(m.SCREEN = um.SCREEN) WHERE um.USERID =:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMOR'][$id] = $row['NOMOR'];
        $array['SCREEN'][$id] = $row['SCREEN'];
        $array['MENU'][$id] = $row['MENU'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NOMOR'][$id] = $row['NOMOR'];
            $array['SCREEN'][$id] = $row['SCREEN'];
            $array['MENU'][$id] = $row['MENU'];
            $id++;
        }
    } else{
        $array['hasil'] = "tidakada";

    }

    echo json_encode($array);
    break;


    case "POSTING_JABATAN":
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $sql = "UPDATE mjabatan set STATUS ='CLOSE',USERID=:USERID WHERE KODEJABATAN = :KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEJABATAN'=>$KODEJABATAN,'USERID'=>$USERID]);
    break;

    case "HAPUS_JABATAN":
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $sql = "DELETE FROM mjabatan WHERE KODEJABATAN =:KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEJABATAN'=>$KODEJABATAN]);
    break;

    case "POSTING_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $USERID = $_POST['USERID'];
    $sql = "UPDATE mpelanggan set STATUS ='CLOSE',USERID = :USERID WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID,'KODEPELANGGAN'=>$KODEPELANGGAN]);
    break;

    case "HAPUS_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $USERID = $_POST['USERID'];
    $sql = "DELETE FROM mpelanggan WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    break;

    case "POSTING_KATEGORI_PELANGGAN":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $USERID = $_POST['USERID'];
    $sql = "UPDATE mkategoripelanggan set STATUS ='CLOSE',USERID =:USERID WHERE KODEKATEGORIPELANGGAN = :KODEKATEGORIPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID'=>$USERID,
        'KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN
    ]);
    break;

    case "HAPUS_KATEGORI_PELANGGAN":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $sql = "DELETE FROM MKATEGORIPELANGGAN WHERE KODEKATEGORIPELANGGAN=:KODEKATEGORIPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN
    ]);
    break;

    case "TAMPIL_INPUT_KARYAWAN":
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT * FROM mjabatan mj WHERE STATUS ='CLOSE' AND KODECABANG =:KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;

    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $row['NAMA'];
        $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
        $id++;

        while($row = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['NAMA'][$id] = $row['NAMA'];
            $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "INPUT_KARYAWAN":
    $KODEKARYAWAN = $_POST['KODEKARYAWAN'];
    $NAMA = $_POST['NAMA'];
    $ALAMAT = $_POST['ALAMAT'];
    $KOTA = $_POST['KOTA'];
    $TELEPON = $_POST['TELEPON'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $NPWP = $_POST['NPWP'];
    $KTP = $_POST['KTP'];
    $TANGGALMASUK = $_POST['TANGGALMASUK'];
    $STATUSKARYAWAN = 'T';
    $USERID = $_POST['USERID'];
    $WA1 = $_POST['WA1'];
    $EMAIL1 = $_POST['EMAIL1'];
    $TANGGALLAHIR = $_POST['TANGGALLAHIR'];

    $sql = "SELECT * FROM mkaryawan WHERE KODEKARYAWAN =:KODEKARYAWAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKARYAWAN'=>$KODEKARYAWAN]);
    $array = array();
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
    } else {
        $array['hasil'] = "tidakada";
        $sql = "INSERT INTO mkaryawan (KODEKARYAWAN, NAMA, ALAMAT, KOTA, TELEPON, KODEJABATAN, KODECABANG, NPWP, KTP, TANGGALMASUK, STATUSKARYAWAN,USERID,WA1, EMAIL1, TANGGALLAHIR) values(:KODEKARYAWAN,:NAMA,:ALAMAT,:KOTA,:TELEPON,:KODEJABATAN,:KODECABANG,:NPWP,:KTP,:TANGGALMASUK,:STATUSKARYAWAN,:USERID,:WA1,:EMAIL1,:TANGGALLAHIR)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODEKARYAWAN'=>$KODEKARYAWAN,
            'NAMA'=>$NAMA,
            'ALAMAT'=>$ALAMAT,
            'KOTA'=>$KOTA,
            'TELEPON'=>$TELEPON,
            'KODEJABATAN'=>$KODEJABATAN,
            'KODECABANG'=>$KODECABANG,
            'NPWP'=>$NPWP,
            'KTP'=>$KTP,
            'TANGGALMASUK'=>date('Y-m-d',strtotime($TANGGALMASUK)),
            'STATUSKARYAWAN'=>$STATUSKARYAWAN,
            'USERID'=>$USERID,
            'WA1'=>$WA1,
            'EMAIL1'=>$EMAIL1,
            'TANGGALLAHIR'=>date("Y-m-d",strtotime($TANGGALLAHIR))
        ]);
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_KARYAWAN":
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT mk.STATUS as STATUS,mk.KODEKARYAWAN AS KODEKARYAWAN, mk.NAMA as NAMA, mk.ALAMAT as ALAMAT, mk.KOTA as KOTA, mk.TELEPON as TELEPON, mj.NAMA as NAMAJABATAN, mk.KODEJABATAN as KODEJABATAN, mk.KODECABANG as KODECABANG, mk.NPWP as NPWP, mk.KTP as KTP, mk.TANGGALMASUK as TANGGALMASUK, mk.TANGGALKELUAR as TANGGALKELUAR, mk.STATUSKARYAWAN as STATUSKARYAWAN, mk.USERID as USERID  FROM mkaryawan mk left JOIN mjabatan mj on(mk.KODEJABATAN=mj.KODEJABATAN) WHERE mk.KODECABANG =:KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array =array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELEPON'][$id] = $row['TELEPON'];
        $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
        $array['NAMAJABATAN'][$id] = $row['NAMAJABATAN'];
        $array['KODECABANG'][$id] = $row['KODECABANG'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['TANGGALMASUK'][$id] = $row['TANGGALMASUK'];
        $array['TANGGALKELUAR'][$id] = $row['TANGGALKELUAR'];
        $array['STATUSKARYAWAN'][$id] = $row['STATUSKARYAWAN'];
        $array['USERID'][$id] = $row['USERID'];
        $array['STATUS'][$id] = $row['STATUS'];
        $id++;
        while($row = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELEPON'][$id] = $row['TELEPON'];
            $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
            $array['NAMAJABATAN'][$id] = $row['NAMAJABATAN'];
            $array['KODECABANG'][$id] = $row['KODECABANG'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['TANGGALMASUK'][$id] = $row['TANGGALMASUK'];
            $array['TANGGALKELUAR'][$id] = $row['TANGGALKELUAR'];
            $array['STATUSKARYAWAN'][$id] = $row['STATUSKARYAWAN'];
            $array['USERID'][$id] = $row['USERID'];
            $array['STATUS'][$id] = $row['STATUS'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_JABATAN":
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT * FROM mjabatan WHERE STATUS = 'CLOSE' AND KODECABANG=:KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    while($row = $stmt->fetch()){
        $array['hasil'] = 'ada';
        $array['KODEJABATAN'][$id]=$row['KODEJABATAN'];
        $array['NAMA'][$id]=$row['NAMA'];
        $id++;
    }

    echo json_encode($array);
    break;


    case "EDIT_KARYAWAN":
    $KODEKARYAWAN = $_POST['KODEKARYAWAN'];
    $NAMA = $_POST['NAMA'];
    $ALAMAT = $_POST['ALAMAT'];
    $KOTA = $_POST['KOTA'];
    $TELEPON = $_POST['TELEPON'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $NPWP = $_POST['NPWP'];
    $KTP = $_POST['KTP'];
    $TANGGALMASUK = $_POST['TANGGALMASUK'];
    $STATUSKARYAWAN = 'T';
    $USERID = $_POST['USERID'];
    $sql = "UPDATE mkaryawan SET NAMA=:NAMA, ALAMAT=:ALAMAT, KOTA=:KOTA, TELEPON=:TELEPON,KODEJABATAN=:KODEJABATAN, KODECABANG=:KODECABANG,NPWP =:NPWP, KTP=:KTP, USERID =:USERID WHERE KODEKARYAWAN=:KODEKARYAWAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'NAMA'=>$NAMA,
        'ALAMAT'=>$ALAMAT,
        'KOTA'=>$KOTA,
        'TELEPON'=>$TELEPON,
        'KODEJABATAN'=>$KODEJABATAN,
        'KODECABANG'=>$KODECABANG,
        'NPWP'=>$NPWP,
        'KTP'=>$KTP,
        'USERID'=>$USERID,
        'KODEKARYAWAN'=>$KODEKARYAWAN
    ]);
    break;

    case "EDIT_KATEGORI_PELANGGAN":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $USERID = $_POST['USERID'];
    $NAMA = $_POST['NAMA'];

    $sql = "SELECT * FROM MKATEGORIPELANGGAN WHERE NAMA =:NAMA";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NAMA'=>$NAMA]);
    $array = array();
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
    } else {
        $array['hasil'] = "tidakada";
        $sql = "UPDATE MKATEGORIPELANGGAN set NAMA =:NAMA, USERID=:USERID WHERE KODEKATEGORIPELANGGAN=:KODEKATEGORIPELANGGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'NAMA'=>$NAMA,
            'USERID'=>$USERID,
            'KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN
        ]);
    }

    echo json_encode($array);
    break;


    case "SELECT_KATEGORI_PELANGGAN":
    $sql = "SELECT * FROM mkategoripelanggan WHERE STATUS = 'CLOSE'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKATEGORIPELANGGAN'][$id] = $row['KODEKATEGORIPELANGGAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['KODEKATEGORIPELANGGAN'][$id] = $row['KODEKATEGORIPELANGGAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;


    case "INPUT_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $NAMA = $_POST['NAMA'];
    $ALAMAT = $_POST['ALAMAT'];
    $KOTA = $_POST['KOTA'];
    $TELPON = $_POST['TELPON'];
    $FAX = $_POST['FAX'];
    $ALAMATKIRIM = $_POST['ALAMATKIRIM'];
    $KATEGORIPELANGGAN = $_POST['KATEGORIPELANGGAN'];
    $USERID = $_POST['USERID'];
    $KODEAREA = $_POST['KODEAREA'];
    $NAMAFAKTUR = $_POST['NAMAFAKTUR'];
    $ALAMATFAKTUR = $_POST['ALAMATFAKTUR'];
    $KODECABANG = $_POST['KODECABANG'];

    if(isset($_POST['KREDITLIMIT'])&&!empty($_POST['KREDITLIMIT'])){
        $KREDITLIMIT = $_POST['KREDITLIMIT'];
    } else {
        $KREDITLIMIT = 0;
    }

    if(isset($_POST['SYARATPIUTANG'])&&!empty($_POST['SYARATPIUTANG'])){
        $SYARATPIUTANG = $_POST['SYARATPIUTANG'];
    } else {
        $SYARATPIUTANG = 0;
    }

    if(isset($_POST['SYARATPIUTANGMAX'])&&!empty($_POST['SYARATPIUTANGMAX'])){
        $SYARATPIUTANGMAX = $_POST['SYARATPIUTANGMAX'];
    } else {
        $SYARATPIUTANGMAX = 0;
    }

    if(isset($_POST['TERM'])&&!empty($_POST['TERM'])){
        $TERM = $_POST['TERM'];
    } else {
        $TERM = 0;
    }


    $sql = "";
    if(isset($_POST['NPWP'])){
        $NPWP = $_POST['NPWP'];
        $sql .= "SELECT * FROM mpelanggan WHERE NPWP !='' AND NPWP =:NPWP";
    } else { $NPWP = null; }

    if(isset($_POST['KTP'])){
        $KTP = $_POST['KTP'];
    } else { $KTP = null; }


    if(isset($_POST['CP1'])){
        $CP1 = $_POST['CP1'];
    } else { $CP1 = null; }

    if(isset($_POST['KJ1'])){
        $KJ1 = $_POST['KJ1'];
    } else { $KJ1 = null; }

    if(isset($_POST['HP1'])){
        $HP1 = $_POST['HP1'];
    } else { $HP1 = null; }

    if(isset($_POST['WA1'])){
        $WA1 = $_POST['WA1'];
    } else { $WA1 = null; }

    if(isset($_POST['EMAIL1'])){
        $EMAIL1 = $_POST['EMAIL1'];
    } else { $EMAIL1 = null; }

    if(isset($_POST['TANGGALLAHIR1'])){
        $TANGGALLAHIR1 = $_POST['TANGGALLAHIR1'];
    } else { $TANGGALLAHIR1 = null; }

    if(isset($_POST['KETERANGAN1'])){
        $KETERANGAN1 = $_POST['KETERANGAN1'];
    } else { $KETERANGAN1 = null; }



    if(isset($_POST['CP2'])){
        $CP2 = $_POST['CP2'];
    } else { $CP2 = null; }

    if(isset($_POST['KJ2'])){
        $KJ2 = $_POST['KJ2'];
    } else { $KJ2 = null; }

    if(isset($_POST['HP2'])){
        $HP2 = $_POST['HP2'];
    } else { $HP2 = null; }

    if(isset($_POST['WA2'])){
        $WA2 = $_POST['WA2'];
    } else { $WA2 = null; }

    if(isset($_POST['EMAIL2'])){
        $EMAIL2 = $_POST['EMAIL2'];
    } else { $EMAIL2 = null; }

    if(isset($_POST['TANGGALLAHIR2'])){
        $TANGGALLAHIR2 = $_POST['TANGGALLAHIR2'];
    } else { $TANGGALLAHIR2 = null; }

    if(isset($_POST['KETERANGAN2'])){
        $KETERANGAN2 = $_POST['KETERANGAN2'];
    } else { $KETERANGAN2 = null; }

    if(isset($_POST['fotoToko'])){
        $fotoToko = $_POST['fotoToko'];
    } else { $fotoToko = null; }

    if(isset($_POST['GAMBARNPWP'])){
        $GAMBARNPWP = $_POST['GAMBARNPWP'];
    } else { $GAMBARNPWP = null; }

    if(isset($_POST['GAMBARKTP'])){
        $GAMBARKTP = $_POST['GAMBARKTP'];
    } else { $GAMBARKTP = null; }

    $array = array();

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NPWP'=>$NPWP]);
    if($row = $stmt->fetch()){
        $array['hasil'] = "npwpterdaftar";
    } else {
        $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN =:KODEPELANGGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
        if($row = $stmt->fetch()){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
            
            $sql = "INSERT into mpelanggan (KODEPELANGGAN, KODECABANG, NAMA, ALAMAT, KOTA, TELPON, FAX, KREDITLIMIT, SYARATPIUTANG, SYARATPIUTANGMAX,TERM, ALAMATKIRIM, KATEGORIPELANGGAN, USERID,STATUS,NPWP,KTP,KODEAREA,NAMAFAKTUR,ALAMATFAKTUR) values(:KODEPELANGGAN, :KODECABANG,:NAMA,:ALAMAT,:KOTA,:TELPON,:FAX,:KREDITLIMIT, :SYARATPIUTANG, :SYARATPIUTANGMAX,:TERM, :ALAMATKIRIM,:KATEGORIPELANGGAN,:USERID,:STATUS,:NPWP,:KTP,:KODEAREA,:NAMAFAKTUR,:ALAMATFAKTUR)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEPELANGGAN'=>$KODEPELANGGAN,
                'KODECABANG'=>$KODECABANG,
                'NAMA'=>$NAMA,
                'ALAMAT'=>$ALAMAT,
                'KOTA' => $KOTA,
                'TELPON' => $TELPON,
                'FAX' => $FAX,
                'KREDITLIMIT' =>$KREDITLIMIT,
                'SYARATPIUTANG' =>$SYARATPIUTANG,
                'SYARATPIUTANGMAX' => $SYARATPIUTANGMAX,
                'TERM' =>$TERM,
                'ALAMATKIRIM' => $ALAMATKIRIM,
                'KATEGORIPELANGGAN' =>$KATEGORIPELANGGAN,
                'USERID' => $USERID,
                'STATUS' => 'OPEN',
                'NPWP' => $NPWP,
                'KTP' => $KTP,
                'KODEAREA' => $KODEAREA,
                'NAMAFAKTUR' => $NAMAFAKTUR,
                'ALAMATFAKTUR' => $ALAMATFAKTUR
            ]);
            

            if(isset($CP1)){
                foreach ($CP1 as $key => $value) {
                    $TANGGALLAHIR;
                    if($TANGGALLAHIR1[$key]){
                        $TANGGALLAHIR =date('Y-m-d',strtotime($TANGGALLAHIR1[$key]));
                    } else {
                        $TANGGALLAHIR = NULL;
                    }
                    $sql = "INSERT INTO dpelanggan1 (KODEPELANGGAN,CP1,KJ1,HP1,WA1,EMAIL1,TANGGALLAHIR,KETERANGAN) 
                    values(:KODEPELANGGAN,:CP1,:KJ1,:HP1,:WA1,:EMAIL1,:TANGGALLAHIR,:KETERANGAN1)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'KODEPELANGGAN'=>$KODEPELANGGAN,
                        'CP1'=>$CP1[$key],
                        'KJ1'=>$KJ1[$key],
                        'HP1'=>$HP1[$key],
                        'WA1'=>$WA1[$key],
                        'EMAIL1'=>$EMAIL1[$key],
                        'TANGGALLAHIR'=>$TANGGALLAHIR,
                        'KETERANGAN1'=>$KETERANGAN1[$key]
                    ]);
                }
            }

            if(isset($CP2)){
                foreach ($CP2 as $key => $value) {
                    $TANGGALLAHIR;
                    if($TANGGALLAHIR2[$key]){
                        $TANGGALLAHIR =date('Y-m-d',strtotime($TANGGALLAHIR2[$key]));

                    } else {
                        $TANGGALLAHIR = NULL;
                    }
                    $sql = "INSERT INTO dpelanggan2 (KODEPELANGGAN,CP1,KJ1,HP1,WA1,EMAIL1,TANGGALLAHIR,KETERANGAN) 
                    values(:KODEPELANGGAN,:CP2,:KJ2,:HP2,:WA2,:EMAIL2,:TANGGALLAHIR,:KETERANGAN2)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'KODEPELANGGAN'=>$KODEPELANGGAN,
                        'CP2'=>$CP2[$key],
                        'KJ2'=>$KJ2[$key],
                        'HP2'=>$HP2[$key],
                        'WA2'=>$WA2[$key],
                        'EMAIL2'=>$EMAIL2[$key],
                        'TANGGALLAHIR'=>$TANGGALLAHIR,
                        'KETERANGAN2'=>$KETERANGAN2[$key]
                    ]);
                }
            }

            if(isset($fotoToko)){
                foreach ($fotoToko as $key => $value) {
                    $KODEGAMBAR = $KODEPELANGGAN."_".$key."_toko";
                    $sql = "INSERT INTO gambarpelanggan (KODEGAMBAR, KODEPELANGGAN,JENIS) values (:KODEGAMBAR,:KODEPELANGGAN,'TOKO')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'KODEGAMBAR'=>$KODEGAMBAR,
                        'KODEPELANGGAN'=>$KODEPELANGGAN,
                    ]);
                }
            }

            if($GAMBARKTP != null){
                $KODEGAMBAR = $KODEPELANGGAN."_ktp";
                $sql = "INSERT INTO gambarpelanggan (KODEGAMBAR, KODEPELANGGAN,JENIS) values ('".$KODEGAMBAR."','".$KODEPELANGGAN."','KTP')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODEGAMBAR'=>$KODEGAMBAR,
                    'KODEPELANGGAN'=>$KODEPELANGGAN
                ]);
            }

            if($GAMBARNPWP != null){
                $KODEGAMBAR = $KODEPELANGGAN."_npwp";
                $sql = "INSERT INTO gambarpelanggan (KODEGAMBAR, KODEPELANGGAN,JENIS) values (:KODEGAMBAR,:KODEPELANGGAN,'NPWP')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODEGAMBAR'=>$KODEGAMBAR,
                    'KODEPELANGGAN'=>$KODEPELANGGAN
                ]);
            }
        }
    }

    echo json_encode($array);
    break;

    case "INPUT_RENCANA_KUNJUNGAN":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $USERID = $_POST['USERID'];
    if(isset($_POST['KODEPELANGGAN'])){
        $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    } else { $KODEPELANGGAN = null; }

    $array = array();
    try {
        $pdo->beginTransaction();

        $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION=:DATETRANSACTION AND NOMORBUKTI LIKE 'RK%' ORDER BY NOMORBUKTI DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION))]);
        if($row = $stmt->fetch()){
            $counter = substr($row['NOMORBUKTI'], -3);
            $counter++;
            foreach ($KODEPELANGGAN as $key => $value) {
                $sql = "SELECT * FROM tmrencanakunjungan WHERE KODEPELANGGAN =:KODEPELANGGAN AND DATETRANSACTION=:DATETRANSACTION AND KODESALESMAN = :KODESALESMAN AND NOMORBUKTI LIKE 'RK%'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION)),
                    'KODEPELANGGAN'=>$value,
                    'KODESALESMAN'=>$USERID
                ]);
                if($stmt->rowCount() > 0){
                    throw new Exception("Pelanggan " .$value." Sudah Terdaftar Rencana Kunjungan");
                }
                /*$sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODEPELANGGAN=:KODEPELANGGAN AND NOMORBUKTI LIKE 'RK%'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION'=> date('Y-m-d',strtotime($DATETRANSACTION)),
                    'KODEPELANGGAN'=> $value,
                ]);
                if($stmt->rowCount() > 0){
                    $kode = $stmt->fetch();
                    throw new Exception("PELANGGAN " . $value . " SUDAH ADA DI DALAM RENCANA KUNJUNGAN " . $kode['KODESALESMAN']);
                }*/

                $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND NOMORBUKTI LIKE 'RK%'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                ]);
            //dicek pelanggan sudah ada di rencana kunjungan atau tidak
                if($row = $stmt->fetch()){
                    $NOMORBUKTI = "RK".date('ymd',strtotime($DATETRANSACTION));
                    if(strlen($counter)==1){
                        $NOMORBUKTI .= "00".$counter;
                    } else if(strlen($counter)==2){
                        $NOMORBUKTI .= "0".$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                    $counter++;
                    $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN) values(:NOMORBUKTI,:DATETRANSACTION,:USERID,:KODEPELANGGAN)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'NOMORBUKTI'=>$NOMORBUKTI,
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                        'USERID'=>$USERID,
                        'KODEPELANGGAN'=>$value
                    ]);
                } else {
                    $NOMORBUKTI = "RK".date('ymd',strtotime($DATETRANSACTION));
                    if(strlen($counter)==1){
                        $NOMORBUKTI .= "00".$counter;
                    } else if(strlen($counter)==2){
                        $NOMORBUKTI .= "0".$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                    $counter++;
                    $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN) values(:NOMORBUKTI,:DATETRANSACTION,
                    :USERID,:KODEPELANGGAN)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'NOMORBUKTI'=>$NOMORBUKTI,
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                        'USERID'=>$USERID,
                        'KODEPELANGGAN'=>$value
                    ]);
                }
            }
        } else {
            $counter = 1;
            foreach ($KODEPELANGGAN as $key => $value) {
                $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND NOMORBUKTI LIKE 'RK%'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                ]);
            //dicek pelanggan sudah ada di rencana kunjungan atau tidak
                if($row = $stmt->fetch()){
                    $NOMORBUKTI = "RK".date('ymd',strtotime($DATETRANSACTION));

                    if(strlen($counter)==1){
                        $NOMORBUKTI .= "00".$counter;
                    } else if(strlen($counter)==2){
                        $NOMORBUKTI .= "0".$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                    $counter++;
                    $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN) values(:NOMORBUKTI,:DATETRANSACTION,:USERID,:KODEPELANGGAN)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'NOMORBUKTI'=>$NOMORBUKTI,
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                        'USERID'=>$USERID,
                        'KODEPELANGGAN'=>$value
                    ]);
                } else {
                    $NOMORBUKTI = "RK".date('ymd',strtotime($DATETRANSACTION));

                    if(strlen($counter)==1){
                        $NOMORBUKTI .= "00".$counter;
                    } else if(strlen($counter)==2){
                        $NOMORBUKTI .= "0".$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                    $counter++;
                    $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN) values(:NOMORBUKTI,:DATETRANSACTION,:USERID,:KODEPELANGGAN)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'NOMORBUKTI'=>$NOMORBUKTI,
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                        'USERID'=>$USERID,
                        'KODEPELANGGAN'=>$value
                    ]);
                }
            }
        }
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch (Exception $e){
        $pdo->rollBack();
        $array['hasil'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "POPUP_TAMPIL_NAMA_TOKO":
    $KODECABANG = $_POST['KODECABANG'];
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM pemakai WHERE USERID = :USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $row = $stmt->fetch();
    $KODEJABATAN = $row['KODEJABATAN'];
    if($USERID == "MPT-1" || $USERID == "MPT-2" || $USERID == "MPT-3"){
        $sql = "SELECT mp.USERID as KODESALESMAN, mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA FROM mpelanggan mp  WHERE mp.KODECABANG = :KODECABANG AND mp.KODEPELANGGAN = 'PT.MODERN'";
    } else {   
        $sql = "SELECT mp.USERID as KODESALESMAN, mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA FROM mpelanggan mp  WHERE mp.KODECABANG = :KODECABANG";
    }
    /*if($USERID == "SUMBER" || $KODEJABATAN != "SALESMAN"){
        $sql = "SELECT mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA FROM mpelanggan mp  WHERE mp.KODECABANG = '".$KODECABANG."'";
    } else {
        $sql = "SELECT mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA FROM mpelanggan mp INNER JOIN dpelanggan1 dp1 on(dp1.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE mp.KODECABANG = '".$KODECABANG."' AND dp1.CP1 = '".$USERID."'";
    }*/
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $id = 0;
    $array = array();
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "INPUT_KUNJUNGAN":
    //KS
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $LATITUDE = $_POST['latGlobal'];
    $LONGITUDE = $_POST['lngGlobal'];
    $USERID = $_POST['USERID'];
    $STATUSKUNJUNGAN = $_POST['STATUSKUNJUNGAN'];
    $PERMINTAANKHUSUS = $_POST['PERMINTAANKHUSUS'];
    //$LATITUDEEDIT = $_POST['LATITUDEEDIT'];
    //$LONGITUDEEDIT = $_POST['LONGITUDEEDIT'];
    $WAKTUMASUK = $_POST['waktuMasukLoginTokoKunjungan'];

    //$NAMAFOTO = $_POST['namaFotoKunjungan'];
    $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN' => $KODEPELANGGAN]);
    $pelanggan = $stmt->fetchAll(PDO::FETCH_CLASS);

    $sql = "SELECT mk.NAMA AS NAMAKATEGORIPELANGGAN FROM mpelanggan mp INNER JOIN mkategoripelanggan mk on(mk.KODEKATEGORIPELANGGAN = mp.KATEGORIPELANGGAN) WHERE KODEPELANGGAN=:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $kategoripelanggan = $stmt->fetch();
    $pelanggan[0]->NAMAKATEGORIPELANGGAN = $kategoripelanggan['NAMAKATEGORIPELANGGAN'];

    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['LATITUDEEDIT'])){
        $LATITUDEEDIT = $_POST['LATITUDEEDIT'];
    } else {
        $LATITUDEEDIT = NULL;
    }

    if(isset($_POST['LONGITUDEEDIT'])){
        $LONGITUDEEDIT = $_POST['LONGITUDEEDIT'];
    } else {
        $LONGITUDEEDIT = NULL;
    }

    if(isset($_POST['TANGGALKEMBALI'])){
        $TANGGALKEMBALI = $_POST['TANGGALKEMBALI'];
    } else {
        $TANGGALKEMBALI = NULL;
    }
    $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= '".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC";


    $LATLONGPELANGGAN = "";
    //CEK LATITUDE LONGITUDE PELANGGAN KOSONG TIDAK
    /*$sql = "SELECT * FROM mpelanggan WHERE ISNULL(LATITUDE) AND ISNULL(LONGITUDE) AND KODEPELANGGAN='".$KODEPELANGGAN."'";
    $result = mysqli_query($conn,$sql);*/
    $sql = "SELECT * FROM mpelanggan WHERE ISNULL(LATITUDE) AND ISNULL(LONGITUDE) AND KODEPELANGGAN=:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($row = $stmt->fetch()){
        $LATLONGPELANGGAN = "belum";
        /*$sql ="UPDATE mpelanggan set LATITUDE ='".$LATITUDE."', LONGITUDE ='".$LONGITUDE."' WHERE KODEPELANGGAN ='".$KODEPELANGGAN."'";
        $result = mysqli_query($conn,$sql);*/
        $sql ="UPDATE mpelanggan set LATITUDE =:LATITUDE, LONGITUDE =:LONGITUDE WHERE KODEPELANGGAN =:KODEPELANGGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'LATITUDE'=>$LATITUDE,
            'LONGITUDE'=>$LONGITUDE,
            'KODEPELANGGAN'=>$KODEPELANGGAN
        ]);
    } else {
        $LATLONGPELANGGAN = "ada";
    }

    $array = array();
    $array['NOMORBUKTI'] = "";
    $NOMORBUKTI = "KS".date('ymd',strtotime($DATETRANSACTION));
    //CEK APA SUDAH ADA DI RENCANA KUNJUNGAN ATAU BELUM
    /*$sql = "SELECT * FROM tmrencanakunjungan WHERE KODEPELANGGAN = '".$KODEPELANGGAN."' AND DATETRANSACTION = '".date('Y-m-d',strtotime($TANGGALKEMBALI))."' AND KODESALESMAN ='".$USERID."'";
    $result = mysqli_query($conn,$sql);*/
    $sql = "SELECT * FROM tmrencanakunjungan WHERE KODEPELANGGAN = :KODEPELANGGAN AND DATETRANSACTION = :DATETRANSACTION AND KODESALESMAN =:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODEPELANGGAN'=>$KODEPELANGGAN,
        'DATETRANSACTION'=>date('Y-m-d',strtotime($TANGGALKEMBALI)),
        'USERID'=>$USERID
    ]);
    if($row = $stmt->fetch()){
        $array['hasil'] = "sudahrencanakembali";
    } else {
        /*$sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= '".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND NOMORBUKTI LIKE 'KS%' AND KODEPELANGGAN ='".$KODEPELANGGAN."' AND KODESALESMAN ='".$USERID."' ORDER BY DATETRANSACTION DESC";*/
        $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE 'KS%' AND KODEPELANGGAN =:KODEPELANGGAN AND KODESALESMAN =:USERID ORDER BY DATETRANSACTION DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
            'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
            'KODEPELANGGAN'=>$KODEPELANGGAN,
            'USERID'=>$USERID
        ]);
        if($row = $stmt->fetch()){

            $sql_jabatan = "";
            $judul = "";
            $isi = "";
            $jenis = "notification_support";
            $array['hasil'] = "pernahkunjungan";
            $time30menit = date('Y-m-d H:i:s',strtotime('+30minutes',strtotime($row['DATETRANSACTION'])));
            $dateTimeSekarang = date("Y-m-d H:i:s");

            if($time30menit>=$dateTimeSekarang){
                $array['hasil'] = "pernahkunjunganlebih";
            } else {

                $array['hasil'] = "belumkunjungan";
                /*$sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= '".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";*/
                $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
                    'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),

                ]);
                if($row = $stmt->fetch()){
                    $counter = substr($row['NOMORBUKTI'], -3);
                    $counter++;
                    if(strlen($counter) == 1){
                        $NOMORBUKTI .= "00" .$counter;

                    } else if (strlen($counter) == 2){
                        $NOMORBUKTI .= "0" .$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                } else {
                    $NOMORBUKTI .= "001";
                }
                if($TANGGALKEMBALI == null && $LATLONGPELANGGAN == "belum"){
                    $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
                    $stmt = $pdo->prepare($sql);  
                    $stmt->execute([
                        'KETERANGAN'=>$KETERANGAN,
                        'KESIMPULAN'=>$KESIMPULAN,
                        'LATITUDEEDIT'=>$LATITUDEEDIT,
                        'LONGITUDEEDIT'=>$LONGITUDEEDIT
                    ]);
                    $array['NOMORBUKTI'] = $NOMORBUKTI;

                    if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                        if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                            $sql_jabatan = " KODEJABATAN = 'FINANCE'";
                            /*$judul = $PERMINTAANKHUSUS;
                            $isi = "PELANGGAN ". $KODEPELANGGAN. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                            $isiPushNotif = "PELANGGAN ". $namaPelanggan. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;*/
                        } else {
                            $sql_jabatan = " KODEJABATAN ='ADMIN'";
                        }

                        $judul = $PERMINTAANKHUSUS;
                        $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                        $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

                        $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        if($stmt->rowCount() > 0){
                            $id = 0;
                            while($pemakai = $stmt->fetch()){
                                $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                                $id++;
                            }
                        }

                        foreach ($array['KODEKARYAWAN'] as $key => $value) {
                            $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->execute([
                                'pengirim'=>$USERID,
                                'penerima'=>$value,
                                'title'=>$judul,
                                'jenis'=>$jenis,
                                'isi'=>$isi
                            ]);
                        }
                    }
                    
                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        /*$sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values('".$NOMORBUKTI."-".$NAMAFOTO[$i]."')";*/
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                        ]);
                    }
                } else if($TANGGALKEMBALI == null && $LATLONGPELANGGAN =="ada"){
                    $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
                    $stmt = $pdo->prepare($sql);  
                    $stmt->execute([
                        'KETERANGAN'=>$KETERANGAN,
                        'KESIMPULAN'=>$KESIMPULAN,
                        'LATITUDEEDIT'=>$LATITUDEEDIT,
                        'LONGITUDEEDIT'=>$LONGITUDEEDIT
                    ]);
                    $array['NOMORBUKTI'] = $NOMORBUKTI;

                    if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                        if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                            $sql_jabatan = " KODEJABATAN = 'FINANCE'";
                            /*$judul = $PERMINTAANKHUSUS;
                            $isi = "PELANGGAN ". $KODEPELANGGAN. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                            $isiPushNotif = "PELANGGAN ". $namaPelanggan. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;*/
                        } else {
                            $sql_jabatan = " KODEJABATAN ='ADMIN'";
                        }

                        $judul = $PERMINTAANKHUSUS;
                        $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                        $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

                        $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        if($stmt->rowCount() > 0){
                            $id = 0;
                            while($pemakai = $stmt->fetch()){
                                $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                                $id++;
                            }
                        }

                        foreach ($array['KODEKARYAWAN'] as $key => $value) {
                            $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->execute([
                                'pengirim'=>$USERID,
                                'penerima'=>$value,
                                'title'=>$judul,
                                'jenis'=>$jenis,
                                'isi'=>$isi
                            ]);
                        }
                    }
                    

                    if(isset($NAMAFOTO)){
                        for($i = 0; $i<count($NAMAFOTO); $i++){
                            /*$sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values('".$NOMORBUKTI."-".$NAMAFOTO[$i]."')";*/
                            $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                            ]);
                        }
                    }

                } else {
                    $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,TANGGALKEMBALI,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,'".date('Y-m-d',strtotime($TANGGALKEMBALI))."',:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
                    $stmt = $pdo->prepare($sql);  
                    $stmt->execute([
                        'KETERANGAN'=>$KETERANGAN,
                        'KESIMPULAN'=>$KESIMPULAN,
                        'LATITUDEEDIT'=>$LATITUDEEDIT,
                        'LONGITUDEEDIT'=>$LONGITUDEEDIT
                    ]);
                    $array['NOMORBUKTI'] = $NOMORBUKTI;

                    if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                        if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                            $sql_jabatan = " KODEJABATAN = 'FINANCE'";
                            /*$judul = $PERMINTAANKHUSUS;
                            $isi = "PELANGGAN ". $KODEPELANGGAN. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                            $isiPushNotif = "PELANGGAN ". $namaPelanggan. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;*/
                        } else {
                            $sql_jabatan = " KODEJABATAN ='ADMIN'";
                        }

                        $judul = $PERMINTAANKHUSUS;
                        $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                        $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

                        $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        if($stmt->rowCount() > 0){
                            $id = 0;
                            while($pemakai = $stmt->fetch()){
                                $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                                $id++;
                            }
                        }

                        foreach ($array['KODEKARYAWAN'] as $key => $value) {
                            $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->execute([
                                'pengirim'=>$USERID,
                                'penerima'=>$value,
                                'title'=>$judul,
                                'jenis'=>$jenis,
                                'isi'=>$isi
                            ]);
                        }
                    }
                    



                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                        ]);
                        
                    }
                    /*$sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION ='".date('Y-m-d',strtotime($DATETRANSACTION))."' AND KODESALESMAN = '".$USERID."'";*/
                    $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODESALESMAN = :USERID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                        'USERID'=>$USERID
                    ]);
                    $NOMORBUKTI = "RK" . date('ymd',strtotime($DATETRANSACTION));
                    if($row = $stmt->fetch()){
                        $counter = substr($row['NOMORBUKTI'], -3);
                        $counter++;
                        if(strlen($counter) == 1){
                            $NOMORBUKTI .= "00" .$counter;
                        } else if (strlen($counter) == 2){
                            $NOMORBUKTI .= "0" .$counter;
                        } else {
                            $NOMORBUKTI .= $counter;
                        }
                    } else {
                        $NOMORBUKTI .= "001";
                    }
                    $sql = "INSERT into tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,STATUS,KETERANGAN,USERID) values('".$NOMORBUKTI."','".date('Y-m-d',strtotime($TANGGALKEMBALI))."','".$USERID."','".$KODEPELANGGAN."','OPEN',:KETERANGAN,'".$USERID."')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['KETERANGAN'=>$KETERANGAN]);
                }
            }
        } else {

            $sql_jabatan = "";
            $judul = "";
            $isi = "";
            $jenis = "notification_support";
            $array['hasil'] = "belumkunjungan";
            /*$sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= '".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";*/
            $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
                'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
            ]);

            if($row = $stmt->fetch()){
                $counter = substr($row['NOMORBUKTI'], -3);
                $counter++;
                if(strlen($counter) == 1){
                    $NOMORBUKTI .= "00" .$counter;

                } else if (strlen($counter) == 2){
                    $NOMORBUKTI .= "0" .$counter;
                } else {
                    $NOMORBUKTI .= $counter;
                }
            } else {
                $NOMORBUKTI .= "001";
            }
            if($TANGGALKEMBALI == null && $LATLONGPELANGGAN == "belum"){

                $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
                $stmt = $pdo->prepare($sql);  
                $stmt->execute([
                    'KETERANGAN'=>$KETERANGAN,
                    'KESIMPULAN'=>$KESIMPULAN,
                    'LATITUDEEDIT'=>$LATITUDEEDIT,
                    'LONGITUDEEDIT'=>$LONGITUDEEDIT
                ]);
                $array['NOMORBUKTI'] = $NOMORBUKTI;

                if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                    if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                        $sql_jabatan = " KODEJABATAN = 'FINANCE'";
                    } else {
                        $sql_jabatan = " KODEJABATAN ='ADMIN'";
                    }

                    $judul = $PERMINTAANKHUSUS;
                    $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                    $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

                    $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    if($stmt->rowCount() > 0){
                        $id = 0;
                        while($pemakai = $stmt->fetch()){
                            $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                            $id++;
                        }
                    }

                    foreach ($array['KODEKARYAWAN'] as $key => $value) {
                        $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([
                            'pengirim'=>$USERID,
                            'penerima'=>$value,
                            'title'=>$judul,
                            'jenis'=>$jenis,
                            'isi'=>$isi
                        ]);
                    }
                }
                

                if(isset($NAMAFOTO)){
                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        /*$sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values('".$NOMORBUKTI."-".$NAMAFOTO[$i]."')";*/
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]]);
                    }
                }

            } else if($TANGGALKEMBALI == null && $LATLONGPELANGGAN =="ada"){

                $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
                $stmt = $pdo->prepare($sql);  
                $stmt->execute([
                    'KETERANGAN'=>$KETERANGAN,
                    'KESIMPULAN'=>$KESIMPULAN,
                    'LATITUDEEDIT'=>$LATITUDEEDIT,
                    'LONGITUDEEDIT'=>$LONGITUDEEDIT
                ]);
                $array['NOMORBUKTI'] = $NOMORBUKTI;
                if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                    if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                        $sql_jabatan = " KODEJABATAN = 'FINANCE'";
                    } else {
                        $sql_jabatan = " KODEJABATAN ='ADMIN'";
                    }

                    $judul = $PERMINTAANKHUSUS;
                    $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                    $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

                    $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    if($stmt->rowCount() > 0){
                        $id = 0;
                        while($pemakai = $stmt->fetch()){
                            $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                            $id++;
                        }
                    }

                    foreach ($array['KODEKARYAWAN'] as $key => $value) {
                        $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([
                            'pengirim'=>$USERID,
                            'penerima'=>$value,
                            'title'=>$judul,
                            'jenis'=>$jenis,
                            'isi'=>$isi
                        ]);
                    }
                }
                

                if(isset($NAMAFOTO)){
                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        /*$sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values('".$NOMORBUKTI."-".$NAMAFOTO[$i]."')";*/
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]]);
                    }
                }

            } else {
                $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN, TANGGALKEMBALI,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,'".date('Y-m-d',strtotime($TANGGALKEMBALI))."',:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
                $stmt = $pdo->prepare($sql);  
                $stmt->execute([
                    'KETERANGAN'=>$KETERANGAN,
                    'KESIMPULAN'=>$KESIMPULAN,
                    'LATITUDEEDIT'=>$LATITUDEEDIT,
                    'LONGITUDEEDIT'=>$LONGITUDEEDIT
                ]);
                $array['NOMORBUKTI'] = $NOMORBUKTI;

                if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                    if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                        $sql_jabatan = " KODEJABATAN = 'FINANCE'";
                    } else {
                        $sql_jabatan = " KODEJABATAN ='ADMIN'";
                    }

                    $judul = $PERMINTAANKHUSUS;
                    $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
                    $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

                    $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    if($stmt->rowCount() > 0){
                        $id = 0;
                        while($pemakai = $stmt->fetch()){
                            $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                            $id++;
                        }
                    }

                    foreach ($array['KODEKARYAWAN'] as $key => $value) {
                        $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([
                            'pengirim'=>$USERID,
                            'penerima'=>$value,
                            'title'=>$judul,
                            'jenis'=>$jenis,
                            'isi'=>$isi
                        ]);
                    }
                }


                if(isset($NAMAFOTO)){
                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]]);
                    }
                }

                $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODESALESMAN = :USERID";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                    'USERID'=>$USERID
                ]);

                $NOMORBUKTI = "RK" . date('ymd',strtotime($DATETRANSACTION));
                if($row = $stmt->fetch()){
                    $counter = substr($row['NOMORBUKTI'], -3);
                    $counter++;
                    if(strlen($counter) == 1){
                        $NOMORBUKTI .= "00" .$counter;
                    } else if (strlen($counter) == 2){
                        $NOMORBUKTI .= "0" .$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                } else {
                    $NOMORBUKTI .= "001";
                }
                $sql = "INSERT into tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,STATUS,KETERANGAN,USERID) values('".$NOMORBUKTI."','".date('Y-m-d',strtotime($TANGGALKEMBALI))."','".$USERID."','".$KODEPELANGGAN."','OPEN',:KETERANGAN,'".$USERID."')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KETERANGAN'=>$KETERANGAN]);

            }

            if(isset($array['KODEKARYAWAN'])){
                foreach ($array['KODEKARYAWAN'] as $key => $value) {
                    $sql = "SELECT * FROM pemakai WHERE USERID = :USERID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['USERID'=>$value]);
                    if($stmt->rowCount() > 0){
                        $pemakai = $stmt->fetch();
                        $id = 0;
                        if(isset($pemakai['GCM_ID']) && $pemakai['GCM_ID'] != ""){
                            $array['GCM_ID'][$id] = $pemakai['GCM_ID'];
                            $id++;
                        }
                    }
                }
            }

            if(isset($array['GCM_ID'])){
                $content = array(
                    "en" => $isiPushNotif
                );  

                $headings = array(
                    "en" =>$judul
                );

                $fields = array(
                    'app_id' => "ec4cf440-afa6-4896-8350-d4e493082179",
                    'include_player_ids' => $array['GCM_ID'],
                    'data' => array("foo" => "bar"),
                    'headings' => $headings,
                    'contents' => $content
                );
                $fields = json_encode($fields);

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
        }
    }

    echo json_encode($array);
    break;

    case "INPUT_KUNJUNGAN_LEBIH":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $LATITUDE = $_POST['latGlobal'];
    $LONGITUDE = $_POST['lngGlobal'];
    $USERID = $_POST['USERID'];
    $STATUSKUNJUNGAN = $_POST['STATUSKUNJUNGAN'];
    $PERMINTAANKHUSUS = $_POST['PERMINTAANKHUSUS'];
    //$LATITUDEEDIT = $_POST['LATITUDEEDIT'];
    //$LONGITUDEEDIT = $_POST['LONGITUDEEDIT'];
    $WAKTUMASUK = $_POST['waktuMasukLoginTokoKunjungan'];

    $sql_jabatan = "";
    $judul = "";
    $isi = "";
    $jenis = "notification_support";


    $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN' => $KODEPELANGGAN]);
    $pelanggan = $stmt->fetchAll(PDO::FETCH_CLASS);



    $sql = "SELECT mk.NAMA AS NAMAKATEGORIPELANGGAN FROM mpelanggan mp INNER JOIN mkategoripelanggan mk on(mk.KODEKATEGORIPELANGGAN = mp.KATEGORIPELANGGAN) WHERE KODEPELANGGAN=:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $kategoripelanggan = $stmt->fetch();
    $pelanggan[0]->NAMAKATEGORIPELANGGAN = $kategoripelanggan['NAMAKATEGORIPELANGGAN'];

    //$NAMAFOTO = $_POST['namaFotoKunjungan'];
    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['LATITUDEEDIT'])){
        $LATITUDEEDIT = $_POST['LATITUDEEDIT'];
    } else {
        $LATITUDEEDIT = NULL;
    }

    if(isset($_POST['LONGITUDEEDIT'])){
        $LONGITUDEEDIT = $_POST['LONGITUDEEDIT'];
    } else {
        $LONGITUDEEDIT = NULL;
    }

    if(isset($_POST['TANGGALKEMBALI'])){
        $TANGGALKEMBALI = $_POST['TANGGALKEMBALI'];
    } else {
        $TANGGALKEMBALI = NULL;
    }


    $array['NOMORBUKTI']= "";
     //masuk insert
    $NOMORBUKTI = "KS".date('ymd',strtotime($DATETRANSACTION));
    $array['hasil'] = "belumkunjungan";
    /*$sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= '".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
    $result = mysqli_query($conn,$sql);*/
    $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE 'KS%' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
        'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))
    ]);
    if($row = $stmt->fetch()){
        $counter = substr($row['NOMORBUKTI'], -3);
        $counter++;
        if(strlen($counter) == 1){
            $NOMORBUKTI .= "00" .$counter;

        } else if (strlen($counter) == 2){
            $NOMORBUKTI .= "0" .$counter;
        } else {
            $NOMORBUKTI .= $counter;
        }
    } else {
        $NOMORBUKTI .= "001";
    }

    if($TANGGALKEMBALI == null){
        $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."',:LATITUDEEDIT,:LONGITUDEEDIT)";
        $stmt = $pdo->prepare($sql);  
        $stmt->execute([
            'KETERANGAN'=>$KETERANGAN,
            'KESIMPULAN'=>$KESIMPULAN,
            'LATITUDEEDIT'=>$LATITUDEEDIT,
            'LONGITUDEEDIT'=>$LONGITUDEEDIT
        ]);
        $array['NOMORBUKTI'] = $NOMORBUKTI;
        if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
            if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                $sql_jabatan = " KODEJABATAN = 'FINANCE'";
            } else {
                $sql_jabatan = " KODEJABATAN ='ADMIN'";
            }

            $judul = $PERMINTAANKHUSUS;
            $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
            $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

            $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $id = 0;
                while($pemakai = $stmt->fetch()){
                    $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                    $id++;
                }
            }

            foreach ($array['KODEKARYAWAN'] as $key => $value) {
                $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([
                    'pengirim'=>$USERID,
                    'penerima'=>$value,
                    'title'=>$judul,
                    'jenis'=>$jenis,
                    'isi'=>$isi
                ]);
            }
        }
        

        if(isset($NAMAFOTO)){
            for($i = 0; $i<count($NAMAFOTO); $i++){
                $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                ]);
            }
        }

    } /*else if($TANGGALKEMBALI == null && $LATLONGPELANGGAN =="ada"){
        //echo "elseif";
        $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."','".$KETERANGAN."','".$KESIMPULAN."','OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."','".$LATITUDEEDIT."','".$LONGITUDEEDIT."')";
        $result = mysqli_query($conn,$sql);
    }*/ else {
        $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,LATITUDE,LONGITUDE,KETERANGAN, TANGGALKEMBALI,KESIMPULAN,STATUS,STATUSKUNJUNGAN,PERMINTAANKHUSUS,LATITUDEEDIT,LONGITUDEEDIT) values ('".$NOMORBUKTI."','".$WAKTUMASUK."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."','".$LATITUDE."','".$LONGITUDE."',:KETERANGAN,'".date('Y-m-d',strtotime($TANGGALKEMBALI))."',:KESIMPULAN,'OPEN','".$STATUSKUNJUNGAN."','".$PERMINTAANKHUSUS."','".$LATITUDEEDIT."','".$LONGITUDEEDIT."')";

        $stmt = $pdo->prepare($sql);  
        $stmt->execute([
            'KETERANGAN'=>$KETERANGAN,
            'KESIMPULAN'=>$KESIMPULAN,
            'LATITUDEEDIT'=>$LATITUDEEDIT,
            'LONGITUDEEDIT'=>$LONGITUDEEDIT
        ]);
        $array['NOMORBUKTI'] = $NOMORBUKTI;
        if($PERMINTAANKHUSUS == "SUPPORT HARGA" || $PERMINTAANKHUSUS == "SUPPORT STOK" || $PERMINTAANKHUSUS == "SUPPORT PROGRAM" || $PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
            if($PERMINTAANKHUSUS == "SUPPORT TAGIHAN"){
                $sql_jabatan = " KODEJABATAN = 'FINANCE'";
            } else {
                $sql_jabatan = " KODEJABATAN ='ADMIN'";
            }

            $judul = $PERMINTAANKHUSUS;
            $isi = "PELANGGAN <span style='font-weight:bold' onClick=".'"'."detailPelanggan('MASTER_KUNJUNGAN','".$pelanggan[0]->KODEPELANGGAN."','".$pelanggan[0]->NAMA."','".$pelanggan[0]->ALAMAT."','".$pelanggan[0]->KOTA."','".$pelanggan[0]->TELPON."','".$pelanggan[0]->FAX."','".$pelanggan[0]->KREDITLIMIT."','".$pelanggan[0]->SYARATPIUTANG."','".$pelanggan[0]->SYARATPIUTANGMAX."','".$pelanggan[0]->USERID."','".$pelanggan[0]->TERM."','".$pelanggan[0]->NAMAKATEGORIPELANGGAN."','".$pelanggan[0]->ALAMATKIRIM."','".$NOMORBUKTI."')".'"'."> ". $pelanggan[0]->NAMA. " </span>MEMBUTUHKAN ". $PERMINTAANKHUSUS;
            $isiPushNotif = "PELANGGAN ". $pelanggan[0]->NAMA. " MEMBUTUHKAN ". $PERMINTAANKHUSUS;

            $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'OWNER' OR " . $sql_jabatan;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $id = 0;
                while($pemakai = $stmt->fetch()){
                    $array['KODEKARYAWAN'][$id] = $pemakai['USERID'];
                    $id++;
                }
            }

            foreach ($array['KODEKARYAWAN'] as $key => $value) {
                $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([
                    'pengirim'=>$USERID,
                    'penerima'=>$value,
                    'title'=>$judul,
                    'jenis'=>$jenis,
                    'isi'=>$isi
                ]);
            }
        }
        

        if(isset($NAMAFOTO)){

            for($i = 0; $i<count($NAMAFOTO); $i++){
                $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                ]);
            }
        }
        
        /*$sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION ='".date('Y-m-d',strtotime($DATETRANSACTION))."' AND KODESALESMAN = '".$USERID."'";
        $result = mysqli_query($conn,$sql);*/
        $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODESALESMAN = :USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
            'USERID'=>$USERID
        ]);

        $NOMORBUKTI = "RK" . date('ymd',strtotime($DATETRANSACTION));
        if($row = $stmt->fetch()){
            $counter = substr($row['NOMORBUKTI'], -3);
            $counter++;
            if(strlen($counter) == 1){
                $NOMORBUKTI .= "00" .$counter;
            } else if (strlen($counter) == 2){
                $NOMORBUKTI .= "0" .$counter;
            } else {
                $NOMORBUKTI .= $counter;
            }
        } else {
            $NOMORBUKTI .= "001";
        }
        $sql = "INSERT into tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,STATUS,KETERANGAN,USERID) values('".$NOMORBUKTI."','".date('Y-m-d',strtotime($TANGGALKEMBALI))."','".$USERID."','".$KODEPELANGGAN."','OPEN',:KETERANGAN,'".$USERID."')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['KETERANGAN'=>$KETERANGAN]);

    }

    if(isset($array['KODEKARYAWAN'])){
        foreach ($array['KODEKARYAWAN'] as $key => $value) {
            $sql = "SELECT * FROM pemakai WHERE USERID = :USERID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['USERID'=>$value]);
            if($stmt->rowCount() > 0){
                $id = 0;
                $pemakai = $stmt->fetch();
                if(isset($pemakai['GCM_ID']) && $pemakai['GCM_ID'] != ""){
                    $array['GCM_ID'][$id] = $pemakai['GCM_ID'];
                    $id++;
                }
            }
        } 
    }

    if(isset($array['GCM_ID'])){
        $content = array(
            "en" => $isiPushNotif
        );  

        $headings = array(
            "en" =>$judul
        );

        $fields = array(
            'app_id' => "ec4cf440-afa6-4896-8350-d4e493082179",
            'include_player_ids' => $array['GCM_ID'],
            'data' => array("foo" => "bar"),
            'headings' => $headings,
            'contents' => $content
        );
        $fields = json_encode($fields);

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

    echo json_encode($array);
    break;

    case "GET_LATITUDE_LONGITUDE_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    /*$sql = "SELECT * FROM mpelanggan WHERE !ISNULL(LATITUDE) AND !ISNULL(LONGITUDE) AND KODEPELANGGAN='".$KODEPELANGGAN."'";
    $result = mysqli_query($conn,$sql);*/
    $sql = "SELECT * FROM mpelanggan WHERE !ISNULL(LATITUDE) AND !ISNULL(LONGITUDE) AND KODEPELANGGAN=:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODEPELANGGAN'=>$KODEPELANGGAN
    ]);
    $array = array();
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['LATITUDE'] = $row['LATITUDE'];
        $array['LONGITUDE'] = $row['LONGITUDE'];
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT * from mjarak";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch();
    $array['jarak'] = $row['jarak'];

    echo json_encode($array);
    break;


    case "TAMPIL_POSTING_HAPUS":
    $tampungHalamanFollowDanCollector = $_POST['tampungHalamanFollowDanCollector'];
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $halamanPostingHapus = $_POST['halamanPostingHapus'];
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];

    if(isset($_POST['parameter_rencana'])){
        $parameter_rencana = $_POST['parameter_rencana'];
    } else { $parameter_rencana = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['STAFFDARI'])){
        $STAFFDARI = $_POST['STAFFDARI'];
    } else { $STAFFDARI = null; }

    if(isset($_POST['STAFFSAMPAI'])){
        $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
    } else { $STAFFSAMPAI = null; }
    

    $sql = "";

    /*if($halamanPostingHapus == "tmrencanakunjungan"){
        $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.STATUS ='OPEN' AND k.KODESALESMAN ='".$USERID."' ";
    } else {
        if($tampungHalamanFollowDanCollector == ""){
            $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN ='".$USERID."' AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'KS%' ";
        } else if( $tampungHalamanFollowDanCollector == "FOLLOWUP"){
            $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN ='".$USERID."' AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'FA%' ";
        } else if( $tampungHalamanFollowDanCollector == "COLLECTOR"){
            $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN ='".$USERID."' AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'FC%' ";
        } else if ($tampungHalamanFollowDanCollector == "KIRIMAN"){
            $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN ='".$USERID."' AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'PF%'";
        } else if ($tampungHalamanFollowDanCollector == "LAPORAN"){
            $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.STATUS ='OPEN' ";
        }
    }*/

    if($halamanPostingHapus == "tmrencanakunjungan"){
        $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.STATUS ='OPEN' AND p.KODECABANG = '".$KODECABANG."' AND ";
        if($parameter_rencana == "ada"){
            if($STAFFDARI){
                $sql.= " k.KODESALESMAN >='".$STAFFDARI."' AND ";
            }

            if($STAFFSAMPAI){
                $sql.= " k.KODESALESMAN <='".$STAFFSAMPAI."' AND ";
            }


            
        } else {
            $sql.= " k.KODESALESMAN ='".$USERID."'";
        }

        $tampungSql = substr($sql,-5);
        if($tampungSql == " AND " || $tampungSql == "WHERE"){
            $sql=substr($sql,0,-5);
        }
    } 

    if($tampungHalamanFollowDanCollector == ""){
        $sql = "SELECT distinct p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN)";
    } else if( $tampungHalamanFollowDanCollector == "FOLLOWUP"){
        $sql = "SELECT distinct  p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN)";
    } else if( $tampungHalamanFollowDanCollector == "COLLECTOR"){
        $sql = "SELECT distinct  p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN)";
    } else if ($tampungHalamanFollowDanCollector == "KIRIMAN"){
        $sql = "SELECT distinct  p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN)";
    } else if ($tampungHalamanFollowDanCollector == "LAPORAN"){
        $sql = "SELECT distinct  p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM ".$halamanPostingHapus." k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN)";
    }

    if($KODEJABATAN == "OWNER" && $halamanPostingHapus !="tmrencanakunjungan"){
        $sql.="WHERE ";
    } else if($halamanPostingHapus == "tmrencanakunjungan"){
        $sql.= "AND";
    } else {
        $sql.=" left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE (dp1.CP1 ='".$USERID."' OR k.KODESALESMAN = '".$USERID."') AND ";
    }

    if($tampungHalamanFollowDanCollector == ""){
        $sql .="  DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'  AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'KS%' ";
    } else if( $tampungHalamanFollowDanCollector == "FOLLOWUP"){
        $sql .= " DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'FA%' ";
    } else if( $tampungHalamanFollowDanCollector == "COLLECTOR"){
        $sql .= " DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'  AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'FC%' ";
    } else if ($tampungHalamanFollowDanCollector == "KIRIMAN"){
        $sql .= " DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'PF%'";
    } else if ($tampungHalamanFollowDanCollector == "LAPORAN"){
        $sql .= " DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'AND k.STATUS ='OPEN' ";
    } else if($tampungHalamanFollowDanCollector == "RENCANA_KUNJUNGAN"){
        $sql .="  DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'  AND k.STATUS ='OPEN' AND NOMORBUKTI LIKE 'RK%'";
    }

    $sql.= " ORDER BY NOMORBUKTI";
    //echo $sql;

    /*
    $sql = "SELECT k.KODESALESMAN as KODESALESMAN,k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE '".$LIKE."' AND k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND (dp1.CP1 ='".$USERID."' OR k.KODESALESMAN = '".$USERID."') GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN ASC, k.NOMORBUKTI DESC";*/
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "POSTING_TERPILIH":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];
    $halamanPostingHapus = $_POST['halamanPostingHapus'];

    foreach ($postingHapusValue as $key => $value) {
        $sql = "UPDATE ".$halamanPostingHapus." set STATUS ='CLOSE',USERID =:USERID WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID,
            'NOMORBUKTI'=>$value
        ]);
    }
    break;

    case "HAPUS_TERPILIH":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];
    $halamanPostingHapus = $_POST['halamanPostingHapus'];

    $id = 0;
    $arrayFotoDihapus = array();
    foreach ($postingHapusValue as $key => $value) {
        $sql = "DELETE FROM " .$halamanPostingHapus. " WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORBUKTI'=>$value]);
    }

    $databasegambar = "";
    if($halamanPostingHapus == "tmkunjungan"){
        $databasegambar = "gambarkunjungan";
    }

    foreach ($postingHapusValue as $key => $value) {
        $sql = "SELECT * FROM ".$databasegambar." WHERE NAMAGAMBAR LIKE :NAMAGAMBAR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NAMAGAMBAR'=>$value."%"]);
        if($unique = $stmt->fetch()){
            $arrayFotoDihapus[$id] = $unique['NAMAGAMBAR'];
            $id++;
            while($unique = $stmt->fetch()){
                $arrayFotoDihapus[$id] = $unique['NAMAGAMBAR'];
                $id++;
            }
        } else {

        }
        $sql = "DELETE FROM ".$databasegambar." WHERE NAMAGAMBAR LIKE :NAMAGAMBAR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NAMAGAMBAR'=>$value."%"]);
    }

    if(isset($arrayFotoDihapus)){
        foreach($arrayFotoDihapus as $key => $value){
            if(file_exists("gambar_kunjungan/".$value.".jpg")){
                unlink("gambar_kunjungan/".$value.".jpg");
            }
        }
        
    }

    break;


    case "TAMPIL_SELURUH_DETAIL_PERUSAHAAN_PENJUALAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $sql = "SELECT * FROM dpelanggan2 WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['dpelanggan2'] = "ada";
        $array['CP2'][$id] = $row['CP1'];
        $array['KJ2'][$id] = $row['KJ1'];
        $array['HP2'][$id] = $row['HP1'];
        $array['WA2'][$id] = $row['WA1'];
        $array['EMAIL2'][$id] = $row['EMAIL1'];
        $array['TANGGALLAHIR2'][$id]=$row['TANGGALLAHIR'];
        $array['KETERANGAN2'][$id]=$row['KETERANGAN'];
        $id++;
        $array['COUNTERPERUSAHAAN'] = $id;
        while($row = $stmt->fetch()){
            $array['CP2'][$id] = $row['CP1'];
            $array['KJ2'][$id] = $row['KJ1'];
            $array['HP2'][$id] = $row['HP1'];
            $array['WA2'][$id] = $row['WA1'];
            $array['EMAIL2'][$id] = $row['EMAIL1'];
            $array['TANGGALLAHIR2'][$id]=$row['TANGGALLAHIR'];
            $array['KETERANGAN2'][$id]=$row['KETERANGAN'];
            $id++;
            $array['COUNTERPERUSAHAAN'] = $id;
        }
    } else {
        $array['dpelanggan2'] = "tidakada";
    }


    $sql = "SELECT mk.KODEKARYAWAN as CP1, mk.KODEJABATAN as KJ1, mk.NAMA as NAMAKARYAWAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as HP1, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR, dp1.KETERANGAN as KETERANGAN FROM dpelanggan1 dp1 INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = dp1.CP1) INNER JOIN mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $id = 0;
    if($row = $stmt->fetch()){
        $array['dpelanggan1'] = "ada";
        $array['NAMAKARYAWAN'][$id] = $row['NAMAKARYAWAN'];
        $array['NAMAJABATAN'][$id] = $row['NAMAJABATAN'];
        $array['CP1'][$id] = $row['CP1'];
        $array['KJ1'][$id] = $row['KJ1'];
        $array['HP1'][$id] = $row['HP1'];
        $array['WA1'][$id] = $row['WA1'];
        $array['EMAIL1'][$id] = $row['EMAIL1'];
        $array['TANGGALLAHIR1'][$id]=$row['TANGGALLAHIR'];
        $array['KETERANGAN1'][$id]=$row['KETERANGAN'];
        $id++;
        $array['COUNTERINTERNAL'] = $id;
        while($row = $stmt->fetch()){
            $array['NAMAKARYAWAN'][$id] = $row['NAMAKARYAWAN'];
            $array['NAMAJABATAN'][$id] = $row['NAMAJABATAN'];
            $array['CP1'][$id] = $row['CP1'];
            $array['KJ1'][$id] = $row['KJ1'];
            $array['HP1'][$id] = $row['HP1'];
            $array['WA1'][$id] = $row['WA1'];
            $array['EMAIL1'][$id] = $row['EMAIL1'];
            $array['TANGGALLAHIR1'][$id]=$row['TANGGALLAHIR'];
            $array['KETERANGAN1'][$id]=$row['KETERANGAN'];
            $id++;
            $array['COUNTERINTERNAL'] = $id;
        }
    } else {
        $array['dpelanggan1'] = "tidakada";
    }

    $sql = "SELECT * FROM gambarpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN AND JENIS='TOKO'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $id = 0;
    if($row = $stmt->fetch()){
        $array['gambartoko'] = "ada";
        $array['KODEGAMBAR'][$id] = $row['KODEGAMBAR'];
        $id++;
        while($row = $stmt->fetch()){
            $array['KODEGAMBAR'][$id] = $row['KODEGAMBAR'];
            $id++;
        }
        //tampung counter untuk foto berikutnya disimpan indexnya
        $tampungCounterFotoToko = $array['KODEGAMBAR'][$id-1];
        $lengthFotoToko = strpos($tampungCounterFotoToko,"_");
        $lengthB = strripos($tampungCounterFotoToko, "_");
        
        $array['TAMPUNGCOUNTERFOTOTOKO'] = substr($tampungCounterFotoToko,strlen($KODEPELANGGAN)+1, $lengthB-($lengthFotoToko+1)) +1;
    } else {

        $array['gambartoko'] = "tidakada";
    }

    $sql = "SELECT * FROM gambarpelanggan WHERE KODEPELANGGAN =:KODEPELANGGAN AND JENIS = 'KTP'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $id = 0;
    if($row = $stmt->fetch()){
        $array['gambarktp'] = "ada";
        $array['KODEGAMBARKTP'] = $row['KODEGAMBAR'];
    } else {
        $array['gambarktp'] = "tidakada";
    }

    $sql = "SELECT * FROM gambarpelanggan WHERE KODEPELANGGAN =:KODEPELANGGAN AND JENIS = 'NPWP'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $id = 0;
    if($row = $stmt->fetch()){
        $array['gambarnpwp'] = "ada";
        $array['KODEGAMBARNPWP'] = $row['KODEGAMBAR'];
    } else {
        $array['gambarnpwp'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_POPUP_MENU":
    $tampungCounterScreenMenu = $_POST['tampungCounterScreenMenu'];
    $array = array();
    $id = 0;
    if($tampungCounterScreenMenu==0){
        $sql = "SELECT * FROM menu WHERE SCREEN <10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        if($row = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['MENU'][$id] = $row['MENU'];
            $array['SCREEN'][$id] = $row['SCREEN'];
            $id++;
            while($row = $stmt->fetch()){    
                $array['MENU'][$id] = $row['MENU'];
                $array['SCREEN'][$id] = $row['SCREEN'];
                $id++;
            }
        }
    } else {
        $sql = "SELECT * FROM menu WHERE SCREEN LIKE '".$tampungCounterScreenMenu."_' AND SCREEN !='".$tampungCounterScreenMenu."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($row = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['MENU'][$id] = $row['MENU'];
            $array['SCREEN'][$id] = $row['SCREEN'];
            $id++;
            while($row = $stmt->fetch()){    
                $array['MENU'][$id] = $row['MENU'];
                $array['SCREEN'][$id] = $row['SCREEN'];
                $id++;
            }
        } else {
            $array['hasil'] = "habis";
        }
    }
    echo json_encode($array);
    break;

    case "SIMPAN_EDIT_HAK_AKSES":
    $SCREEN = $_POST['SCREEN_USERMENU'];
    $USERID = $_POST['USERID'];
    $TAMBAH = $_POST['TAMBAH_USERMENU'];
    $UBAH = $_POST['UBAH_USERMENU'];
    $HAPUS = $_POST['HAPUS_USERMENU'];
    $CETAK = $_POST['CETAK_USERMENU'];
    $POSTING = $_POST['POSTING_USERMENU'];
    $CARI = $_POST['CARI_USERMENU'];
    $halaman_hak_akses = $_POST['halaman_hak_akses'];

    if($halaman_hak_akses == "EDIT_USER_HAK_AKSES"){
        $sql ="DELETE FROM usermenu WHERE USERID =:USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID]);

        for($i = 0; $i<count($SCREEN); $i++){
            $sql = "INSERT INTO usermenu (SCREEN,USERID,TAMBAH,UBAH,HAPUS,CETAK,POSTING,CARI) values(:SCREEN,:USERID,:TAMBAH,:UBAH,:HAPUS,:CETAK,:POSTING,:CARI)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'SCREEN'=>$SCREEN[$i],
                'USERID'=>$USERID,
                'TAMBAH'=>$TAMBAH[$i],
                'UBAH'=>$UBAH[$i],
                'HAPUS'=>$HAPUS[$i],
                'CETAK'=>$CETAK[$i],
                'POSTING'=>$POSTING[$i],
                'CARI'=>$CARI[$i]
            ]);
        }
    } else {
        $PASSWORD = $_POST['PASSWORD'];
        $KODECABANG = $_POST['KODECABANG'];
        $KODEJABATAN = $_POST['KODEJABATAN'];

        $sql ="INSERT INTO pemakai (USERID,PASSWORD,KODEJABATAN,KODECABANG) values (:USERID,:PASSWORD,:KODEJABATAN,:KODECABANG)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID,
            'PASSWORD'=>$PASSWORD,
            'KODEJABATAN'=>$KODEJABATAN,
            'KODECABANG'=>$KODECABANG
        ]);

        for($i = 0; $i<count($SCREEN); $i++){
            $sql = "INSERT INTO usermenu (SCREEN,USERID,TAMBAH,UBAH,HAPUS,CETAK,POSTING,CARI) values(:SCREEN,:USERID,:TAMBAH,:UBAH,:HAPUS,:CETAK,:POSTING,:CARI)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'SCREEN'=>$SCREEN[$i],
                'USERID'=>$USERID,
                'TAMBAH'=>$TAMBAH[$i],
                'UBAH'=>$UBAH[$i],
                'HAPUS'=>$HAPUS[$i],
                'CETAK'=>$CETAK[$i],
                'POSTING'=>$POSTING[$i],
                'CARI'=>$CARI[$i]
            ]);
        }
    }
    

    break;

    case "TAMPIL_EDIT_USERMENU":
    $USERID = $_POST['USERID'];
    $sql = "SELECT m.SCREEN as SCREEN, m.MENU as MENU, u.TAMBAH as TAMBAH, u.UBAH as UBAH, u.HAPUS as HAPUS, u.CETAK as CETAK, u.POSTING as POSTING, u.CARI as CARI FROM menu m inner join usermenu u on(u.SCREEN = m.SCREEN) WHERE u.USERID = :USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $id = 0;
    $array = array();
    if($row = $stmt->fetch()){
        $array['HASIL_USERMENU'] = "ada";
        $array['SCREEN'][$id] = $row['SCREEN'];
        $array['MENU'][$id] = $row['MENU'];
        $array['TAMBAH'][$id] = $row['TAMBAH'];
        $array['UBAH'][$id] = $row['UBAH'];
        $array['HAPUS'][$id] = $row['HAPUS'];
        $array['CETAK'][$id] = $row['CETAK'];
        $array['POSTING'][$id] = $row['POSTING'];
        $array['CARI'][$id] = $row['CARI'];
        $id++;
        while($row = $stmt->fetch()){
            $array['SCREEN'][$id] = $row['SCREEN'];
            $array['MENU'][$id] = $row['MENU'];
            $array['TAMBAH'][$id] = $row['TAMBAH'];
            $array['UBAH'][$id] = $row['UBAH'];
            $array['HAPUS'][$id] = $row['HAPUS'];
            $array['CETAK'][$id] = $row['CETAK'];
            $array['POSTING'][$id] = $row['POSTING'];
            $array['CARI'][$id] = $row['CARI'];
            $id++;
        }
    } else {
        $array['HASIL_USERMENU'] = "tidakada";
    }
    echo json_encode($array);
    break;



    case "TAMPIL_POPUP_KARYAWAN":
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT mk.KODEKARYAWAN as KODEKARYAWAN, mk.NAMA as NAMA FROM mkaryawan mk LEFT JOIN pemakai p on(p.USERID = mk.KODEKARYAWAN) WHERE mk.KODECABANG = :KODECABANG AND isnull(p.USERID) AND STATUS ='CLOSE'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "EDIT_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $NAMA = $_POST['NAMA'];
    $ALAMAT = $_POST['ALAMAT'];
    $KOTA = $_POST['KOTA'];
    $TELPON = $_POST['TELPON'];
    $FAX = $_POST['FAX'];
    $KREDITLIMIT = $_POST['KREDITLIMIT'];
    $SYARATPIUTANG = $_POST['SYARATPIUTANG'];
    $SYARATPIUTANGMAX = $_POST['SYARATPIUTANGMAX'];
    $TERM = $_POST['TERM'];
    $ALAMATKIRIM = $_POST['ALAMATKIRIM'];
    $KATEGORIPELANGGAN = $_POST['KATEGORIPELANGGAN'];
    $USERID = $_POST['USERID'];
    $KODEAREA = $_POST['KODEAREA'];
    $NAMAFAKTUR = $_POST['NAMAFAKTUR'];
    $ALAMATFAKTUR = $_POST['ALAMATFAKTUR'];
    $KODECABANG = $_POST['KODECABANG'];


    $sql = "";
    if(isset($_POST['NPWP'])){
        $NPWP = $_POST['NPWP'];
        $sql .= "SELECT * FROM mpelanggan WHERE NPWP!= '' AND NPWP ='".$NPWP."' AND KODEPELANGGAN !='".$KODEPELANGGAN."'";
    } else { $NPWP = null; }

    if(isset($_POST['KTP'])){
        $KTP = $_POST['KTP'];
    } else { $KTP = null; }


    if(isset($_POST['CP1'])){
        $CP1 = $_POST['CP1'];
    } else { $CP1 = null; }

    if(isset($_POST['KJ1'])){
        $KJ1 = $_POST['KJ1'];
    } else { $KJ1 = null; }

    if(isset($_POST['HP1'])){
        $HP1 = $_POST['HP1'];
    } else { $HP1 = null; }

    if(isset($_POST['WA1'])){
        $WA1 = $_POST['WA1'];
    } else { $WA1 = null; }

    if(isset($_POST['EMAIL1'])){
        $EMAIL1 = $_POST['EMAIL1'];
    } else { $EMAIL1 = null; }

    if(isset($_POST['TANGGALLAHIR1'])){
        $TANGGALLAHIR1 = $_POST['TANGGALLAHIR1'];
    } else { $TANGGALLAHIR1 = null; }

    if(isset($_POST['KETERANGAN1'])){
        $KETERANGAN1 = $_POST['KETERANGAN1'];
    } else { $KETERANGAN1 = null; }



    if(isset($_POST['CP2'])){
        $CP2 = $_POST['CP2'];
    } else { $CP2 = null; }

    if(isset($_POST['KJ2'])){
        $KJ2 = $_POST['KJ2'];
    } else { $KJ2 = null; }

    if(isset($_POST['HP2'])){
        $HP2 = $_POST['HP2'];
    } else { $HP2 = null; }

    if(isset($_POST['WA2'])){
        $WA2 = $_POST['WA2'];
    } else { $WA2 = null; }

    if(isset($_POST['EMAIL2'])){
        $EMAIL2 = $_POST['EMAIL2'];
    } else { $EMAIL2 = null; }

    if(isset($_POST['TANGGALLAHIR2'])){
        $TANGGALLAHIR2 = $_POST['TANGGALLAHIR2'];
    } else { $TANGGALLAHIR2 = null; }

    if(isset($_POST['KETERANGAN2'])){
        $KETERANGAN2 = $_POST['KETERANGAN2'];
    } else { $KETERANGAN2 = null; }

    if(isset($_POST['namaFotoToko'])){
        $namaFotoToko = $_POST['namaFotoToko'];
    } else { $namaFotoToko = null; }

    if(isset($_POST['hapusFotoToko'])){
        $hapusFotoToko = $_POST['hapusFotoToko'];
    } else { $hapusFotoToko = null; }

    if(isset($_POST['GAMBARNPWP'])){
        $GAMBARNPWP = $_POST['GAMBARNPWP'];
    } else { $GAMBARNPWP = null; }

    if(isset($_POST['GAMBARKTP'])){
        $GAMBARKTP = $_POST['GAMBARKTP'];
    } else { $GAMBARKTP = null; }


    $array = array();

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($row = $stmt->fetch()){
        $array['hasil'] = "npwpterdaftar";
    } else {
        $array['hasil'] = "tidakada";
        $sql = "UPDATE mpelanggan set KODEPELANGGAN=:KODEPELANGGAN, KODECABANG=:KODECABANG, NAMA=:NAMA, ALAMAT=:ALAMAT, KOTA=:KOTA, TELPON=:TELPON, FAX=:FAX, KREDITLIMIT=:KREDITLIMIT, SYARATPIUTANG=:SYARATPIUTANG, SYARATPIUTANGMAX=:SYARATPIUTANGMAX, TERM=:TERM, ALAMATKIRIM=:ALAMATKIRIM,  KATEGORIPELANGGAN=:KATEGORIPELANGGAN, USERID=:USERID, STATUS=:STATUS, NPWP=:NPWP, KTP=:KTP, KODEAREA=:KODEAREA, NAMAFAKTUR=:NAMAFAKTUR, ALAMATFAKTUR=:ALAMATFAKTUR WHERE KODEPELANGGAN =:KODEPELANGGANWHERE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODEPELANGGAN'=>$KODEPELANGGAN,
            'KODECABANG'=>$KODECABANG,
            'NAMA'=>$NAMA,
            'ALAMAT'=>$ALAMAT,
            'KOTA'=>$KOTA,
            'TELPON'=>$TELPON,
            'FAX'=>$FAX,
            'KREDITLIMIT'=>$KREDITLIMIT,
            'SYARATPIUTANG'=>$SYARATPIUTANG,
            'SYARATPIUTANGMAX'=>$SYARATPIUTANGMAX,
            'TERM'=>$TERM,
            'ALAMATKIRIM'=>$ALAMATKIRIM,
            'KATEGORIPELANGGAN'=>$KATEGORIPELANGGAN,
            'USERID'=>$USERID,
            'STATUS'=>'OPEN',
            'NPWP'=>$NPWP,
            'KTP'=>$KTP,
            'KODEAREA'=>$KODEAREA,
            'NAMAFAKTUR'=>$NAMAFAKTUR,
            'ALAMATFAKTUR'=>$ALAMATFAKTUR,
            'KODEPELANGGANWHERE'=>$KODEPELANGGAN,
        ]);

        $sql = "DELETE FROM dpelanggan1 WHERE KODEPELANGGAN =:KODEPELANGGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
        $sql = "DELETE FROM dpelanggan2 WHERE KODEPELANGGAN =:KODEPELANGGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);

        if(isset($CP1)){
            foreach ($CP1 as $key => $value) {
                $TANGGALLAHIR[$key];
                if($TANGGALLAHIR1[$key] && $TANGGALLAHIR1[$key] != "0000-00-00"){
                    $TANGGALLAHIR =date('Y-m-d',strtotime($TANGGALLAHIR1[$key]));
                } else {
                    $TANGGALLAHIR = NULL;
                }
                $sql = "INSERT INTO dpelanggan1 (KODEPELANGGAN,CP1,KJ1,HP1,WA1,EMAIL1,TANGGALLAHIR,KETERANGAN) values 
                (:KODEPELANGGAN,:CP1,:KJ1,:HP1,:WA1,:EMAIL1,:TANGGALLAHIR,:KETERANGAN1)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODEPELANGGAN'=>$KODEPELANGGAN,
                    'CP1'=>$CP1[$key],
                    'KJ1'=>$KJ1[$key],
                    'HP1'=>$HP1[$key],
                    'WA1'=>$WA1[$key],
                    'EMAIL1'=>$EMAIL1[$key],
                    'TANGGALLAHIR'=>$TANGGALLAHIR,
                    'KETERANGAN1'=>$KETERANGAN1[$key]
                ]);
            }
        }

        if(isset($namaFotoToko)){
            foreach ($namaFotoToko as $key => $value) {
                $KODEGAMBAR = $KODEPELANGGAN."_".$value."_toko";
                $sql = "INSERT INTO gambarpelanggan (KODEGAMBAR, KODEPELANGGAN,JENIS) values (:KODEGAMBAR,:KODEPELANGGAN,'TOKO')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODEGAMBAR'=>$KODEGAMBAR,
                    'KODEPELANGGAN'=>$KODEPELANGGAN,
                ]);
            }
        }

        if($GAMBARKTP != null){
            $KODEGAMBAR = $KODEPELANGGAN."_ktp";
            $sql = "INSERT INTO gambarpelanggan (KODEGAMBAR, KODEPELANGGAN,JENIS) values (:KODEGAMBAR,:KODEPELANGGAN,'KTP')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEGAMBAR'=>$KODEGAMBAR,
                'KODEPELANGGAN'=>$KODEPELANGGAN
            ]);
        }

        if($GAMBARNPWP != null){
            $KODEGAMBAR = $KODEPELANGGAN."_npwp";
            $sql = "INSERT INTO gambarpelanggan (KODEGAMBAR, KODEPELANGGAN,JENIS) values ('".$KODEGAMBAR."','".$KODEPELANGGAN."','NPWP')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEGAMBAR'=>$KODEGAMBAR,
                'KODEPELANGGAN'=>$KODEPELANGGAN
            ]);
        }

        if(isset($CP2)){
            foreach ($CP2 as $key => $value) {
                $TANGGALLAHIR;
                if($TANGGALLAHIR2[$key]){
                    $TANGGALLAHIR =date('Y-m-d',strtotime($TANGGALLAHIR2[$key]));

                } else {
                    $TANGGALLAHIR = NULL;
                }
                $sql = "INSERT INTO dpelanggan2 (KODEPELANGGAN,CP1,KJ1,HP1,WA1,EMAIL1,TANGGALLAHIR,KETERANGAN) 
                values(:KODEPELANGGAN,:CP2,:KJ2,:HP2,:WA2,:EMAIL2,:TANGGALLAHIR,:KETERANGAN2)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODEPELANGGAN'=>$KODEPELANGGAN,
                    'CP2'=>$CP2[$key],
                    'KJ2'=>$KJ2[$key],
                    'HP2'=>$HP2[$key],
                    'WA2'=>$WA2[$key],
                    'EMAIL2'=>$EMAIL2[$key],
                    'TANGGALLAHIR'=>$TANGGALLAHIR,
                    'KETERANGAN2'=>$KETERANGAN2[$key]
                ]);
            }
        }

        if (isset($hapusFotoToko)){
            foreach ($hapusFotoToko as $key => $value) {
                $sql = "DELETE from gambarpelanggan WHERE KODEGAMBAR =:KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEGAMBAR'=>$value]);
                if(file_exists("gambar_pelanggan/toko/".$value.".jpg")){

                    unlink("gambar_pelanggan/toko/".$value.".jpg");
                }
            }
        }
    }

    echo json_encode($array);
    break;

    //master_kunjungan
    case "TAMPIL_MASTER_KUNJUNGAN":
    $USERID = $_POST['USERID'];
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT DISTINCT DATETRANSACTION FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE KODESALESMAN = :USERID AND mp.KODECABANG =:KODECABANG";

    $counter = 0;
    $array['hasil'] ="";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID'=>$USERID,
        'KODECABANG'=>$KODECABANG
    ]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    }

    /* $sql ="SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE NOMORBUKTI LIKE 'KS%' AND KODESALESMAN = '".$USERID."'";*/
    if($KODEJABATAN == "OWNER"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE NOMORBUKTI LIKE 'KS%' AND mp.KODECABANG =:KODECABANG";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k INNER JOIN dpelanggan1 dp1 on(dp1.CP1 = k.KODESALESMAN) INNER JOIN pemakai p on(dp1.CP1 = p.USERID) where NOMORBUKTI LIKE 'KS%' AND p.KODECABANG = :KODECABANG ";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    }

    if($array['hasil'] == "ada"){


        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();

        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        foreach ($arrayTampung as $key => $value) {
            $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan WHERE DATE(DATETRANSACTION) = :DATETRANSACTION AND KODESALESMAN =:USERID AND NOMORBUKTI LIKE 'KS%' GROUP BY DATE(DATETRANSACTION)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'DATETRANSACTION'=>$value,
                'USERID'=>$USERID
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHKUNJUNGAN'][$key] = $row['JUMLAHKUNJUNGAN'];
            } else {
                $array['JUMLAHKUNJUNGAN'][$key] = 0;
            }
        }

        foreach ($arrayTampung as $key => $value) {
            $sql = "SELECT count(*) as JUMLAHRENCANAKUNJUNGAN FROM tmrencanakunjungan WHERE DATETRANSACTION = :DATETRANSACTION AND KODESALESMAN =:USERID GROUP BY DATE(DATETRANSACTION)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'DATETRANSACTION'=>$value,
                'USERID'=>$USERID
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHRENCANAKUNJUNGAN'][$key] = $row['JUMLAHRENCANAKUNJUNGAN'];
            } else {
                $array['JUMLAHRENCANAKUNJUNGAN'][$key] = 0;
            }
        }

        if($counter == 1){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
        }
    }

    echo json_encode($array);
    break;


    case "POSTING_KARYAWAN":
    $KODEKARYAWAN = $_POST['KODEKARYAWAN'];
    $USERID = $_POST['USERID'];
    $sql = "UPDATE mkaryawan SET STATUS ='CLOSE', USERID = :USERID WHERE KODEKARYAWAN = :KODEKARYAWAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID'=>$USERID,
        'KODEKARYAWAN'=>$KODEKARYAWAN
    ]);
    break;

    case "HAPUS_KARYAWAN":
    $KODEKARYAWAN = $_POST['KODEKARYAWAN'];

    $sql = "DELETE from  mkaryawan WHERE KODEKARYAWAN = :KODEKARYAWAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKARYAWAN'=>$KODEKARYAWAN]);
    break;

    case "TAMPIL_PERMINTAAN_KHUSUS":
    $sql = "SELECT * FROM mpermintaankhusus";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    while ($row = $stmt->fetch()) {
        $array['KODEPERMINTAANKHUSUS'][$id] = $row['KODEPERMINTAANKHUSUS'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
    }

    echo json_encode($array);
    break;


    case "TAMPIL_MASTER_FOLLOWUP":
    $USERID = $_POST['USERID'];
    $array = array();
    $id = 0;
    $array['hasil'] = "";
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT DISTINCT DATETRANSACTION FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE KODESALESMAN = :USERID AND mp.KODECABANG =:KODECABANG";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID'=>$USERID,
        'KODECABANG'=>$KODECABANG
    ]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    }


    /*$sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE KODESALESMAN ='".$USERID."' AND NOMORBUKTI LIKE 'FA%'";*/
    if($KODEJABATAN == "OWNER"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE NOMORBUKTI LIKE 'FA%' AND mp.KODECABANG =:KODECABANG";

    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k INNER JOIN dpelanggan1 dp1 on(dp1.CP1 = k.KODESALESMAN) INNER JOIN mpelanggan mp on (mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'FA%' and mp.KODECABANG =:KODECABANG";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){



        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
        //return strtotime($a) - strtotime($b);
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();



        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        foreach ($arrayTampung as $key => $value) {
            $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan WHERE DATE(DATETRANSACTION) = :DATETRANSACTION AND KODESALESMAN =:USERID AND NOMORBUKTI LIKE 'FA%' GROUP BY DATE(DATETRANSACTION)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'DATETRANSACTION'=>$value,
                'USERID'=>$USERID
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHKUNJUNGAN'][$key] = $row['JUMLAHKUNJUNGAN'];
            } else {
                $array['JUMLAHKUNJUNGAN'][$key] = 0;
            }
        }

        foreach ($arrayTampung as $key => $value) {
            $sql = "SELECT count(*) as JUMLAHRENCANAKUNJUNGAN FROM tmrencanakunjungan WHERE DATETRANSACTION = '".$value."' AND KODESALESMAN ='".$USERID."' GROUP BY DATE(DATETRANSACTION)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'DATETRANSACTION'=>$value,
                'USERID'=>$USERID
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHRENCANAKUNJUNGAN'][$key] = $row['JUMLAHRENCANAKUNJUNGAN'];
            } else {
                $array['JUMLAHRENCANAKUNJUNGAN'][$key] = 0;
            }
        }
    }
    echo json_encode($array);
    break;


    case "INPUT_RENCANA_KIRIMAN_COLLECTOR":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $USERID = $_POST['USERID'];
    $HALAMAN = $_POST['HALAMAN'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $KODESALESMAN = $_POST['KODESALESMAN'];

    $array = array();
    $array['NOMORBUKTI'] = "";
    $array['hasil'] = "tidakada";
    $id = 0;
    if(isset($_POST['KETERANGAN'])){
        $KETERANGAN = $_POST['KETERANGAN'];
    } else {
        $KETERANGAN = NULL;
    }

    if(isset($_POST['PERMINTAANKHUSUS'])){
        $PERMINTAANKHUSUS = $_POST['PERMINTAANKHUSUS'];
    } else {
        $PERMINTAANKHUSUS = NULL;
    }

    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if($HALAMAN == "RENCANA_KIRIMAN"){
        $HALAMAN = "RF";
    } else {
        $HALAMAN = "RC";
    }

    
    $tampungAja = "";

    try {


        foreach ($KODEPELANGGAN as $key =>$value){
            $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION ='".date('Y-m-d',strtotime($DATETRANSACTION))."' AND KODESALESMAN =:KODESALESMAN AND KODEPELANGGAN=:KODEPELANGGAN AND NOMORBUKTI LIKE :HALAMAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEPELANGGAN'=>$KODEPELANGGAN[$key],
                'KODESALESMAN'=>$USERID,
                'HALAMAN'=>$HALAMAN.'%'
            ]);
            if($pengumuman = $stmt->fetch()){
                $tampungAja = "pernah";
                $array['hasil'] = "sudahpernah";
                throw new Exception("Terdapat Rencana Ke Pelanggan " . $value . " Oleh " . $pengumuman['KODESALESMAN']);
            } else {
                $tampungAja = "tidakpernah";
            }
        }

        if($tampungAja == "tidakpernah"){
            foreach ($KODEPELANGGAN as $key =>$value){
                $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND NOMORBUKTI LIKE :HALAMAN ORDER BY NOMORBUKTI DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                    'HALAMAN'=>$HALAMAN.'%'
                ]);
                $NOMORBUKTI = $HALAMAN . date('ymd',strtotime($DATETRANSACTION));
                if($row = $stmt->fetch()){
                    $counter = substr($row['NOMORBUKTI'], -3);
                    $counter++;
                    if(strlen($counter) == 1){
                        $NOMORBUKTI .= "00" .$counter;
                    } else if (strlen($counter) == 2){
                        $NOMORBUKTI .= "0" .$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                } else {
                    $NOMORBUKTI .= "001";
                }
                $array['NOMORBUKTI'] = $NOMORBUKTI;



                if($HALAMAN == "RF"){
                    $array['hasil'] = "ada";
                    $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,USERID,KETERANGAN,PERMINTAANKHUSUS) values (:NOMORBUKTI,:DATETRANSACTION,:KODESALESMAN,:KODEPELANGGAN,:USERID,:KETERANGAN,:PERMINTAANKHUSUS)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),'KODESALESMAN'=>$KODESALESMAN,'KODEPELANGGAN'=>$KODEPELANGGAN[$key],'USERID'=>$USERID,'KETERANGAN'=>$KETERANGAN[$key],'PERMINTAANKHUSUS'=>$PERMINTAANKHUSUS]);

                } else {
                    $array['hasil'] = "ada";
                    $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,PERMINTAANKHUSUS,USERID) values (:NOMORBUKTI,:DATETRANSACTION,:KODESALESMAN,:KODEPELANGGAN,:KETERANGAN,:PERMINTAANKHUSUS,:USERID)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),'KODESALESMAN'=>$KODESALESMAN,'KODEPELANGGAN'=>$KODEPELANGGAN[$key],'KETERANGAN'=>$KETERANGAN[$key],'PERMINTAANKHUSUS'=>$PERMINTAANKHUSUS,'USERID'=>$USERID]);
                }


                $array['KODEPELANGGAN'][$id] = $value;
                if(isset($NAMAFOTO[$value])){
                    foreach($NAMAFOTO[$value] as $key2 => $value2){
                        $array[$value][$key2] = $NOMORBUKTI."-".$value2;
                        $stmt = $pdo->prepare("INSERT INTO gambarrencanakunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)");
                        $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$value2]);
                    }
                    $id++;
                    $array['adafoto'] = "ada";
                }

            }

        } else {
            $array['hasil'] = "sudahpernah";
        }


    } catch (Exception $e){
        $array['alert'] = $e->getMessage();
    }
    


    echo json_encode($array);
    break;


    case "INPUT_FOLLOW_COLLECTOR":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $USERID = $_POST['USERID'];
    $PERMINTAANKHUSUS = $_POST['PERMINTAANKHUSUS'];
    $LIKE = $_POST['LIKE'];
    $WAKTUMASUK = "";
    //$NAMAFOTO = $_POST['namaFotoKunjungan'];

    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['TANGGALKEMBALI'])){
        $TANGGALKEMBALI = $_POST['TANGGALKEMBALI'];
    } else {
        $TANGGALKEMBALI = NULL;
    }
    $NOMORBUKTI = "";
    if($LIKE == "FA%"){
        $NOMORBUKTI = "FA";
        $WAKTUMASUK = date('Y-m-d H:i:s');
    } else if($LIKE =="FC%"){
        $NOMORBUKTI = "FC";
        $WAKTUMASUK = $_POST['waktuMasukLoginTokoCollector'];
    } else if($LIKE =="PF%"){
        $NOMORBUKTI = "PF";
        $WAKTUMASUK = $_POST['waktuMasukLoginTokoKiriman'];
    } 

    if(isset($_POST['TRANSFER']) && !empty($_POST['TRANSFER'])){
        $TRANSFER = $_POST['TRANSFER'];
    } else {
        $TRANSFER = NULL;
    }

    if(isset($_POST['GIRO']) && !empty($_POST['GIRO'])){
        $GIRO = $_POST['GIRO'];
    } else {
        $GIRO = NULL;
    }

    if(isset($_POST['TUNAI']) && !empty($_POST['TUNAI'])){
        $TUNAI = $_POST['TUNAI'];
    } else {
        $TUNAI = NULL;
    }

    if(isset($_POST['LATITUDE'])&&!empty($_POST['LATITUDE'])){
        $LATITUDE = $_POST['LATITUDE'];
    } else {
        $LATITUDE = NULL;
    }

    if(isset($_POST['LONGITUDE'])&&!empty($_POST['LATITUDE'])){
        $LONGITUDE = $_POST['LONGITUDE'];
    } else {
        $LONGITUDE = NULL;
    }


    $array = array();
    $array['NOMORBUKTI'] = "";
    $NOMORBUKTI = $NOMORBUKTI . date('ymd',strtotime($DATETRANSACTION));
    $sql = "SELECT * FROM tmrencanakunjungan WHERE KODEPELANGGAN = :KODEPELANGGAN AND DATETRANSACTION = :DATETRANSACTION AND KODESALESMAN =:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODEPELANGGAN'=>$KODEPELANGGAN,
        'DATETRANSACTION'=>date('Y-m-d',strtotime($TANGGALKEMBALI)),
        'USERID'=>$USERID
    ]);
    if($row = $stmt->fetch()){
        $array['hasil'] = "sudahrencanakembali";
    } else {
        $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND KODEPELANGGAN =:KODEPELANGGAN AND NOMORBUKTI LIKE :LIKE AND KODESALESMAN =:USERID ORDER BY DATETRANSACTION DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
            'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
            'KODEPELANGGAN'=>$KODEPELANGGAN,
            'LIKE'=>$LIKE,
            'USERID'=>$USERID
        ]);
        if($row = $stmt->fetch()){
            $array['hasil'] = "pernahkunjungan";
            $time30menit = date('Y-m-d H:i:s',strtotime('+30minutes',strtotime($row['DATETRANSACTION'])));
            $dateTimeSekarang = date("Y-m-d H:i:s");

            if($time30menit>=$dateTimeSekarang){
                $array['hasil'] = "pernahkunjunganlebih";
            } else {
                $array['hasil'] = "belumkunjungan";
                $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >=:STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE ':LIKE' ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
                    'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
                    'LIKE'=>$LIKE
                ]);
                if($row = $stmt->fetch()){
                    $counter = substr($row['NOMORBUKTI'], -3);
                    $counter++;
                    if(strlen($counter) == 1){
                        $NOMORBUKTI .= "00" .$counter;
                    } else if (strlen($counter) == 2){
                        $NOMORBUKTI .= "0" .$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                } else {
                    $NOMORBUKTI .= "001";
                }

                if($TANGGALKEMBALI == null){
                    $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,KESIMPULAN,STATUS,PERMINTAANKHUSUS,TRANSFER,GIRO,TUNAI,LATITUDE,LONGITUDE,USERID) values ('".$NOMORBUKTI."','".date('Y-m-d H:i:s',strtotime($WAKTUMASUK))."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."',:KETERANGAN,:KESIMPULAN,'OPEN','".$PERMINTAANKHUSUS."',:TRANSFER,:GIRO,:TUNAI,:LATITUDE,:LONGITUDE,'".$USERID."')";
                    

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'KETERANGAN'=>$KETERANGAN,
                        'KESIMPULAN'=>$KESIMPULAN,
                        'TRANSFER'=>$TRANSFER,
                        'GIRO'=>$GIRO,
                        'TUNAI'=>$TUNAI,
                        'LATITUDE'=>$LATITUDE,
                        'LONGITUDE'=>$LONGITUDE
                    ]);
                    $array['NOMORBUKTI'] = $NOMORBUKTI;
                    if($LIKE != "FA%"){
                        for($i = 0; $i<count($NAMAFOTO); $i++){
                            $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i],
                            ]);
                        }
                    }
                    
                }  else {
                    $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,TANGGALKEMBALI,KESIMPULAN,STATUS,PERMINTAANKHUSUS,TRANSFER,GIRO,TUNAI,LATITUDE,LONGITUDE,USERID) values ('".$NOMORBUKTI."','".date('Y-m-d H:i:s',strtotime($WAKTUMASUK))."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."',':KETERANGAN','".date('Y-m-d',strtotime($TANGGALKEMBALI))."',':KESIMPULAN','OPEN','".$PERMINTAANKHUSUS."',:TRANSFER,:GIRO,:TUNAI,:LATITUDE,:LONGITUDE,'".$USERID."')";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'KETERANGAN'=>$KETERANGAN,
                        'KESIMPULAN'=>$KESIMPULAN,
                        'TRANSFER'=>$TRANSFER,
                        'GIRO'=>$GIRO,
                        'TUNAI'=>$TUNAI,
                        'LATITUDE'=>$LATITUDE,
                        'LONGITUDE'=>$LONGITUDE
                    ]);
                    

                    $array['NOMORBUKTI'] = $NOMORBUKTI;
                    if($LIKE != "FA%"){
                        for($i = 0; $i<count($NAMAFOTO); $i++){
                            $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                            ]);
                        }
                    }
                    $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODESALESMAN = :USERID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                        'USERID'=>$USERID
                    ]);
                    $NOMORBUKTI = "RK" . date('ymd',strtotime($DATETRANSACTION));
                    if($row = $stmt->fetch()){
                        $counter = substr($row['NOMORBUKTI'], -3);
                        $counter++;
                        if(strlen($counter) == 1){
                            $NOMORBUKTI .= "00" .$counter;
                        } else if (strlen($counter) == 2){
                            $NOMORBUKTI .= "0" .$counter;
                        } else {
                            $NOMORBUKTI .= $counter;
                        }
                    } else {
                        $NOMORBUKTI .= "001";
                    }
                    $sql = "INSERT into tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,STATUS,KETERANGAN,USERID) values(:NOMRBUKTI,:TANGGALKEMBALI,:KODESALESMAN,:KODEPELANGGAN,:STATUS,:KETERANGAN,:USERID)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'NOMORBUKTI'=>$NOMORBUKTI,
                        'TANGGALKEMBALI'=>date('Y-m-d',strtotime($TANGGALKEMBALI)),
                        'KODESALESMAN'=>$USERID,
                        'KODEPELANGGAN'=>$KODEPELANGGAN,
                        'STATUS'=>'OPEN',
                        'KETERANGAN'=>$KETERANGAN,
                        'USERID'=>$USERID
                    ]);

                }
            }
        } else {
            $array['hasil'] = "belumkunjungan";
            $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE :LIKE ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
                'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
                'LIKE'=>$LIKE
            ]);
            if($row = $stmt->fetch()){
                $counter = substr($row['NOMORBUKTI'], -3);
                $counter++;
                if(strlen($counter) == 1){
                    $NOMORBUKTI .= "00" .$counter;

                } else if (strlen($counter) == 2){
                    $NOMORBUKTI .= "0" .$counter;
                } else {
                    $NOMORBUKTI .= $counter;
                }
            } else {
                $NOMORBUKTI .= "001";
            }

            if($TANGGALKEMBALI == null){
                $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,KESIMPULAN,STATUS,PERMINTAANKHUSUS,TRANSFER,GIRO,TUNAI,LATITUDE,LONGITUDE,USERID) values ('".$NOMORBUKTI."','".date('Y-m-d H:i:s',strtotime($WAKTUMASUK))."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."',:KETERANGAN,:KESIMPULAN,'OPEN','".$PERMINTAANKHUSUS."',:TRANSFER,:GIRO,:TUNAI,:LATITUDE,:LONGITUDE,'".$USERID."')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KETERANGAN'=>$KETERANGAN,
                    'KESIMPULAN'=>$KESIMPULAN,
                    'TRANSFER'=>$TRANSFER,
                    'GIRO'=>$GIRO,
                    'TUNAI'=>$TUNAI,
                    'LATITUDE'=>$LATITUDE,
                    'LONGITUDE'=>$LONGITUDE
                ]);
                $array['NOMORBUKTI'] = $NOMORBUKTI;
                if($LIKE != "FA%"){
                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                        ]);
                    }
                }
            }  else {
                $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,TANGGALKEMBALI,KESIMPULAN,STATUS,PERMINTAANKHUSUS,TRANSFER,GIRO,TUNAI,LATITUDE,LONGITUDE,USERID) values ('".$NOMORBUKTI."','".date('Y-m-d H:i:s',strtotime($WAKTUMASUK))."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."',:KETERANGAN,'".date('Y-m-d',strtotime($TANGGALKEMBALI))."',:KESIMPULAN,'OPEN','".$PERMINTAANKHUSUS."',:TRANSFER,:GIRO,:TUNAI,:LATITUDE,:LONGITUDE,'".$USERID."')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KETERANGAN'=>$KETERANGAN,
                    'KESIMPULAN'=>$KESIMPULAN,
                    'TRANSFER'=>$TRANSFER,
                    'GIRO'=>$GIRO,
                    'TUNAI'=>$TUNAI,
                    'LATITUDE'=>$LATITUDE,
                    'LONGITUDE'=>$LONGITUDE
                ]);
                $array['NOMORBUKTI'] = $NOMORBUKTI;
                if($LIKE != "FA%"){
                    for($i = 0; $i<count($NAMAFOTO); $i++){
                        $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                        ]);
                    }
                }

                $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODESALESMAN = :USERID";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                    'USERID'=>$USERID
                ]);

                $NOMORBUKTI = "RK" . date('ymd',strtotime($DATETRANSACTION));
                if($row = $stmt->fetch()){
                    $counter = substr($row['NOMORBUKTI'], -3);
                    $counter++;
                    if(strlen($counter) == 1){
                        $NOMORBUKTI .= "00" .$counter;
                    } else if (strlen($counter) == 2){
                        $NOMORBUKTI .= "0" .$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                } else {
                    $NOMORBUKTI .= "001";
                }
                $sql = "INSERT into tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,STATUS,KETERANGAN,USERID) values(:NOMORBUKTI,:TANGGALKEMBALI,:USERID,:KODEPELANGGAN,:STATUS,:KETERANGAN,:USERID)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NOMORBUKTI'=>$NOMORBUKTI,
                    'TANGGALKEMBALI'=>date('Y-m-d',strtotime($TANGGALKEMBALI)),
                    'USERID'=>$USERID,
                    'KODEPELANGGAN'=>$KODEPELANGGAN,
                    'STATUS'=>'OPEN',
                    'KETERANGAN'=>$KETERANGAN,
                    'USERID'=>$USERID
                ]);

            }
        }
    }


    echo json_encode($array);
    break;


    case "INPUT_FOLLOW_COLLECTOR_LEBIH":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $USERID = $_POST['USERID'];
    $PERMINTAANKHUSUS = $_POST['PERMINTAANKHUSUS'];
    $LIKE = $_POST['LIKE'];
    $WAKTUMASUK = "";
    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['TANGGALKEMBALI'])){
        $TANGGALKEMBALI = $_POST['TANGGALKEMBALI'];
    } else {
        $TANGGALKEMBALI = NULL;
    }
    $NOMORBUKTI = "";
    if($LIKE == "FA%"){
        $NOMORBUKTI = "FA";
        $WAKTUMASUK = date('Y-m-d H:i:s');
    } else if($LIKE =="FC%"){
        $NOMORBUKTI = "FC";
        $WAKTUMASUK = $_POST['waktuMasukLoginTokoCollector'];
    } else if($LIKE =="PF%"){
        $NOMORBUKTI = "PF";
        $WAKTUMASUK = $_POST['waktuMasukLoginTokoKiriman'];
    }

    if(isset($_POST['TRANSFER']) && !empty($_POST['TRANSFER'])){
        $TRANSFER = $_POST['TRANSFER'];
    } else {
        $TRANSFER = NULL;
    }

    if(isset($_POST['GIRO']) && !empty($_POST['GIRO'])){
        $GIRO = $_POST['GIRO'];
    } else {
        $GIRO = NULL;
    }

    if(isset($_POST['TUNAI']) && !empty($_POST['TUNAI'])){
        $TUNAI = $_POST['TUNAI'];
    } else {
        $TUNAI = NULL;
    }

    if(isset($_POST['LATITUDE'])&&!empty($_POST['LATITUDE'])){
        $LATITUDE = $_POST['LATITUDE'];
    } else {
        $LATITUDE = NULL;
    }

    if(isset($_POST['LONGITUDE'])&&!empty($_POST['LATITUDE'])){
        $LONGITUDE = $_POST['LONGITUDE'];
    } else {
        $LONGITUDE = NULL;
    }
    

    $array = array();
    $array['NOMORBUKTI'] = "";
    $NOMORBUKTI = $NOMORBUKTI . date('ymd',strtotime($DATETRANSACTION));
    $array['hasil'] = "belumkunjungan";
    $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >= :STARTDATE AND DATETRANSACTION <:FINISHDATE AND NOMORBUKTI LIKE :LIKE ORDER BY DATETRANSACTION DESC,NOMORBUKTI DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
        'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
        'LIKE'=>$LIKE
    ]);
    if($row = $stmt->fetch()){
        $counter = substr($row['NOMORBUKTI'], -3);
        $counter++;
        if(strlen($counter) == 1){
            $NOMORBUKTI .= "00" .$counter;

        } else if (strlen($counter) == 2){
            $NOMORBUKTI .= "0" .$counter;
        } else {
            $NOMORBUKTI .= $counter;
        }
    } else {
        $NOMORBUKTI .= "001";
    }

    if($TANGGALKEMBALI == null){
        $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,KESIMPULAN,STATUS,PERMINTAANKHUSUS,TRANSFER,GIRO,TUNAI,LATITUDE,LONGITUDE,USERID) values ('".$NOMORBUKTI."','".date('Y-m-d H:i:s',strtotime($WAKTUMASUK))."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."',:KETERANGAN,:KESIMPULAN,'OPEN','".$PERMINTAANKHUSUS."',:TRANSFER,:GIRO,:TUNAI,:LATITUDE,:LONGITUDE,'".$USERID."')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KETERANGAN'=>$KETERANGAN,
            'KESIMPULAN'=>$KESIMPULAN,
            'TRANSFER'=>$TRANSFER,
            'GIRO'=>$GIRO,
            'TUNAI'=>$TUNAI,
            'LATITUDE'=>$LATITUDE,
            'LONGITUDE'=>$LONGITUDE
        ]);
        $array['NOMORBUKTI'] = $NOMORBUKTI;
        if($LIKE != "FA%"){
            for($i = 0; $i<count($NAMAFOTO); $i++){
                $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                ]);
            }
        }
    }  else {
        $sql = "INSERT INTO tmkunjungan (NOMORBUKTI,WAKTUMASUK,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,KETERANGAN,TANGGALKEMBALI,KESIMPULAN,STATUS,PERMINTAANKHUSUS,TRANSFER,GIRO,TUNAI,LATITUDE,LONGITUDE,USERID) values ('".$NOMORBUKTI."','".date('Y-m-d H:i:s',strtotime($WAKTUMASUK))."','".date("Y-m-d H:i:s")."','".$USERID."','".$KODEPELANGGAN."',:KETERANGAN,'".date('Y-m-d',strtotime($TANGGALKEMBALI))."',:KESIMPULAN,'OPEN','".$PERMINTAANKHUSUS."',:TRANSFER,:GIRO,:TUNAI,:LATITUDE,:LONGITUDE,'".$USERID."')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KETERANGAN'=>$KETERANGAN,
            'KESIMPULAN'=>$KESIMPULAN,
            'TRANSFER'=>$TRANSFER,
            'GIRO'=>$GIRO,
            'TUNAI'=>$TUNAI,
            'LATITUDE'=>$LATITUDE,
            'LONGITUDE'=>$LONGITUDE
        ]);
        $array['NOMORBUKTI'] = $NOMORBUKTI;
        if($LIKE != "FA%"){
            for($i = 0; $i<count($NAMAFOTO); $i++){
                $sql = "INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NAMAGAMBAR'=>$NOMORBUKTI."-".$NAMAFOTO[$i]
                ]);
            }
        }

        $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION =:DATETRANSACTION AND KODESALESMAN = :USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
            'USERID'=>$USERID
        ]);

        $NOMORBUKTI = "RK" . date('ymd',strtotime($DATETRANSACTION));
        if($row = $stmt->fetch()){
            $counter = substr($row['NOMORBUKTI'], -3);
            $counter++;
            if(strlen($counter) == 1){
                $NOMORBUKTI .= "00" .$counter;
            } else if (strlen($counter) == 2){
                $NOMORBUKTI .= "0" .$counter;
            } else {
                $NOMORBUKTI .= $counter;
            }
        } else {
            $NOMORBUKTI .= "001";
        }
        $sql = "INSERT into tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN,STATUS,KETERANGAN,USERID) values(:NOMORBUKTI,:TANGGALKEMBALI,:KODESALESMAN,:KODEPELANGGAN,:STATUS,:KETERANGAN,:USERID)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'NOMORBUKTI'=>$NOMORBUKTI,
            'TANGGALKEMBALI'=>date('Y-m-d',strtotime($TANGGALKEMBALI)),
            'KODESALESMAN'=>$USERID,
            'KODEPELANGGAN'=>$KODEPELANGGAN,
            'STATUS'=>'OPEN',
            'KETERANGAN'=>$KETERANGAN,
            'USERID'=>$USERID
        ]);
    }

    echo json_encode($array);
    break;


    case "TAMPIL_SEMUA_OUTLET_FOLLOWUP_COLLECTOR":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $LIKE = $_POST['LIKE'];
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    
    /*$sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.NOMORBUKTI LIKE '".$LIKE."' AND k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' GROUP BY k.NOMORBUKTI ORDER BY k.NOMORBUKTI DESC";*/
    

    if($KODEJABATAN == "OWNER"){
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.NOMORBUKTI LIKE '".$LIKE."' AND k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND p.KODECABANG =:KODECABANG GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN ASC, k.NOMORBUKTI DESC";

    } else {
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN,k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE '".$LIKE."' AND k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND (dp1.CP1 ='".$USERID."' OR k.KODESALESMAN = '".$USERID."') AND p.KODECABANG =:KODECABANG GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN ASC, k.NOMORBUKTI DESC";
    }


    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODECABANG'=>$KODECABANG
    ]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $row['NAMA'];
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['TERM'][$id] = $row['TERM'];
        $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
        $array['KODEAREA'][$id] = $row['KODEAREA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
        $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NAMA'][$id] = $row['NAMA'];
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['TERM'][$id] = $row['TERM'];
            $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
            $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
            $array['KODEAREA'][$id] = $row['KODEAREA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
            $array['KODESALESMAN'][$id] = $row['KODESALESMAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;



    case "TAMPIL_MASTER_COLLECTOR":
    $USERID = $_POST['USERID'];
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $array['hasil'] = "";
    /*$sql = "SELECT DISTINCT DATETRANSACTION FROM tmrencanakunjungan WHERE KODESALESMAN = '".$USERID."'";
    
    
    $result = mysqli_query($conn,$sql);
    if($row=mysqli_fetch_object($result)){
        $DATETRANSACTION = $row->DATETRANSACTION;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row->DATETRANSACTION;
        $id++;
        while($row=mysqli_fetch_object($result)){
            $array['DATETRANSACTION'][$id] = $row->DATETRANSACTION;
            $id++;
        }
    }*/

    /*$sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE KODESALESMAN ='".$USERID."' AND NOMORBUKTI LIKE 'FC%'";*/
    /*if($KODEJABATAN == "OWNER"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE NOMORBUKTI LIKE 'FC%'";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k INNER JOIN dpelanggan1 dp1 on(dp1.CP1 = k.KODESALESMAN) WHERE k.NOMORBUKTI LIKE 'FC%'";
    }*/
    $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN pemakai p on(p.USERID = tk.KODESALESMAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE NOMORBUKTI LIKE 'FC%' AND mp.KODECABANG = :KODECABANG";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){
        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();



        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }
    }


    echo json_encode($array);
    break;


    case "TAMPIL_MASTER_KIRIMAN":
    $USERID = $_POST['USERID'];
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    /*$sql = "SELECT DISTINCT DATETRANSACTION FROM tmrencanakunjungan WHERE KODESALESMAN = '".$USERID."' AND NOMORBUKTI LIKE 'RF%' ";
    $array['hasil'] = "";
    $result = mysqli_query($conn,$sql);
    if($row=mysqli_fetch_object($result)){
        $DATETRANSACTION = $row->DATETRANSACTION;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row->DATETRANSACTION;
        $id++;
        while($row=mysqli_fetch_object($result)){
            $array['DATETRANSACTION'][$id] = $row->DATETRANSACTION;
            $id++;
        }
    }*/

    /*$sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE KODESALESMAN ='".$USERID."' AND NOMORBUKTI LIKE 'PF%'";*/
    /*if($KODEJABATAN == "OWNER"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE NOMORBUKTI LIKE 'PF%'";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k INNER JOIN dpelanggan1 dp1 on(dp1.CP1 = k.KODESALESMAN) WHERE k.NOMORBUKTI LIKE 'PF%'";
    }*/
    //$sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan  WHERE NOMORBUKTI LIKE 'PF%'";
    $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN pemakai p on(p.USERID = tk.KODESALESMAN) WHERE NOMORBUKTI LIKE 'PF%' AND p.KODECABANG =:KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){
        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }

        usort($arrayTampung, "date_sort");
        $array = array();



        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }
    }

    echo json_encode($array);
    break;


    case "TAMPIL_POPUP_KARYAWAN_PELANGGAN":
    $KODECABANG = $_POST['KODECABANG'];
    $USERID = $_POST['USERID'];
    $sql = "SELECT mk.KODEKARYAWAN as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) WHERE mk.KODECABANG =:KODECABANG ORDER BY mk.KODEKARYAWAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
        $array['TELEPON'][$id] = $row['TELEPON'];
        $array['WA1'][$id] = $row['WA1'];
        

        if($row['EMAIL1'] == null){
            $array['EMAIL1'][$id] = "";
        } else {
            $array['EMAIL1'][$id] = $row['EMAIL1'];
        }

        if($row['TANGGALLAHIR'] == null){
            $array['TANGGALLAHIR'][$id] = "-";
        } else {   
            $array['TANGGALLAHIR'][$id] = $row['TANGGALLAHIR'];
        }
        $array['NAMAJABATAN'][$id] = $row['NAMAJABATAN'];
        $id++;
        while ($row = $stmt->fetch()) {
            $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $array['KODEJABATAN'][$id] = $row['KODEJABATAN'];
            $array['TELEPON'][$id] = $row['TELEPON'];
            $array['WA1'][$id] = $row['WA1'];


            if($row['EMAIL1'] == null){
                $array['EMAIL1'][$id] = "";
            } else {
                $array['EMAIL1'][$id] = $row['EMAIL1'];
            }

            if($row['TANGGALLAHIR'] == null){
                $array['TANGGALLAHIR'][$id] = "-";
            } else {   
                $array['TANGGALLAHIR'][$id] = $row['TANGGALLAHIR'];
            }
            $array['NAMAJABATAN'][$id] = $row['NAMAJABATAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    /*case "TAMPIL_POPUP_KARYAWAN_PELANGGAN_BERDASARKAN_JABATAN":
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $sql = "SELECT * FROM mjabatan WHERE KODEJABATAN =:KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEJABATAN'=>$KODEJABATAN]);
    if($jabatan = $stmt->fetch()){
        $sql = "SELECT mk.KODEKARYAWAN as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) WHERE mk.KODECABANG = :KODECABANG AND mk.KODEJABATAN ='".$KODEJABATAN."' ORDER BY mk.KODEKARYAWAN";
    } else {
        $sql = "SELECT mk.KODEKARYAWAN as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) WHERE mk.KODECABANG = :KODECABANG OR mk.KODECABANG='SEMUA' ORDER BY mk.KODEKARYAWAN";

    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        
        $id++;
        while ($row = $stmt->fetch()) {
            $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;*/

    case "TAMPIL_POPUP_KARYAWAN_PELANGGAN_BERDASARKAN_JABATAN":
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $sql = "SELECT * FROM mjabatan WHERE KODEJABATAN =:KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEJABATAN'=>$KODEJABATAN]);
    if($jabatan = $stmt->fetch()){
        $sql = "SELECT p.USERID as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) inner join pemakai p on(p.USERID = mk.KODEKARYAWAN) WHERE mk.KODECABANG = :KODECABANG AND mk.KODEJABATAN ='".$KODEJABATAN."' ORDER BY mk.KODEKARYAWAN";
    } else {
        $sql = "SELECT p.USERID as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) inner join pemakai p on(p.USERID = mk.KODEKARYAWAN) WHERE mk.KODECABANG = :KODECABANG OR mk.KODECABANG='SEMUA' ORDER BY mk.KODEKARYAWAN";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while ($row = $stmt->fetch()) {
            $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_POPUP_KARYAWAN_PELANGGAN_BERDASARKAN_USERID":
    try {
        $USERID = $_POST['USERID'];
        $KODECABANG = $_POST['KODECABANG'];
        $sql = "SELECT mk.nama AS NAMA, mk.KODEKARYAWAN AS KODEKARYAWAN, p.KODEJABATAN AS KODEJABATAN FROM pemakai p INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE p.USERID = :USERID AND (p.KODECABANG=:KODECABANG OR p.KODECABANG ='SEMUA') ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID,'KODECABANG'=>$KODECABANG]);
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            $id = 0;
            $array['hasil'] = "ada";
            if($row['KODEJABATAN'] == "OWNER"){
                $sql = "SELECT mk.nama AS NAMA, mk.KODEKARYAWAN AS KODEKARYAWAN, p.KODEJABATAN AS KODEJABATAN FROM pemakai p INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE p.KODECABANG = :KODECABANG";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODECABANG'=>$KODECABANG]);
                while($row = $stmt->fetch()){
                    $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
                    $array['NAMA'][$id] = $row['NAMA'];
                    $id++;        
                }
            } else {
                $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
                $array['NAMA'][$id] = $row['NAMA'];
                $id++;

            }
        } else {
            $array['hasil'] = "err";
        }
        echo json_encode($array);
    } catch(Exception $e){
        $array['hasil'] = $e->getMessage();
        echo json_encode($array);
    }

    break;

    case "TAMPIL_POPUP_KARYAWAN_PELANGGAN_PER_TANGGAL":

    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['KODEKARYAWAN'];
    
    /*if($KODEJABATAN == "OWNER"){
        $sql =  "SELECT mk.KODEKARYAWAN as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) WHERE mk.KODECABANG = '".$KODECABANG."' ORDER BY mk.KODEKARYAWAN";
        
    } else {
        $sql = "SELECT * FROM mkaryawan mk INNER JOIN pemakai p on(p.USERID = mk.KODEKARYAWAN) WHERE p.USERID = '".$USERID."' ORDER BY mk.KODEKARYAWAN";
    }*/
    $sql =  "SELECT mk.KODEKARYAWAN as KODEKARYAWAN, mk.NAMA as NAMA, mk.KODEJABATAN as KODEJABATAN, mj.NAMA as NAMAJABATAN, mk.TELEPON as TELEPON, mk.WA1 as WA1, mk.EMAIL1 as EMAIL1, mk.TANGGALLAHIR AS TANGGALLAHIR FROM mkaryawan mk inner join mjabatan mj on(mj.KODEJABATAN = mk.KODEJABATAN) WHERE mk.KODECABANG = :KODECABANG ORDER BY mk.KODEKARYAWAN";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
        $array['NAMA'][$id] = $row['NAMA'];
        
        $id++;
        while ($row = $stmt->fetch()) {
            $array['KODEKARYAWAN'][$id] = $row['KODEKARYAWAN'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;


    case "CEK_VERSI_APP":
    $KODEVERSI = $_POST['KODEVERSI'];
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM mversi";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $row = $stmt->fetch();
    $array['hasil'] = $row['KODEVERSI'];

    $sql = "UPDATE pemakai set KODEVERSI=:KODEVERSI,LASTONLINE=:LASTONLINE WHERE USERID = :USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID,'KODEVERSI'=>$KODEVERSI,'LASTONLINE'=>date('Y-m-d H:i:s')]);

    if(isset($_POST['VERSIMANUAL']) && (!empty($USERID) && $USERID!="null") ){
        $VERSIMANUAL = $_POST['VERSIMANUAL'];
        $sql = "INSERT INTO log (USERID,KODEVERSI,VERSIMANUAL) values (:USERID,:KODEVERSI,:VERSIMANUAL)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID,
            'KODEVERSI'=>$KODEVERSI,
            'VERSIMANUAL'=>$VERSIMANUAL]);
    } 


    echo json_encode($array);
    break;

    case "WAKTU_SERVER":
    echo date("Y-m-d H:i:s");
    break;


    case "TAMPIL_MASTER_PRICELIST":
    $stmt = $pdo->prepare("SELECT * FROM mbrg");
    $stmt->execute();
    $array = array();
    $id = 0;
    if($barang = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['kodebarang'][$id] = $barang['kodebarang'];
        $array['nama'][$id] = $barang['nama'];
        $array['pricelistj1'][$id] = $barang['pricelistj1'];
        $array['pricelistj2'][$id] = $barang['pricelistj2'];
        $array['pricelistj3'][$id] = $barang['pricelistj3'];
        $id++;
        while($barang = $stmt->fetch()){
            $array['kodebarang'][$id] = $barang['kodebarang'];
            $array['nama'][$id] = $barang['nama'];
            $array['pricelistj1'][$id] = $barang['pricelistj1'];
            $array['pricelistj2'][$id] = $barang['pricelistj2'];
            $array['pricelistj3'][$id] = $barang['pricelistj3'];
            $id++;
        }

    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT * FROM logmbrg";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($logmbrg = $stmt->fetch()){
        $array['LOG']=
        [
            'WAKTUUBAHSTOK'=>$logmbrg['WAKTUUBAHSTOK'],
            'WAKTUUBAHSEMUA'=>$logmbrg['WAKTUUBAHSEMUA'],
            'USERIDSTOK'=>$logmbrg['USERIDSTOK'],
            'USERIDSEMUA'=>$logmbrg['USERIDSEMUA']
        ];
    }
    

    echo json_encode($array);
    break;

    case "TAMPIL_SEMUA_LAPORAN":
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];

    /*if($KODEJABATAN == "OWNER" || $USERID == "ANTON"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN pemakai p on(p.USERID = tk.KODESALESMAN) WHERE mp.KODECABANG ='".$KODECABANG."'";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) inner join pemakai p on(p.USERID = k.KODESALESMAN) WHERE (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN = '".$USERID."') AND p.KODECABANG ='".$KODECABANG."' ";
    }*/

    if($KODEJABATAN == "OWNER" || $USERID == "ANTON"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) inner join mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN = '".$USERID."') AND mp.KODECABANG =:KODECABANG";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);

    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){


        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();

        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        foreach ($arrayTampung as $key => $value) {
            if($KODEJABATAN == "OWNER" || $USERID == "ANTON"){
                $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE DATE(DATETRANSACTION) = :VALUE AND mp.KODECABANG =:KODECABANG GROUP BY DATE(DATETRANSACTION)";
            } else {
                $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE DATE(DATETRANSACTION) =:VALUE AND (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') AND mp.KODECABANG =:KODECABANG GROUP BY DATE(DATETRANSACTION)";
                
            }
            /*$sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan WHERE DATE(DATETRANSACTION) = '".$value."' GROUP BY DATE(DATETRANSACTION)";*/
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODECABANG'=>$KODECABANG,
                'VALUE'=>$value
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHKUNJUNGAN'][$key] = $row['JUMLAHKUNJUNGAN'];
            } else {
                $array['JUMLAHKUNJUNGAN'][$key] = 0;
            }
        }

        if($counter == 1){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
        }
    }
    

    echo json_encode($array);
    break;


    case "TAMPIL_SEMUA_NOTA_LAPORAN":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql ="";
    if(isset($_POST['filterPermintaanKhususGlobal'])){
        $filterPermintaanKhususGlobal = $_POST['filterPermintaanKhususGlobal'];
    } else { $filterPermintaanKhususGlobal = null; }

    if(isset($_POST['filterKunjunganGlobal'])){
        $filterKunjunganGlobal = $_POST['filterKunjunganGlobal'];
    } else { $filterKunjunganGlobal = null; }

    /*$sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN ASC";*/

    /*if($KODEJABATAN =="OWNER" || $USERID == "ANTON"){
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'";
    } else {
        $sql = "SELECT  k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."')";
    }*/

    if($KODEJABATAN =="OWNER" || $USERID == "ANTON"){
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.DATETRANSACTION >=:STARTDATE AND k.DATETRANSACTION <:FINISHDATE AND p.KODECABANG =:KODECABANG";
    } else {
        $sql = "SELECT  k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.DATETRANSACTION >=:STARTDATE AND k.DATETRANSACTION <:FINISHDATE AND (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') AND p.KODECABANG=:KODECABANG";
    }

    if($filterPermintaanKhususGlobal){
        $sql.= " AND k.PERMINTAANKHUSUS='".$filterPermintaanKhususGlobal."'";
    }

    if($filterKunjunganGlobal != "SEMUA"){
        $sql.= " AND NOMORBUKTI LIKE '".$filterKunjunganGlobal."%'";
    }

    $sql.=" GROUP BY k.NOMORBUKTI ORDER BY k.KODESALESMAN ASC,k.DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'STARTDATE'=>date('Y-m-d 00:00:00',strtotime($DATETRANSACTION)),
        'FINISHDATE'=>date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION))),
        'KODECABANG'=>$KODECABANG
    ]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $row['NAMA'];
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['TERM'][$id] = $row['TERM'];
        $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
        $array['KODEAREA'][$id] = $row['KODEAREA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
        $array['KODESALESMAN'][$id]=$row['KODESALESMAN']; 
        $id++;
        while($row = $stmt->fetch()){
            $array['NAMA'][$id] = $row['NAMA'];
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['TERM'][$id] = $row['TERM'];
            $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
            $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
            $array['KODEAREA'][$id] = $row['KODEAREA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
            $array['KODESALESMAN'][$id]=$row['KODESALESMAN']; 
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;


    case "TAMPIL_SEMUA_NOTA_LAPORAN_FILTER":
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql ="";
    if(isset($_POST['filterPermintaanKhususGlobal'])){
        $filterPermintaanKhususGlobal = $_POST['filterPermintaanKhususGlobal'];
    } else { $filterPermintaanKhususGlobal = null; }

    if(isset($_POST['filterKunjunganGlobal'])){
        $filterKunjunganGlobal = $_POST['filterKunjunganGlobal'];
    } else { $filterKunjunganGlobal = null; }

    if(isset($_POST['filterSampaiTanggalGlobal'])){
        $filterSampaiTanggalGlobal = $_POST['filterSampaiTanggalGlobal'];
    } else { $filterSampaiTanggalGlobal = null; }

    if(isset($_POST['filterDariTanggalGlobal'])){
        $filterDariTanggalGlobal = $_POST['filterDariTanggalGlobal'];
    } else { $filterDariTanggalGlobal = null; }

    /*if($KODEJABATAN =="OWNER"){
        $sql = "SELECT k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'";
    } else {
        $sql = "SELECT  k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') ";
    }*/

    //yang dipakai kedua bawah
    /*if($KODEJABATAN =="OWNER" || $USERID == "ANTON"){
        $sql = "SELECT mk.NAMA as NAMAKARYAWAN,k.WAKTUMASUK AS JAMMASUK, k.DATETRANSACTION AS JAMREPORT,k.KETERANGAN as KETERANGAN, k.KESIMPULAN as KESIMPULAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE";
    } else {
        $sql = "SELECT mk.NAMA as NAMAKARYAWAN,k.WAKTUMASUK AS JAMMASUK, k.DATETRANSACTION AS JAMREPORT,k.KETERANGAN as KETERANGAN, k.KESIMPULAN as KESIMPULAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') ";
    }*/

    if($KODEJABATAN =="OWNER" || $USERID == "ANTON"){
        $sql = "SELECT mk.NAMA as NAMAKARYAWAN,k.WAKTUMASUK AS JAMMASUK, k.DATETRANSACTION AS JAMREPORT,k.KETERANGAN as KETERANGAN, k.KESIMPULAN as KESIMPULAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE p.KODECABANG=:KODECABANG";
    } else {
        $sql = "SELECT mk.NAMA as NAMAKARYAWAN,k.WAKTUMASUK AS JAMMASUK, k.DATETRANSACTION AS JAMREPORT,k.KETERANGAN as KETERANGAN, k.KESIMPULAN as KESIMPULAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.KODESALESMAN as KODESALESMAN, k.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, p.NAMA as NAMA,p.KODEPELANGGAN as KODEPELANGGAN, p.ALAMAT as ALAMAT, p.KOTA as KOTA, p.TELPON as TELPON, p.FAX as FAX, p.KREDITLIMIT as KREDITLIMIT, p.SYARATPIUTANG as SYARATPIUTANG, p.SYARATPIUTANGMAX as SYARATPIUTANGMAX, p.TERM as TERM, kp.NAMA as NAMAKATEGORIPELANGGAN, p.ALAMATKIRIM as ALAMATKIRIM, p.NPWP as NPWP, p.KTP as KTP, p.NAMAFAKTUR as NAMAFAKTUR, p.ALAMATFAKTUR as ALAMATFAKTUR, p.KODEAREA as KODEAREA, p.USERID as USERID FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkategoripelanggan kp on(kp.KODEKATEGORIPELANGGAN = p.KATEGORIPELANGGAN) left join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') AND p.KODECABANG =:KODECABANG";
    }
    
    
    

    if($filterPermintaanKhususGlobal){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql.= " k.PERMINTAANKHUSUS='".$filterPermintaanKhususGlobal."'";
        } else {
            $sql.= " AND k.PERMINTAANKHUSUS='".$filterPermintaanKhususGlobal."'"; 
        }
    }

    if($filterKunjunganGlobal != "SEMUA"){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql.= " NOMORBUKTI LIKE '".$filterKunjunganGlobal."%'";
        } else {
            $sql.= " AND NOMORBUKTI LIKE '".$filterKunjunganGlobal."%'"; 
        }
    }

    if($filterDariTanggalGlobal){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql.= "  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($filterDariTanggalGlobal))."'";
        } else {
            $sql.= " AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($filterDariTanggalGlobal))."'"; 
        }
    }

    if($filterSampaiTanggalGlobal){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql.= " k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($filterSampaiTanggalGlobal)))."'";
        } else {
            $sql.= " AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($filterSampaiTanggalGlobal)))."'"; 
        }
    }

    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    

    $sql.=" GROUP BY k.NOMORBUKTI ORDER BY k.DATETRANSACTION DESC,k.KODESALESMAN ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $row['NAMA'];
        $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
        $array['ALAMAT'][$id] = $row['ALAMAT'];
        $array['KOTA'][$id] = $row['KOTA'];
        $array['TELPON'][$id] = $row['TELPON'];
        $array['FAX'][$id] = $row['FAX'];
        $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
        $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
        $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
        $array['TERM'][$id] = $row['TERM'];
        $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
        $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
        $array['NPWP'][$id] = $row['NPWP'];
        $array['KTP'][$id] = $row['KTP'];
        $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
        $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
        $array['KODEAREA'][$id] = $row['KODEAREA'];
        $array['USERID'][$id] = $row['USERID'];
        $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
        $array['KODESALESMAN'][$id]=$row['KODESALESMAN'];
        $array['JAMMASUK'][$id] = $row['JAMMASUK'];
        $array['JAMREPORT'][$id] = $row['JAMREPORT'];
        $array['KETERANGAN'][$id] = $row['KETERANGAN'];
        $array['KESIMPULAN'][$id] = $row['KESIMPULAN'];
        $array['PERMINTAANKHUSUS'][$id] = $row['PERMINTAANKHUSUS'];
        $array['NAMAKARYAWAN'][$id] = $row['NAMAKARYAWAN'];
        $id++;
        $array['TOTJUMLAH'] = $id;
        while($row = $stmt->fetch()){
            $array['NAMA'][$id] = $row['NAMA'];
            $array['KODEPELANGGAN'][$id] = $row['KODEPELANGGAN'];
            $array['ALAMAT'][$id] = $row['ALAMAT'];
            $array['KOTA'][$id] = $row['KOTA'];
            $array['TELPON'][$id] = $row['TELPON'];
            $array['FAX'][$id] = $row['FAX'];
            $array['KREDITLIMIT'][$id] = $row['KREDITLIMIT'];
            $array['SYARATPIUTANG'][$id] = $row['SYARATPIUTANG'];
            $array['SYARATPIUTANGMAX'][$id] = $row['SYARATPIUTANGMAX'];
            $array['TERM'][$id] = $row['TERM'];
            $array['NAMAKATEGORIPELANGGAN'][$id] = $row['NAMAKATEGORIPELANGGAN'];
            $array['ALAMATKIRIM'][$id] = $row['ALAMATKIRIM'];
            $array['NPWP'][$id] = $row['NPWP'];
            $array['KTP'][$id] = $row['KTP'];
            $array['NAMAFAKTUR'][$id] = $row['NAMAFAKTUR'];
            $array['ALAMATFAKTUR'][$id] = $row['ALAMATFAKTUR'];
            $array['KODEAREA'][$id] = $row['KODEAREA'];
            $array['USERID'][$id] = $row['USERID'];
            $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
            $array['KODESALESMAN'][$id]=$row['KODESALESMAN'];
            $array['JAMMASUK'][$id] = $row['JAMMASUK'];
            $array['JAMREPORT'][$id] = $row['JAMREPORT'];
            $array['KETERANGAN'][$id] = $row['KETERANGAN'];
            $array['KESIMPULAN'][$id] = $row['KESIMPULAN'];
            $array['PERMINTAANKHUSUS'][$id] = $row['PERMINTAANKHUSUS'];
            $array['NAMAKARYAWAN'][$id] = $row['NAMAKARYAWAN'];
            
            $id++;
            $array['TOTJUMLAH'] = $id;
        }
    } else {
        $array['hasil'] = "tidakada";
        $array['TOTJUMLAH'] = $id;
    }

    echo json_encode($array);
    break;


    case "TAMPIL_SEMUA_LAPORAN_BERDASARKAN_PELANGGAN":
    $array = array();
    $id = 0;
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $array['hasil'] ="";
    $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    }

    if($array['hasil'] == "ada"){

        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();

        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        foreach ($arrayTampung as $key => $value) {
            $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan WHERE DATE(DATETRANSACTION) = :VALUE AND KODEPELANGGAN =:KODEPELANGGAN GROUP BY DATE(DATETRANSACTION)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEPELANGGAN'=>$KODEPELANGGAN,
                'VALUE'=>$value
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHKUNJUNGAN'][$key] = $row['JUMLAHKUNJUNGAN'];
            } else {
                $array['JUMLAHKUNJUNGAN'][$key] = 0;
            }
        }

        if($counter == 1){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
        }
    } else {

    }
    

    echo json_encode($array);
    break;

    case "TAMPIL_SEMUA_NOTA_LAPORAN_BERDASARKAN_PELANGGAN":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $stmt = $pdo->prepare("SELECT k.NOMORBUKTI as NOMORBUKTI, k.DATETRANSACTION as DATETRANSACTION, k.KODESALESMAN as KODESALESMAN, p.NAMA as NAMAPELANGGAN,k.KETERANGAN as KETERANGAN, k.KESIMPULAN as KESIMPULAN, k.PERMINTAAN as PERMINTAAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.TRANSFER as TRANSFER, k.GIRO as GIRO, k.TUNAI as TUNAI, k.WAKTUMASUK as WAKTUMASUK FROM tmkunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN ORDER BY k.NOMORBUKTI DESC");
    $stmt->execute(['KODEPELANGGAN' =>$KODEPELANGGAN]);
    $array['hasil'] = "";
    $id = 0;

    if($kunjungan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
        $array['JAMMASUK'][$id] = date('H:i:s',strtotime($kunjungan['WAKTUMASUK']));
        $array['JAMREPORT'][$id] = date('H:i:s',strtotime($kunjungan['DATETRANSACTION']));
        $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
        $array['KETERANGAN'][$id] = $kunjungan['KETERANGAN'];
        $array['KESIMPULAN'][$id] = $kunjungan['KESIMPULAN'];
        $array['PERMINTAAN'][$id] = $kunjungan['PERMINTAAN'];
        $array['PERMINTAANKHUSUS'][$id] = $kunjungan['PERMINTAANKHUSUS'];
        $array['TRANSFER'][$id] = $kunjungan['TRANSFER'];
        $array['GIRO'][$id] = $kunjungan['GIRO'];
        $array['TUNAI'][$id] = $kunjungan['TUNAI'];
        $array['WAKTUMASUK'][$id] = $kunjungan['WAKTUMASUK'];
        $array['NAMAPELANGGAN'][$id] = $kunjungan['NAMAPELANGGAN'];

        /*$array['PELANGGAN'][$counter] = 
        [
            'KODEKARYAWAN'=>$jumlah['KODEKARYAWAN'],
            'TANGGAL'=>date('Y-m-d',strtotime($jumlah['DATETRANSACTION'])) ,
            'JUMLAH'=>$jumlah['JUMLAH']
        ];
        $counter++;*/

        $id++;
        while($kunjungan = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
            $array['JAMMASUK'][$id] = date('H:i:s',strtotime($kunjungan['WAKTUMASUK']));
            $array['JAMREPORT'][$id] = date('H:i:s',strtotime($kunjungan['DATETRANSACTION']));
            $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
            $array['KETERANGAN'][$id] = $kunjungan['KETERANGAN'];
            $array['KESIMPULAN'][$id] = $kunjungan['KESIMPULAN'];
            $array['PERMINTAAN'][$id] = $kunjungan['PERMINTAAN'];
            $array['PERMINTAANKHUSUS'][$id] = $kunjungan['PERMINTAANKHUSUS'];
            $array['TRANSFER'][$id] = $kunjungan['TRANSFER'];
            $array['GIRO'][$id] = $kunjungan['GIRO'];
            $array['TUNAI'][$id] = $kunjungan['TUNAI'];
            $array['WAKTUMASUK'][$id] = $kunjungan['WAKTUMASUK'];
            $array['NAMAPELANGGAN'][$id] = $kunjungan['NAMAPELANGGAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if(isset($array['NOMORBUKTI'])){
        $id = 0;
        foreach ($array['NOMORBUKTI'] as $key => $value) {
            $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'NOMORBUKTI'=>$value.'%'
            ]);
            if($gambar = $stmt->fetch()){
                $array['hasilgambar'] = "ada";
                $array['GAMBAR'][$id] = [
                    'NAMAGAMBAR' =>$gambar['NAMAGAMBAR'],
                    'NOMORBUKTI' =>$value
                ];
                $id++;
                while($gambar = $stmt->fetch()){
                    $array['GAMBAR'][$id] = [
                        'NAMAGAMBAR' =>$gambar['NAMAGAMBAR'],
                        'NOMORBUKTI' =>$value
                    ];
                    $id++;
                }
            } 
        }
        
    }

    


    echo json_encode($array);
    break;

    case "TAMPIL_EDIT_SEMUA_KUNJUNGAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $stmt = $pdo->prepare("SELECT * FROM tmkunjungan WHERE NOMORBUKTI LIKE :NOMORBUKTI");
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    $array = array();
    if($kunjungan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['PERMINTAANKHUSUS'] = $kunjungan['PERMINTAANKHUSUS'];
        $array['KESIMPULAN'] = $kunjungan['KESIMPULAN'];
        $array['KETERANGAN'] = $kunjungan['KETERANGAN'];
        
        while($kunjungan = $stmt->fetch()){
            $array['PERMINTAANKHUSUS'] = $kunjungan['PERMINTAANKHUSUS'];
            $array['KESIMPULAN'] = $kunjungan['KESIMPULAN'];
            $array['KETERANGAN'] = $kunjungan['KETERANGAN'];
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    $stmt = $pdo->prepare("SELECT * FROM mpermintaankhusus");
    $stmt->execute();
    $id = 0;
    if($permintaankhusus = $stmt->fetch()){
        $array['hasilpermintaan'] = "ada";
        $array['NAMA'][$id] = $permintaankhusus['NAMA'];
        $id++;
        while($permintaankhusus = $stmt->fetch()){
            $array['NAMA'][$id] = $permintaankhusus['NAMA'];
            $id++;
        }

    } else {
        $array['hasilpermintaan'] = "tidakada";
    }

    $stmt = $pdo->prepare("SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'");
    $stmt->execute();
    $id = 0;
    if($gambar = $stmt->fetch()){
        $array['hasilgambarkunjungan'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);
            $id++;
        }
    } else {
        $array['hasilgambarkunjungan'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "EDIT_SEMUA_KUNJUNGAN":
    $PERMINTAANKHUSUS = $_POST['PERMINTAANKHUSUS'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $USERID = $_POST['USERID'];
    $KETERANGAN = $_POST['KETERANGAN'];
    //$namaFotoKunjungan = $_POST['namaFotoKunjungan'];
    $array = array();

    $array['coba']="";
    $array['uploadgambar'] = "";
    if(isset($_POST['NAMAGAMBAR'])){
        $NAMAGAMBAR = $_POST['NAMAGAMBAR'];
    } else { $NAMAGAMBAR = null; }

    if(isset($_POST['namaFotoKunjungan'])){
        $namaFotoKunjungan = $_POST['namaFotoKunjungan'];
    } else { $namaFotoKunjungan = null; }

    if($NAMAGAMBAR != null){
        for($i = 0; $i<count($NAMAGAMBAR); $i++){
            $stmt = $pdo->prepare("DELETE FROM gambarkunjungan WHERE NAMAGAMBAR='".$NOMORBUKTI."-".$NAMAGAMBAR[$i]."'");
            $stmt->execute();

            if(file_exists("gambar_kunjungan/".$NOMORBUKTI."-".$NAMAGAMBAR[$i].".jpg")){
                unlink("gambar_kunjungan/".$NOMORBUKTI."-".$NAMAGAMBAR[$i].".jpg");
            }

        }
    }

    if($namaFotoKunjungan != null){
        $array['uploadgambar'] = "ada";
        for($i = 0; $i<count($namaFotoKunjungan); $i++){
            $stmt = $pdo->prepare("INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)");
            $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$namaFotoKunjungan[$i]]);
            $array['coba'] = $NOMORBUKTI."-".$namaFotoKunjungan[$i];
        }
    }

    $stmt = $pdo->prepare("UPDATE tmkunjungan set USERID =:USERID,PERMINTAANKHUSUS=:PERMINTAANKHUSUS,KETERANGAN=:KETERANGAN,KESIMPULAN=:KESIMPULAN WHERE NOMORBUKTI =:NOMORBUKTI");
    $stmt->execute(['USERID'=>$USERID,'NOMORBUKTI'=>$NOMORBUKTI,'KETERANGAN'=>$KETERANGAN,'KESIMPULAN'=>$KESIMPULAN,'PERMINTAANKHUSUS'=>$PERMINTAANKHUSUS]);


    $array['uploadgambar'] = "tidakada";
    echo json_encode($array);
    break;


    case "TAMPIL_MASTER_KATEGORI_BARANG":
    $stmt = $pdo->prepare("SELECT * FROM mkategori WHERE status = 'CLOSE'");
    $stmt->execute();
    $array = array();
    $id = 0;
    if($kategori = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKATEGORI'][$id] = $kategori['kodekategori'];
        $array['NAMA'][$id] = $kategori['nama'];
        $id++;
        while($kategori = $stmt->fetch()){
            $array['KODEKATEGORI'][$id] = $kategori['kodekategori'];
            $array['NAMA'][$id] = $kategori['nama'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
        
    }
    echo json_encode($array);
    break;



    case "TAMPIL_MASTER_PERMINTAAN_KHUSUS":
    $stmt = $pdo->prepare("SELECT * FROM mpermintaankhusus");
    $stmt->execute();
    $array = array();
    $id = 0;
    if($permintaanKhusus = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $permintaanKhusus['NAMA'];
        $id++;
        while($permintaanKhusus = $stmt->fetch()){
            $array['NAMA'][$id] = $permintaanKhusus['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "INPUT_PRICELIST":
    $kodekategori = $_POST['kodekategori'];
    $kodebarang = $_POST['kodebarang'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $satuan = $_POST['satuan'];
    $pricelistj1 = $_POST['pricelistj1'];
    $pricelistj2 = $_POST['pricelistj2'];
    $pricelistj3 = $_POST['pricelistj3'];
    $USERID = $_POST['USERID'];
    
    if(isset($_POST['namaFotoPricelist'])){
        $namaFotoPricelist = $_POST['namaFotoPricelist'];
    } else { $namaFotoPricelist = null; }

    if(isset($_POST['listMerkMobilChecked'])){
        $listMerkMobilChecked = $_POST['listMerkMobilChecked'];
    } else { $listMerkMobilChecked = null; }
    

    $array = array();

    $stmt = $pdo->prepare("SELECT * FROM mbrg WHERE kodebarang =:kodebarang");
    $stmt->execute(['kodebarang'=>$kodebarang]);
    if($stmt->fetch()){
        $array['hasil'] = "ada";
    } else {
        $stmt = $pdo->prepare("INSERT INTO mbrg(kodekategori,kodebarang,nama,keterangan,satuan,pricelistj1,pricelistj2,pricelistj3,USERID) values(?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$kodekategori,$kodebarang,$nama,$keterangan,$satuan,$pricelistj1,$pricelistj2,$pricelistj3,$USERID]);

        if($namaFotoPricelist != null){
            $array['uploadgambar'] = "ada";
            for($i = 0; $i<count($namaFotoPricelist); $i++){
                $sql = "SELECT * FROM gambarbarang ORDER BY ID DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $ID = 1;
                if($counter = $stmt->fetch()){
                    $ID = $counter['ID']+1;
                }

                $sql = "SELECT * FROM gambarbarang WHERE KODEBARANG =:KODEBARANG ORDER BY URUTAN DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEBARANG'=>$kodebarang]);
                $URUTAN = 1;
                if($counter = $stmt->fetch()){
                    $URUTAN = $counter['URUTAN']+1;
                }


                $stmt = $pdo->prepare("INSERT INTO gambarbarang(ID,KODEBARANG,NAMAGAMBAR,URUTAN,USERID) values(:ID,:KODEBARANG,:NAMAGAMBAR,:URUTAN,:USERID)");
                $stmt->execute([
                    'ID'=>$ID,
                    'KODEBARANG'=>$kodebarang,
                    'NAMAGAMBAR'=>$kodebarang."-".$ID,
                    'URUTAN'=>$URUTAN,
                    'USERID'=>$USERID,
                ]);
                $array['NAMAFOTOPRICELIST'][$i] = $kodebarang."-".$ID;
            }
        }
        $array['hasil'] = "berhasil";
    }


    if(isset($listMerkMobilChecked)){
        for($i = 0; $i<count($listMerkMobilChecked); $i++){
            $sql = "INSERT INTO mdbrg (kodebarang,KODEMOBIL) values (:KODEBARANG,:KODEMOBIL)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEBARANG' => $kodebarang,
                'KODEMOBIL' => $listMerkMobilChecked[$i]]);
        }
    }

    echo json_encode($array);
    break;

    case "DETAIL_PRICELIST":
    $kodebarang = $_POST['kodebarang'];
    $stmt = $pdo->prepare("SELECT * FROM mbrg WHERE kodebarang=:kodebarang");
    $stmt->execute(['kodebarang'=>$kodebarang]);
    $array=array();
    $id=0;
    if($barang = $stmt->fetch()){
        $array['kodebarang'] = $barang['kodebarang'];
        $array['kodekategori'] = $barang['kodekategori'];
        $array['nama'] = $barang['nama'];
        $array['stok'] = $barang['stok'];
        $array['satuan'] = $barang['satuan'];
        $array['keterangan'] = $barang['keterangan'];
        $array['pricelistj1'] = 'Rp. '.number_format($barang['pricelistj1'],0,",",".");
        $array['pricelistj2'] = 'Rp. '.number_format($barang['pricelistj2'],0,",",".");
        $array['pricelistj3'] = 'Rp. '.number_format($barang['pricelistj3'],0,",",".");
    }

    $stmt = $pdo->prepare("SELECT * FROM gambarbarang WHERE KODEBARANG =:KODEBARANG AND STATUS='AKTIF' ORDER BY URUTAN ASC");
    $stmt->execute(['KODEBARANG'=>$kodebarang]);
    if($gambarBarang = $stmt->fetch()){
        $array['gambar'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambarBarang['NAMAGAMBAR'];
        $id++;
        while($gambarBarang = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambarBarang['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['gambar'] = "tidakada";
    }

    $sql = "SELECT m.NAMA as NAMA FROM mbrg b INNER JOIN mdbrg db on(b.kodebarang = db.kodebarang) INNER JOIN mmobil m on(m.KODEMOBIL = db.KODEMOBIL) WHERE db.kodebarang = :kodebarang";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kodebarang'=>$kodebarang]);
    $id = 0;
    if($merkmobil = $stmt->fetch()){
        $array['merkmobil'] = "ada";
        $array['NAMA'][$id] = $merkmobil['NAMA'];
        $id++;
        while($merkmobil = $stmt->fetch()){
            $array['NAMA'][$id] = $merkmobil['NAMA'];
            $id++;
        }
    } else {
        $array['merkmobil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "EDIT_PRICELIST":
    $kodebarang = $_POST['kodebarang'];

    $stmt = $pdo->prepare("SELECT k.nama as namakategori,b.kodebarang as kodebarang, b.kodekategori as kodekategori, b.nama as nama, b.keterangan as keterangan, b.satuan as satuan, b.pricelistj1 as pricelistj1, b.pricelistj2 as pricelistj2, b.pricelistj3 as pricelistj3 FROM mbrg b inner join mkategori k on(k.kodekategori = b.kodekategori) WHERE b.kodebarang =:kodebarang");

    $stmt->execute(['kodebarang'=>$kodebarang]);
    $array = array();
    $barang = $stmt->fetch();
    $array['namakategori'] = $barang['namakategori'];
    $array['kodebarang'] = $barang['kodebarang'];
    $array['kodekategori'] = $barang['kodekategori'];
    $array['nama'] = $barang['nama'];
    $array['keterangan'] = $barang['keterangan'];
    $array['satuan'] = $barang['satuan'];
    $array['pricelistj1'] = $barang['pricelistj1'];
    $array['pricelistj2'] = $barang['pricelistj2'];
    $array['pricelistj3'] = $barang['pricelistj3'];

    $stmt = $pdo->prepare("SELECT * FROM gambarbarang WHERE NAMAGAMBAR LIKE :kodebarang AND STATUS ='AKTIF'");
    $stmt->execute(['kodebarang'=>$kodebarang."%"]);
    $id = 0;
    if($gambar = $stmt->fetch()){
        $array['gambar'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $array['counter'][$id] = $gambar['URUTAN'];
        $array['ID'][$id] = $gambar['ID'];
        /*$array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);*/
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $array['counter'][$id] = $gambar['URUTAN'];
            $array['ID'][$id] = $gambar['ID'];
            /*$array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);*/
            $id++;
        }
    } else {
        $array['gambar'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "SIMPAN_EDIT_PRICELIST":
    $kodekategori = $_POST['kodekategori'];
    $kodebarang = $_POST['kodebarang'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $satuan = $_POST['satuan'];
    $pricelistj1 = $_POST['pricelistj1'];
    $pricelistj2 = $_POST['pricelistj2'];
    $pricelistj3 = $_POST['pricelistj3'];
    $USERID = $_POST['USERID'];
    $array = array();
    $array['uploadgambar'] ="tidakada";
    $stmt = $pdo->prepare("UPDATE mbrg set kodekategori=:kodekategori,nama=:nama,keterangan=:keterangan,satuan=:satuan, pricelistj1=:pricelistj1, pricelistj2=:pricelistj2, pricelistj3=:pricelistj3, userid=:USERID WHERE kodebarang = :kodebarang");
    $stmt->execute(['kodekategori'=>$kodekategori, 'nama'=>$nama,'keterangan'=>$keterangan,'satuan'=>$satuan,'pricelistj1'=>$pricelistj1,'pricelistj2'=>$pricelistj2,'pricelistj3'=>$pricelistj3,'USERID'=>$USERID,'kodebarang'=>$kodebarang]);


    if(isset($_POST['namaFotoPricelist'])){
        $namaFotoPricelist = $_POST['namaFotoPricelist'];
    } else { $namaFotoPricelist = null; }

    if(isset($_POST['gambarHapus'])){
        $gambarHapus = $_POST['gambarHapus'];
    } else { $gambarHapus = null; }

    if(isset($_POST['listMerkMobilChecked'])){
        $listMerkMobilChecked = $_POST['listMerkMobilChecked'];
    } else { $listMerkMobilChecked = null; }
    

    if($gambarHapus != null){
        for($i = 0; $i<count($gambarHapus); $i++){
            /*$stmt = $pdo->prepare("DELETE FROM gambarbarang WHERE NAMAGAMBAR='".$kodebarang."-".$gambarHapus[$i]."'");
            $stmt->execute();*/
            $sql = "UPDATE gambarbarang set STATUS = 'TIDAK_AKTIF' WHERE ID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['ID'=>$gambarHapus[$i]]);

            if(file_exists("gambar_barang/".$kodebarang."-".$gambarHapus[$i].".jpg")){
                unlink("gambar_barang/".$kodebarang."-".$gambarHapus[$i].".jpg");
            }

        }
    }

    if($namaFotoPricelist != null){
        $array['uploadgambar'] = "ada";
        for($i = 0; $i<count($namaFotoPricelist); $i++){
            $sql = "SELECT * FROM gambarbarang ORDER BY ID DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $ID = 1;
            if($counter = $stmt->fetch()){
                $ID = $counter['ID']+1;
            }

            $sql = "SELECT * FROM gambarbarang WHERE KODEBARANG =:KODEBARANG ORDER BY URUTAN DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['KODEBARANG'=>$kodebarang]);
            $URUTAN = 1;
            if($counter = $stmt->fetch()){
                $URUTAN = $counter['URUTAN']+1;
            }

            $stmt = $pdo->prepare("INSERT INTO gambarbarang(KODEBARANG,NAMAGAMBAR,URUTAN,USERID) values(:KODEBARANG,:NAMAGAMBAR,:URUTAN,:USERID)");
            $stmt->execute([
                'KODEBARANG'=>$kodebarang,
                'NAMAGAMBAR'=>$kodebarang."-".$ID,
                'URUTAN'=>$URUTAN,
                'USERID'=>$USERID
            ]);
            $array['NAMAFOTOPRICELIST'][$i] = $kodebarang."-".$ID;
        }
    }

    $sql = "DELETE FROM mdbrg WHERE kodebarang=:kodebarang";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kodebarang'=>$kodebarang]);
    if(isset($listMerkMobilChecked)){
        for($i = 0; $i<count($listMerkMobilChecked); $i++){
            $sql = "INSERT INTO mdbrg (kodebarang,KODEMOBIL) values (:KODEBARANG,:KODEMOBIL)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODEBARANG' => $kodebarang,
                'KODEMOBIL' => $listMerkMobilChecked[$i]]);
        }
    }

    echo json_encode($array);
    break;


    //ga dipakai
    case "TAMPIL_SEMUA_LAPORAN_RENCANA_KUNJUNGAN":
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];

    if($KODEJABATAN == "OWNER"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmrencanakunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = dp1.KODEPELANGGAN) WHERE (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN = '".$USERID."') AND mp.KODECABANG =:KODECABANG";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($row = $stmt->fetch()){
        $DATETRANSACTION = $row['DATETRANSACTION'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row= $stmt->fetch()){
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){


        $arrayTampung= array_unique($array['DATETRANSACTION']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();

        for($i = 0; $i<count($arrayTampung); $i++){
            $array['DATETRANSACTION'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        foreach ($arrayTampung as $key => $value) {
            if($KODEJABATAN == "OWNER"){
                $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE DATE(DATETRANSACTION) = :VALUE AND mp.KODECABANG = :KODECABANG GROUP BY DATE(DATETRANSACTION)";
            } else {
                $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmrencanakunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE DATE(DATETRANSACTION) = :VALUE AND (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') AND mp.KODECABANG =:KODECABANG GROUP BY DATE(DATETRANSACTION)";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODECABANG'=>$KODECABANG,
                'VALUE'=>$value
            ]);
            if($row = $stmt->fetch()){
                $array['JUMLAHKUNJUNGAN'][$key] = $row['JUMLAHKUNJUNGAN'];
            } else {
                $array['JUMLAHKUNJUNGAN'][$key] = 0;
            }
        }

        if($counter == 1){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
        }
    }

    echo json_encode($array);
    break;

    case "TAMPIL_SEMUA_NOTA_LAPORAN_RENCANA_KUNJUNGAN":
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql= "";
    if($KODEJABATAN == "OWNER"){
        $sql.= "SELECT  mk.NAMA as NAMAKARYAWAN,k.NOMORBUKTI as NOMORBUKTI, k.DATETRANSACTION as DATETRANSACTION, k.KODESALESMAN as KODESALESMAN, p.NAMA as NAMAPELANGGAN,k.KETERANGAN as KETERANGAN FROM tmrencanakunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE k.DATETRANSACTION ='".date('Y-m-d',strtotime($DATETRANSACTION))."' AND p.KODECABANG='".$KODECABANG."'";
    } else {
        $sql.="SELECT mk.NAMA as NAMAKARYAWAN,k.NOMORBUKTI as NOMORBUKTI, k.DATETRANSACTION as DATETRANSACTION, k.KODESALESMAN as KODESALESMAN, p.NAMA as NAMAPELANGGAN,k.KETERANGAN as KETERANGAN FROM tmrencanakunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE k.DATETRANSACTION ='".date('Y-m-d',strtotime($DATETRANSACTION))."' AND (dp1.CP1 ='".$USERID."' OR k.KODEPELANGGAN ='".$USERID."') AND p.KODECABANG = '".$KODECABANG."'";
    }

    if(isset($_POST['FILTER'])){
        $FILTER = $_POST['FILTER'];
    } else { $FILTER = null; }

    if($FILTER !=null){
        $sql .= " AND k.KODESALESMAN ='".$FILTER."'";
    }
    $sql.=" ORDER BY k.NOMORBUKTI DESC";
    
    


    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $array['hasil'] = "";
    $id = 0;
    if($kunjungan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
        $array['NAMAKARYAWAN'][$id] = $kunjungan['NAMAKARYAWAN'];
        $array['DATETRANSACTION'][$id] = date('Y-m-d',strtotime($kunjungan['DATETRANSACTION']));
        $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
        $array['KETERANGAN'][$id] = $kunjungan['KETERANGAN'];
        $array['NAMAPELANGGAN'][$id] = $kunjungan['NAMAPELANGGAN'];
        $id++;
        while($kunjungan = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
            $array['NAMAKARYAWAN'][$id] = $kunjungan['NAMAKARYAWAN'];
            $array['DATETRANSACTION'][$id] = date('Y-m-d',strtotime($kunjungan['DATETRANSACTION']));
            $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
            $array['KETERANGAN'][$id] = $kunjungan['KETERANGAN'];
            $array['NAMAPELANGGAN'][$id] = $kunjungan['NAMAPELANGGAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;


    case "TAMPIL_DETAIL_MASTER_GPS_HARIAN":
    $KODESALESMAN = $_POST['KODESALESMAN'];
    $DATETRANSACTION = $_POST['DATETRANSACTION'];

    $sql = "SELECT k.WAKTUMASUK as WAKTUMASUK, k.DATETRANSACTION as DATETRANSACTION,p.ALAMAT as ALAMAT,k.NOMORBUKTI as NOMORBUKTI, k.LATITUDE AS LATITUDE, k.LONGITUDE as LONGITUDE,k.STATUSKUNJUNGAN as STATUSKUNJUNGAN,k.KETERANGAN as KETERANGAN, p.NAMA as NAMAPELANGGAN,mk.NAMA as NAMAKARYAWAN FROM tmkunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE k.NOMORBUKTI LIKE 'KS%' AND k.KODESALESMAN ='".$KODESALESMAN."' AND k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."'";
    //echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($gps = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $gps['NOMORBUKTI'];
        $array['LATITUDE'][$id] = $gps['LATITUDE'];
        $array['LONGITUDE'][$id] = $gps['LONGITUDE'];
        $array['STATUSKUNJUNGAN'][$id] = $gps['STATUSKUNJUNGAN'];
        $array['KETERANGAN'][$id] = $gps['KETERANGAN'];
        $array['NAMAPELANGGAN'][$id] = $gps['NAMAPELANGGAN'];
        $array['NAMAKARYAWAN'][$id] = $gps['NAMAKARYAWAN'];
        $array['ALAMAT'][$id] = $gps['ALAMAT'];
        $array['JAMMASUK'][$id] = date("Y-m-d H:i:s",strtotime($gps['WAKTUMASUK']));
        $array['JAMREPORT'][$id] = date("Y-m-d H:i:s",strtotime($gps['DATETRANSACTION']));
        $id++;
        while($gps = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $gps['NOMORBUKTI'];
            $array['LATITUDE'][$id] = $gps['LATITUDE'];
            $array['LONGITUDE'][$id] = $gps['LONGITUDE'];
            $array['STATUSKUNJUNGAN'][$id] = $gps['STATUSKUNJUNGAN'];
            $array['KETERANGAN'][$id] = $gps['KETERANGAN'];
            $array['NAMAPELANGGAN'][$id] = $gps['NAMAPELANGGAN'];
            $array['NAMAKARYAWAN'][$id] = $gps['NAMAKARYAWAN'];
            $array['ALAMAT'][$id] = $gps['ALAMAT'];
            $array['JAMMASUK'][$id] = date("Y-m-d H:i:s",strtotime($gps['WAKTUMASUK']));
            $array['JAMREPORT'][$id] = date("Y-m-d H:i:s",strtotime($gps['DATETRANSACTION']));
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_FOTO_KARYAWAN":
    $KODEKARYAWAN = $_POST['KODEKARYAWAN'];
    $array = array();

    if(file_exists("gambar_karyawan/".$KODEKARYAWAN."-karyawan.jpg")){
        $array['gambardiri'] = "ada";
    }

    if(file_exists("gambar_karyawan/".$KODEKARYAWAN."-ktp.jpg")){
        $array['gambarktp'] = "ada";
    }

    if(file_exists("gambar_karyawan/".$KODEKARYAWAN."-npwp.jpg")){
        $array['gambarnpwp'] = "ada";
    }
    echo json_encode($array);
    break;


    case "TAMPIL_SEMUA_LAPORAN_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $sql = "SELECT mk.NAMA as NAMAKARYAWAN,p.KODEPELANGGAN as KODEPELANGGAN, p.NAMA as NAMA, p.ALAMAT as ALAMAT, k.NOMORBUKTI as NOMORBUKTI, k.WAKTUMASUK as JAMMASUK, k.DATETRANSACTION as DATETRANSACTION, k.KODESALESMAN as KODESALESMAN, k.KETERANGAN as KETERANGAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.KESIMPULAN AS KESIMPULAN,k.TRANSFER as TRANSFER, k.GIRO as GIRO, k.TUNAI as TUNAI FROM mpelanggan p inner join tmkunjungan k on(k.KODEPELANGGAN = p.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) WHERE k.KODEPELANGGAN =:KODEPELANGGAN ORDER BY DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $array = array();
    $arrayObj =  (object) array();
    $id = 0;
    if($kunjungan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $kunjungan['KODEPELANGGAN'];
        $array['NAMA'][$id] = $kunjungan['NAMA'];
        $array['ALAMAT'][$id] = $kunjungan['ALAMAT'];
        $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
        $array['JAMMASUK'][$id] = $kunjungan['JAMMASUK'];
        $array['DATETRANSACTION'][$id] = $kunjungan['DATETRANSACTION'];
        $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
        $array['KETERANGAN'][$id] = $kunjungan['KETERANGAN'];
        $array['PERMINTAANKHUSUS'][$id] = $kunjungan['PERMINTAANKHUSUS'];
        $array['KESIMPULAN'][$id] = $kunjungan['KESIMPULAN'];
        $array['TRANSFER'][$id] = $kunjungan['TRANSFER'];
        $array['GIRO'][$id] = $kunjungan['GIRO'];
        $array['TUNAI'][$id] = $kunjungan['TUNAI'];
        $array['NAMAKARYAWAN'][$id] = $kunjungan['NAMAKARYAWAN'];
        $id++;
        while($kunjungan = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $kunjungan['KODEPELANGGAN'];
            $array['NAMA'][$id] = $kunjungan['NAMA'];
            $array['ALAMAT'][$id] = $kunjungan['ALAMAT'];
            $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
            $array['JAMMASUK'][$id] = $kunjungan['JAMMASUK'];
            $array['DATETRANSACTION'][$id] = $kunjungan['DATETRANSACTION'];
            $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
            $array['KETERANGAN'][$id] = $kunjungan['KETERANGAN'];
            $array['PERMINTAANKHUSUS'][$id] = $kunjungan['PERMINTAANKHUSUS'];
            $array['KESIMPULAN'][$id] = $kunjungan['KESIMPULAN'];
            $array['TRANSFER'][$id] = $kunjungan['TRANSFER'];
            $array['GIRO'][$id] = $kunjungan['GIRO'];
            $array['TUNAI'][$id] = $kunjungan['TUNAI'];
            $array['NAMAKARYAWAN'][$id] = $kunjungan['NAMAKARYAWAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if(isset($array['NOMORBUKTI'])){
        $counter = 0;
        for($i = 0;$i<count($array['NOMORBUKTI']);$i++){
            //echo $array['NOMORBUKTI'][$i];

            $stmt=$pdo->prepare("SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE '".$array['NOMORBUKTI'][$i]."%'");
            $stmt->execute();

            if($gambar = $stmt->fetch()){
                $array['hasilgambar'] = "ada";
                $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                $counter++;
                while($gambar = $stmt->fetch()){
                    $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                    $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                    $counter++;
                }
            } 
        }
    } 
    

    echo json_encode($array);
    break;


    case "AMBIL_GAMBARKUNJUNGAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id=0;
    if($gambar = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "AMBIL_GAMBAR_PRICELIST":
    $KODEBARANG = $_POST['KODEBARANG'];
    $sql = "SELECT * FROM gambarbarang WHERE KODEBARANG =:KODEBARANG AND STATUS='AKTIF'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEBARANG'=>$KODEBARANG]);
    $array = array();
    $id = 0;
    if($gambar = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['GAMBAR'][$id] = 
        [
            'KODEBARANG' => $gambar['KODEBARANG'],
            'ID' =>$gambar['ID'],
            'NAMAGAMBAR'=>$gambar['NAMAGAMBAR'],
            'URUTAN'=>$gambar['URUTAN'],
            'USERID'=>$gambar['USERID']
        ];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['GAMBAR'][$id] = 
            [
                'KODEBARANG' => $gambar['KODEBARANG'],
                'ID' =>$gambar['ID'],
                'NAMAGAMBAR'=>$gambar['NAMAGAMBAR'],
                'URUTAN'=>$gambar['URUTAN'],
                'USERID'=>$gambar['USERID']
            ];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "AMBIL_GAMBAR_RENCANA_KUNJUNGAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM gambarrencanakunjungan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id=0;
    if($gambar = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "AMBIL_GAMBAR_KUNJUNGAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id=0;
    if($gambar = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_DETAIL_RENCANA_VS_KUNJUNGAN":
    $DATETRANSACTION =$_POST['DATETRANSACTION'];
    $KODESALESMAN = $_POST['KODESALESMAN'];
    $array = array();
    $id = 0;
    $array['KESIMPULAN'] = "";
    $array['PERMINTAANKHUSUS'] = array();
    $array['KETERANGANKUNJUNGAN'] = array();
    $array['KESIMPULAN'] = array();
    $array['KODEPELANGGAN'] = array();
    $array['STATUSBERKUNJUNG'] = array();
    $array['KETERANGAN'] = array();
    $sql = "SELECT rk.KODEPELANGGAN as KODEPELANGGAN,rk.KODESALESMAN AS KODESALESMAN, p.NAMA AS NAMAPELANGGAN, rk.NOMORBUKTI as NOMORBUKTI, rk.KETERANGAN as KETERANGAN, k.NAMA as NAMAKARYAWAN FROM tmrencanakunjungan rk inner join mpelanggan p on(p.KODEPELANGGAN = rk.KODEPELANGGAN) inner join mkaryawan k on(k.KODEKARYAWAN = rk.KODESALESMAN) WHERE rk.DATETRANSACTION=:DATETRANSACTION AND rk.KODESALESMAN=:KODESALESMAN";
    //echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['DATETRANSACTION' =>date("Y-m-d",strtotime($DATETRANSACTION)),'KODESALESMAN'=>$KODESALESMAN]);
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
        $array['KODESALESMAN'][$id] =$rencana['KODESALESMAN'];
        $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
        $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
        $array['NAMAKARYAWAN'][$id] = $rencana['NAMAKARYAWAN'];
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
            $array['KODESALESMAN'][$id] =$rencana['KODESALESMAN'];
            $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
            $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
            $array['NAMAKARYAWAN'][$id] = $rencana['NAMAKARYAWAN'];
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }



    if(isset($array['NOMORBUKTI'])){
        for($i = 0; $i <count($array['NOMORBUKTI']); $i++){
            $sql = "SELECT * FROM tmkunjungan WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND KODESALESMAN =:KODESALESMAN AND KODEPELANGGAN=:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['KODESALESMAN'=>$array['KODESALESMAN'][$i], 'KODEPELANGGAN'=>$array['KODEPELANGGAN'][$i]]);
            if($kunjungan = $stmt->fetch()){
                $array['STATUSBERKUNJUNG'][$i] = "SESUAI RENCANA";
                $array['KESIMPULAN'][$i] = $kunjungan['KESIMPULAN'];
                $array['PERMINTAANKHUSUS'][$i] = $kunjungan['PERMINTAANKHUSUS'];
                $array['KESIMPULAN'][$i] = $kunjungan['KESIMPULAN'];
                $array['KETERANGANKUNJUNGAN'][$i] = $kunjungan['KETERANGAN'];
            } else {
                $array['STATUSBERKUNJUNG'][$i] = "TIDAK SESUAI";
            }
        }
    }

    $sql = "SELECT distinct k.KESIMPULAN as KESIMPULAN, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS,k.KODEPELANGGAN as KODEPELANGGAN,k.KODESALESMAN AS KODESALESMAN, p.NAMA AS NAMAPELANGGAN, k.NOMORBUKTI as NOMORBUKTI, k.KETERANGAN as KETERANGAN, mk.NAMA as NAMAKARYAWAN FROM tmkunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) left join tmrencanakunjungan rk on (rk.KODEPELANGGAN = p.KODEPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN =:KODESALESMAN";

    if(isset($array['KODEPELANGGAN'])){
        for($i = 0; $i<count($array['KODEPELANGGAN']); $i++){
            $sql.=" AND k.KODEPELANGGAN !='".$array['KODEPELANGGAN'][$i]."'";
        }
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODESALESMAN'=>$KODESALESMAN]);


    if($kunjungan = $stmt->fetch()){
        $array['hasilkunjungan'] = "ada";
        $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
        $array['KODESALESMAN'][$id] =$kunjungan['KODESALESMAN'];
        $array['NAMAPELANGGAN'][$id] = $kunjungan['NAMAPELANGGAN'];
        $array['KETERANGANKUNJUNGAN'][$id] = $kunjungan['KETERANGAN'];
        $array['NAMAKARYAWAN'][$id] = $kunjungan['NAMAKARYAWAN'];
        $array['KODEPELANGGAN'][$id] = $kunjungan['KODEPELANGGAN'];
        $array['KESIMPULAN'][$id] = $kunjungan['KESIMPULAN'];
        $array['PERMINTAANKHUSUS'][$id] = $kunjungan['PERMINTAANKHUSUS'];
        $id++;
        while($kunjungan = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
            $array['KODESALESMAN'][$id] =$kunjungan['KODESALESMAN'];
            $array['NAMAPELANGGAN'][$id] = $kunjungan['NAMAPELANGGAN'];
            $array['KETERANGANKUNJUNGAN'][$id] = $kunjungan['KETERANGAN'];
            $array['NAMAKARYAWAN'][$id] = $kunjungan['NAMAKARYAWAN'];
            $array['KODEPELANGGAN'][$id] = $kunjungan['KODEPELANGGAN'];
            $array['KESIMPULAN'][$id] = $kunjungan['KESIMPULAN'];
            $array['PERMINTAANKHUSUS'][$id] = $kunjungan['PERMINTAANKHUSUS'];
            $id++;
        }
    } else {
        $array['hasilkunjungan'] = "tidakada";
    }


    echo json_encode($array);
    break;

    case "TAMPIL_DETAIL_LAPORAN_KUNJUNGAN_FOLLOWUP_PER_STAFF":
    if(isset($_POST['KODEJABATAN'])){
        $KODEJABATAN = $_POST['KODEJABATAN'];
    } else { $KODEJABATAN = null; }

    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATANUSERID = $_POST['KODEJABATANUSERID'];

    if($KODEJABATANUSERID == "OWNER"){
        $sql = "SELECT count(*) as JUMLAHPERMINTAANKHUSUS,tk.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, mk.NAMA as NAMAKARYAWAN, tk.KODESALESMAN as KODEKARYAWAN, count(*) as TOTALJUMLAHKUNJUNGAN FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) left join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = tk.PERMINTAANKHUSUS) WHERE";
    } else {
        $sql = "SELECT count(*) as JUMLAHPERMINTAANKHUSUS,tk.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, mk.NAMA as NAMAKARYAWAN, tk.KODESALESMAN as KODEKARYAWAN, count(*) as TOTALJUMLAHKUNJUNGAN FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) left join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = tk.PERMINTAANKHUSUS) WHERE mk.KODECABANG ='".$KODECABANG."'";
    }
    /*$sql = "SELECT count(*) as JUMLAHPERMINTAANKHUSUS,tk.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, mk.NAMA as NAMAKARYAWAN, tk.KODESALESMAN as KODEKARYAWAN, count(*) as TOTALJUMLAHKUNJUNGAN FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) left join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = tk.PERMINTAANKHUSUS) WHERE mk.KODECABANG ='".$KODECABANG."'";*/

    


    if($KODEJABATAN!="SEMUA"){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " KODEJABATAN='".$KODEJABATAN."'";
        } else {
            $sql .= " AND KODEJABATAN ='".$KODEJABATAN."'";
        }
    }

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION>='".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION>='".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime($TANGGALSAMPAI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI)))."'";
        }
    }
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .=" GROUP BY tk.KODESALESMAN,tk.PERMINTAANKHUSUS ORDER BY tk.KODESALESMAN";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($laporan = $stmt->fetch()){
        $array['hasil'] = "ada";
        //$array['NAMAKARYAWAN'][$id] = $laporan['NAMAKARYAWAN'];
        $array['KODEKARYAWAN'][$id] = $laporan['KODEKARYAWAN'];
        $array['PERMINTAANKHUSUS'][$id] = $laporan['PERMINTAANKHUSUS'];
        $array['JUMLAHPERMINTAANKHUSUS'][$id] = $laporan['JUMLAHPERMINTAANKHUSUS'];
        $id++;
        while($laporan = $stmt->fetch()){
            //$array['NAMAKARYAWAN'][$id] = $laporan['NAMAKARYAWAN'];

            $array['KODEKARYAWAN'][$id] = $laporan['KODEKARYAWAN'];
            $array['PERMINTAANKHUSUS'][$id] = $laporan['PERMINTAANKHUSUS'];
            $array['JUMLAHPERMINTAANKHUSUS'][$id] = $laporan['JUMLAHPERMINTAANKHUSUS'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    /*$sql = "SELECT mk.NAMA as NAMAKARYAWAN, tk.KODESALESMAN as KODEKARYAWAN, count(*) as TOTALJUMLAHKUNJUNGAN FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) WHERE mk.KODECABANG ='".$KODECABANG."'";*/
    
    if($KODEJABATANUSERID == "OWNER"){
        $sql = "SELECT count(*) as JUMLAHPERMINTAANKHUSUS,tk.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, mk.NAMA as NAMAKARYAWAN, tk.KODESALESMAN as KODEKARYAWAN, count(*) as TOTALJUMLAHKUNJUNGAN FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) left join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = tk.PERMINTAANKHUSUS) WHERE";
    } else {
        $sql = "SELECT count(*) as JUMLAHPERMINTAANKHUSUS,tk.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, mk.NAMA as NAMAKARYAWAN, tk.KODESALESMAN as KODEKARYAWAN, count(*) as TOTALJUMLAHKUNJUNGAN FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) left join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = tk.PERMINTAANKHUSUS) WHERE mk.KODECABANG ='".$KODECABANG."'";
    }

    if($KODEJABATAN!="SEMUA"){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " KODEJABATAN='".$KODEJABATAN."'";
        } else {
            $sql .= " AND KODEJABATAN ='".$KODEJABATAN."'";
        }
    }

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION>='".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION>='".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime($TANGGALSAMPAI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI)))."'";
        }
    }
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .=" GROUP BY tk.KODESALESMAN ORDER BY tk.KODESALESMAN";
    
    //echo $sql;

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    //$array = array();
    $id = 0;
    if($laporan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMAKARYAWANUTAMA'][$id] = $laporan['NAMAKARYAWAN'];
        $array['KODEKARYAWANUTAMA'][$id] = $laporan['KODEKARYAWAN'];
        $array['TOTALJUMLAHKUNJUNGAN'][$id] = $laporan['TOTALJUMLAHKUNJUNGAN'];
        
        $id++;
        while($laporan = $stmt->fetch()){
            $array['NAMAKARYAWANUTAMA'][$id] = $laporan['NAMAKARYAWAN'];
            $array['KODEKARYAWANUTAMA'][$id] = $laporan['KODEKARYAWAN'];
            $array['TOTALJUMLAHKUNJUNGAN'][$id] = $laporan['TOTALJUMLAHKUNJUNGAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;




    case "TAMPIL_LAPORAN_PELANGGAN_PER_TANGGAL":
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    $KODECABANG = $_POST['KODECABANG'];


    /*$sql = "SELECT tk.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, mk.NAMA as NAMAKARYAWAN,mpel.NAMA as NAMA,tk.WAKTUMASUK AS JAMMASUK,tk.DATETRANSACTION AS JAMREPORT,tk.KETERANGAN as KETERANGAN, tk.KESIMPULAN as KESIMPULAN, tk.PERMINTAANKHUSUS as PERMINTAANKHUSUS FROM tmkunjungan tk INNER join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) inner join mpermintaankhusus mp on(mp.NAMA = tk.PERMINTAANKHUSUS) left join mpelanggan mpel on(mpel.KODEPELANGGAN = tk.KODEPELANGGAN) right join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE";

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " dp1.CP1>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND dp1.CP1>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " dp1.CP1<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND dp1.CP1<='".$KODESALESMANSAMPAI."'";
        }
    }*/

    $sql = "SELECT tk.STATUSKUNJUNGAN AS STATUSKUNJUNGAN, tk.NOMORBUKTI as NOMORBUKTIKUNJUNGAN, mk.NAMA as NAMAKARYAWAN,mpel.NAMA as NAMA,tk.WAKTUMASUK AS JAMMASUK,tk.DATETRANSACTION AS JAMREPORT,tk.KETERANGAN as KETERANGAN, tk.KESIMPULAN as KESIMPULAN, tk.PERMINTAANKHUSUS as PERMINTAANKHUSUS FROM tmkunjungan tk INNER join mpelanggan as mpel on(mpel.KODEPELANGGAN = tk.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) inner join mpermintaankhusus mp on(mp.NAMA = tk.PERMINTAANKHUSUS) WHERE mpel.KODECABANG =:KODECABANG";

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " tk.KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND tk.KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND tk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION>='".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION>='".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime($TANGGALSAMPAI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI)))."'";
        }
    }
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .="GROUP BY tk.NOMORBUKTI ORDER BY tk.DATETRANSACTION DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'][$id] = $row['NAMA'];
        $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
        $array['JAMMASUK'][$id] = $row['JAMMASUK'];
        $array['JAMREPORT'][$id] = $row['JAMREPORT'];
        $array['KETERANGAN'][$id] = $row['KETERANGAN'];
        $array['KESIMPULAN'][$id] = $row['KESIMPULAN'];
        $array['PERMINTAANKHUSUS'][$id] = $row['PERMINTAANKHUSUS'];
        $array['NAMAKARYAWAN'][$id] = $row['NAMAKARYAWAN'];
        $array['STATUSKUNJUNGAN'][$id] = $row['STATUSKUNJUNGAN'];
        $id++;
        $array['TOTJUMLAH'] = $id;
        while($row = $stmt->fetch()){
            $array['NAMA'][$id] = $row['NAMA'];
            $array['NOMORBUKTIKUNJUNGAN'][$id] = $row['NOMORBUKTIKUNJUNGAN'];
            $array['JAMMASUK'][$id] = $row['JAMMASUK'];
            $array['JAMREPORT'][$id] = $row['JAMREPORT'];
            $array['KETERANGAN'][$id] = $row['KETERANGAN'];
            $array['KESIMPULAN'][$id] = $row['KESIMPULAN'];
            $array['PERMINTAANKHUSUS'][$id] = $row['PERMINTAANKHUSUS'];
            $array['NAMAKARYAWAN'][$id] = $row['NAMAKARYAWAN'];
            $array['STATUSKUNJUNGAN'][$id] = $row['STATUSKUNJUNGAN'];
            $id++;
            $array['TOTJUMLAH'] = $id;
        }
    } else {
        $array['hasil'] = "tidakada";
        $array['TOTJUMLAH'] = $id;
    }
    if(isset($array['NOMORBUKTIKUNJUNGAN'])){
        $counter = 0;
        for($i = 0;$i<count($array['NOMORBUKTIKUNJUNGAN']);$i++){

            $stmt=$pdo->prepare("SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE '".$array['NOMORBUKTIKUNJUNGAN'][$i]."%'");
            $stmt->execute();

            if($gambar = $stmt->fetch()){
                $array['hasilgambar'] = "ada";
                $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                $counter++;
                while($gambar = $stmt->fetch()){
                    $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                    $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                    $counter++;
                }
            }
        }
    }

    echo json_encode($array);
    break;

    case "TAMPIL_NAMA_KARYAWAN":
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM mkaryawan WHERE KODEKARYAWAN =:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    if($karyawan = $stmt->fetch()){
        echo $karyawan['NAMA'];
    } else {
        echo "Tidak Ada";
    }
    break;

    case "TAMPIL_POPUP_PELANGGAN_BERDASARKAN_KARYAWAN":
    $KODEKARYAWANDARI = $_POST['KODEKARYAWANDARI'];
    $KODEKARYAWANSAMPAI = $_POST['KODEKARYAWANSAMPAI'];
    $sql = "SELECT * FROM mpelanggan WHERE USERID >=:KODEKARYAWANDARI AND USERID<=:KODEKARYAWANSAMPAI ORDER BY KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKARYAWANDARI'=>$KODEKARYAWANDARI,'KODEKARYAWANSAMPAI'=>$KODEKARYAWANSAMPAI]);
    $array = array();
    $id = 0;
    if($pelanggan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $pelanggan['KODEPELANGGAN'];
        $array['NAMA'][$id] = $pelanggan['NAMA'];
        $id++;
        while($pelanggan = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $pelanggan['KODEPELANGGAN'];
            $array['NAMA'][$id] = $pelanggan['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "TAMPIL_PEMETAAN_PELANGGAN":
    $array = array();
    $id = 0;
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['KODEPELANGGANDARI'])){
        $KODEPELANGGANDARI = $_POST['KODEPELANGGANDARI'];
    } else { $KODEPELANGGANDARI = null; }

    if(isset($_POST['KODEPELANGGANSAMPAI'])){
        $KODEPELANGGANSAMPAI = $_POST['KODEPELANGGANSAMPAI'];
    } else { $KODEPELANGGANSAMPAI = null; }

    if(isset($_POST['TANGGALPEMETAANDARI'])){
        $TANGGALPEMETAANDARI = $_POST['TANGGALPEMETAANDARI'];
    } else { $TANGGALPEMETAANDARI = null; }

    if(isset($_POST['TANGGALPEMETAANSAMPAI'])){
        $TANGGALPEMETAANSAMPAI = $_POST['TANGGALPEMETAANSAMPAI'];
    } else { $TANGGALPEMETAANSAMPAI = null; }

    $KODECABANG = $_POST['KODECABANG'];


    //$sql = "SELECT * FROM mpelanggan WHERE !isnull(LATITUDE) AND !isnull(LONGITUDE) AND KODECABANG='".$KODECABANG."'";
    $sql = "SELECT tk.NOMORBUKTI AS NOMORBUKTI, tk.STATUSKUNJUNGAN AS STATUSKUNJUNGAN, mp.ALAMAT AS ALAMAT, mp.KODECABANG AS KODECABANG, tk.DATETRANSACTION AS DATETRANSACTION, mk.NAMA AS NAMAKARYAWAN, mp.NAMA AS NAMAPELANGGAN, tk.KODESALESMAN AS KODESALESMAN, mp.KODEPELANGGAN AS KODEPELANGGAN, tk.LATITUDE AS LATITUDE, tk.LONGITUDE AS LONGITUDE FROM tmkunjungan tk INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE !isnull(tk.LATITUDE) AND !isnull(tk.LONGITUDE) AND tk.NOMORBUKTI NOT LIKE 'FA%' AND mp.KODECABANG ='".$KODECABANG."'";
    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " tk.KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND tk.KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND tk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($KODEPELANGGANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " mp.KODEPELANGGAN>='".$KODEPELANGGANDARI."'";
        } else {
            $sql .= " AND mp.KODEPELANGGAN>='".$KODEPELANGGANDARI."'";
        }
    }

    if($KODEPELANGGANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " mp.KODEPELANGGAN<='".$KODEPELANGGANSAMPAI."'";
        } else {
            $sql .= " AND mp.KODEPELANGGAN<='".$KODEPELANGGANSAMPAI."'";
        }
    }

    if($TANGGALPEMETAANDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALPEMETAANDARI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALPEMETAANDARI))."'";
        }
    }

    if($TANGGALPEMETAANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " tk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALPEMETAANSAMPAI))."'";
        } else {
            $sql .= " AND tk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALPEMETAANSAMPAI))."'";
        }
    }

    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }

    $sql.= " ORDER BY tk.DATETRANSACTION ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($pelanggan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['USERID'][$id] = $pelanggan['KODESALESMAN'];
        $array['STATUSKUNJUNGAN'][$id] = $pelanggan['STATUSKUNJUNGAN'];
        $array['LATITUDE'][$id] = $pelanggan['LATITUDE'];
        $array['LONGITUDE'][$id] = $pelanggan['LONGITUDE'];
        $array['KODEPELANGGAN'][$id] = $pelanggan['KODEPELANGGAN'];
        $array['NAMA'][$id] = $pelanggan['NAMAPELANGGAN'];
        $array['ALAMAT'][$id] = $pelanggan['ALAMAT'];
        $array['DATETRANSACTION'][$id] = $pelanggan['DATETRANSACTION'];
        if(substr($pelanggan['NOMORBUKTI'], 0, 2) == "KS"){
            $array['JENISKUNJUNGAN'][$id] = "kunjungan";
        } else if(substr($pelanggan['NOMORBUKTI'], 0, 2) == "PF"){
            $array['JENISKUNJUNGAN'][$id] = "kiriman";
        } else if(substr($pelanggan['NOMORBUKTI'], 0, 2) == "FC") {
            $array['JENISKUNJUNGAN'][$id] = "collector";
        }
        $id++;
        while($pelanggan = $stmt->fetch()){
            $array['LATITUDE'][$id] = $pelanggan['LATITUDE'];
            $array['USERID'][$id] = $pelanggan['KODESALESMAN'];
            $array['STATUSKUNJUNGAN'][$id] = $pelanggan['STATUSKUNJUNGAN'];
            $array['LONGITUDE'][$id] = $pelanggan['LONGITUDE'];
            $array['KODEPELANGGAN'][$id] = $pelanggan['KODEPELANGGAN'];
            $array['NAMA'][$id] = $pelanggan['NAMAPELANGGAN'];
            $array['ALAMAT'][$id] = $pelanggan['ALAMAT'];
            $array['DATETRANSACTION'][$id] = $pelanggan['DATETRANSACTION'];
            if(substr($pelanggan['NOMORBUKTI'], 0, 2) == "KS"){
                $array['JENISKUNJUNGAN'][$id] = "kunjungan";
            } else if(substr($pelanggan['NOMORBUKTI'], 0, 2) == "PF"){
                $array['JENISKUNJUNGAN'][$id] = "kiriman";
            } else if(substr($pelanggan['NOMORBUKTI'], 0, 2) == "FC") {
                $array['JENISKUNJUNGAN'][$id] = "collector";
            }
            $id++;  
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_KUNJUNGAN_UPDATE_GPS_PELANGGAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $array = array();
    $id = 0;
    $sql = "SELECT mp.NAMA as NAMA,tk.LATITUDE as LATITUDE, tk.LONGITUDE as LONGITUDE, tk.KODESALESMAN as KODESALESMAN, mp.ALAMAT as ALAMAT, tk.NOMORBUKTI AS NOMORBUKTI FROM tmkunjungan tk inner join mpelanggan mp on(tk.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE !ISNULL(tk.LATITUDE)  AND !ISNULL(tk.LONGITUDE) AND tk.LONGITUDE!=0 AND tk.LATITUDE !=0 AND tk.KODEPELANGGAN=:KODEPELANGGAN";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($kunjungan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['LATITUDE'][$id] = $kunjungan['LATITUDE'];
        $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
        $array['LONGITUDE'][$id] = $kunjungan['LONGITUDE'];
        $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
        $array['ALAMAT'][$id] = $kunjungan['ALAMAT'];
        $array['NAMA'][$id] = $kunjungan['NAMA'];
        $id++;
        while($kunjungan = $stmt->fetch()){
            $array['LATITUDE'][$id] = $kunjungan['LATITUDE'];
            $array['KODESALESMAN'][$id] = $kunjungan['KODESALESMAN'];
            $array['LONGITUDE'][$id] = $kunjungan['LONGITUDE'];
            $array['NOMORBUKTI'][$id] = $kunjungan['NOMORBUKTI'];
            $array['ALAMAT'][$id] = $kunjungan['ALAMAT'];
            $array['NAMA'][$id] = $kunjungan['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($pelanggan = $stmt->fetch()){
        $array['LATITUDEPELANGGAN'] = $pelanggan['LATITUDE'];
        $array['LONGITUDEPELANGGAN'] = $pelanggan['LONGITUDE'];
    }

    echo json_encode($array);
    break;

    case "UPDATE_GPS_PELANGGAN_DETAIL":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $LATITUDE = $_POST['LATITUDE'];
    $LONGITUDE = $_POST['LONGITUDE'];
    $sql = "UPDATE mpelanggan set LATITUDE=:LATITUDE,LONGITUDE=:LONGITUDE,VERGPS = 'T' WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['LATITUDE'=>$LATITUDE,'LONGITUDE'=>$LONGITUDE,'KODEPELANGGAN'=>$KODEPELANGGAN]);

    echo "berhasil";
    break;

    case "TAMPIL_HASIL_LAPORAN_KIRIMAN_COLLECTOR":
    $HALAMAN = $_POST['HALAMAN'];
    $array = array();
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    $KODECABANG =$_POST['KODECABANG'];

    $sql = "SELECT k.KESIMPULAN as KESIMPULAN,k.STATUS as STATUS,mku.NAMA as NAMAKARYAWANUSERID, k.NOMORBUKTI as NOMORBUKTI, k.WAKTUMASUK as JAMMASUK ,k.DATETRANSACTION as DATETRANSACTION,mk.NAMA as NAMAKARYAWANDRIVER, mpel.NAMA as NAMAPELANGGAN, k.KETERANGAN as KETERANGAN,k.PERMINTAANKHUSUS as NAMAPERMINTAAN FROM tmkunjungan k INNER join mpelanggan as mpel on(mpel.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN)  left join mkaryawan mku on(mku.KODEKARYAWAN = k.USERID) WHERE mpel.KODECABANG ='".$KODECABANG."'";

    if($HALAMAN == "LAPORAN_KIRIMAN"){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'PF%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'PF%'";
        } 
    } else {
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'FC%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'FC%'";
        } 
    }

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " k.KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND k.KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " k.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND k.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " k.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND k.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " k.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days',strtotime($TANGGALSAMPAI)))."'";
        } else {
            $sql .= " AND k.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days',strtotime($TANGGALSAMPAI)))."'";
        }
    }

    //echo $sql;
    //$sql .= " AND k.DATETRANSACTION<='".date('Y-m-d',strtotime('+1days',strtotime($TANGGALSAMPAI)))."'";
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .=" GROUP BY k.NOMORBUKTI ORDER BY k.DATETRANSACTION DESC";


    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
        $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
        $array['JAMMASUK'][$id] = $rencana['JAMMASUK'];
        $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
        $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
        $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
        $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
        $array['NAMAPERMINTAAN'][$id] = $rencana['NAMAPERMINTAAN'];
        $array['STATUS'][$id] = $rencana['STATUS'];
        $array['KESIMPULAN'][$id] = $rencana['KESIMPULAN'];
        $id++;
        $array['TOTJUMLAH'] = $id;
        while($rencana = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
            $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
            $array['JAMMASUK'][$id] = $rencana['JAMMASUK'];
            $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
            $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
            $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
            $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
            $array['NAMAPERMINTAAN'][$id] = $rencana['NAMAPERMINTAAN'];
            $array['STATUS'][$id] = $rencana['STATUS'];
            $array['KESIMPULAN'][$id] = $rencana['KESIMPULAN'];
            $id++;
            $array['TOTJUMLAH'] = $id;
        }
    } else {
        $array['hasil'] = "tidakada";
        $array['TOTJUMLAH'] = $id;
    }

    if(isset($array['NOMORBUKTI'])){
        $counter = 0;
        for($i = 0;$i<count($array['NOMORBUKTI']);$i++){
            //echo $array['NOMORBUKTI'][$i];

            $stmt=$pdo->prepare("SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE '".$array['NOMORBUKTI'][$i]."%'");
            $stmt->execute();

            if($gambar = $stmt->fetch()){
                $array['hasilgambar'] = "ada";
                $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                $counter++;
                while($gambar = $stmt->fetch()){
                    $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                    $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                    $counter++;
                }
            } 
        }
    } 

    echo json_encode($array);
    break;

    case "TAMPIL_HASIL_LAPORAN_RENCANA_KIRIMAN_COLLECTOR":
    $HALAMAN = $_POST['HALAMAN'];
    $array = array();
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    $sql = "SELECT rk.STATUS as STATUS,mku.NAMA as NAMAKARYAWANUSERID, rk.NOMORBUKTI as NOMORBUKTI, rk.DATETRANSACTION as DATETRANSACTION,mk.NAMA as NAMAKARYAWANDRIVER, mpel.NAMA as NAMAPELANGGAN, rk.KETERANGAN as KETERANGAN, mp.NAMA as NAMAPERMINTAAN FROM tmrencanakunjungan rk INNER join mpelanggan as mpel on(mpel.KODEPELANGGAN = rk.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) inner join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = rk.PERMINTAANKHUSUS) left join mkaryawan mku on(mku.KODEKARYAWAN = rk.USERID) WHERE";

    if($HALAMAN == "LAPORAN_RENCANA_KIRIMAN"){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'RF%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'RF%'";
        } 
    } else {
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'RC%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'RC%'";
        } 
    }

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);
        
        if($tampungSql == "WHERE"){
            $sql .= " rk.KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND rk.KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND rk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND rk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALSAMPAI))."'";
        } else {
            $sql .= " AND rk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALSAMPAI))."'";
        }
    }
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .=" GROUP BY rk.NOMORBUKTI ORDER BY rk.DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
        $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
        $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
        $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
        $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
        $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
        $array['NAMAPERMINTAAN'][$id] = $rencana['NAMAPERMINTAAN'];
        $array['STATUS'][$id] = $rencana['STATUS'];
        $id++;
        $array['TOTJUMLAH'] = $id;
        while($rencana = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
            $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
            $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
            $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
            $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
            $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
            $array['NAMAPERMINTAAN'][$id] = $rencana['NAMAPERMINTAAN'];
            $array['STATUS'][$id] = $rencana['STATUS'];
            $id++;
            $array['TOTJUMLAH'] = $id;
        }
    } else {
        $array['hasil'] = "tidakada";
        $array['TOTJUMLAH'] = $id;
    }

    if(isset($array['NOMORBUKTI'])){
        $counter = 0;
        for($i = 0;$i<count($array['NOMORBUKTI']);$i++){
            //echo $array['NOMORBUKTI'][$i];

            $stmt=$pdo->prepare("SELECT * FROM gambarrencanakunjungan WHERE NAMAGAMBAR LIKE '".$array['NOMORBUKTI'][$i]."%'");
            $stmt->execute();

            if($gambar = $stmt->fetch()){
                $array['hasilgambar'] = "ada";
                $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                $counter++;
                while($gambar = $stmt->fetch()){
                    $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                    $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11);
                    $counter++;
                }
            } 
        }
    } 

    echo json_encode($array);
    break;

    case "TAMPIL_POPUP_KARYAWAN_CLASS":
    $array = array();
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT p.USERID as KODEKARYAWAN, mk.NAMA as NAMA FROM pemakai p inner join mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE p.KODEJABATAN =:KODEJABATAN AND p.KODECABANG=:KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEJABATAN'=>$KODEJABATAN,'KODECABANG'=>$KODECABANG]);
    $id = 0;
    if($karyawan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEKARYAWAN'][$id] = $karyawan['KODEKARYAWAN'];
        $array['NAMA'][$id] = $karyawan['NAMA'];
        $id++;
        while($karyawan = $stmt->fetch()){
            $array['KODEKARYAWAN'][$id] = $karyawan['KODEKARYAWAN'];
            $array['NAMA'][$id] = $karyawan['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;


    case "TAMPIL_POSTING_HAPUS_KIRIMAN_COLLECTOR":
    $HALAMAN = $_POST['HALAMAN'];
    $array = array();
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    $sql = "SELECT mku.NAMA as NAMAKARYAWANUSERID, rk.NOMORBUKTI as NOMORBUKTI, rk.DATETRANSACTION as DATETRANSACTION,mk.NAMA as NAMAKARYAWANDRIVER, mpel.NAMA as NAMAPELANGGAN, rk.KETERANGAN as KETERANGAN FROM tmkunjungan rk INNER join mpelanggan as mpel on(mpel.KODEPELANGGAN = rk.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) left join mkaryawan mku on(mku.KODEKARYAWAN = rk.USERID) WHERE rk.STATUS ='OPEN'";

    /*$sql = "SELECT k.KESIMPULAN as KESIMPULAN,k.STATUS as STATUS,mku.NAMA as NAMAKARYAWANUSERID, k.NOMORBUKTI as NOMORBUKTI, k.WAKTUMASUK as JAMMASUK ,k.DATETRANSACTION as DATETRANSACTION,mk.NAMA as NAMAKARYAWANDRIVER, mpel.NAMA as NAMAPELANGGAN, k.KETERANGAN as KETERANGAN,k.PERMINTAANKHUSUS as NAMAPERMINTAAN FROM tmkunjungan k INNER join mpelanggan as mpel on(mpel.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN)  left join mkaryawan mku on(mku.KODEKARYAWAN = k.USERID) WHERE";*/

    if($HALAMAN == "LAPORAN_KIRIMAN"){
        $tampungSql = substr($sql,-5);

        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'PF%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'PF%'";
        } 
    } else {
        $tampungSql = substr($sql,-5);

        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'FC%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'FC%'";
        } 
    }

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);

        if($tampungSql == "WHERE"){
            $sql .= " rk.KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND rk.KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND rk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND rk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALSAMPAI))."'";
        } else {
            $sql .= " AND rk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALSAMPAI))."'";
        }
    }
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .=" GROUP BY rk.NOMORBUKTI ORDER BY rk.DATETRANSACTION DESC";

    //echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
        $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
        $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
        $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
        $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
        $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
        $id++;
        $array['TOTJUMLAH'] = $id;
        while($rencana = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
            $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
            $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
            $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
            $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
            $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
            $id++;
            $array['TOTJUMLAH'] = $id;
        }
    } else {
        $array['hasil'] = "tidakada";
        $array['TOTJUMLAH'] = $id;
    }

    echo json_encode($array);
    break;



    case "TAMPIL_POSTING_HAPUS_SEMUA":
    $HALAMAN = $_POST['HALAMAN'];
    $array = array();
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    $sql = "SELECT mku.NAMA as NAMAKARYAWANUSERID, rk.NOMORBUKTI as NOMORBUKTI, rk.DATETRANSACTION as DATETRANSACTION,mk.NAMA as NAMAKARYAWANDRIVER, mpel.NAMA as NAMAPELANGGAN, rk.KETERANGAN as KETERANGAN, mp.NAMA as NAMAPERMINTAAN FROM tmrencanakunjungan rk INNER join mpelanggan as mpel on(mpel.KODEPELANGGAN = rk.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) inner join mpermintaankhusus mp on(mp.KODEPERMINTAANKHUSUS = rk.PERMINTAANKHUSUS) left join mkaryawan mku on(mku.KODEKARYAWAN = rk.USERID) WHERE rk.STATUS ='OPEN'";

    if($HALAMAN == "LAPORAN_RENCANA_KIRIMAN"){
        $tampungSql = substr($sql,-5);

        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'RF%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'RF%'";
        } 
    } else {
        $tampungSql = substr($sql,-5);

        if($tampungSql == "WHERE"){
            $sql .= " NOMORBUKTI LIKE 'RC%'";
        } else {
            $sql .= " AND NOMORBUKTI LIKE 'RC%'";
        } 
    }

    if($KODESALESMANDARI){
        $tampungSql = substr($sql,-5);

        if($tampungSql == "WHERE"){
            $sql .= " rk.KODESALESMAN>='".$KODESALESMANDARI."'";
        } else {
            $sql .= " AND rk.KODESALESMAN>='".$KODESALESMANDARI."'";
        }
    }

    if($KODESALESMANSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        } else {
            $sql .= " AND rk.KODESALESMAN<='".$KODESALESMANSAMPAI."'";
        }
    }

    if($TANGGALDARI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        } else {
            $sql .= " AND rk.DATETRANSACTION>='".date('Y-m-d',strtotime($TANGGALDARI))."'";
        }
    }

    if($TANGGALSAMPAI){
        $tampungSql = substr($sql,-5);
        if($tampungSql == "WHERE"){
            $sql .= " rk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALSAMPAI))."'";
        } else {
            $sql .= " AND rk.DATETRANSACTION<='".date('Y-m-d',strtotime($TANGGALSAMPAI))."'";
        }
    }
    $tampungSql = substr($sql,-5);
    if($tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $sql .=" GROUP BY rk.NOMORBUKTI ORDER BY rk.DATETRANSACTION DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
        $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
        $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
        $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
        $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
        $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
        $array['NAMAPERMINTAAN'][$id] = $rencana['NAMAPERMINTAAN'];
        $id++;
        $array['TOTJUMLAH'] = $id;
        while($rencana = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $rencana['NOMORBUKTI'];
            $array['DATETRANSACTION'][$id] = $rencana['DATETRANSACTION'];
            $array['NAMAKARYAWANDRIVER'][$id] = $rencana['NAMAKARYAWANDRIVER'];
            $array['NAMAKARYAWANUSERID'][$id] = $rencana['NAMAKARYAWANUSERID'];
            $array['NAMAPELANGGAN'][$id] = $rencana['NAMAPELANGGAN'];
            $array['KETERANGAN'][$id] = $rencana['KETERANGAN'];
            $array['NAMAPERMINTAAN'][$id] = $rencana['NAMAPERMINTAAN'];
            $id++;
            $array['TOTJUMLAH'] = $id;
        }
    } else {
        $array['hasil'] = "tidakada";
        $array['TOTJUMLAH'] = $id;
    }

    echo json_encode($array);
    break;


    case "POSTING_TERPILIH_SEMUA":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];
    $HALAMAN = $_POST['HALAMAN'];
    if($HALAMAN == "LAPORAN_RENCANA_KIRIMAN" || $HALAMAN == "LAPORAN_RENCANA_COLLECTOR"){
        $HALAMAN = "tmrencanakunjungan";
    } else if($HALAMAN == "LAPORAN_KIRIMAN" || $HALAMAN == "LAPORAN_COLLECTOR"){
        $HALAMAN = "tmkunjungan";
    }

    foreach ($postingHapusValue as $key => $value) {
        $sql = "UPDATE " .$HALAMAN. " set STATUS ='CLOSE',USERID =:USERID WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID,
            'NOMORBUKTI'=>$value
        ]);
    }


    break;


    case "HAPUS_TERPILIH_SEMUA":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];
    $HALAMAN = $_POST['HALAMAN'];
    $FOLDERGAMBAR = "";
    $arrayFotoDihapus = array();
    if($HALAMAN == "LAPORAN_RENCANA_KIRIMAN" || $HALAMAN == "LAPORAN_RENCANA_COLLECTOR"){
        $HALAMAN = "tmrencanakunjungan";
        $DATABASEGAMBAR = "gambarrencanakunjungan";
        $FOLDERGAMBAR = "gambar_rencana_kunjungan";
        $KODEUNIK = "NAMAGAMBAR";
    } else if($HALAMAN == "LAPORAN_KIRIMAN" || $HALAMAN == "LAPORAN_COLLECTOR"){
        $HALAMAN = "tmkunjungan";
        $DATABASEGAMBAR = "gambarkunjungan";
        $FOLDERGAMBAR = "gambar_kunjungan";
        $KODEUNIK = "NAMAGAMBAR";
    }


    $id = 0;
    foreach ($postingHapusValue as $key => $value) {
        $sql = "DELETE FROM :HALAMAN WHERE NOMORBUKTI = :VALUE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'HALAMAN'=>$HALAMAN,
            'VALUE'=>$value
        ]);

        $sql = "SELECT * FROM :DATABASEGAMBAR WHERE :KODEUNIK LIKE :VALUE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'DATABASEGAMBAR'=>$DATABASEGAMBAR,
            'KODEUNIK'=>$KODEUNIK,
            'VALUE'=>$value.'%'
        ]);
        if($row = $stmt->fetch()){
            $arrayFotoDihapus[$id] = $row['NAMAGAMBAR'];
            $id++;
            while($row = $stmt->fetch()){
                $arrayFotoDihapus[$id] = $row['NAMAGAMBAR'];
                $id++;
            }
        }
    }

    if($arrayFotoDihapus){
        foreach($arrayFotoDihapus as $key =>$value){
            $sql = "DELETE from ".$DATABASEGAMBAR." WHERE ".$KODEUNIK." LIKE '".$value."%'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'DATABASEGAMBAR'=>$DATABASEGAMBAR,
                'KODEUNIK'=>$KODEUNIK,
                'VALUE'=>$value.'%'
            ]);
            if(file_exists($FOLDERGAMBAR."/".$value.".jpg")){
                unlink($FOLDERGAMBAR."/".$value.".jpg");
            } 
        }

    } 
    break;

    case "TAMPIL_EDIT_KIRIMAN_COLLECTOR":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $array = array();
    $id = 0;
    $sql = "SELECT * FROM tmkunjungan WHERE NOMORBUKTI=:NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KETERANGAN'] = $rencana['KETERANGAN'];
        $array['KESIMPULAN'] = $rencana['KESIMPULAN'];
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT * from gambarkunjungan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($gambar = $stmt->fetch()){
        $array['gambar'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);
        $id++;
        while($gambar = $stmt->fetch()){ 
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);
            $id++;
        }
    } else {
        $array['gambar'] = "tidakada";
    }
    echo json_encode($array);
    break;


    case "TAMPIL_EDIT_RENCANA_KIRIMAN_COLLECTOR":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $array = array();
    $id = 0;
    $sql = "SELECT * FROM tmrencanakunjungan WHERE NOMORBUKTI=:NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($rencana = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KETERANGAN'] = $rencana['KETERANGAN'];
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT * from gambarrencanakunjungan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($gambar = $stmt->fetch()){
        $array['gambar'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);
        $id++;
        while($gambar = $stmt->fetch()){ 
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "-") + 1);
            $id++;
        }
    } else {
        $array['gambar'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "EDIT_KIRIMAN_COLLECTOR":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $USERID = $_POST['USERID'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $array = array();

    $array['coba']="";
    $array['uploadgambar'] = "";
    if(isset($_POST['NAMAGAMBAR'])){
        $NAMAGAMBAR = $_POST['NAMAGAMBAR'];
    } else { $NAMAGAMBAR = null; }

    if(isset($_POST['namaFotoKunjungan'])){
        $namaFotoKunjungan = $_POST['namaFotoKunjungan'];
    } else { $namaFotoKunjungan = null; }

    if($NAMAGAMBAR != null){
        for($i = 0; $i<count($NAMAGAMBAR); $i++){
            $stmt = $pdo->prepare("DELETE FROM gambarkunjungan WHERE NAMAGAMBAR='".$NOMORBUKTI."-".$NAMAGAMBAR[$i]."'");
            $stmt->execute();

            if(file_exists("gambar_kunjungan/".$NOMORBUKTI."-".$NAMAGAMBAR[$i].".jpg")){
                unlink("gambar_kunjungan/".$NOMORBUKTI."-".$NAMAGAMBAR[$i].".jpg");
            }

        }
    }

    if($namaFotoKunjungan != null){
        $array['uploadgambar'] = "ada";
        for($i = 0; $i<count($namaFotoKunjungan); $i++){
            $stmt = $pdo->prepare("INSERT INTO gambarkunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)");
            $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$namaFotoKunjungan[$i]]);
            $array['coba'] = $NOMORBUKTI."-".$namaFotoKunjungan[$i];
        }
    }

    $stmt = $pdo->prepare("UPDATE tmkunjungan set USERID =:USERID,KETERANGAN=:KETERANGAN,KESIMPULAN=:KESIMPULAN WHERE NOMORBUKTI =:NOMORBUKTI");
    $stmt->execute(['USERID'=>$USERID,'NOMORBUKTI'=>$NOMORBUKTI,'KETERANGAN'=>$KETERANGAN,'KESIMPULAN'=>$KESIMPULAN]);


    $array['uploadgambar'] = "tidakada";
    echo json_encode($array);
    break;


    case "EDIT_RENCANA_KIRIMAN_COLLECTOR":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $USERID = $_POST['USERID'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $array = array();

    $array['coba']="";
    $array['uploadgambar'] = "";
    if(isset($_POST['NAMAGAMBAR'])){
        $NAMAGAMBAR = $_POST['NAMAGAMBAR'];
    } else { $NAMAGAMBAR = null; }

    if(isset($_POST['namaFotoKunjungan'])){
        $namaFotoKunjungan = $_POST['namaFotoKunjungan'];
    } else { $namaFotoKunjungan = null; }

    if($NAMAGAMBAR != null){
        for($i = 0; $i<count($NAMAGAMBAR); $i++){
            $stmt = $pdo->prepare("DELETE FROM gambarrencanakunjungan WHERE NAMAGAMBAR='".$NOMORBUKTI."-".$NAMAGAMBAR[$i]."'");
            $stmt->execute();

            if(file_exists("gambar_rencana_kunjungan/".$NOMORBUKTI."-".$NAMAGAMBAR[$i].".jpg")){
                unlink("gambar_rencana_kunjungan/".$NOMORBUKTI."-".$NAMAGAMBAR[$i].".jpg");
            }

        }
    }

    if($namaFotoKunjungan != null){
        $array['uploadgambar'] = "ada";
        for($i = 0; $i<count($namaFotoKunjungan); $i++){
            $stmt = $pdo->prepare("INSERT INTO gambarrencanakunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)");
            $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$namaFotoKunjungan[$i]]);
            $array['coba'] = $NOMORBUKTI."-".$namaFotoKunjungan[$i];
        }
    }

    $stmt = $pdo->prepare("UPDATE tmrencanakunjungan set USERID =:USERID,KETERANGAN=:KETERANGAN WHERE NOMORBUKTI =:NOMORBUKTI");
    $stmt->execute(['USERID'=>$USERID,'NOMORBUKTI'=>$NOMORBUKTI,'KETERANGAN'=>$KETERANGAN]);


    $array['uploadgambar'] = "tidakada";
    echo json_encode($array);
    break;

    case "TAMPIL_KONFIGURASI_TRUK_DAN_KAROSERI":
    $sql = "SELECT * FROM mkaroseri WHERE STATUS='CLOSE'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($karoseri = $stmt->fetch()){
        $array['karoseri'] = "ada";
        $array['KODEKAROSERI'][$id] = $karoseri['KODE'];
        $id++;
        while($karoseri = $stmt->fetch()){
            $array['KODEKAROSERI'][$id] = $karoseri['KODE'];
            $id++;
        }
    } else {
        $array['karoseri'] = "tidakada";
    }

    $sql = "SELECT * FROM mkonfigurasi WHERE STATUS='CLOSE'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($konfigurasi = $stmt->fetch()){
        $array['konfigurasi'] = "ada";
        $array['KODEKONFIGURASI'][$id] = $konfigurasi['KODE'];
        $id++;
        while($konfigurasi = $stmt->fetch()){
            $array['KODEKONFIGURASI'][$id] = $konfigurasi['KODE'];
            $id++;
        }
    } else {
        $array['konfigurasi'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "POPUP_TAMPIL_BARANG":
    $sql = "SELECT * FROM mbrg WHERE STATUS ='CLOSE'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $array = array();
    $id = 0;
    if($barang = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEBARANG'][$id] = $barang['kodebarang'];
        $array['NAMA'][$id] = $barang['nama'];
        $array['HARGAJUAL'][$id] = $barang['hargajual'];
        $id++;
        while($barang = $stmt->fetch()){
            $array['KODEBARANG'][$id] = $barang['kodebarang'];
            $array['NAMA'][$id] = $barang['nama'];
            $array['HARGAJUAL'][$id] = $barang['hargajual'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "INPUT_PEMASANGAN_BAN":
    
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $TANGGAL = $_POST['TANGGAL'];
    $NOMORPLAT = $_POST['NOMORPLAT'];
    $ODOMETER = $_POST['ODOMETER'];
    $NAMASOPIR = $_POST['NAMASOPIR'];
    $NOHPSOPIR = $_POST['NOHPSOPIR'];
    $NAMAPENGAWAS = $_POST['NAMAPENGAWAS'];
    $NOHPPENGAWAS = $_POST['NOHPPENGAWAS'];
    $MEREKTRUK = $_POST['MEREKTRUK'];
    $KONFIGURASITRUK = $_POST['KONFIGURASITRUK'];
    $KAROSERI = $_POST['KAROSERI'];
    $USERID = $_POST['USERID'];
    $array = array();
    //print_r($_POST);

    if(isset($_POST['KODEBARANG'])){
        $KODEBARANG = $_POST['KODEBARANG'];
    } else { $KODEBARANG = null; }

    if(isset($_POST['SERIALNUMBER'])){
        $SERIALNUMBER = $_POST['SERIALNUMBER'];
    } else { $SERIALNUMBER = null; }

    if(isset($_POST['POSISI'])){
        $POSISI = $_POST['POSISI'];
    } else { $POSISI = null; }

    if(isset($_POST['TEKANANANGIN'])){
        $TEKANANANGIN = $_POST['TEKANANANGIN'];
    } else { $TEKANANANGIN = null; }

    if(isset($_POST['TD'])){
        $TD = $_POST['TD'];
    } else { $TD = null; }

    if(isset($_POST['HARGA'])){
        $HARGA = $_POST['HARGA'];
    } else { $HARGA = null; }

    if(isset($_POST['KETERANGAN'])){
        $KETERANGAN = $_POST['KETERANGAN'];
    } else { $KETERANGAN = null; }

    if(isset($_POST['arrayFoto'])){
        $arrayFoto = $_POST['arrayFoto'];
    } else { $arrayFoto = null; }

    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    $NOMORBUKTI = "PB".date('ymd',strtotime($TANGGAL));

    $sql = "SELECT * FROM tmpemasangan WHERE TANGGAL = :TANGGAL AND NOMORBUKTI LIKE 'PB%' ORDER BY NOMORBUKTI DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
    if($row = $stmt->fetch()){
        $counter = substr($row['NOMORBUKTI'], -3);
        $counter++;
        if(strlen($counter) == 1){
            $NOMORBUKTI .= "00" .$counter;

        } else if (strlen($counter) == 2){
            $NOMORBUKTI .= "0" .$counter;
        } else {
            $NOMORBUKTI .= $counter;
        }
    } else {
        $NOMORBUKTI .= "001";
    }

    $array['adafoto'] = "tidakada";
    
    try {
        $id = 0;

        $sql = "INSERT INTO tmpemasangan (NOMORBUKTI,KODEPELANGGAN,TANGGAL,NOMORPLAT,ODOMETER, NAMASOPIR, NOHPSOPIR, NAMAPENGAWAS, NOHPPENGAWAS, MEREKTRUK, KONFIGURASITRUK, KAROSERI,USERID, STATUS) values(:NOMORBUKTI,:KODEPELANGGAN,:TANGGAL,:NOMORPLAT,:ODOMETER,:NAMASOPIR,:NOHPSOPIR,:NAMAPENGAWAS,:NOHPPENGAWAS,:MEREKTRUK,:KONFIGURASITRUK,:KAROSERI,:USERID,:STATUS)";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute([
            'NOMORBUKTI'=>$NOMORBUKTI,
            'KODEPELANGGAN'=>$KODEPELANGGAN,
            'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL)),
            'NOMORPLAT'=>$NOMORPLAT,
            'ODOMETER'=>$ODOMETER,
            'NAMASOPIR'=>$NAMASOPIR,
            'NOHPSOPIR'=>$NOHPSOPIR,
            'NAMAPENGAWAS'=>$NAMAPENGAWAS,
            'NOHPPENGAWAS'=>$NOHPPENGAWAS,
            'MEREKTRUK'=>$MEREKTRUK,
            'KONFIGURASITRUK'=>$KONFIGURASITRUK,
            'KAROSERI'=>$KAROSERI,
            'USERID'=>$USERID,
            'STATUS'=>'CLOSE'
        ])){
            for($i = 0; $i<count($KODEBARANG); $i++){
                $sql = "SELECT * FROM tdpemasangan WHERE SERIALNUMBER =:SERIALNUMBER";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['SERIALNUMBER'=>$SERIALNUMBER[$i]]);
                if($checking = $stmt->fetch()){
                    $array['hasil'] = "duplicate_serialnumber";
                    throw new Exception("error");
                }

                $sql = "INSERT INTO tdpemasangan (NOMORBUKTI,KODEBARANG,SERIALNUMBER,POSISI,TEKANANANGIN,TD,HARGA,KETERANGAN) values (:NOMORBUKTI,:KODEBARANG,:SERIALNUMBER,:POSISI,:TEKANANANGIN,:TD,:HARGA,:KETERANGAN)";
                $stmt = $pdo->prepare($sql);


                if($stmt->execute([
                    'NOMORBUKTI'=>$NOMORBUKTI,
                    'KODEBARANG'=>$KODEBARANG[$i],
                    'SERIALNUMBER'=>$SERIALNUMBER[$i],
                    'POSISI'=>$POSISI[$i],
                    'TEKANANANGIN'=>$TEKANANANGIN[$i],
                    'TD'=>$TD[$i],
                    'HARGA'=>$HARGA[$i],
                    'KETERANGAN'=>$KETERANGAN[$i]]) == 1){
                    $array['hasil'] = "berhasil";
                    $LASTID = $pdo->lastInsertId();
                } else {
                    $array['hasil'] = "duplicate_primary_key";
                    throw new Exception("error");
                }

                
                if(isset($NAMAFOTO[$arrayFoto[$i]])){
                    //echo "asd";
                    //hati-hati $id atau $i
                    $array['LASTID'][$id] = $arrayFoto[$i];

                    //$array[$arrayFoto[$i]][$id] = $arrayFoto[$i];
                    foreach($NAMAFOTO[$arrayFoto[$i]] as $key2 => $value2){
                        //echo $key2;
                        $array[$LASTID][$key2] = $LASTID."-".$NOMORBUKTI."-".$value2;
                        $array[$arrayFoto[$i]][$key2] = $LASTID."-".$NOMORBUKTI."-".$value2;
                        //echo $array[$arrayFoto[$i]][0];
                        $stmt = $pdo->prepare("INSERT INTO gambartdpemasangan(ID_TDPEMASANGAN,NAMAGAMBAR) values(:ID_TDPEMASANGAN,:NAMAGAMBAR)");
                        if($stmt->execute([
                            'ID_TDPEMASANGAN'=>$LASTID,
                            'NAMAGAMBAR'=>$LASTID."-".$NOMORBUKTI."-".$value2])){
                            $array['hasil'] = "berhasil";
                        } else {
                            //echo "TEST";
                            $array['hasil'] = "error";
                            throw new Exception("error");
                        }
                    }
                    $id++;
                    $array['adafoto'] = "ada";
                }

                            /*  $array['KODEPELANGGAN'][$id] = $value;
                                    if(isset($NAMAFOTO[$value])){
                             foreach($NAMAFOTO[$value] as $key2 => $value2){
                          $array[$value][$key2] = $NOMORBUKTI."-".$value2;
                          $stmt = $pdo->prepare("INSERT INTO gambarrencanakunjungan(NAMAGAMBAR) values(:NAMAGAMBAR)");
                          $stmt->execute(['NAMAGAMBAR'=>$NOMORBUKTI."-".$value2]);
                      }
                      $id++;
                      $array['adafoto'] = "ada";
                  } */

              }
          } else {
            throw new Exception("error");
            $array['hasil'] = "error";
        }

    } catch (Exception $e){
        //$array['hasil'] = "error";
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){
            $sql = "DELETE FROM tmpemasangan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);

            $sql = "DELETE FROM tdpemasangan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
        }

    }



    echo json_encode($array);
    break;

    case "PEMANTAUAN_SEMUA_USER_LATITUDE_LONGITUDE":
    $KODECABANG = $_POST['KODECABANG'];
    /*$sql = "SELECT tabel1.*,
    (select LATITUDE from history_perjalanan ad where ad.USERID=tabel1.userid and ad.WAKTU=tabel1.waktu) LATITUDE,
    (select LONGITUDE from history_perjalanan ad where ad.USERID=tabel1.userid and ad.WAKTU=tabel1.waktu) LONGITUDE,
    (select id from history_perjalanan ad where ad.USERID=tabel1.userid and ad.WAKTU=tabel1.waktu) ID FROM (
    select ab.USERID,(select max(waktu) from history_perjalanan ac where ac.USERID=ab.USERID)WAKTU 
    from pemakai ab WHERE (ab.KODECABANG = :KODECABANG OR ab.KODECABANG = 'SEMUA') ) tabel1";*/
    $sql = "SELECT hp.USERID as USERID,hp.LATITUDE as LATITUDE, hp.LONGITUDE as LONGITUDE, max(hp.WAKTU) as WAKTU
    FROM history_perjalanan hp INNER JOIN pemakai p on(hp.USERID = p.USERID)
    WHERE WAKTU IN (
    SELECT MAX(WAKTU) AS WAKTU
    FROM history_perjalanan GROUP BY USERID ORDER BY ID DESC) AND (p.KODECABANG =:KODECABANG OR p.KODECABANG='SEMUA') GROUP BY USERID";
    /*$sql = "SELECT hp.USERID as USERID,hp.LATITUDE as LATITUDE, hp.LONGITUDE as LONGITUDE, hp.WAKTU as WAKTU
    FROM history_perjalanan hp INNER JOIN pemakai p on(hp.USERID = p.USERID)
    WHERE WAKTU IN (
    SELECT MAX(WAKTU) AS WAKTU
    FROM history_perjalanan GROUP BY USERID) AND (p.KODECABANG =:KODECABANG OR p.KODECABANG='SEMUA') GROUP BY USERID";*/
    /*$sql = "SELECT p.USERID,ANY_VALUE(LATITUDE) AS LATITUDE ,ANY_VALUE(LONGITUDE)AS LONGITUDE, MAX(hp.WAKTU) as WAKTU
    FROM pemakai p INNER JOIN history_perjalanan hp ON (p.USERID = hp.USERID) WHERE p.KODECABANG = :KODECABANG OR p.KODECABANG ='SEMUA'
    GROUP BY p.USERID";*/
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($koordinat = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['USERID'][$id] = $koordinat['USERID'];
        $array['LATITUDE'][$id] = $koordinat['LATITUDE'];
        $array['LONGITUDE'][$id] = $koordinat['LONGITUDE'];
        $id++;
        while($koordinat = $stmt->fetch()){ 
            $array['USERID'][$id] = $koordinat['USERID'];
            $array['LATITUDE'][$id] = $koordinat['LATITUDE'];
            $array['LONGITUDE'][$id] = $koordinat['LONGITUDE'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "INPUT_MERK_MOBIL":
    $KODEMOBIL = $_POST['KODEMOBIL'];
    $NAMA = $_POST['NAMA'];
    $sql = "INSERT INTO mmobil (KODEMOBIL,NAMA) values (:KODEMOBIL,:NAMA)";
    $stmt = $pdo->prepare($sql);

    try{
        if($stmt->execute([
            'KODEMOBIL'=>$KODEMOBIL,
            'NAMA'=>$NAMA
        ]) == 1){
            $array['hasil'] = "berhasil";
        } else {
            $array['hasil'] = "duplicate_primary_key";
            throw new Exception("error");
        }
    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }
    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_MERK_MOBIL":
    $sql = "SELECT * FROM mmobil";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    try{
        if($stmt->execute() == 1){
            $array['hasil'] = "ada";
            while($mobil = $stmt->fetch()){
                $array['KODEMOBIL'][$id] = $mobil['KODEMOBIL'];
                $array['NAMA'][$id] = $mobil['NAMA'];
                $array[$mobil['KODEMOBIL']] = $mobil['NAMA'];
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }
    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "TAMPIL_DETAIL_BARANG_MERK_MOBIL":
    $kodebarang = $_POST['kodebarang'];
    $sql = "SELECT mm.KODEMOBIL AS KODEMOBIL, mm.NAMA as NAMA FROM mdbrg db INNER JOIN mmobil mm on(db.KODEMOBIL = mm.KODEMOBIL) WHERE kodebarang =:kodebarang";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kodebarang'=>$kodebarang]);
    $array = array();
    $id = 0;
    if($barang = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEMOBIL'][$id] = $barang['KODEMOBIL'];
        $array['NAMA'][$id] = $barang['NAMA'];
        $id++;
        while($barang = $stmt->fetch()){
            $array['KODEMOBIL'][$id] = $barang['KODEMOBIL'];
            $array['NAMA'][$id] = $barang['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "CEK_USERNAME_PASSWORD":
    $USERID = $_POST['USERID'];
    $PASSWORD = $_POST['PASSWORD'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $GCM_ID = $_POST['GCM_ID'];
    $array = array();
    $sql = "SELECT * FROM pemakai WHERE USERID =:USERID AND PASSWORD=:PASSWORD AND KODEJABATAN=:KODEJABATAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID'=>$USERID,
        'PASSWORD'=>$PASSWORD,
        'KODEJABATAN'=>$KODEJABATAN]);
    if($stmt->rowCount() > 0){
        $array['hasil'] = "sama";
        if(isset($GCM_ID) && $GCM_ID != ""){
            $sql = "UPDATE pemakai set GCM_ID = :GCM_ID WHERE USERID = :USERID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID'=>$USERID,
                'GCM_ID'=>$GCM_ID
            ]);
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "INPUT_PEMANTAUAN_BAN":
    $TANGGAL = $_POST['TANGGAL'];
    $ODOMETER = $_POST['ODOMETER'];
    $NOMORPLAT = $_POST['NOMORPLAT'];
    $USERID = $_POST['USERID'];
    $array = array();

    if(isset($_POST['SERIALNUMBER'])){
        $SERIALNUMBER = $_POST['SERIALNUMBER'];
    } else { $SERIALNUMBER = null; }

    if(isset($_POST['POSISI'])){
        $POSISI = $_POST['POSISI'];
    } else { $POSISI = null; }

    if(isset($_POST['TEKANANANGIN'])){
        $TEKANANANGIN = $_POST['TEKANANANGIN'];
    } else { $TEKANANANGIN = null; }

    if(isset($_POST['TD'])){
        $TD = $_POST['TD'];
    } else { $TD = null; }

    if(isset($_POST['KETERANGAN'])){
        $KETERANGAN = $_POST['KETERANGAN'];
    } else { $KETERANGAN = null; }

    if(isset($_POST['namaFotoKunjungan'])){
        $NAMAFOTO = $_POST['namaFotoKunjungan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['counterArrFoto'])){
        $counterArrFoto = $_POST['counterArrFoto'];
    } else {
        $counterArrFoto = NULL;
    }

    if(isset($_POST['arrayFoto'])){
        $arrayFoto = $_POST['arrayFoto'];
    } else { $arrayFoto = null; }

    if(isset($_POST['namaFotoKunjunganz'])){
        $NAMAFOTOZ = $_POST['namaFotoKunjunganz'];
    } else {
        $NAMAFOTOZ = NULL;
    }

    $NOMORBUKTI = "TB".date('ymd',strtotime($TANGGAL));

    try {
        $sql = "SELECT max(ODOMETER) as ODOMETER, max(TANGGAL) as TANGGAL FROM tmpemantauan WHERE NOMORPLAT = :NOMORPLAT";
        $stmt = $pdo->prepare($sql);
        $stmt ->execute(['NOMORPLAT'=>$NOMORPLAT]);

        if($stmt->rowCount() > 0){

            $pemantauan = $stmt->fetch();

            $TANGGAL = date('Y-m-d',strtotime($TANGGAL));
            $TANGGALMAX = date('Y-m-d',strtotime($pemantauan['TANGGAL']));

            if($TANGGAL > $TANGGALMAX){
                $array['hasil'] = "berhasil";
            } else if($TANGGAL == $TANGGALMAX){
                $array['hasil'] = "batas_tanggal_sama";
                throw new Exception("error");
            } else {
                $array['hasil'] = "batas_tanggal";
                throw new Exception("error");
            }

            if($ODOMETER > $pemantauan['ODOMETER']){
                $array['hasil'] = "berhasil";

            } else {
                $array['hasil'] = "batas_odometer";
                throw new Exception("error");
            }

            if($array['hasil'] == "berhasil"){
                $sql = "SELECT * FROM tmpemantauan WHERE TANGGAL = :TANGGAL AND NOMORBUKTI LIKE 'TB%' ORDER BY NOMORBUKTI DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))
                ]);
                if($row = $stmt->fetch()){
                    $counter = substr($row['NOMORBUKTI'], -3);
                    $counter++;
                    if(strlen($counter) == 1){
                        $NOMORBUKTI .= "00" .$counter;

                    } else if (strlen($counter) == 2){
                        $NOMORBUKTI .= "0" .$counter;
                    } else {
                        $NOMORBUKTI .= $counter;
                    }
                } else {
                    $NOMORBUKTI .= "001";
                }

                $sql = "INSERT INTO tmpemantauan (NOMORBUKTI,ODOMETER,TANGGAL,NOMORPLAT,USERID,STATUS) values (:NOMORBUKTI,:ODOMETER,:TANGGAL,:NOMORPLAT,:USERID,:STATUS)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NOMORBUKTI'=>$NOMORBUKTI,
                    'ODOMETER'=>$ODOMETER,
                    'TANGGAL'=>$TANGGAL,
                    'NOMORPLAT'=>$NOMORPLAT,
                    'USERID'=>$USERID,
                    'STATUS'=>'OPEN']);
            } else {
                throw new Exception("error");
            }

        } else {
            $array['hasil'] = "error";
            throw new Exception("error");
        }



        if(isset($NAMAFOTO) && $array['hasil'] == "berhasil"){
            $sql = "SELECT * FROM gambarpemantauan ORDER BY ID DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $counterId = 1;
            if($checking = $stmt->fetch()){
                $counterId = $checking['ID'] + 1;
            } else {
                $counterId = 1;
            }
            foreach ($NAMAFOTO as $key => $value) {
                $KODEGAMBAR = $NOMORBUKTI."_".$counterId;
                //$KODEGAMBAR = $NOMORBUKTI."_".$value;
                $sql = "INSERT INTO gambarpemantauan (NOMORBUKTI_PEMANTAUAN_BAN, NAMAGAMBAR) values (:NOMORBUKTI,:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'NAMAGAMBAR'=>$KODEGAMBAR]);
                $counterId++;
                $array['NAMAGAMBARPEMANTAUAN'][$key] = $KODEGAMBAR;
            }
        }

        $array['NOMORBUKTI'] = $NOMORBUKTI;

    //try disini
        $id = 0;

        if(isset($POSISI)){
            for($i = 0; $i<count($POSISI); $i++){
                $sql = "INSERT INTO tdpemantauan (NOMORBUKTI,POSISI,SERIALNUMBER,PSI,TD,KETERANGAN,STATUS) values (:NOMORBUKTI,:POSISI,:SERIALNUMBER,:TEKANANANGIN,:TD,:KETERANGAN,:STATUS)";
                $stmt = $pdo->prepare($sql);
                //echo $KETERANGAN[$i];
                if($stmt->execute([
                    'NOMORBUKTI'=>$NOMORBUKTI,
                    'SERIALNUMBER'=>$SERIALNUMBER[$i],
                    'POSISI'=>$POSISI[$i],
                    'TEKANANANGIN'=>$TEKANANANGIN[$i],
                    'TD'=>$TD[$i],
                    'KETERANGAN'=>$KETERANGAN[$i],
                    'STATUS'=>"OPEN"]) == 1){
                    $array['hasil'] = "berhasil";
                    $LASTID = $pdo->lastInsertId();
                } /*else {
                    $array['hasil'] = "duplicate_primary_key";
                    throw new Exception("error");
                }*/

                
                if(isset($NAMAFOTOZ[$counterArrFoto[$i]])){
                    $array['LASTID'][$id] = $counterArrFoto[$i];
                    $sql = "SELECT * FROM gambartdpemantauan ORDER BY ID DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $counterId = 1;
                    if($checking = $stmt->fetch()){
                        $counterId = $checking['ID'] + 1;
                    } else {
                        $counterId = 1;
                    }

                    foreach($NAMAFOTOZ[$counterArrFoto[$i]] as $key2 => $value2){
                    /*
                        $array[$LASTID][$key2] = $LASTID."-".$NOMORBUKTI."-".$value2;*//*
                        $array[$LASTID][$key2] = $NOMORBUKTI."_".$POSISI[$id]."_".$value2;*/
                        $array[$LASTID][$key2] = $NOMORBUKTI."_".$POSISI[$id]."_".$counterId;/*
                        $array[$arrayFoto[$i]][$key2] = $LASTID."-".$NOMORBUKTI."-".$value2;*//*
                        $array[$arrayFoto[$i]][$key2] = $NOMORBUKTI."_".$POSISI[$id]."_".$value2;
                        */
                        $array[$counterArrFoto[$i]][$key2] = $NOMORBUKTI."_".$POSISI[$id]."_".$counterId;
                        $stmt = $pdo->prepare("INSERT INTO gambartdpemantauan(NOMORBUKTI,POSISI,NAMAGAMBAR) values(:NOMORBUKTI,:POSISI,:NAMAGAMBAR)");
                        if($stmt->execute([
                            'NOMORBUKTI'=>$NOMORBUKTI,
                            'POSISI'=>$POSISI[$id],
                            'NAMAGAMBAR'=>$NOMORBUKTI."_".$POSISI[$id]."_".$counterId])){
                            $array['hasil'] = "berhasil";
                        } else {
                            $array['hasil'] = "error";
                            throw new Exception("error");
                        }
                        //echo $NOMORBUKTI."_".$POSISI[$id]."_".$value2;
                        $counterId++;
                    }
                    $id++;
                    $array['adafoto'] = "ada";
                }
            }

        } else {
            $array['hasil'] = "posisi_tidakada";
            throw new Exception("error");
        }


    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){
            $sql = "DELETE FROM tdpemantauan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);

            $sql = "DELETE FROM tmpemantauan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);

            $sql = "DELETE FROM gambarpemantauan WHERE NOMORBUKTI_PEMANTAUAN_BAN = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);

            $sql = "DELETE FROM gambartdpemantauan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
        }

    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_PEMANTAUAN_BAN":
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];

    /*if($KODEJABATAN == "OWNER" || $USERID == "ANTON"){
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE mp.KODECABANG ='".$KODECABANG."'";
    } else {
        $sql = "SELECT DISTINCT DATE(DATETRANSACTION) as DATETRANSACTION FROM tmkunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) inner join mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN = '".$USERID."') AND mp.KODECABANG ='".$KODECABANG."' ";
    }*/

    if($USERID == "MPT-1" || $USERID == "MPT-2" || $USERID == "MPT-3"){
        $sql = "SELECT * FROM tmpemantauan tmp INNER JOIN mkendaraan mk on(mk.NOMORPLAT = tmp.NOMORPLAT) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = mk.KODEPELANGGAN) WHERE mp.KODECABANG=:KODECABANG AND mp.KODEPELANGGAN ='PT.MODERN'";
    } else {
        $sql = "SELECT * FROM tmpemantauan tmp INNER JOIN mkendaraan mk on(mk.NOMORPLAT = tmp.NOMORPLAT) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = mk.KODEPELANGGAN) WHERE mp.KODECABANG=:KODECABANG";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);

    if($row = $stmt->fetch()){
        $TANGGAL = $row['TANGGAL'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['TANGGAL'][$id] = $row['TANGGAL'];
        $id++;
        while($row = $stmt->fetch()){
            $array['TANGGAL'][$id] = $row['TANGGAL'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){


        $arrayTampung= array_unique($array['TANGGAL']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();

        for($i = 0; $i<count($arrayTampung); $i++){
            $array['TANGGAL'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        /*foreach ($arrayTampung as $key => $value) {
            if($KODEJABATAN == "OWNER" || $USERID == "ANTON"){
                $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE DATE(DATETRANSACTION) = '".$value."' AND mp.KODECABANG ='".$KODECABANG."' GROUP BY DATE(DATETRANSACTION)";
            } else {
                $sql = "SELECT count(*) as JUMLAHKUNJUNGAN FROM tmkunjungan k inner join dpelanggan1 dp1 on(dp1.KODEPELANGGAN = k.KODEPELANGGAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE DATE(DATETRANSACTION) = '".$value."' AND (dp1.CP1 = '".$USERID."' OR k.KODESALESMAN ='".$USERID."') AND mp.KODECABANG = '".$KODECABANG."' GROUP BY DATE(DATETRANSACTION)";
                
            }
            
            $result = mysqli_query($conn,$sql);
            if($row=mysqli_fetch_object($result)){
                $array['JUMLAHKUNJUNGAN'][$key] = $row->JUMLAHKUNJUNGAN;
            } else {
                $array['JUMLAHKUNJUNGAN'][$key] = 0;
            }
        }*/

        if($counter == 1){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
        }
    }
    

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_PEMANTAUAN_BAN_PER_TANGGAL":
    $TANGGAL = $_POST['TANGGAL'];
    $array = array();
    $sql = "SELECT * FROM tmpemantauan WHERE TANGGAL = :TANGGAL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
    $id = 0;
    if($nomorplat = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORPLAT'][$id] = $nomorplat['NOMORPLAT'];
        $array['NOMORBUKTI'][$id] = $nomorplat['NOMORBUKTI'];
        $id++;
        while($nomorplat = $stmt->fetch()){
            $array['NOMORPLAT'][$id] = $nomorplat['NOMORPLAT'];
            $array['NOMORBUKTI'][$id] = $nomorplat['NOMORBUKTI'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_PEMASANGAN_BAN_PER_TANGGAL":
    $TANGGAL = $_POST['TANGGAL'];
    $array = array();
    $sql = "SELECT * FROM tmpemasangan WHERE TANGGAL = :TANGGAL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
    $id = 0;
    if($nomorplat = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORPLAT'][$id] = $nomorplat['NOMORPLAT'];
        $array['NOMORBUKTI'][$id] = $nomorplat['NOMORBUKTI'];
        $id++;
        while($nomorplat = $stmt->fetch()){
            $array['NOMORPLAT'][$id] = $nomorplat['NOMORPLAT'];
            $array['NOMORBUKTI'][$id] = $nomorplat['NOMORBUKTI'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_DETAIL_PEMANTAUAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $array = array();
    $id = 0;

    $sql = "SELECT * FROM tmpemantauan WHERE NOMORBUKTI=:NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($pemantauan = $stmt->fetch()){
        $array['hasil_tm'] = "ada";
        $array['ODOMETER'] = $pemantauan['ODOMETER'];
        $array['TANGGAL'] = $pemantauan['TANGGAL'];
        $array['NOMORPLAT'] = $pemantauan['NOMORPLAT'];
        $array['USERID'] = $pemantauan['USERID'];
        $array['STATUSTM'] = $pemantauan['STATUS'];
    } else {
        $array['hasil_tm'] = "tidakada";
    }

    $sql = "SELECT * FROM gambarpemantauan WHERE NOMORBUKTI_PEMANTAUAN_BAN = :NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($gambar = $stmt->fetch()){
        $array['foto_tm'] = "ada";
        $array['NAMAGAMBARTM'][$id] = $gambar['NAMAGAMBAR'];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['foto_tm'] = "ada";
            $array['NAMAGAMBARTM'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['foto_tm'] = "tidakada";
    }

    $id = 0;
    $sql = "SELECT * FROM tdpemantauan WHERE NOMORBUKTI=:NOMORBUKTI ORDER BY POSISI asc";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($pemantauan = $stmt->fetch()){
        $array['hasil_td'] = "ada";
        $array['NOMORBUKTI'][$id] = $pemantauan['NOMORBUKTI'];
        $array['POSISI'][$id] = $pemantauan['POSISI'];
        $array['SERIALNUMBER'][$id] = $pemantauan['SERIALNUMBER'];
        $array['PSI'][$id] = $pemantauan['PSI'];
        $array['TD'][$id] = $pemantauan['TD'];
        $array['KETERANGAN'][$id] = $pemantauan['KETERANGAN'];
        $array['STATUS'][$id] = $pemantauan['STATUS'];
        $array['NOMORBUKTIPOSISI'][$id] = $pemantauan['NOMORBUKTI'] . "_" . $pemantauan['POSISI'];
        $id++;
        while($pemantauan = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $pemantauan['NOMORBUKTI'];
            $array['POSISI'][$id] = $pemantauan['POSISI'];
            $array['SERIALNUMBER'][$id] = $pemantauan['SERIALNUMBER'];
            $array['PSI'][$id] = $pemantauan['PSI'];
            $array['TD'][$id] = $pemantauan['TD'];
            $array['KETERANGAN'][$id] = $pemantauan['KETERANGAN'];
            $array['STATUS'][$id] = $pemantauan['STATUS'];
            $array['NOMORBUKTIPOSISI'][$id] = $pemantauan['NOMORBUKTI'] . "_" . $pemantauan['POSISI'];
            $id++;
        }
    } else {
        $array['hasil_td'] = "tidakada";
    }


    if(isset($array['NOMORBUKTI'])){
        $counter = 0;
        for($i = 0;$i<count($array['NOMORBUKTI']);$i++){
            $stmt=$pdo->prepare("SELECT * FROM gambartdpemantauan WHERE NAMAGAMBAR LIKE '".$array['NOMORBUKTI'][$i]."_".$array['POSISI'][$i]."%'");
            $stmt->execute();

            if($gambar = $stmt->fetch()){
                $array['hasilgambar'] = "ada";
                $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11) . "_" . $array['POSISI'][$i];
                $counter++;
                while($gambar = $stmt->fetch()){
                    $array['NAMAGAMBAR'][$counter] = $gambar['NAMAGAMBAR'];
                    $array['COUNTERGAMBAR'][$counter] = substr($gambar['NAMAGAMBAR'],0,11) . "_" . $array['POSISI'][$i];
                    $counter++;
                }
            } 
        }
    }

    echo json_encode($array);
    break;

    case "AMBIL_GAMBAR_TD_PEMANTAUAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM gambartdpemantauan WHERE NAMAGAMBAR LIKE '".$NOMORBUKTI."%'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id=0;
    if($gambar = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "AMBIL_GAMBAR_TM_PEMANTAUAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM gambarpemantauan WHERE NOMORBUKTI_PEMANTAUAN_BAN =:NOMORBUKTI";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    $array = array();
    $id=0;
    if($gambar = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_EDIT_PEMANTAUAN_BAN":
    $NOMORBUKTIPOSISI = $_POST['NOMORBUKTIPOSISI'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $POSISI = $_POST['POSISI'];
    $id = 0;
    $array = array();
    $sql = "SELECT * FROM tdpemantauan WHERE NOMORBUKTI = :NOMORBUKTI AND POSISI = :POSISI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'POSISI'=>$POSISI]);
    if($pemantauan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['SERIALNUMBER'] = $pemantauan['SERIALNUMBER'];
        $array['PSI'] = $pemantauan['PSI'];
        $array['TD'] = $pemantauan['TD'];
        $array['KETERANGAN'] = $pemantauan['KETERANGAN'];
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT * FROM gambartdpemantauan WHERE NAMAGAMBAR LIKE :NOMORBUKTIPOSISI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTIPOSISI'=>$NOMORBUKTIPOSISI.'%']);
    if($gambar = $stmt->fetch()){
        $array['hasil_foto'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];/*
        $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "_") + 1);*/
        $counter = explode("_", $gambar['NAMAGAMBAR'], 3);
        $array['counter'][$id] = $counter[2];
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];/*
            $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "_") + 1);*/
            $counter = explode("_", $gambar['NAMAGAMBAR'], 3);
            $array['counter'][$id] = $counter[2];
            $id++;
        }
    } else {
        $array['hasil_foto'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "TAMPIL_EDIT_TM_PEMANTAUAN_BAN":
    $array = array();
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM tmpemantauan WHERE NOMORBUKTI=:NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($pemantauan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['ODOMETER'] = $pemantauan['ODOMETER'];
        $array['TANGGAL'] = $pemantauan['TANGGAL'];
        $array['NOMORPLAT'] = $pemantauan['NOMORPLAT'];
    } else {
        $array['hasil'] = "tidakada";
    }

    $id = 0;
    $sql = "SELECT * FROM gambarpemantauan WHERE NOMORBUKTI_PEMANTAUAN_BAN=:NOMORBUKTI ORDER BY NAMAGAMBAR ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($gambar = $stmt->fetch()){
        $array['hasil_foto'] = "ada";
        $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
        $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "_") + 1);
        
        $id++;
        while($gambar = $stmt->fetch()){
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $array['counter'][$id] = substr($gambar['NAMAGAMBAR'], strpos($gambar['NAMAGAMBAR'], "_") + 1);
            $id++;
        }
    } else {
        $array['hasil_foto'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "EDIT_PEMANTAUAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $POSISI = $_POST['POSISI'];
    $SERIALNUMBER = $_POST['SERIALNUMBER'];
    $TEKANANANGIN = $_POST['TEKANANANGIN'];
    $TD = $_POST['TD'];
    $KETERANGAN = $_POST['KETERANGAN'];

    if(isset($_POST['namaFotoPemantauan'])){
        $NAMAFOTO = $_POST['namaFotoPemantauan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['HAPUSGAMBAR'])){
        $gambarHapus = $_POST['HAPUSGAMBAR'];
    } else { $gambarHapus = null; }

    

    if($gambarHapus != null){
        for($i = 0; $i<count($gambarHapus); $i++){
            $stmt = $pdo->prepare("DELETE FROM gambartdpemantauan WHERE NOMORBUKTI=:NOMORBUKTI AND NAMAGAMBAR=:NAMAGAMBAR");
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'NAMAGAMBAR'=>$NOMORBUKTI."_".$POSISI."_".$gambarHapus[$i]]);

            if(file_exists("gambar_td_pemantauan_ban/".$NOMORBUKTI."_".$POSISI."_".$gambarHapus[$i].".jpg")){
                unlink("gambar_td_pemantauan_ban/".$NOMORBUKTI."_".$POSISI."_".$gambarHapus[$i].".jpg");
            }
        }
    }

    $array = array();
    $sql = "UPDATE tdpemantauan set SERIALNUMBER=:SERIALNUMBER,PSI=:TEKANANANGIN,TD=:TD,KETERANGAN=:KETERANGAN WHERE NOMORBUKTI=:NOMORBUKTI AND POSISI=:POSISI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'SERIALNUMBER'=>$SERIALNUMBER,
        'TEKANANANGIN'=>$TEKANANANGIN,
        'TD'=>$TD,
        'KETERANGAN'=>$KETERANGAN,
        'NOMORBUKTI'=>$NOMORBUKTI,
        'POSISI'=>$POSISI]);


    if(isset($NAMAFOTO)){
        foreach ($NAMAFOTO as $key => $value) {
            $KODEGAMBAR = $NOMORBUKTI."_".$POSISI."_".$value;
            $sql = "INSERT INTO gambartdpemantauan (NOMORBUKTI, NAMAGAMBAR) values (:NOMORBUKTI,:NAMAGAMBAR)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'NAMAGAMBAR'=>$KODEGAMBAR]);
        }
    }

    
    echo json_encode($array);
    break;

    case "EDIT_TM_PEMANTAUAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $NOMORPLAT = $_POST['NOMORPLAT'];
    $ODOMETER = $_POST['ODOMETER'];
    $TANGGAL = $_POST['TANGGAL'];

    if(isset($_POST['namaFotoPemantauan'])){
        $NAMAFOTO = $_POST['namaFotoPemantauan'];
    } else {
        $NAMAFOTO = NULL;
    }

    if(isset($_POST['HAPUSGAMBAR'])){
        $gambarHapus = $_POST['HAPUSGAMBAR'];
    } else { $gambarHapus = null; }

    try {
        $sql = "SELECT * FROM tmpemantauan WHERE NOMORPLAT=:NOMORPLAT AND TANGGAL =:TANGGAL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORPLAT'=>$NOMORPLAT,'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
        if($pemantauan = $stmt->fetch()){
            if($NOMORBUKTI == $pemantauan['NOMORBUKTI']){
                $array['hasil'] = "berhasil";
                $sql = "SELECT max(ODOMETER) as ODOMETER FROM tmpemantauan WHERE NOMORPLAT=:NOMORPLAT AND TANGGAL<:TANGGAL";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORPLAT'=>$NOMORPLAT,'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
                if($pemantauan = $stmt->fetch()){
                    if($ODOMETER>$pemantauan['ODOMETER'] || !$pemantauan['ODOMETER']){
                        $array['hasil'] = "berhasil";
                    } else {
                        $array['hasil'] = "odometer_rendah";
                        throw new Exception("error");
                    }
                } else {
                    $array['hasil'] = "berhasil";
                }

                $sql = "SELECT min(ODOMETER) AS ODOMETER FROM tmpemantauan WHERE NOMORPLAT=:NOMORPLAT AND TANGGAL>:TANGGAL";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORPLAT'=>$NOMORPLAT,'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
                if($pemantauan = $stmt->fetch()){
                    if($ODOMETER<$pemantauan['ODOMETER'] || !$pemantauan['ODOMETER']){
                        $array['hasil'] = "berhasil";
                    } else {
                        $array['hasil'] = "odometer_tinggi";
                        throw new Exception("error");
                    }
                } else {
                    $array['hasil'] = "berhasil";
                }
            } else {
                $array['hasil'] = "tanggal_sama_beda_nomorbukti";
                throw new Exception("error");
            }
        } else {
            $array['hasil'] = "berhasil";
        }

        $sql = "SELECT max(ODOMETER) as ODOMETER FROM tmpemantauan WHERE NOMORPLAT=:NOMORPLAT AND TANGGAL<:TANGGAL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORPLAT'=>$NOMORPLAT,'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
        if($pemantauan = $stmt->fetch()){
            if($ODOMETER>$pemantauan['ODOMETER'] || !$pemantauan['ODOMETER']){
                $array['hasil'] = "berhasil";
            } else {
                $array['hasil'] = "odometer_rendah";
                throw new Exception("error");
            }
        } else {
            $array['hasil'] = "berhasil";
        }

        $sql = "SELECT min(ODOMETER) AS ODOMETER FROM tmpemantauan WHERE NOMORPLAT=:NOMORPLAT AND TANGGAL>:TANGGAL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORPLAT'=>$NOMORPLAT,'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
        if($pemantauan = $stmt->fetch()){
            if($ODOMETER<$pemantauan['ODOMETER'] || !$pemantauan['ODOMETER']){
                $array['hasil'] = "berhasil";
            } else {
                $array['hasil'] = "odometer_tinggi";
                throw new Exception("error");
            }
        } else {
            $array['hasil'] = "berhasil";
        }

        

        if($array['hasil'] == "berhasil"){
            $sql = "UPDATE tmpemantauan set ODOMETER=:ODOMETER,NOMORPLAT=:NOMORPLAT WHERE NOMORBUKTI =:NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['ODOMETER'=>$ODOMETER,'NOMORPLAT'=>$NOMORPLAT,'NOMORBUKTI'=>$NOMORBUKTI]);

            if($gambarHapus != null){
                for($i = 0; $i<count($gambarHapus); $i++){
                    $stmt = $pdo->prepare("DELETE FROM gambarpemantauan WHERE NOMORBUKTI_PEMANTAUAN_BAN=:NOMORBUKTI AND NAMAGAMBAR=:NAMAGAMBAR");
                    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'NAMAGAMBAR'=>$NOMORBUKTI."_".$gambarHapus[$i]]);

                    if(file_exists("gambar_pemantauan_ban/".$NOMORBUKTI."_".$gambarHapus[$i].".jpg")){
                        unlink("gambar_pemantauan_ban/".$NOMORBUKTI."_".$gambarHapus[$i].".jpg");
                    }
                }
            }

            if(isset($NAMAFOTO) && $array['hasil'] == "berhasil"){
                foreach ($NAMAFOTO as $key => $value) {
                    $KODEGAMBAR = $NOMORBUKTI."_".$value;
                    $sql = "INSERT INTO gambarpemantauan (NOMORBUKTI_PEMANTAUAN_BAN, NAMAGAMBAR) values (:NOMORBUKTI,:NAMAGAMBAR)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'NAMAGAMBAR'=>$KODEGAMBAR]);
                }
            }
        }


    } catch (Exception $e){
        //$array['hasil'] = "error";
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){
            $sql = "DELETE FROM tmpemasangan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);

            $sql = "DELETE FROM tdpemasangan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
        }

    }
    /*if($gambarHapus != null){
        for($i = 0; $i<count($gambarHapus); $i++){
            $stmt = $pdo->prepare("DELETE FROM gambartdpemantauan WHERE NOMORBUKTI=:NOMORBUKTI AND NAMAGAMBAR=:NAMAGAMBAR");
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'NAMAGAMBAR'=>$NOMORBUKTI."_".$POSISI."_".$gambarHapus[$i]]);

            if(file_exists("gambar_td_pemantauan_ban/".$NOMORBUKTI."_".$POSISI."_".$gambarHapus[$i].".jpg")){
                unlink("gambar_td_pemantauan_ban/".$NOMORBUKTI."_".$POSISI."_".$gambarHapus[$i].".jpg");
            }
        }
    }*/

    echo json_encode($array);
    break;

    case "GET_POSISI_TD_PEMANTAUAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT * FROM tdpemantauan WHERE NOMORBUKTI LIKE :NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $array = array();
    $id = 0;
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI.'%']);
    if($posisi = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['POSISI'][$id] = $posisi['POSISI'];
        $id++;
        while($posisi = $stmt->fetch()){
            $array['POSISI'][$id] = $posisi['POSISI'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_KENDARAAN":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    if($USERID == "MPT-1" || $USERID == "MPT-2" || $USERID == "MPT-3"){

        $sql = "SELECT DISTINCT mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA as NAMA FROM mkendaraan mk INNER JOIN mpelanggan mp on(mk.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG AND mp.KODEPELANGGAN = 'PT.MODERN'";
    } else {

        $sql = "SELECT DISTINCT mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA as NAMA FROM mkendaraan mk INNER JOIN mpelanggan mp on(mk.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    $id = 0;
    $array = array();
    if($kendaraan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $kendaraan['KODEPELANGGAN'];
        $array['NAMA'][$id] = $kendaraan['NAMA'];
        $id++;
        while($kendaraan = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $kendaraan['KODEPELANGGAN'];
            $array['NAMA'][$id] = $kendaraan['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "INPUT_MASTER_KENDARAAN":
    $array = array();
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $NOMORPLAT = $_POST['NOMORPLAT'];
    $KAROSERI = $_POST['KAROSERI'];
    $KONFIGURASITRUK = $_POST['KONFIGURASITRUK'];
    $USERID = $_POST['USERID'];
    $sql = "SELECT * FROM mkendaraan WHERE KODEPELANGGAN=:KODEPELANGGAN AND NOMORPLAT=:NOMORPLAT";
    $stmt = $pdo->prepare($sql);
    try{
        foreach($NOMORPLAT as $key =>$value){
            $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN,'NOMORPLAT'=>$value]);
            if($checking = $stmt->fetch()){
                $array['hasil'] = "duplicate_nomorplat";
                throw new Exception("error");
            }
        }

        $sql = "INSERT INTO mkendaraan (KODEPELANGGAN,NOMORPLAT,KAROSERI,KONFIGURASITRUK,USERID,STATUS) values(:KODEPELANGGAN,:NOMORPLAT,:KAROSERI,:KONFIGURASITRUK,:USERID,:STATUS)";
        $stmt = $pdo->prepare($sql);
        foreach($NOMORPLAT as $key =>$value){
            $stmt->execute([
                'KODEPELANGGAN'=>$KODEPELANGGAN,
                'NOMORPLAT'=>$value,
                'KAROSERI'=>$KAROSERI,
                'KONFIGURASITRUK'=>$KONFIGURASITRUK,
                'USERID'=>$USERID,
                'STATUS'=>'OPEN']);
        }
        $array['hasil'] = "berhasil";

    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){

        }

    }
    echo json_encode($array);
    break;

    case "TAMPIL_ISI_KENDARAAN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $sql = "SELECT * FROM mkendaraan WHERE KODEPELANGGAN =:KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    $array = array();
    $id = 0;
    if($plat = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $plat['KODEPELANGGAN'];
        $array['NOMORPLAT'][$id] = $plat['NOMORPLAT'];
        $array['ODOMETERPASANG'][$id] = $plat['ODOMETERPASANG'];
        $array['ODOMETERAKHIR'][$id] = $plat['ODOMETERAKHIR'];
        $array['ODOMETERTOTAL'][$id] = $plat['ODOMETERTOTAL'];
        $array['TDAWAL'][$id] = $plat['TDAWAL'];
        $array['TDAKHIR'][$id] = $plat['TDAKHIR'];
        $array['TDSELISIH'][$id] = $plat['TDSELISIH'];
        $array['USERID'][$id] = $plat['USERID'];
        $array['STATUS'][$id] = $plat['STATUS'];
        $id++;
        while($plat = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $plat['KODEPELANGGAN'];
            $array['NOMORPLAT'][$id] = $plat['NOMORPLAT'];
            $array['ODOMETERPASANG'][$id] = $plat['ODOMETERPASANG'];
            $array['ODOMETERAKHIR'][$id] = $plat['ODOMETERAKHIR'];
            $array['ODOMETERTOTAL'][$id] = $plat['ODOMETERTOTAL'];
            $array['TDAWAL'][$id] = $plat['TDAWAL'];
            $array['TDAKHIR'][$id] = $plat['TDAKHIR'];
            $array['TDSELISIH'][$id] = $plat['TDSELISIH'];
            $array['USERID'][$id] = $plat['USERID'];
            $array['STATUS'][$id] = $plat['STATUS'];
            $id++;
        }
        
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "POPUP_TAMPIL_PELANGGAN_NOMORPLAT":
    $KODECABANG = $_POST['KODECABANG'];
    $USERID = $_POST['USERID'];
    if($USERID == "MPT-1" || $USERID == "MPT-2" || $USERID == "MPT-3"){
        $sql = "SELECT DISTINCT mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA FROM mkendaraan mk INNER JOIN mpelanggan mp on(mk.KODEPELANGGAN=mp.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG AND mp.KODEPELANGGAN = 'PT.MODERN'";
    } else {
        $sql = "SELECT DISTINCT mp.KODEPELANGGAN as KODEPELANGGAN, mp.NAMA as NAMA FROM mkendaraan mk INNER JOIN mpelanggan mp on(mk.KODEPELANGGAN=mp.KODEPELANGGAN) WHERE mp.KODECABANG =:KODECABANG";
    }
    $stmt = $pdo->prepare($sql);
    $array = array();
    $id = 0;
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($pelanggan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['KODEPELANGGAN'][$id] = $pelanggan['KODEPELANGGAN'];
        $array['NAMA'][$id] = $pelanggan['NAMA'];
        $id++;
        while($pelanggan = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $pelanggan['KODEPELANGGAN'];
            $array['NAMA'][$id] = $pelanggan['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "POPUP_TAMPIL_NOMORPLAT":
    $array = array();
    $id = 0;
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $sql = "SELECT * FROM mkendaraan WHERE KODEPELANGGAN = :KODEPELANGGAN";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEPELANGGAN'=>$KODEPELANGGAN]);
    if($nomor = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORPLAT'][$id] = $nomor['NOMORPLAT'];
        $array['KAROSERI'][$id] = $nomor['KAROSERI'];
        $array['KONFIGURASITRUK'][$id] = $nomor['KONFIGURASITRUK'];
        $id++;
        while($nomor = $stmt->fetch()){
            $array['NOMORPLAT'][$id] = $nomor['NOMORPLAT'];
            $array['KAROSERI'][$id] = $nomor['KAROSERI'];
            $array['KONFIGURASITRUK'][$id] = $nomor['KONFIGURASITRUK'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "INPUT_PEMASANGAN_BANN":
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $TANGGAL = $_POST['TANGGAL'];
    $NOMORPLAT = $_POST['NOMORPLAT'];
    $ODOMETER = $_POST['ODOMETER'];
    $NAMASOPIR = $_POST['NAMASOPIR'];
    $NOHPSOPIR = $_POST['NOHPSOPIR'];
    $NAMAPENGAWAS = $_POST['NAMAPENGAWAS'];
    $NOHPPENGAWAS = $_POST['NOHPPENGAWAS'];
    $MEREKTRUK = $_POST['MEREKTRUK'];
    $KONFIGURASITRUK = $_POST['KONFIGURASITRUK'];
    $KAROSERI = $_POST['KAROSERI'];
    $USERID = $_POST['USERID'];
    $KESIMPULAN = $_POST['KESIMPULAN'];
    $array = array();
    //print_r($_POST);

    if(isset($_POST['KODEBARANG'])){
        $KODEBARANG = $_POST['KODEBARANG'];
    } else { $KODEBARANG = null; }

    if(isset($_POST['SERIALNUMBER'])){
        $SERIALNUMBER = $_POST['SERIALNUMBER'];
    } else { $SERIALNUMBER = null; }

    if(isset($_POST['POSISI'])){
        $POSISI = $_POST['POSISI'];
    } else { $POSISI = null; }

    if(isset($_POST['TEKANANANGIN'])){
        $TEKANANANGIN = $_POST['TEKANANANGIN'];
    } else { $TEKANANANGIN = null; }

    if(isset($_POST['TD'])){
        $TD = $_POST['TD'];
    } else { $TD = null; }

    if(isset($_POST['HARGA'])){
        $HARGA = $_POST['HARGA'];
    } else { $HARGA = null; }

    if(isset($_POST['KETERANGAN'])){
        $KETERANGAN = $_POST['KETERANGAN'];
    } else { $KETERANGAN = null; }


    $NOMORBUKTI = "PB".date('ymd',strtotime($TANGGAL));

    $sql = "SELECT * FROM tmpemasangan WHERE TANGGAL = '".date('Y-m-d',strtotime($TANGGAL))."' AND NOMORBUKTI LIKE 'PB%' ORDER BY NOMORBUKTI DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
    if($row = $stmt->fetch()){
        $counter = substr($row['NOMORBUKTI'], -3);
        $counter++;
        if(strlen($counter) == 1){
            $NOMORBUKTI .= "00" .$counter;

        } else if (strlen($counter) == 2){
            $NOMORBUKTI .= "0" .$counter;
        } else {
            $NOMORBUKTI .= $counter;
        }
    } else {
        $NOMORBUKTI .= "001";
    }

    
    try {
        $sql = "SELECT * FROM tmpemasangan WHERE NOMORPLAT=:NOMORPLAT AND TANGGAL=:TANGGAL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORPLAT'=>$NOMORPLAT,'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
        if($stmt->fetch()){
            $array['hasil'] = "duplicated";
            throw new Exception("error");
        } else {
            $array['hasil'] = "berhasil";
        }
        $id = 0;

        $sql = "SELECT * FROM tmpemasangan WHERE NOMORPLAT =:NOMORPLAT AND TANGGAL>:TANGGAL AND ODOMETER<:ODOMETER  ORDER BY TANGGAL ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'TANGGAL' => date("Y-m-d",strtotime($TANGGAL)),
            'ODOMETER' => $ODOMETER,
            'NOMORPLAT' => $NOMORPLAT
        ]);
        if($pemasangan = $stmt->fetch()){
            $array = [
                'hasil' => "odometer_ketinggian",
                'odometer' => $pemasangan['ODOMETER'],
                'tanggal' => $pemasangan['TANGGAL']
            ];
            throw new Exception("error");
        } 

        $sql = "SELECT * FROM tmpemasangan WHERE NOMORPLAT =:NOMORPLAT AND TANGGAL<:TANGGAL AND ODOMETER>:ODOMETER  ORDER BY TANGGAL ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'TANGGAL' => date("Y-m-d",strtotime($TANGGAL)),
            'ODOMETER' => $ODOMETER,
            'NOMORPLAT' => $NOMORPLAT
        ]);
        if($pemasangan = $stmt->fetch()){
            $array = [
                'hasil' => "odometer_kerendahan",
                'value' => $pemasangan['ODOMETER'],
                'tanggal' => $pemasangan['TANGGAL']
            ];
            throw new Exception("error");
        }

        $sql = "INSERT INTO tmpemasangan (NOMORBUKTI,KODEPELANGGAN,TANGGAL,NOMORPLAT,ODOMETER, NAMASOPIR, NOHPSOPIR, NAMAPENGAWAS, NOHPPENGAWAS, MEREKTRUK, KONFIGURASITRUK, KAROSERI,USERID, STATUS,KESIMPULAN) values(:NOMORBUKTI,:KODEPELANGGAN,:TANGGAL,:NOMORPLAT,:ODOMETER,:NAMASOPIR,:NOHPSOPIR,:NAMAPENGAWAS,:NOHPPENGAWAS,:MEREKTRUK,:KONFIGURASITRUK,:KAROSERI,:USERID,:STATUS,:KESIMPULAN)";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute([
            'NOMORBUKTI'=>$NOMORBUKTI,
            'KODEPELANGGAN'=>$KODEPELANGGAN,
            'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL)),
            'NOMORPLAT'=>$NOMORPLAT,
            'ODOMETER'=>$ODOMETER,
            'NAMASOPIR'=>$NAMASOPIR,
            'NOHPSOPIR'=>$NOHPSOPIR,
            'NAMAPENGAWAS'=>$NAMAPENGAWAS,
            'NOHPPENGAWAS'=>$NOHPPENGAWAS,
            'MEREKTRUK'=>$MEREKTRUK,
            'KONFIGURASITRUK'=>$KONFIGURASITRUK,
            'KAROSERI'=>$KAROSERI,
            'USERID'=>$USERID,
            'STATUS'=>'OPEN',
            'KESIMPULAN'=>$KESIMPULAN
        ])){
            $array['hasil'] = "berhasil";
            $array['NOMORBUKTI'] = $NOMORBUKTI;
        } else {
            $array['hasil'] = "error";
            throw new Exception("error");
        }

    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){
            $sql = "DELETE FROM tmpemasangan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
        }

    }



    echo json_encode($array);
    break;

    case "INPUT_DETAIL_PEMASANGAN_BAN":
    $KODEBARANG = $_POST['KODEBARANG'];
    $SERIALNUMBER = $_POST['SERIALNUMBER'];
    $TEKANANANGIN = $_POST['TEKANANANGIN'];
    $TD = $_POST['TD'];
    $TD2 = $_POST['TD2'];
    $TD3 = $_POST['TD3'];
    $TD4 = $_POST['TD4'];
    $POSISI = $_POST['POSISI'];
    $HARGA = $_POST['HARGA'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $array = array();
    if(isset($_POST['namaFoto'])){
        $namaFoto = $_POST['namaFoto'];
    } else { $namaFoto = null; }

    try{
        $sql = "SELECT * FROM tdpemasangan WHERE SERIALNUMBER=:SERIALNUMBER";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['SERIALNUMBER'=>$SERIALNUMBER]);
        if($stmt->fetch()){
            $array['hasil'] = "duplicate_serialnumber";
            throw new Exception("error");
        }

        $sql = "INSERT INTO tdpemasangan (NOMORBUKTI,KODEBARANG,SERIALNUMBER,POSISI,TEKANANANGIN,TD1,HARGA,KETERANGAN,TD2,TD3,TD4) values(:NOMORBUKTI,:KODEBARANG,:SERIALNUMBER,:POSISI,:TEKANANANGIN,:TD,:HARGA,:KETERANGAN,:TD2,:TD3,:TD4)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'NOMORBUKTI'=>$NOMORBUKTI,
            'KODEBARANG'=>$KODEBARANG,
            'SERIALNUMBER'=>$SERIALNUMBER,
            'POSISI'=>$POSISI,
            'TEKANANANGIN'=>$TEKANANANGIN,
            'TD'=>$TD,
            'HARGA'=>$HARGA,
            'KETERANGAN'=>$KETERANGAN,
            'TD2'=>$TD2,
            'TD3'=>$TD3,
            'TD4'=>$TD4]);
        $LASTID = $pdo->lastInsertId();
        

        if(isset($namaFoto)){


            $counter = 0;
            foreach ($namaFoto as $key => $value) {
                $sql = "SELECT * FROM gambartdpemasangan ORDER BY ID DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                if($checking = $stmt->fetch()){
                    $id = $checking['ID'] + 1;
                } else {
                    $id = 1;
                }

                $sql = "INSERT INTO gambartdpemasangan ( ID_TDPEMASANGAN,NAMAGAMBAR) values (:ID_TDPEMASANGAN,:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['ID_TDPEMASANGAN'=>$LASTID,'NAMAGAMBAR'=>"PB_".$id]);
                $array['namaFoto'][$counter] = "PB_".$id;
                $counter++;
            }
            $array['hasilfoto'] = "ada";
        }

        $array['hasil'] = "berhasil";
    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){
            $sql = "DELETE FROM tmpemasangan WHERE NOMORBUKTI = :NOMORBUKTI";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
        }
    }
    

    echo json_encode($array);
    break;

    case "CEK_INPUT_DETAIL_PEMASANGAN_BAN":
    $array = array();
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $POSISI = $_POST['POSISI'];
    $sql = "SELECT td.ID as ID,mb.kodebarang AS KODEBARANG, mb.nama AS NAMA, td.SERIALNUMBER as SERIALNUMBER, td.TEKANANANGIN as TEKANANANGIN, td.TD1 as TD1, td.TD2 as TD2, td.TD3 as TD3, td.TD4 as TD4, td.HARGA as HARGA, td.KETERANGAN as KETERANGAN FROM tdpemasangan td INNER JOIN mbrg mb on (mb.kodebarang = td.KODEBARANG) WHERE NOMORBUKTI = :NOMORBUKTI AND POSISI =:POSISI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI,'POSISI'=>$POSISI]);
    if($pemasangan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NAMA'] = $pemasangan['NAMA'];
        $array['ID'] = $pemasangan['ID'];
        $array['KODEBARANG'] = $pemasangan['KODEBARANG'];
        $array['SERIALNUMBER'] = $pemasangan['SERIALNUMBER'];
        $array['TEKANANANGIN'] = $pemasangan['TEKANANANGIN'];
        $array['TD1'] = $pemasangan['TD1'];
        $array['TD2'] = $pemasangan['TD2'];
        $array['TD3'] = $pemasangan['TD3'];
        $array['TD4'] = $pemasangan['TD4'];
        $array['HARGA'] = $pemasangan['HARGA'];
        $array['KETERANGAN'] = $pemasangan['KETERANGAN'];

        $sql = "SELECT * FROM gambartdpemasangan WHERE ID_TDPEMASANGAN=:ID_TDPEMASANGAN";
        $stmt = $pdo->prepare($sql);
        $id = 0;
        $stmt->execute(['ID_TDPEMASANGAN'=>$array['ID']]);
        if($gambar = $stmt->fetch()){
            $array['foto'] = "ada";
            $array['ID_GAMBAR'][$id] = $gambar['ID'];
            $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
            $id++;
            while($gambar = $stmt->fetch()){
                $array['ID_GAMBAR'][$id] = $gambar['ID'];
                $array['NAMAGAMBAR'][$id] = $gambar['NAMAGAMBAR'];
                $id++;
            }
        } else {
            $array['foto'] = "tidakada";
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    
    echo json_encode($array);
    break;


    case "EDIT_DETAIL_PEMASANGAN_BAN":
    $KODEBARANG = $_POST['KODEBARANG'];
    $SERIALNUMBER = $_POST['SERIALNUMBER'];
    $TEKANANANGIN = $_POST['TEKANANANGIN'];
    $TD = $_POST['TD'];
    $TD2 = $_POST['TD2'];
    $TD3 = $_POST['TD3'];
    $TD4 = $_POST['TD4'];
    $POSISI = $_POST['POSISI'];
    $HARGA = $_POST['HARGA'];
    $KETERANGAN = $_POST['KETERANGAN'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $array = array();
    $ID_TDPEMASANGAN = $_POST['ID'];
    if(isset($_POST['namaFoto'])){
        $namaFoto = $_POST['namaFoto'];
    } else { $namaFoto = null; }

    if(isset($_POST['gambarHapus'])){
        $gambarHapus = $_POST['gambarHapus'];
    } else { $gambarHapus = null; }
    

    

    try{
        $sql = "SELECT * FROM tdpemasangan WHERE SERIALNUMBER=:SERIALNUMBER AND ID !=:ID_TDPEMASANGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['SERIALNUMBER'=>$SERIALNUMBER,'ID_TDPEMASANGAN'=>$ID_TDPEMASANGAN]);
        if($stmt->fetch()){
            $array['hasil'] = "duplicate_serialnumber";
            throw new Exception("error");
        }

        
        $sql = "UPDATE tdpemasangan set KODEBARANG=:KODEBARANG,SERIALNUMBER=:SERIALNUMBER, POSISI=:POSISI, TEKANANANGIN=:TEKANANANGIN, TD1=:TD, HARGA=:HARGA, KETERANGAN=:KETERANGAN,TD2=:TD2,TD3=:TD3, TD4=:TD4 WHERE ID =:ID_TDPEMASANGAN";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODEBARANG'=>$KODEBARANG,
            'SERIALNUMBER'=>$SERIALNUMBER,
            'POSISI'=>$POSISI,
            'TEKANANANGIN'=>$TEKANANANGIN,
            'TD'=>$TD,
            'HARGA'=>$HARGA,
            'KETERANGAN'=>$KETERANGAN,
            'ID_TDPEMASANGAN'=>$ID_TDPEMASANGAN,
            'TD2'=>$TD2,
            'TD3'=>$TD3,
            'TD4'=>$TD4]);

        if(isset($namaFoto)){
            $counter = 0;
            foreach ($namaFoto as $key => $value) {
                $sql = "SELECT * FROM gambartdpemasangan ORDER BY ID DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                if($checking = $stmt->fetch()){
                    $id = $checking['ID'] + 1;
                } else {
                    $id = 1;
                }

                $sql = "INSERT INTO gambartdpemasangan ( ID_TDPEMASANGAN,NAMAGAMBAR) values (:ID_TDPEMASANGAN,:NAMAGAMBAR)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['ID_TDPEMASANGAN'=>$ID_TDPEMASANGAN,'NAMAGAMBAR'=>"PB_".$id]);
                $array['namaFoto'][$counter] = "PB_".$id;
                $counter++;
            }
            $array['hasilfoto'] = "ada";
        }

        if($gambarHapus != null){
            for($i = 0; $i<count($gambarHapus); $i++){
                $stmt = $pdo->prepare("DELETE FROM gambartdpemasangan WHERE NAMAGAMBAR=:NAMAGAMBAR");
                $stmt->execute(['NAMAGAMBAR'=>$gambarHapus[$i]]);

                if(file_exists("gambar_td_pemasangan_ban/".$gambarHapus[$i].".jpg")){
                    unlink("gambar_td_pemasangan_ban/".$gambarHapus[$i].".jpg");
                }

            }
        }

        $array['hasil'] = "berhasil";
    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
        if($e->getMessage() == "error"){

        }
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_PEMASANGAN_BAN":
    $array = array();
    $id = 0;
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];

    $sql = "SELECT * FROM tmpemasangan tmp INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tmp.KODEPELANGGAN) WHERE mp.KODECABANG = :KODECABANG";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);

    if($row = $stmt->fetch()){
        $TANGGAL = $row['TANGGAL'];
        $counter = 1;
        $array['hasil'] = "ada";
        $array['TANGGAL'][$id] = $row['TANGGAL'];
        $id++;
        while($row = $stmt->fetch()){
            $array['TANGGAL'][$id] = $row['TANGGAL'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    if($array['hasil'] == "ada"){


        $arrayTampung= array_unique($array['TANGGAL']);

        function date_sort($a, $b) {
            return strtotime($b) - strtotime($a);
        }


        usort($arrayTampung, "date_sort");
        $array = array();

        for($i = 0; $i<count($arrayTampung); $i++){
            $array['TANGGAL'][$i] = $arrayTampung[$i];
            $array['hasil'] = "ada";
        }

        if($counter == 1){
            $array['hasil'] = "ada";
        } else {
            $array['hasil'] = "tidakada";
        }
    }

    echo json_encode($array);
    break;

    case "TAMPIL_DETAIL_PEMASANGAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $array = array();
    $id = 0;

    $sql = "SELECT tm.KESIMPULAN AS KESIMPULAN, mp.NAMA as NAMA, mp.KODEPELANGGAN as KODEPELANGGAN, tm.NOMORPLAT as NOMORPLAT, tm.TANGGAL AS TANGGAL, tm.ODOMETER AS ODOMETER, tm.NAMASOPIR as NAMASOPIR, tm.NOHPSOPIR AS NOHPSOPIR, tm.NAMAPENGAWAS AS NAMAPENGAWAS, tm.NOHPPENGAWAS AS NOHPPENGAWAS, tm.MEREKTRUK AS MEREKTRUK, tm.KONFIGURASITRUK AS KONFIGURASITRUK, tm.KAROSERI AS KAROSERI, tm.USERID AS USERID, tm.STATUS as STATUS FROM tmpemasangan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE NOMORBUKTI=:NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    if($pemasangan = $stmt->fetch()){
        $array['hasil_tm'] = "ada";
        $array['NAMA'] = $pemasangan['NAMA'];
        $array['KODEPELANGGAN'] = $pemasangan['KODEPELANGGAN'];
        $array['NAMASOPIR'] = $pemasangan['NAMASOPIR'];
        $array['NOHPSOPIR'] = $pemasangan['NOHPSOPIR'];
        $array['NAMAPENGAWAS'] = $pemasangan['NAMAPENGAWAS'];
        $array['NOHPPENGAWAS'] = $pemasangan['NOHPPENGAWAS'];
        $array['KONFIGURASITRUK'] = $pemasangan['KONFIGURASITRUK'];
        $array['MEREKTRUK'] = $pemasangan['MEREKTRUK'];
        $array['KAROSERI'] = $pemasangan['KAROSERI'];
        $array['KESIMPULAN'] = $pemasangan['KESIMPULAN'];

        $array['ODOMETER'] = $pemasangan['ODOMETER'];
        $array['TANGGAL'] = $pemasangan['TANGGAL'];
        $array['NOMORPLAT'] = $pemasangan['NOMORPLAT'];
        $array['USERID'] = $pemasangan['USERID'];
        $array['STATUSTM'] = $pemasangan['STATUS'];
    } else {
        $array['hasil_tm'] = "tidakada";
    }

    $sql = "SELECT td.SERIALNUMBER AS SERIALNUMBER, td.POSISI AS POSISI, td.TEKANANANGIN AS TEKANANANGIN, td.TD1 AS TD1, td.TD2 as TD2, td.TD3 AS TD3, td.TD4 AS TD4, td.HARGA as HARGA, td.KETERANGAN AS KETERANGAN, mb.nama AS NAMABARANG FROM tdpemasangan td INNER JOIN tmpemasangan tm on(tm.NOMORBUKTI = td.NOMORBUKTI) INNER JOIN mbrg mb on(mb.KODEBARANG = td.KODEBARANG) WHERE td.NOMORBUKTI =:NOMORBUKTI ORDER BY td.POSISI ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    $id = 0;
    if($pemasangan = $stmt->fetch()){
        $array['hasil_td'] = "ada";
        $array['TD_PEMASANGAN'][$id] = [
            'SERIALNUMBER' => $pemasangan['SERIALNUMBER'],
            'POSISI' => $pemasangan['POSISI'],
            'TEKANANANGIN' => $pemasangan['TEKANANANGIN'],
            'TD1' => $pemasangan['TD1'],
            'TD2' => $pemasangan['TD2'],
            'TD3' => $pemasangan['TD3'],
            'TD4' => $pemasangan['TD4'],
            'HARGA' => 'Rp. '.number_format($pemasangan['HARGA'],0,",","."),
            'KETERANGAN' => $pemasangan['KETERANGAN'],
            'NAMABARANG' => $pemasangan['NAMABARANG'],
        ];
        $id++;
        while($pemasangan = $stmt->fetch()){
            $array['TD_PEMASANGAN'][$id] = [
                'SERIALNUMBER' => $pemasangan['SERIALNUMBER'],
                'POSISI' => $pemasangan['POSISI'],
                'TEKANANANGIN' => $pemasangan['TEKANANANGIN'],
                'TD1' => $pemasangan['TD1'],
                'TD2' => $pemasangan['TD2'],
                'TD3' => $pemasangan['TD3'],
                'TD4' => $pemasangan['TD4'],
                'HARGA' => 'Rp. '.number_format($pemasangan['HARGA'],0,",","."),
                'KETERANGAN' => $pemasangan['KETERANGAN'],
                'NAMABARANG' => $pemasangan['NAMABARANG'],
            ];
            $id++;
        }
    } else {
        $array['hasil_td'] = "tidakada";
    }

    echo json_encode($array);

    break;

    case "EDIT_PEMASANGAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $sql = "SELECT mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN,tm.TANGGAL AS TANGGAL, tm.NOMORPLAT AS NOMORPLAT, tm.ODOMETER AS ODOMETER, tm.NAMASOPIR AS NAMASOPIR, tm.NOHPSOPIR as NOHPSOPIR, tm.NAMAPENGAWAS AS NAMAPENGAWAS, tm.NOHPPENGAWAS AS NOHPPENGAWAS, tm.MEREKTRUK AS MEREKTRUK, tm.KONFIGURASITRUK AS KONFIGURASITRUK, tm.KAROSERI AS KAROSERI, tm.KESIMPULAN AS KESIMPULAN FROM tmpemasangan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE tm.NOMORBUKTI = :NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
    $array = array();
    if($pemasangan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['PEMASANGAN'] = [
            'KODEPELANGGAN' => $pemasangan['KODEPELANGGAN'],
            'NAMAPELANGGAN' => $pemasangan['NAMAPELANGGAN'],
            'TANGGAL' => $pemasangan['TANGGAL'],
            'NOMORPLAT' => $pemasangan['NOMORPLAT'],
            'ODOMETER' => $pemasangan['ODOMETER'],
            'NAMASOPIR' => $pemasangan['NAMASOPIR'],
            'NOHPSOPIR' => $pemasangan['NOHPSOPIR'],
            'NAMAPENGAWAS' => $pemasangan['NAMAPENGAWAS'],
            'NOHPPENGAWAS' => $pemasangan['NOHPPENGAWAS'],
            'MEREKTRUK' => $pemasangan['MEREKTRUK'],
            'KONFIGURASITRUK' => $pemasangan['KONFIGURASITRUK'],
            'KAROSERI' => $pemasangan['KAROSERI'],
            'KESIMPULAN' => $pemasangan['KESIMPULAN']
        ];
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "UPDATE_PEMASANGAN_BAN":
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
    $TANGGAL = $_POST['TANGGAL'];
    $NOMORPLAT = $_POST['NOMORPLAT'];
    $ODOMETER = $_POST['ODOMETER'];
    $NAMASOPIR = $_POST['NAMASOPIR'];
    $NOHPSOPIR = $_POST['NOHPSOPIR'];
    $NAMAPENGAWAS = $_POST['NAMAPENGAWAS'];
    $NOHPPENGAWAS = $_POST['NOHPPENGAWAS'];
    $MEREKTRUK = $_POST['MEREKTRUK'];
    $KONFIGURASITRUK = $_POST['KONFIGURASITRUK'];
    $KAROSERI = $_POST['KAROSERI'];
    $USERID = $_POST['USERID'];
    try{
        $array = array();

        $sql = "SELECT * FROM tmpemasangan WHERE NOMORPLAT =:NOMORPLAT AND TANGGAL>:TANGGAL AND ODOMETER<:ODOMETER AND NOMORBUKTI !=:NOMORBUKTI ORDER BY TANGGAL ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'TANGGAL' => date("Y-m-d",strtotime($TANGGAL)),
            'ODOMETER' => $ODOMETER,
            'NOMORPLAT' => $NOMORPLAT,
            'NOMORBUKTI' => $NOMORBUKTI
        ]);
        if($pemasangan = $stmt->fetch()){
            $array = [
                'hasil' => "odometer_ketinggian",
                'odometer' => $pemasangan['ODOMETER'],
                'tanggal' => $pemasangan['TANGGAL']
            ];
            throw new Exception("error");
        } 

        $sql = "SELECT * FROM tmpemasangan WHERE NOMORPLAT =:NOMORPLAT AND TANGGAL<:TANGGAL AND ODOMETER>:ODOMETER AND NOMORBUKTI !=:NOMORBUKTI ORDER BY TANGGAL ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'TANGGAL' => date("Y-m-d",strtotime($TANGGAL)),
            'ODOMETER' => $ODOMETER,
            'NOMORPLAT' => $NOMORPLAT,
            'NOMORBUKTI' => $NOMORBUKTI
        ]);
        if($pemasangan = $stmt->fetch()){
            $array = [
                'hasil' => "odometer_kerendahan",
                'value' => $pemasangan['ODOMETER'],
                'tanggal' => $pemasangan['TANGGAL']
            ];
            throw new Exception("error");
        }

        $sql = "UPDATE tmpemasangan set TANGGAL=:TANGGAL, ODOMETER=:ODOMETER, NAMASOPIR=:NAMASOPIR, NOHPSOPIR=:NOHPSOPIR, NAMAPENGAWAS=:NAMAPENGAWAS, NOHPPENGAWAS=:NOHPPENGAWAS, MEREKTRUK=:MEREKTRUK, KONFIGURASITRUK=:KONFIGURASITRUK, KAROSERI=:KAROSERI, USERID=:USERID WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'NOMORBUKTI'=>$NOMORBUKTI,
            'TANGGAL'=>date('Y-m-d',strtotime($TANGGAL)),
            'ODOMETER'=>$ODOMETER,
            'NAMASOPIR'=>$NAMASOPIR,
            'NOHPSOPIR'=>$NOHPSOPIR,
            'NAMAPENGAWAS'=>$NAMAPENGAWAS,
            'NOHPPENGAWAS'=>$NOHPPENGAWAS,
            'MEREKTRUK'=>$MEREKTRUK,
            'KONFIGURASITRUK'=>$KONFIGURASITRUK,
            'KAROSERI'=>$KAROSERI,
            'USERID'=>$USERID,
        ]);
        $array = [
            'hasil'=> "berhasil"
        ];


    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }
    echo json_encode($array);
    break;

    case "TAMPIL_POSTING_HAPUS_BAN":
    $array = array();
    $halamanGlobal = $_POST['HALAMAN'];
    $TANGGAL = $_POST['TANGGAL'];
    $id = 0;
    $sql = "SELECT * FROM tmpemasangan WHERE TANGGAL = :TANGGAL AND STATUS ='OPEN'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['TANGGAL'=>date('Y-m-d',strtotime($TANGGAL))]);
    if($nomor = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $nomor['NOMORBUKTI'];
        $array['NOMORPLAT'][$id] = $nomor['NOMORPLAT'];
        $id++;
        while($nomor = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $nomor['NOMORBUKTI'];
            $array['NOMORPLAT'][$id] = $nomor['NOMORPLAT'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "HAPUS_BAN_TERPILIH":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];
    $HALAMAN = $_POST['HALAMAN'];
    $FOLDERGAMBAR = "";
    $database = "";
    $databasetd = "";
    $databasegambar = "";
    $idFotoDihapus = array();
    $arrayFotoDihapus = array();

    
    if($HALAMAN == "PEMASANGAN_BAN"){
        $FOLDERGAMBAR = "gambar_td_pemasangan_ban/";
        $database = "tmpemasangan";
        $databasetd = "tdpemasangan";
        $databasegambar = "gambartdpemasangan";
    }


    $id = 0;
    foreach ($postingHapusValue as $key => $value) {
        $sql = "DELETE FROM ".$database." WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORBUKTI'=>$value]);

        $sql = "SELECT * FROM ".$databasetd." WHERE NOMORBUKTI='".$value."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($row = $stmt->fetch()){
            $idFotoDihapus[$id] = $row['ID'];
            $id++;
            while($row = $stmt->fetch()){
                $idFotoDihapus[$id] = $row['ID'];
                $id++;
            }
        }

        $sql = "DELETE FROM ".$databasetd." WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORBUKTI'=>$value]);
    }

    $counter = 0;
    if($idFotoDihapus){
        foreach ($idFotoDihapus as $key => $value) {
            $sql = "SELECT * FROM gambartdpemasangan WHERE ID_TDPEMASANGAN =:ID_TDPEMASANGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['ID_TDPEMASANGAN'=>$value]);
            if($gambar = $stmt->fetch()){
                $arrayFotoDihapus[$counter] = $gambar['NAMAGAMBAR'];
                $counter++;
                while($gambar = $stmt->fetch()){
                    $arrayFotoDihapus[$counter] = $gambar['NAMAGAMBAR'];
                    $counter++;
                }
            }

            $sql = "DELETE FROM ".$databasegambar." WHERE ID_TDPEMASANGAN=:ID_TDPEMASANGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['ID_TDPEMASANGAN'=>$value]);
        }

    }

    if($arrayFotoDihapus){
        foreach ($arrayFotoDihapus as $key => $value) {
            if(file_exists($FOLDERGAMBAR."/".$value.".jpg")){
                unlink($FOLDERGAMBAR."/".$value.".jpg");
            }
        }   
    }

    $array['hasil'] = "berhasil";
    break;

    case "POSTING_BAN_TERPILIH":
    $array = array();
    $USERID = $_POST['USERID'];
    $HALAMAN = $_POST['HALAMAN'];
    $postingHapusValue = $_POST['postingHapusValue'];

    foreach ($postingHapusValue as $key => $value) {
        $sql = "UPDATE tmpemasangan set STATUS ='CLOSE' WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORBUKTI'=>$value]);
    }
    break;

    case "HASIL_REKAP_RENCANA":
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }
    $HALAMAN = $_POST['HALAMAN'];
    $KODEBUKTI;
    if($HALAMAN == "REKAP_RENCANA_KIRIMAN"){
        $KODEBUKTI = "RF%";
    } else if($HALAMAN == "REKAP_RENCANA_COLLECTOR"){
        $KODEBUKTI = "RC%";
    }

    $array = array();
    $sql = "SELECT rk.KODEPELANGGAN AS KODEPELANGGAN, rk.DATETRANSACTION AS DATETRANSACTION, rk.NOMORBUKTI AS NOMORBUKTI,mk.NAMA as NAMAKARYAWAN,mk.KODEKARYAWAN AS KODEKARYAWAN, mp.LATITUDE as LATITUDE, mp.LONGITUDE as LONGITUDE, mp.NAMA as NAMAPELANGGAN FROM mpelanggan mp INNER JOIN tmrencanakunjungan rk on(rk.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) WHERE rk.NOMORBUKTI LIKE :KODE AND rk.KODESALESMAN >=:KODESALESMANDARI AND rk.KODESALESMAN <= :KODESALESMANSAMPAI";
    if($TANGGALDARI){
        $sql .= " AND rk.DATETRANSACTION >= '".date("Y-m-d",strtotime($TANGGALDARI))."'";
    }

    if($TANGGALSAMPAI){
        $sql .= " AND rk.DATETRANSACTION <= '".date("Y-m-d",strtotime($TANGGALSAMPAI))."'";
    }

    $sql .= " ORDER BY rk.KODESALESMAN ASC, DATETRANSACTION DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODE'=>$KODEBUKTI."%",
        'KODESALESMANDARI'=>$KODESALESMANDARI,
        'KODESALESMANSAMPAI'=>$KODESALESMANSAMPAI
    ]);

    $id = 0;
    
    if($rekap = $stmt->fetch()){
        $array["hasil"] = "ada";
        $array['KODEPELANGGAN'][$id] = $rekap['KODEPELANGGAN'];
        $array['DATETRANSACTION'][$id] = $rekap['DATETRANSACTION'];
        $array['NOMORBUKTI'][$id] = $rekap['NOMORBUKTI'];
        $array['LATITUDE'][$id] = $rekap['LATITUDE'];
        $array['LONGITUDE'][$id] = $rekap['LONGITUDE'];
        $array['NAMAPELANGGAN'][$id] = $rekap['NAMAPELANGGAN'];
        $array['NAMAKARYAWAN'][$id] = $rekap['NAMAKARYAWAN'];
        $array['KODEKARYAWAN'][$id] = $rekap['KODEKARYAWAN'];
        
        $id++;
        while($rekap = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rekap['KODEPELANGGAN'];
            $array['DATETRANSACTION'][$id] = $rekap['DATETRANSACTION'];
            $array['NOMORBUKTI'][$id] = $rekap['NOMORBUKTI'];
            $array['LATITUDE'][$id] = $rekap['LATITUDE'];
            $array['LONGITUDE'][$id] = $rekap['LONGITUDE'];
            $array['NAMAPELANGGAN'][$id] = $rekap['NAMAPELANGGAN'];
            $array['NAMAKARYAWAN'][$id] = $rekap['NAMAKARYAWAN'];
            $array['KODEKARYAWAN'][$id] = $rekap['KODEKARYAWAN'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT count(DATETRANSACTION)  AS JUMLAH,rk.KODEPELANGGAN AS KODEPELANGGAN, rk.DATETRANSACTION AS DATETRANSACTION, rk.NOMORBUKTI AS NOMORBUKTI,mk.NAMA as NAMAKARYAWAN,mk.KODEKARYAWAN AS KODEKARYAWAN, mp.LATITUDE as LATITUDE, mp.LONGITUDE as LONGITUDE, mp.NAMA as NAMAPELANGGAN FROM mpelanggan mp INNER JOIN tmrencanakunjungan rk on(rk.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) WHERE rk.NOMORBUKTI LIKE :KODE AND rk.KODESALESMAN >=:KODESALESMANDARI AND rk.KODESALESMAN <= :KODESALESMANSAMPAI";
    if($TANGGALDARI){
        $sql .= " AND rk.DATETRANSACTION >= '".date("Y-m-d",strtotime($TANGGALDARI))."'";
    }

    if($TANGGALSAMPAI){
        $sql .= " AND rk.DATETRANSACTION <= '".date("Y-m-d",strtotime($TANGGALSAMPAI))."'";
    }

    $sql .= " GROUP BY KODEKARYAWAN, DATETRANSACTION ORDER BY rk.KODESALESMAN ASC, DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODE'=>$KODEBUKTI."%",
        'KODESALESMANDARI'=>$KODESALESMANDARI,
        'KODESALESMANSAMPAI'=>$KODESALESMANSAMPAI
    ]);
    $counterKode = "";
    $counter = 0;
    if($jumlah = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['PELANGGAN'][$counter] = ['KODEKARYAWAN'=>$jumlah['KODEKARYAWAN'],'TANGGAL'=>$jumlah['DATETRANSACTION'] ,'JUMLAH'=>$jumlah['JUMLAH']];
        $counter++;
        while($jumlah = $stmt->fetch()){
            $array['PELANGGAN'][$counter] = ['KODEKARYAWAN'=>$jumlah['KODEKARYAWAN'],'TANGGAL'=>$jumlah['DATETRANSACTION'] ,'JUMLAH'=>$jumlah['JUMLAH']];
            $counter++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    /*$array['PELANGGAN'][0] = ['nama' =>"abs"];*/

    echo json_encode($array);
    break;

    case "HASIL_REKAP":
    if(isset($_POST['KODESALESMANDARI'])){
        $KODESALESMANDARI = $_POST['KODESALESMANDARI'];
    } else { $KODESALESMANDARI = null; }

    if(isset($_POST['KODESALESMANSAMPAI'])){
        $KODESALESMANSAMPAI = $_POST['KODESALESMANSAMPAI'];
    } else { $KODESALESMANSAMPAI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }
    $HALAMAN = $_POST['HALAMAN'];
    $KODEBUKTI;
    if($HALAMAN == "REKAP_KIRIMAN"){
        $KODEBUKTI = "PF%";
    } else if($HALAMAN == "REKAP_COLLECTOR"){
        $KODEBUKTI ="FC%";
    }

    $array = array();
    $sql = "SELECT rk.KODEPELANGGAN AS KODEPELANGGAN, rk.DATETRANSACTION AS DATETRANSACTION, rk.NOMORBUKTI AS NOMORBUKTI,mk.NAMA as NAMAKARYAWAN,mk.KODEKARYAWAN AS KODEKARYAWAN, mp.LATITUDE as LATITUDE, mp.LONGITUDE as LONGITUDE, mp.NAMA as NAMAPELANGGAN FROM mpelanggan mp INNER JOIN tmkunjungan rk on(rk.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) WHERE rk.NOMORBUKTI LIKE :KODE AND rk.KODESALESMAN >=:KODESALESMANDARI AND rk.KODESALESMAN <= :KODESALESMANSAMPAI";
    if($TANGGALDARI){
        $sql .= " AND rk.DATETRANSACTION >= '".date("Y-m-d",strtotime($TANGGALDARI))."'";
    }

    if($TANGGALSAMPAI){
        $sql .= " AND rk.DATETRANSACTION <= '".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI)))."'";
    }

    $sql .= " ORDER BY rk.KODESALESMAN ASC, DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODE'=>$KODEBUKTI."%",
        'KODESALESMANDARI'=>$KODESALESMANDARI,
        'KODESALESMANSAMPAI'=>$KODESALESMANSAMPAI
    ]);

    $id = 0;
    
    if($rekap = $stmt->fetch()){
        $array["hasil"] = "ada";
        $array['KODEPELANGGAN'][$id] = $rekap['KODEPELANGGAN'];
        $array['DATETRANSACTION'][$id] = $rekap['DATETRANSACTION'];
        $array['NOMORBUKTI'][$id] = $rekap['NOMORBUKTI'];
        $array['LATITUDE'][$id] = $rekap['LATITUDE'];
        $array['LONGITUDE'][$id] = $rekap['LONGITUDE'];
        $array['NAMAPELANGGAN'][$id] = $rekap['NAMAPELANGGAN'];
        $array['NAMAKARYAWAN'][$id] = $rekap['NAMAKARYAWAN'];
        $array['KODEKARYAWAN'][$id] = $rekap['KODEKARYAWAN'];
        $array['TANGGAL'][$id] = date('Y-m-d',strtotime($rekap['DATETRANSACTION']));
        $id++;
        while($rekap = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rekap['KODEPELANGGAN'];
            $array['DATETRANSACTION'][$id] = $rekap['DATETRANSACTION'];
            $array['NOMORBUKTI'][$id] = $rekap['NOMORBUKTI'];
            $array['LATITUDE'][$id] = $rekap['LATITUDE'];
            $array['LONGITUDE'][$id] = $rekap['LONGITUDE'];
            $array['NAMAPELANGGAN'][$id] = $rekap['NAMAPELANGGAN'];
            $array['NAMAKARYAWAN'][$id] = $rekap['NAMAKARYAWAN'];
            $array['KODEKARYAWAN'][$id] = $rekap['KODEKARYAWAN'];
            $array['TANGGAL'][$id] = date('Y-m-d',strtotime($rekap['DATETRANSACTION']));
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    $sql = "SELECT count(DATETRANSACTION) as JUMLAH,rk.KODEPELANGGAN AS KODEPELANGGAN, rk.DATETRANSACTION AS DATETRANSACTION, rk.NOMORBUKTI AS NOMORBUKTI,mk.NAMA as NAMAKARYAWAN,mk.KODEKARYAWAN AS KODEKARYAWAN, mp.LATITUDE as LATITUDE, mp.LONGITUDE as LONGITUDE, mp.NAMA as NAMAPELANGGAN FROM mpelanggan mp INNER JOIN tmkunjungan rk on(rk.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = rk.KODESALESMAN) WHERE rk.NOMORBUKTI LIKE :KODE AND rk.KODESALESMAN >=:KODESALESMANDARI AND rk.KODESALESMAN <= :KODESALESMANSAMPAI";
    if($TANGGALDARI){
        $sql .= " AND rk.DATETRANSACTION >= '".date("Y-m-d",strtotime($TANGGALDARI))."'";
    }

    if($TANGGALSAMPAI){
        $sql .= " AND rk.DATETRANSACTION <= '".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI)))."'";
    }

    $sql .= " GROUP BY KODEKARYAWAN, date(DATETRANSACTION) ORDER BY rk.KODESALESMAN ASC, DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODE'=>$KODEBUKTI."%",
        'KODESALESMANDARI'=>$KODESALESMANDARI,
        'KODESALESMANSAMPAI'=>$KODESALESMANSAMPAI
    ]);
    $counterKode = "";
    $counter = 0;
    if($jumlah = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['PELANGGAN'][$counter] = ['KODEKARYAWAN'=>$jumlah['KODEKARYAWAN'],'TANGGAL'=> date('Y-m-d',strtotime($jumlah['DATETRANSACTION'])) ,'JUMLAH'=>$jumlah['JUMLAH']];
        $counter++;
        while($jumlah = $stmt->fetch()){
            $array['PELANGGAN'][$counter] = ['KODEKARYAWAN'=>$jumlah['KODEKARYAWAN'],'TANGGAL'=>date('Y-m-d',strtotime($jumlah['DATETRANSACTION'])) ,'JUMLAH'=>$jumlah['JUMLAH']];
            $counter++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "DB_UPDATE_STOK_MBRG":
    $arrData = $_POST['data'];
    $USERID = $_POST['USERID'];
    
    $sql = "UPDATE mbrg set stok = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    foreach ($arrData as $key => $value) {
        $sql = "UPDATE mbrg set stok=:stok WHERE kodebarang =:kodebarang";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'kodebarang' => $value['kodebarang'],
            'stok' =>$value['stok']
        ]);
    }

    $sql = "UPDATE logmbrg set WAKTUUBAHSTOK =:WAKTUUBAHSTOK,USERIDSTOK=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'WAKTUUBAHSTOK'=>date("Y-m-d H:i:s"),
        'USERID'=>$USERID
    ]);

    $array = array();
    $array['hasil'] = "berhasil";
    echo json_encode($array);
    break;

    case "DB_UPDATE_SEMUA_MBRG":
    $arrData = $_POST['data'];
    $USERID = $_POST['USERID'];
    $array = array();
    try {
        foreach ($arrData as $key => $value) {
            $sql = "SELECT * FROM mbrg WHERE kodebarang=:kodebarang";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['kodebarang'=>$value['kodebarang']]);
            if($barang = $stmt->fetch()){
                $sql = "UPDATE mbrg set nama=:nama,keterangan=:keterangan,satuan=:satuan,discmax=:discmax,minbrg=:minbrg,maxbrg=:maxbrg,hargabeli=:hargabeli, hargajual=:hargajual,pricelistb1=:pricelistb1,pricelistb2=:pricelistb2,pricelistb3=:pricelistb3, pricelistj1=:pricelistj1, pricelistj2=:pricelistj2, pricelistj3=:pricelistj3, userid=:userid, status=:status,kodegolongan=:kodegolongan,kodegolonganb=:kodegolonganb,kodegolonganc=:kodegolonganc, kodegolongand=:kodegolongand, pengali=:pengali, ring=:ring, hpt=:hpt, pointbeli=:pointbeli, pointjual=:pointjual WHERE kodebarang =:kodebarang";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'kodebarang' => $value['kodebarang'],
                    'nama'=>$value['nama'],
                    'keterangan'=>$value['keterangan'],
                    'satuan'=>$value['satuan'],
                    'discmax'=>$value['discmax'],
                    'minbrg'=>$value['minbrg'],
                    'maxbrg'=>$value['maxbrg'],
                    'hargabeli'=>$value['hargabeli'],
                    'hargajual'=>$value['hargajual'],
                    'pricelistb1'=>$value['pricelistb1'],
                    'pricelistb2'=>$value['pricelistb2'],
                    'pricelistb3'=>$value['pricelistb3'],
                    'pricelistj1'=>$value['pricelistj1'],
                    'pricelistj2'=>$value['pricelistj2'],
                    'pricelistj3'=>$value['pricelistj3'],
                    'userid'=>$value['userid'],
                    'status'=>$value['status'],
                    'kodegolongan'=>$value['kodegolongan'],
                    'kodegolonganb'=>$value['kodegolonganb'],
                    'kodegolonganc'=>$value['kodegolonganc'],
                    'kodegolongand'=>$value['kodegolongand'],
                    'pengali'=>$value['pengali'],
                    'ring'=>$value['ring'],
                    'hpt'=>$value['hpt'],
                    'pointbeli'=>$value['pointbeli'],
                    'pointjual'=>$value['pointjual']
                ]);
            } else {
                $sql = "INSERT INTO mbrg (kodekategori,kodebarang,nama,keterangan,satuan,discmax,minbrg,maxbrg,hargabeli,hargajual,pricelistb1,pricelistb2,pricelistb3,pricelistj1,pricelistj2,pricelistj3,userid,status,kodegolongan,kodegolonganb,kodegolonganc,kodegolongand,pengali,ring,hpt,pointbeli,pointjual) values(:kodekategori,:kodebarang,:nama,:keterangan,:satuan,:discmax,:minbrg,:maxbrg,:hargabeli,:hargajual,:pricelistb1,:pricelistb2,:pricelistb3,:pricelistj1,:pricelistj2,:pricelistj3,:userid,:status,:kodegolongan,:kodegolonganb,:kodegolonganc,:kodegolongand,:pengali,:ring,:hpt,:pointbeli,:pointjual)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'kodebarang' => $value['kodebarang'],
                    'kodekategori' => $value['kodekategori'],
                    'nama'=>$value['nama'],
                    'keterangan'=>$value['keterangan'],
                    'satuan'=>$value['satuan'],
                    'discmax'=>$value['discmax'],
                    'minbrg'=>$value['minbrg'],
                    'maxbrg'=>$value['maxbrg'],
                    'hargabeli'=>$value['hargabeli'],
                    'hargajual'=>$value['hargajual'],
                    'pricelistb1'=>$value['pricelistb1'],
                    'pricelistb2'=>$value['pricelistb2'],
                    'pricelistb3'=>$value['pricelistb3'],
                    'pricelistj1'=>$value['pricelistj1'],
                    'pricelistj2'=>$value['pricelistj2'],
                    'pricelistj3'=>$value['pricelistj3'],
                    'userid'=>$value['userid'],
                    'status'=>$value['status'],
                    'kodegolongan'=>$value['kodegolongan'],
                    'kodegolonganb'=>$value['kodegolonganb'],
                    'kodegolonganc'=>$value['kodegolonganc'],
                    'kodegolongand'=>$value['kodegolongand'],
                    'pengali'=>$value['pengali'],
                    'ring'=>$value['ring'],
                    'hpt'=>$value['hpt'],
                    'pointbeli'=>$value['pointbeli'],
                    'pointjual'=>$value['pointjual']
                ]);
            }
        }

        $array['hasil'] = "berhasil";
    } catch (Exception $e){
        $array['hasil'] = "error";
    }


    $sql = "UPDATE logmbrg set WAKTUUBAHSEMUA =:WAKTUUBAHSEMUA,USERIDSEMUA=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'WAKTUUBAHSEMUA'=>date("Y-m-d H:i:s"),
        'USERID'=>$USERID
    ]);

    echo json_encode($array);
    break;

    case "TAMPIL_POPUP_NOMORPLAT":

    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $array = array();
    $id = 0;

    $sql = "";
    if($USERID == "MPT-1" || $USERID == "MPT-2" || $USERID == "MPT-3"){
        $sql = "SELECT mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMA, mk.NOMORPLAT AS NOMORPLAT FROM mkendaraan mk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = mk.KODEPELANGGAN) WHERE mp.KODECABANG = :KODECABANG AND mp.KODEPELANGGAN = 'PT.MODERN'";
    } else {
        $sql = "SELECT mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMA, mk.NOMORPLAT AS NOMORPLAT FROM mkendaraan mk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = mk.KODEPELANGGAN) WHERE mp.KODECABANG = :KODECABANG";    
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($nomor = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['DETAIL'][$id] = [
            'KODEPELANGGAN'=>$nomor['KODEPELANGGAN'],
            'NAMAPELANGGAN'=>$nomor['NAMA'],
            'NOMORPLAT'=>$nomor['NOMORPLAT']
        ];
        $id++;
        while($nomor = $stmt->fetch()){
            $array['DETAIL'][$id] = [
                'KODEPELANGGAN'=>$nomor['KODEPELANGGAN'],
                'NAMAPELANGGAN'=>$nomor['NAMA'],
                'NOMORPLAT'=>$nomor['NOMORPLAT']
            ];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }
    

    echo json_encode($array);
    break;


    case "PEMETAAN_BY_PELANGGAN":
    $karyawan = array();
    $sql = "SELECT p.USERID AS USERID, m.nama as nama_gambar FROM pemakai p INNER JOIN mlogo m on(m.id = p.id_logo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($stmt->rowCount() > 0){
        while($pemakai = $stmt->fetch()){
            $karyawan[$pemakai['USERID']] = $pemakai['nama_gambar'];
        }
    }

    $sql = "SELECT * FROM mpelanggan mp INNER JOIN dpelanggan1 dp on(dp.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE LATITUDE IS NOT NULL AND LONGITUDE IS NOT NULL AND dp.KJ1 = 'SALESMAN' GROUP BY dp.KODEPELANGGAN ORDER BY KATEGORIPELANGGAN ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($KOORDINAT = $stmt->fetch()){
        $array['hasil'] = "ada";
        $nama_gambar = "";
        if(isset($karyawan[$KOORDINAT['CP1']])){
            $nama_gambar = $karyawan[$KOORDINAT['CP1']];
        } else {
            $nama_gambar = 'bendera-hijau.png';
        }

        $array['KOORDINAT'][$id] = [
            'LATITUDE' =>$KOORDINAT['LATITUDE'],
            'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
            'NAMA' =>$KOORDINAT['NAMA'],
            'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
            'ALAMAT'=>$KOORDINAT['ALAMAT'],
            'KOTA'=>$KOORDINAT['KOTA'],
            'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
            'USERID'=>$KOORDINAT['CP1'],
            'nama_gambar'=>$nama_gambar
        ];
        $array['karyawan'][$id] = [
            'nama_gambar'=>$nama_gambar,
            'USERID'=>$KOORDINAT['CP1']
        ];
        $id++;

        while($KOORDINAT = $stmt->fetch()){
            $nama_gambar = "";
            if(isset($karyawan[$KOORDINAT['CP1']])){
                $nama_gambar = $karyawan[$KOORDINAT['CP1']];
            } else {
                $nama_gambar = 'bendera-hijau.png';
            }
            $array['hasil'] = "ada";
            $array['KOORDINAT'][$id] = [
                'LATITUDE' =>$KOORDINAT['LATITUDE'],
                'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
                'NAMA' =>$KOORDINAT['NAMA'],
                'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
                'ALAMAT'=>$KOORDINAT['ALAMAT'],
                'KOTA'=>$KOORDINAT['KOTA'],
                'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
                'USERID'=>$KOORDINAT['CP1'],
                'nama_gambar'=>$nama_gambar
            ];
            $array['karyawan'][$id] = [
                'nama_gambar'=>$nama_gambar,
                'USERID'=>$KOORDINAT['CP1']
            ];
            $id++;
            
        }
    } else {
        $array['hasil'] = "tidakada";
    }


    $array['karyawan'] = array_unique($array['karyawan'], SORT_REGULAR);
    $array['karyawan'] = array_merge($array['karyawan'], array());

    echo json_encode($array);
    break;

    case "PEMETAAN_BY_PELANGGAN_VERIFIKASI":
    $karyawan = array();
    $array = array();
    $sql = "SELECT p.USERID AS USERID, m.nama as nama_gambar FROM pemakai p INNER JOIN mlogo m on(m.id = p.id_logo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;
    if($stmt->rowCount() > 0){
        while($pemakai = $stmt->fetch()){
            $karyawan[$pemakai['USERID']] = $pemakai['nama_gambar'];
        }
    }

    $sql = "SELECT dp.CP1 AS USERID, mp.LATITUDE AS LATITUDE, mp.LONGITUDE AS LONGITUDE, mp.NAMA AS NAMA, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.ALAMAT AS ALAMAT, mp.KOTA AS KOTA, mp.KATEGORIPELANGGAN AS KATEGORIPELANGGAN, dp.CP1 AS USERID FROM mpelanggan mp INNER JOIN dpelanggan1 dp on (dp.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN pemakai p on (p.USERID = dp.CP1) WHERE LATITUDE IS NOT NULL AND LONGITUDE IS NOT NULL AND VERGPS ='T' AND p.KODEJABATAN = 'SALESMAN' GROUP BY mp.KODEPELANGGAN ORDER BY KATEGORIPELANGGAN,mp.NAMA ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $id = 0;

    if($KOORDINAT = $stmt->fetch()){
        $array['hasil'] = "ada";
        $nama_gambar = "";
        if(isset($karyawan[$KOORDINAT['USERID']])){
            $nama_gambar = $karyawan[$KOORDINAT['USERID']];
        } else {
            $nama_gambar = 'bendera-hijau.png';
        }
        $array['KOORDINAT'][$id] = [
            'LATITUDE' =>$KOORDINAT['LATITUDE'],
            'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
            'NAMA' =>$KOORDINAT['NAMA'],
            'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
            'ALAMAT'=>$KOORDINAT['ALAMAT'],
            'KOTA'=>$KOORDINAT['KOTA'],
            'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
            'USERID'=>$KOORDINAT['USERID'],
            'nama_gambar'=>$nama_gambar
        ];
        
        $array['karyawan'][$id] = [
            'nama_gambar'=>$nama_gambar,
            'USERID'=>$KOORDINAT['USERID']
        ];
        $id++;
        while($KOORDINAT = $stmt->fetch()){
            $array['hasil'] = "ada";
            $nama_gambar = "";
            if(isset($karyawan[$KOORDINAT['USERID']])){
                $nama_gambar = $karyawan[$KOORDINAT['USERID']];
            } else {
                $nama_gambar = 'bendera-hijau.png';
            }
            $array['KOORDINAT'][$id] = [
                'LATITUDE' =>$KOORDINAT['LATITUDE'],
                'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
                'NAMA' =>$KOORDINAT['NAMA'],
                'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
                'ALAMAT'=>$KOORDINAT['ALAMAT'],
                'KOTA'=>$KOORDINAT['KOTA'],
                'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
                'USERID'=>$KOORDINAT['USERID'],
                'nama_gambar'=>$nama_gambar
            ];

            $array['karyawan'][$id] = [
                'nama_gambar'=>$nama_gambar,
                'USERID'=>$KOORDINAT['USERID']
            ];
            
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    $array['karyawan'] = array_unique($array['karyawan'], SORT_REGULAR);
    $array['karyawan'] = array_merge($array['karyawan'], array());

    echo json_encode($array);
    break;

    case "PEMETAAN_BY_PELANGGAN_VERIFIKASI_BY_KATEGORI":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $VERIFIKASI = $_POST['VERIFIKASI'];
    $karyawan = array();
    $array = array();
    $sql = "SELECT p.USERID AS USERID, m.nama as nama_gambar FROM pemakai p INNER JOIN mlogo m on(m.id = p.id_logo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        while($pemakai = $stmt->fetch()){
            $karyawan[$pemakai['USERID']] = $pemakai['nama_gambar'];
        }
    }


    $sql = "SELECT * FROM mpelanggan mp INNER JOIN dpelanggan1 dp on (dp.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN pemakai p on (p.USERID = dp.CP1) WHERE LATITUDE IS NOT NULL AND LONGITUDE IS NOT NULL AND VERGPS =:VERIFIKASI AND KATEGORIPELANGGAN = :KODEKATEGORIPELANGGAN AND p.KODEJABATAN = 'SALESMAN' GROUP BY mp.KODEPELANGGAN ORDER BY KATEGORIPELANGGAN,mp.NAMA ASC ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN,'VERIFIKASI'=>$VERIFIKASI]);
    $id = 0;

    if($KOORDINAT = $stmt->fetch()){
        $array['hasil'] = "ada";
        $nama_gambar = "";
        if(isset($karyawan[$KOORDINAT['USERID']])){
            $nama_gambar = $karyawan[$KOORDINAT['USERID']];
        } else {
            $nama_gambar = 'bendera-hijau.png';
        }
        $array['KOORDINAT'][$id] = [
            'LATITUDE' =>$KOORDINAT['LATITUDE'],
            'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
            'NAMA' =>$KOORDINAT['NAMA'],
            'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
            'ALAMAT'=>$KOORDINAT['ALAMAT'],
            'KOTA'=>$KOORDINAT['KOTA'],
            'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
            'USERID'=>$KOORDINAT['USERID'],
            'nama_gambar'=>$nama_gambar
        ];

        $array['karyawan'][$id] = [
            'nama_gambar'=>$nama_gambar,
            'USERID'=>$KOORDINAT['USERID']
        ];
        
        $id++;
        while($KOORDINAT = $stmt->fetch()){
            $array['hasil'] = "ada";
            $nama_gambar = "";
            if(isset($karyawan[$KOORDINAT['USERID']])){
                $nama_gambar = $karyawan[$KOORDINAT['USERID']];
            } else {
                $nama_gambar = 'bendera-hijau.png';
            }
            $array['KOORDINAT'][$id] = [
                'LATITUDE' =>$KOORDINAT['LATITUDE'],
                'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
                'NAMA' =>$KOORDINAT['NAMA'],
                'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
                'ALAMAT'=>$KOORDINAT['ALAMAT'],
                'KOTA'=>$KOORDINAT['KOTA'],
                'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
                'USERID'=>$KOORDINAT['USERID'],
                'nama_gambar'=>$nama_gambar
            ];

            $array['karyawan'][$id] = [
                'nama_gambar'=>$nama_gambar,
                'USERID'=>$KOORDINAT['USERID']
            ];
            
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }


    $array['karyawan'] = array_unique($array['karyawan'], SORT_REGULAR);
    $array['karyawan'] = array_merge($array['karyawan'], array());
    echo json_encode($array);
    break;

    

    case "PEMETAAN_BY_PELANGGAN_VERIFIKASI_BY_KATEGORI_BY_SALESMAN":
    $KODEKATEGORIPELANGGAN = $_POST['KODEKATEGORIPELANGGAN'];
    $VERIFIKASI = $_POST['VERIFIKASI'];
    $KODESALESMAN = $_POST['KODESALESMAN'];
    $karyawan = array();
    $array = array();
    $sql = "SELECT p.USERID AS USERID, m.nama as nama_gambar FROM pemakai p INNER JOIN mlogo m on(m.id = p.id_logo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        while($pemakai = $stmt->fetch()){
            $karyawan[$pemakai['USERID']] = $pemakai['nama_gambar'];
        }
    }


    $sql = "SELECT * FROM mpelanggan mp INNER JOIN dpelanggan1 dp on (dp.KODEPELANGGAN = mp.KODEPELANGGAN) INNER JOIN pemakai p on (p.USERID = dp.CP1) WHERE LATITUDE IS NOT NULL AND LONGITUDE IS NOT NULL AND VERGPS =:VERIFIKASI AND KATEGORIPELANGGAN = :KODEKATEGORIPELANGGAN AND p.KODEJABATAN = 'SALESMAN' AND p.USERID = :KODESALESMAN GROUP BY mp.KODEPELANGGAN ORDER BY KATEGORIPELANGGAN,mp.NAMA ASC ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODEKATEGORIPELANGGAN'=>$KODEKATEGORIPELANGGAN,'VERIFIKASI'=>$VERIFIKASI,'KODESALESMAN'=>$KODESALESMAN]);
    $id = 0;

    if($KOORDINAT = $stmt->fetch()){
        $array['hasil'] = "ada";
        $nama_gambar = "";
        if(isset($karyawan[$KOORDINAT['USERID']])){
            $nama_gambar = $karyawan[$KOORDINAT['USERID']];
        } else {
            $nama_gambar = 'bendera-hijau.png';
        }
        $array['KOORDINAT'][$id] = [
            'LATITUDE' =>$KOORDINAT['LATITUDE'],
            'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
            'NAMA' =>$KOORDINAT['NAMA'],
            'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
            'ALAMAT'=>$KOORDINAT['ALAMAT'],
            'KOTA'=>$KOORDINAT['KOTA'],
            'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
            'USERID'=>$KOORDINAT['USERID'],
            'nama_gambar'=>$nama_gambar
        ];

        $array['karyawan'][$id] = [
            'nama_gambar'=>$nama_gambar,
            'USERID'=>$KOORDINAT['USERID']
        ];
        
        $id++;
        while($KOORDINAT = $stmt->fetch()){
            $array['hasil'] = "ada";
            $nama_gambar = "";
            if(isset($karyawan[$KOORDINAT['USERID']])){
                $nama_gambar = $karyawan[$KOORDINAT['USERID']];
            } else {
                $nama_gambar = 'bendera-hijau.png';
            }
            $array['KOORDINAT'][$id] = [
                'LATITUDE' =>$KOORDINAT['LATITUDE'],
                'LONGITUDE' =>$KOORDINAT['LONGITUDE'],
                'NAMA' =>$KOORDINAT['NAMA'],
                'KODEPELANGGAN'=>$KOORDINAT['KODEPELANGGAN'],
                'ALAMAT'=>$KOORDINAT['ALAMAT'],
                'KOTA'=>$KOORDINAT['KOTA'],
                'KATEGORIPELANGGAN'=>$KOORDINAT['KATEGORIPELANGGAN'],
                'USERID'=>$KOORDINAT['USERID'],
                'nama_gambar'=>$nama_gambar
            ];

            $array['karyawan'][$id] = [
                'nama_gambar'=>$nama_gambar,
                'USERID'=>$KOORDINAT['USERID']
            ];
            
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }


    $array['karyawan'] = array_unique($array['karyawan'], SORT_REGULAR);
    $array['karyawan'] = array_merge($array['karyawan'], array());
    echo json_encode($array);
    break;

    case "IMPORT_LAPORAN":
    if(isset($_POST['JABATANDARI'])){
        $JABATANDARI = $_POST['JABATANDARI'];
    } else { $JABATANDARI = null; }

    $JABATANSAMPAI = $_POST['JABATANSAMPAI'];
    $TANGGALDARI = $_POST['TANGGALDARI'];
    $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    $KARYAWANDARI = $_POST['KARYAWANDARI'];
    $KARYAWANSAMPAI = $_POST['KARYAWANSAMPAI'];

    $array = array();
    $sql = "SELECT * FROM tmkunjungan tk INNER JOIN pemakai p on(p.USERID = tk.KODESALESMAN) WHERE";
    if($TANGGALDARI){
        $sql .= " tk.DATETRANSACTION >'".date('Y-m-d 00:00:00',strtotime($TANGGALDARI))."' AND ";
    }

    if($TANGGALSAMPAI){
        $sql .= " tk.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime($TANGGALSAMPAI))."' AND ";
    }

    if($KARYAWANDARI){
        $sql .= " tk.KODESALESMAN >= '" . $KARYAWANDARI ."' AND ";
    }

    if($KARYAWANSAMPAI){
        $sql .= " tk.KODESALESMAN <= '" . $KARYAWANSAMPAI ."' AND ";
    }

    if($JABATANDARI){
        $sql .= " p.KODEJABATAN>= '" . $JABATANDARI ."' AND ";
    }

    if($JABATANSAMPAI){
        $sql .= " p.KODEJABATAN <= '" . $JABATANSAMPAI ."' AND ";
    }

    

    $tampungSql = substr($sql,-5);
    if($tampungSql == " AND " || $tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($kunjungan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['kunjungan'][$id] = [
            'NOMORBUKTI' =>$kunjungan['NOMORBUKTI'],
            'WAKTUMASUK' =>$kunjungan['WAKTUMASUK'],
            'DATETRANSACTION' =>$kunjungan['DATETRANSACTION'],
            'KODESALESMAN' =>$kunjungan['KODESALESMAN'],
            'KODEPELANGGAN' =>$kunjungan['KODEPELANGGAN'],
            'LATITUDE' =>$kunjungan['LATITUDE'],
            'LONGITUDE' =>$kunjungan['LONGITUDE'],
            'KETERANGAN' =>$kunjungan['KETERANGAN'],
            'TANGGALKEMBALI' =>$kunjungan['TANGGALKEMBALI'],
            'KESIMPULAN' =>$kunjungan['KESIMPULAN'],
            'PERMINTAAN' =>$kunjungan['PERMINTAAN'],
            'STATUSKUNJUNGAN' =>$kunjungan['STATUSKUNJUNGAN'],
            'USERID' =>$kunjungan['USERID'],
            'STATUS' =>$kunjungan['STATUS'],
            'PERMINTAANKHUSUS' =>$kunjungan['PERMINTAANKHUSUS'],
            'LATITUDEEDIT' =>$kunjungan['LATITUDEEDIT'],
            'LONGITUDEEDIT' =>$kunjungan['LONGITUDEEDIT'],
            'TRANSFER' =>$kunjungan['TRANSFER'],
            'GIRO' =>$kunjungan['GIRO'],
            'TUNAI' =>$kunjungan['TUNAI'],
        ];
        $id++;
        while($kunjungan = $stmt->fetch()){
            $array['kunjungan'][$id] = [
                'NOMORBUKTI' =>$kunjungan['NOMORBUKTI'],
                'WAKTUMASUK' =>$kunjungan['WAKTUMASUK'],
                'DATETRANSACTION' =>$kunjungan['DATETRANSACTION'],
                'KODESALESMAN' =>$kunjungan['KODESALESMAN'],
                'KODEPELANGGAN' =>$kunjungan['KODEPELANGGAN'],
                'LATITUDE' =>$kunjungan['LATITUDE'],
                'LONGITUDE' =>$kunjungan['LONGITUDE'],
                'KETERANGAN' =>$kunjungan['KETERANGAN'],
                'TANGGALKEMBALI' =>$kunjungan['TANGGALKEMBALI'],
                'KESIMPULAN' =>$kunjungan['KESIMPULAN'],
                'PERMINTAAN' =>$kunjungan['PERMINTAAN'],
                'STATUSKUNJUNGAN' =>$kunjungan['STATUSKUNJUNGAN'],
                'USERID' =>$kunjungan['USERID'],
                'STATUS' =>$kunjungan['STATUS'],
                'PERMINTAANKHUSUS' =>$kunjungan['PERMINTAANKHUSUS'],
                'LATITUDEEDIT' =>$kunjungan['LATITUDEEDIT'],
                'LONGITUDEEDIT' =>$kunjungan['LONGITUDEEDIT'],
                'TRANSFER' =>$kunjungan['TRANSFER'],
                'GIRO' =>$kunjungan['GIRO'],
                'TUNAI' =>$kunjungan['TUNAI'],
            ];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";

    }
    echo json_encode($array);
    break;

    case "DASHBOARD_RENCANA":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    //$DATETRANSACTION = "2019-05-03";


    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.NOMORBUKTI LIKE 'RK%' AND tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $array = array();
    $array['KODEPELANGGAN'] = array();
    $array['rencana'] = array();
    $array['kunjungan'] = array();
    $id = 0;
    $counter = 0;
    $counterKunjungan = 0;

    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    }


    if(isset($array['KODEPELANGGAN'])){
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql = "SELECT k.NOMORBUKTI AS NOMORBUKTI, k.DATETRANSACTION AS DATETRANSACTION, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan k LEFT JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'KS%' AND k.KODESALESMAN =:USERID AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID' => $USERID,
                'KODEPELANGGAN' =>$value
            ]);
            if($rencana = $stmt->fetch()){
                $array['hasil'] = "tidakada";
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'sesuai_rencana',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION']
                ];
                


                $array['rencana'][$counter] = [
                    'jenis' => 'sudah_kunjungan',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA']
                ];
                


                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI' => $rencana['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                
                $counter++;
                $counterKunjungan++;

            } else {
                $array['hasil'] = "ada";
                $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEPELANGGAN'=>$value]);
                $rencana = $stmt->fetch();
                $array['rencana'][$counter] = [
                    'jenis' => 'kunjungan',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMA'],
                    'KOTA' => $rencana['KOTA']
                ];

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                $counter++;
            }
        }
    }



    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.NOMORBUKTI LIKE 'RC%' AND tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $id = 0;

    $array['KODEPELANGGAN'] = array();

    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    } else {

    }


    if(isset($array['KODEPELANGGAN'])){
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql = "SELECT k.NOMORBUKTI AS NOMORBUKTI, k.DATETRANSACTION AS DATETRANSACTION,  mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan k LEFT JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'FC%' AND k.KODESALESMAN =:USERID AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID' => $USERID,
                'KODEPELANGGAN' =>$value
            ]);
            if($rencana = $stmt->fetch()){
                $array['hasil'] = "tidakada";
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'sesuai_rencana',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION']
                ];

                $array['rencana'][$counter] = [
                    'jenis' => 'sudah_collector',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA']
                ];

                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI' => $rencana['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                
                $counter++;
                $counterKunjungan++;
            } else {
                $array['hasil'] = "ada";
                $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEPELANGGAN'=>$value]);
                $rencana = $stmt->fetch();
                $array['rencana'][$counter] = [
                    'jenis' => 'collector',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMA'],
                    'KOTA' => $rencana['KOTA']
                ];
                
                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                $counter++;
            }
        }
    }

    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.NOMORBUKTI LIKE 'RF%' AND tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $array['KODEPELANGGAN'] = array();
    $id = 0;

    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    } else {

    }


    if(isset($array['KODEPELANGGAN'])){
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql = "SELECT k.NOMORBUKTI AS NOMORBUKTI, k.DATETRANSACTION AS DATETRANSACTION,  mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan k LEFT JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'PF%' AND k.KODESALESMAN =:USERID AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID' => $USERID,
                'KODEPELANGGAN' =>$value
            ]);
            if($rencana = $stmt->fetch()){
                $array['hasil'] = "tidakada";
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'sesuai_rencana',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION']
                ];
                $array['rencana'][$counter] = [
                    'jenis' => 'sudah_kiriman',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA']
                ];

                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI' => $rencana['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                
                $counter++;
                $counterKunjungan++;
            } else {
                $array['hasil'] = "ada";
                $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEPELANGGAN'=>$value]);
                $rencana = $stmt->fetch();
                $array['rencana'][$counter] = [
                    'jenis' => 'kiriman',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMA'],
                    'KOTA' => $rencana['KOTA']
                ];
                
                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                $counter++;
            }
        }
    }




    $sql = "SELECT mk.NAMA AS NAMA FROM pemakai p INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE p.USERID=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $pemakai = $stmt->fetch();
    $array['PEMAKAI'] = [
        'NAMA'=>$pemakai['NAMA']
    ];



    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $array['KODEPELANGGAN'] = array();
    $id = 0;
    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    }

    if(isset($array['KODEPELANGGAN'])){
        $sql = "SELECT distinct k.NOMORBUKTI AS NOMORBUKTI, k.DATETRANSACTION AS DATETRANSACTION, k.KODEPELANGGAN as KODEPELANGGAN,k.KODESALESMAN AS KODESALESMAN, p.NAMA AS NAMAPELANGGAN, p.KOTA AS KOTA FROM tmkunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) left join tmrencanakunjungan rk on (rk.KODEPELANGGAN = p.KODEPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN =:KODESALESMAN  AND k.NOMORBUKTI NOT LIKE 'FA%'";
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql.=" AND k.KODEPELANGGAN !='".$value."'";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODESALESMAN'=>$USERID,
        ]);
        if($stmt->rowCount() > 0){
            while($kunjungan = $stmt->fetch()){
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'tambahan_kunjungan',
                    'KODEPELANGGAN' => $kunjungan['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $kunjungan['NAMAPELANGGAN'],
                    'KOTA' => $kunjungan['KOTA'],
                    'DATETRANSACTION' => $kunjungan['DATETRANSACTION']
                ];

                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->execute(['NOMORBUKTI' => $kunjungan['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt2->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }
                $counterKunjungan++;
            }
        } else {

        }
    }


    //TENTUKAN TOTAL KUNJUNGAN
    $id = 0;
    if(date('d',strtotime($DATETRANSACTION)) < 26){
        $TANGGALMULAI = date('Y-m-26 00:00:00',strtotime('-1months', strtotime($DATETRANSACTION)));
        $TANGGALSELESAI = date('Y-m-26 00:00:00',strtotime($DATETRANSACTION));
    } else {
        $TANGGALMULAI = date('Y-m-26 00:00:00',strtotime($DATETRANSACTION));
        $TANGGALSELESAI = date('Y-m-26 00:00:00',strtotime('+1months', strtotime($DATETRANSACTION)));
    }

    $sql = "SELECT * FROM tmkunjungan WHERE KODESALESMAN=:KODESALESMAN AND DATETRANSACTION >=:TANGGALMULAI AND DATETRANSACTION <:TANGGALSELESAI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODESALESMAN' => $USERID,
        'TANGGALMULAI' => $TANGGALMULAI,
        'TANGGALSELESAI' => $TANGGALSELESAI
    ]);
    if($point = $stmt->fetch()){
        $array['hasil_point'] = "ada";
        $id++;
        $array['total_kunjungan'] = $id;
        while($point = $stmt->fetch()){
            $id++;
            $array['total_kunjungan'] = $id;
        }
    } else {
        $array['hasil_point'] = "tidakada";
    }

    //TENTUKAN SESUAI RENCANA / TIDAK
    $id = 0;
    if(date('d',strtotime($DATETRANSACTION)) < 26){
        $TANGGALMULAI = date('Y-m-26',strtotime('-1months', strtotime($DATETRANSACTION)));
        $TANGGALSELESAI = date('Y-m-26',strtotime($DATETRANSACTION));
    } else {
        $TANGGALMULAI = date('Y-m-26',strtotime($DATETRANSACTION));
        $TANGGALSELESAI = date('Y-m-26',strtotime('+1months', strtotime($DATETRANSACTION)));
    }

    $sql = "SELECT * FROM tmrencanakunjungan WHERE KODESALESMAN=:KODESALESMAN AND DATETRANSACTION >=:TANGGALMULAI AND DATETRANSACTION <:TANGGALSELESAI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'KODESALESMAN' => $USERID,
        'TANGGALMULAI' => $TANGGALMULAI,
        'TANGGALSELESAI' => $TANGGALSELESAI
    ]);
    if($point = $stmt->fetch()){
        $array['hasil_rencana'] = "ada";
        $array['point_kunjungan'][$id] = [
            'NOMORBUKTI' => $point['NOMORBUKTI'],
            'KODEPELANGGAN' => $point['KODEPELANGGAN'],
            'DATETRANSACTION' => $point['DATETRANSACTION']
        ];
        $id++;
        while($point = $stmt->fetch()){
            $array['point_kunjungan'][$id] = [
                'NOMORBUKTI' => $point['NOMORBUKTI'],
                'KODEPELANGGAN' => $point['KODEPELANGGAN'],
                'DATETRANSACTION' => $point['DATETRANSACTION']
            ];
            $id++;
        }
    } else {
        $array['hasil_rencana'] = "tidakada";
    }


    if(isset($array['point_kunjungan'])){
        $counter = 0;
        foreach ($array['point_kunjungan'] as $key => $value) {
            $jenis = "";
            if(substr($value['NOMORBUKTI'], 0, 2) == "RK"){
                $jenis = "KS";
            } else if(substr($value['NOMORBUKTI'], 0, 2) == "RC"){
                $jenis = "FC";
            } else if(substr($value['NOMORBUKTI'], 0, 2) == "RF"){
                $jenis = "PF";
            }
            $sql = "SELECT * FROM tmkunjungan WHERE NOMORBUKTI LIKE :JENIS AND DATETRANSACTION >='".date('Y-m-d 00:00:00', strtotime($value['DATETRANSACTION'])) ."' AND DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($value['DATETRANSACTION'])))."' AND KODEPELANGGAN =:KODEPELANGGAN AND KODESALESMAN =:USERID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'JENIS' =>$jenis."%",
                'KODEPELANGGAN' =>$value['KODEPELANGGAN'],
                'USERID' =>$USERID
            ]);
            if($point = $stmt->fetch()){
                $counter++;
                $array['total_sesuai_rencana'] = $counter;
                /*while($point = $stmt->fetch()){
                    $counter++;
                    $array['total_sesuai_rencana'] = $counter;
                }*/
            } else {

            }
        }
    } else {

    }

    $sql = "SELECT * FROM mkaryawan WHERE KODEKARYAWAN = :USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    if($profile = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['profile'] = [
            'NAMA'=>$profile['NAMA'],
            'TELEPON' =>$profile['TELEPON']
        ];
    } else {
        $array['hasil'] = "error";
    }

    if(file_exists("gambar_karyawan/".$USERID."-karyawan.jpg")){
        $array['profile']['FOTO'] = "ada";
    } else {
        $array['profile']['FOTO'] = "tidakada";
    }

    $timeDiff = abs(strtotime($TANGGALSELESAI) - strtotime($TANGGALMULAI));
    $numberDays = $timeDiff/86400;
    $numberDays = intval($numberDays);
    $array['total_point_kunjungan'] = 0;

    for($i = 0; $i<$numberDays; $i++){
        $sql = "SELECT mp.NAMA AS NAMAPELANGGAN, tk.KODEPELANGGAN AS KODEPELANGGAN, tk.DATETRANSACTION AS DATETRANSACTION_KUNJUNGAN, tk.NOMORBUKTI AS NOMORBUKTI_KUNJUNGAN FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.DATETRANSACTION >'".date('Y-m-d 00:00:00',strtotime('+'.$i.'days',strtotime($TANGGALMULAI)))."' AND tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+'.($i+1).'days',strtotime($TANGGALMULAI)))."' AND tk.KODESALESMAN =:USERID ORDER BY tk.DATETRANSACTION ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID
        ]);

        if($stmt->rowCount() > 0){
            $array['hasil_point_rencana_vs_kunjungan'] = "ada";
            while($point = $stmt->fetch()){
                $sql = "SELECT tr.DATETRANSACTION AS DATETRANSACTION_RENCANA, mp.NAMA as NAMAPELANGGAN, tr.NOMORBUKTI AS NOMORBUKTI_RENCANA, tr.CREATED_AT AS CREATED_AT FROM tmrencanakunjungan tr INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tr.KODEPELANGGAN) WHERE tr.KODEPELANGGAN = :KODEPELANGGAN AND tr.DATETRANSACTION = :DATETRANSACTION AND KODESALESMAN = :KODESALESMAN";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->execute([
                    'KODEPELANGGAN'=>$point['KODEPELANGGAN'],
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($point['DATETRANSACTION_KUNJUNGAN'])),
                    'KODESALESMAN'=>$USERID
                ]);

                if($stmt2->rowCount() > 0){
                    while($point2 = $stmt2->fetch()){
                        if(date('Y-m-d',strtotime($point2['CREATED_AT'])) < date('Y-m-d',strtotime($point2['DATETRANSACTION_RENCANA']))){
                            $array['total_point_kunjungan'] = $array['total_point_kunjungan'] +2;
                        } else {
                            $array['total_point_kunjungan'] = $array['total_point_kunjungan'] +1;
                        }

                    }
                } else {
                    $array['total_point_kunjungan'] = $array['total_point_kunjungan'] +1;
                }
            }
        }
    }

    $sql = "SELECT * FROM setting WHERE nama = 'standar_point_kunjungan'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $standar = $stmt->fetch();
    $array['standar_point_kunjungan'] = $standar['value'];

    $sql = "SELECT * FROM inbox WHERE penerima = :USERID AND status='terkirim' AND ISNULL(deleted_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    if($stmt->rowCount() > 0){
        $array['inbox'] = $stmt->rowCount();
    } else {
        $array['inbox'] = 0;
    }

    $sql = "SELECT * FROM perhitungan_point";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array['presentase_kunjungan'] = $stmt->fetchAll(PDO::FETCH_CLASS);


    echo json_encode($array);
    break;

    case "EDIT_PROFILE":
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT * FROM mkaryawan WHERE KODEKARYAWAN = :USERID";
    $stmt = $pdo->prepare($sql);
    $array = array();
    $stmt->execute(
        [
            'USERID' => $USERID
        ]);
    if($profile = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['profile'] = [
            'NAMA' =>$profile['NAMA'],
            'ALAMAT' =>$profile['ALAMAT'],
            'TELEPON' =>$profile['TELEPON']
        ];
    } else {
        $array['hasil'] = "tidakada";
    }

    if(file_exists("gambar_karyawan/".$USERID."-karyawan.jpg")){
        $array['profile']['FOTO'] = "ada";
    } else {
        $array['profile']['FOTO'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "UPDATE_PROFILE":
    $NAMA = $_POST['NAMA'];
    $ALAMAT = $_POST['ALAMAT'];
    $TELEPON = $_POST['TELEPON'];
    $USERID = $_POST['USERID'];
    try {
        $sql = "UPDATE mkaryawan set NAMA =:NAMA, ALAMAT=:ALAMAT, TELEPON=:TELEPON WHERE KODEKARYAWAN=:USERID";
        $stmt = $pdo->prepare($sql);
        $array = array();

        if($stmt->execute([
            'USERID'=>$USERID,
            'NAMA'=>$NAMA,
            'ALAMAT'=>$ALAMAT,
            'TELEPON'=>$TELEPON
        ]) == 1){
            $array['hasil'] = "berhasil";
        }
    } catch(Exception $e){
        $array['hasil'] = "eror";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_KODE_KARYAWAN":
    $KODEJABATANDARI = $_POST['KODEJABATANDARI'];
    $KODEJABATANSAMPAI = $_POST['KODEJABATANSAMPAI'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT m.NAMA AS NAMA, p.USERID as USERID FROM pemakai p INNER JOIN mkaryawan m on(m.KODEKARYAWAN = p.USERID) WHERE p.KODECABANG =:KODECABANG AND ";
    if($KODEJABATANDARI){
        $sql .= " p.KODEJABATAN>= '" . $KODEJABATANDARI ."' AND ";
    }

    if($KODEJABATANSAMPAI){
        $sql .= " p.KODEJABATAN <= '" . $KODEJABATANSAMPAI ."' AND ";
    }

    $tampungSql = substr($sql,-5);
    if($tampungSql == " AND " || $tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }

    $array = array();
    $id = 0;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['KODECABANG'=>$KODECABANG]);
    if($karyawan = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['karyawan'][$id] = [
            'KODEKARYAWAN' => $karyawan['USERID'],
            'NAMA' =>$karyawan['NAMA'],

        ];
        $id++;
        while($karyawan = $stmt->fetch()){
            $array['karyawan'][$id] = [
                'KODEKARYAWAN' => $karyawan['USERID'],
                'NAMA' =>$karyawan['NAMA'],

            ];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "DETAIL_REALISASI_KUNJUNGAN":
    $USERID = $_POST['KODESALESMAN'];
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $DATETRANSACTION = $_POST['TANGGAL'];


    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.NOMORBUKTI LIKE 'RK%' AND tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $array = array();
    $array['KODEPELANGGAN'] = array();
    $array['rencana'] = array();
    $array['kunjungan'] = array();
    $id = 0;
    $counter = 0;
    $counterKunjungan = 0;

    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    }


    if(isset($array['KODEPELANGGAN'])){
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql = "SELECT k.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, k.NOMORBUKTI AS NOMORBUKTI, k.DATETRANSACTION AS DATETRANSACTION, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan k LEFT JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'KS%' AND k.KODESALESMAN =:USERID AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID' => $USERID,
                'KODEPELANGGAN' =>$value
            ]);
            if($rencana = $stmt->fetch()){
                $array['hasil'] = "tidakada";
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'sesuai_rencana',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION'],
                    'NOMORBUKTI'=>$rencana['NOMORBUKTI'],
                    'PERMINTAANKHUSUS'=>$rencana['PERMINTAANKHUSUS'],
                ];
                


                $array['rencana'][$counter] = [
                    'jenis' => 'sudah_kunjungan',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA']
                ];
                


                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI' => $rencana['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                
                $counter++;
                $counterKunjungan++;

            } else {
                $array['hasil'] = "ada";
                $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEPELANGGAN'=>$value]);
                $rencana = $stmt->fetch();
                $array['rencana'][$counter] = [
                    'jenis' => 'kunjungan',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMA'],
                    'KOTA' => $rencana['KOTA']
                ];

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                $counter++;
            }
        }
    }



    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.NOMORBUKTI LIKE 'RC%' AND tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $id = 0;

    $array['KODEPELANGGAN'] = array();

    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    } else {

    }


    if(isset($array['KODEPELANGGAN'])){
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql = "SELECT k.NOMORBUKTI AS NOMORBUKTI, k.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, k.DATETRANSACTION AS DATETRANSACTION,  mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan k LEFT JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'FC%' AND k.KODESALESMAN =:USERID AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID' => $USERID,
                'KODEPELANGGAN' =>$value
            ]);
            if($rencana = $stmt->fetch()){
                $array['hasil'] = "tidakada";
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'sesuai_rencana',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION'],
                    'NOMORBUKTI'=>$rencana['NOMORBUKTI'],
                    'PERMINTAANKHUSUS'=>$rencana['PERMINTAANKHUSUS'],
                ];

                $array['rencana'][$counter] = [
                    'jenis' => 'sudah_collector',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA']
                ];

                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI' => $rencana['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                
                $counter++;
                $counterKunjungan++;
            } else {
                $array['hasil'] = "ada";
                $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEPELANGGAN'=>$value]);
                $rencana = $stmt->fetch();
                $array['rencana'][$counter] = [
                    'jenis' => 'collector',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMA'],
                    'KOTA' => $rencana['KOTA']
                ];
                
                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                $counter++;
            }
        }
    }

    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.NOMORBUKTI LIKE 'RF%' AND tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $array['KODEPELANGGAN'] = array();
    $id = 0;

    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    } else {

    }


    if(isset($array['KODEPELANGGAN'])){
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql = "SELECT k.NOMORBUKTI AS NOMORBUKTI, k.PERMINTAANKHUSUS as PERMINTAANKHUSUS, k.DATETRANSACTION AS DATETRANSACTION,  mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan k LEFT JOIN mpelanggan mp on(mp.KODEPELANGGAN = k.KODEPELANGGAN) WHERE k.NOMORBUKTI LIKE 'PF%' AND k.KODESALESMAN =:USERID AND  k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID' => $USERID,
                'KODEPELANGGAN' =>$value
            ]);
            if($rencana = $stmt->fetch()){
                $array['hasil'] = "tidakada";
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'sesuai_rencana',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION'],
                    'NOMORBUKTI'=>$rencana['NOMORBUKTI'],
                    'PERMINTAANKHUSUS'=>$rencana['PERMINTAANKHUSUS'],
                ];
                $array['rencana'][$counter] = [
                    'jenis' => 'sudah_kiriman',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMAPELANGGAN'],
                    'KOTA' => $rencana['KOTA']
                ];

                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['NOMORBUKTI' => $rencana['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }

                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                
                $counter++;
                $counterKunjungan++;
            } else {
                $array['hasil'] = "ada";
                $sql = "SELECT * FROM mpelanggan WHERE KODEPELANGGAN = :KODEPELANGGAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEPELANGGAN'=>$value]);
                $rencana = $stmt->fetch();
                $array['rencana'][$counter] = [
                    'jenis' => 'kiriman',
                    'KODEPELANGGAN' => $rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $rencana['NAMA'],
                    'KOTA' => $rencana['KOTA']
                ];
                
                $sql = "SELECT * FROM gambarpelanggan WHERE JENIS =:JENIS  AND KODEGAMBAR LIKE :KODEGAMBAR";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'JENIS'=>'TOKO',
                    'KODEGAMBAR'=>$value.'%'
                ]);
                if($gambartoko = $stmt->fetch()){
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "ada";
                    $array['rencana'][$counter]['nama_gambar'] = $gambartoko['KODEGAMBAR'];
                } else {
                    $array['rencana'][$counter]['gambar_rencana_kunjungan'] = "tidakada";
                    $array['rencana'][$counter]['nama_gambar'] = "no_pict";
                }
                $counter++;
            }
        }
    }




    $sql = "SELECT mk.NAMA AS NAMA FROM pemakai p INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE p.USERID=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $pemakai = $stmt->fetch();
    $array['PEMAKAI'] = [
        'NAMA'=>$pemakai['NAMA']
    ];



    $sql = "SELECT mp.KOTA AS KOTA, tk.NOMORBUKTI AS NOMORBUKTI, mp.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.KODESALESMAN =:USERID AND tk.DATETRANSACTION = :DATETRANSACTION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'USERID' => $USERID,
        'DATETRANSACTION' => date('Y-m-d',strtotime($DATETRANSACTION))
    ]);

    $array['KODEPELANGGAN'] = array();
    $id = 0;
    if($rencana = $stmt->fetch()){
        $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
        $id++;
        while($rencana = $stmt->fetch()){
            $array['KODEPELANGGAN'][$id] = $rencana['KODEPELANGGAN'];
            $id++;
        }
    }

    if(isset($array['KODEPELANGGAN'])){
        $sql = "SELECT distinct k.NOMORBUKTI AS NOMORBUKTI, k.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, k.DATETRANSACTION AS DATETRANSACTION, k.KODEPELANGGAN as KODEPELANGGAN,k.KODESALESMAN AS KODESALESMAN, p.NAMA AS NAMAPELANGGAN, p.KOTA AS KOTA FROM tmkunjungan k inner join mpelanggan p on(p.KODEPELANGGAN = k.KODEPELANGGAN) inner join mkaryawan mk on(mk.KODEKARYAWAN = k.KODESALESMAN) left join tmrencanakunjungan rk on (rk.KODEPELANGGAN = p.KODEPELANGGAN) WHERE k.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND k.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.KODESALESMAN =:KODESALESMAN AND k.NOMORBUKTI NOT LIKE 'FA%'";
        foreach ($array['KODEPELANGGAN'] as $key => $value) {
            $sql.=" AND k.KODEPELANGGAN !='".$value."'";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODESALESMAN'=>$USERID,
        ]);
        if($stmt->rowCount() > 0){
            while($kunjungan = $stmt->fetch()){
                $array['kunjungan'][$counterKunjungan] = [
                    'jenis' => 'tambahan_kunjungan',
                    'KODEPELANGGAN' => $kunjungan['KODEPELANGGAN'],
                    'NAMAPELANGGAN' => $kunjungan['NAMAPELANGGAN'],
                    'KOTA' => $kunjungan['KOTA'],
                    'DATETRANSACTION' => $kunjungan['DATETRANSACTION'],
                    'NOMORBUKTI'=>$kunjungan['NOMORBUKTI'],
                    'PERMINTAANKHUSUS'=>$kunjungan['PERMINTAANKHUSUS'],
                ];

                $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NOMORBUKTI";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->execute(['NOMORBUKTI' => $kunjungan['NOMORBUKTI'].'%']);
                if($gambarkunjungan = $stmt2->fetch()){
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "ada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = $gambarkunjungan['NAMAGAMBAR'];
                } else {
                    $array['kunjungan'][$counterKunjungan]["gambar_kunjungan"] = "tidakada";
                    $array['kunjungan'][$counterKunjungan]["nama_gambar"] = "no_pict";
                }
                $counterKunjungan++;
            }
        } else {

        }
    }
    echo json_encode($array);
    break;

    case "DETAIL_HISTORY_TRACKING":
    $TANGGAL = $_POST['TANGGAL'];
    $KODESALESMAN = $_POST['KODESALESMAN'];
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $array = array();
    try {
        $array['WAKTU_START'] = "";
        $array['WAKTU_FINISH'] = "";
        $id = 0;
        $sql = "SELECT mp.KOTA AS KOTA, mp.ALAMAT AS ALAMAT,tk.NOMORBUKTI AS NOMORBUKTI, tk.STATUSKUNJUNGAN AS STATUSKUNJUNGAN, mp.ALAMAT AS ALAMAT, mp.KODECABANG AS KODECABANG, tk.DATETRANSACTION AS DATETRANSACTION, mk.NAMA AS NAMAKARYAWAN, mp.NAMA AS NAMAPELANGGAN, tk.KODESALESMAN AS KODESALESMAN, mp.KODEPELANGGAN AS KODEPELANGGAN, tk.LATITUDE AS LATITUDE, tk.LONGITUDE AS LONGITUDE FROM tmkunjungan tk INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = tk.KODESALESMAN) INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE !isnull(tk.LATITUDE) AND !isnull(tk.LONGITUDE) AND tk.NOMORBUKTI NOT LIKE 'FA%' AND mp.KODECABANG =:KODECABANG AND tk.KODESALESMAN = :KODESALESMAN AND tk.DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($TANGGAL))."' AND tk.DATETRANSACTION <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGAL)))."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODECABANG'=>$KODECABANG,
            'KODESALESMAN'=>$KODESALESMAN
        ]);
        if($kunjungan = $stmt->fetch()){
            $array['hasil_kunjungan'] = "ada";
            $jenis="";
            if(substr($kunjungan['NOMORBUKTI'], 0, 2) == "KS"){
                $jenis = "kunjungan";
            } else if(substr($kunjungan['NOMORBUKTI'], 0, 2) == "PF"){
                $jenis = "kiriman";
            } else if(substr($kunjungan['NOMORBUKTI'], 0, 2) == "FC") {
                $jenis = "collector";
            }
            $array['kunjungan'][$id] = [
                'STATUSKUNJUNGAN'=>$kunjungan['STATUSKUNJUNGAN'],
                'ALAMAT'=>$kunjungan['ALAMAT'],
                'DATETRANSACTION'=>$kunjungan['DATETRANSACTION'],
                'NAMAKARYAWAN'=>$kunjungan['NAMAKARYAWAN'],
                'NAMAPELANGGAN'=>$kunjungan['NAMAPELANGGAN'],
                'KODEPELANGGAN'=>$kunjungan['KODEPELANGGAN'],
                'LATITUDE'=>$kunjungan['LATITUDE'],
                'LONGITUDE'=>$kunjungan['LONGITUDE'],
                'KOTA'=>$kunjungan['KOTA'],
                'ALAMAT'=>$kunjungan['ALAMAT'],
                'JENIS'=>$jenis
            ];
            $id++;

            $array['WAKTU_START'] = $kunjungan['DATETRANSACTION'];
            while($kunjungan = $stmt->fetch()){
                $jenis="";
                if(substr($kunjungan['NOMORBUKTI'], 0, 2) == "KS"){
                    $jenis = "kunjungan";
                } else if(substr($kunjungan['NOMORBUKTI'], 0, 2) == "PF"){
                    $jenis = "kiriman";
                } else if(substr($kunjungan['NOMORBUKTI'], 0, 2) == "FC") {
                    $jenis = "collector";
                }
                $array['kunjungan'][$id] = [
                    'STATUSKUNJUNGAN'=>$kunjungan['STATUSKUNJUNGAN'],
                    'ALAMAT'=>$kunjungan['ALAMAT'],
                    'DATETRANSACTION'=>$kunjungan['DATETRANSACTION'],
                    'NAMAKARYAWAN'=>$kunjungan['NAMAKARYAWAN'],
                    'NAMAPELANGGAN'=>$kunjungan['NAMAPELANGGAN'],
                    'KODEPELANGGAN'=>$kunjungan['KODEPELANGGAN'],
                    'LATITUDE'=>$kunjungan['LATITUDE'],
                    'LONGITUDE'=>$kunjungan['LONGITUDE'],
                    'KOTA'=>$kunjungan['KOTA'],
                    'ALAMAT'=>$kunjungan['ALAMAT'],
                    'JENIS'=>$jenis
                ];
                $id++;

                $array['WAKTU_FINISH'] = $kunjungan['DATETRANSACTION'];
            }
        } else {
            $array['hasil_kunjungan'] = "tidakada";
        }


        /*$sql = "SELECT hp.LATITUDE AS LATITUDE, hp.LONGITUDE AS LONGITUDE, hp.WAKTU AS WAKTU, mk.NAMA AS NAMAKARYAWAN FROM history_perjalanan hp INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = hp.USERID) WHERE hp.USERID=:KODESALESMAN AND hp.WAKTU >='".date('Y-m-d 00:00:00',strtotime($TANGGAL))."' AND hp.WAKTU <'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGAL)))."'";*/

        $sql = "SELECT hp.LATITUDE AS LATITUDE, hp.LONGITUDE AS LONGITUDE, hp.WAKTU AS WAKTU, mk.NAMA AS NAMAKARYAWAN FROM history_perjalanan hp INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = hp.USERID) WHERE hp.USERID=:KODESALESMAN AND hp.WAKTU >'".date('Y-m-d H:i:s',strtotime($array['WAKTU_START']))."' AND hp.WAKTU <'".date('Y-m-d H:i:s', strtotime($array['WAKTU_FINISH']))."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODESALESMAN'=>$KODESALESMAN
        ]);
        if($koordinat = $stmt->fetch()){
            $id = 0;
            $counter = 0;
            $array['hasil'] = "ada";
            $array['tracking'][$id] = [
                'LATITUDE'=>$koordinat['LATITUDE'],
                'LONGITUDE'=>$koordinat['LONGITUDE'],
                'WAKTU'=>$koordinat['WAKTU'],
                'NAMAKARYAWAN'=>$koordinat['NAMAKARYAWAN'],
                'RUTE'=> $counter+1
            ];
            if($array['kunjungan'][$counter]['DATETRANSACTION'] > $koordinat['WAKTU']){

            } else {
                $counter++;
            }
            $id++;
            while($koordinat = $stmt->fetch()){
                $array['tracking'][$id] = [
                    'LATITUDE'=>$koordinat['LATITUDE'],
                    'LONGITUDE'=>$koordinat['LONGITUDE'],
                    'WAKTU'=>$koordinat['WAKTU'],
                    'NAMAKARYAWAN'=>$koordinat['NAMAKARYAWAN'],
                    'RUTE'=> $counter
                ];
                if($array['kunjungan'][$counter]['DATETRANSACTION'] > $koordinat['WAKTU']){

                } else {
                    $counter++;
                }
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }

    } catch (Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }
    echo json_encode($array);
    break;

    case "EXPORT_RENCANA_KUNJUNGAN":
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];

    try{

        if(isset($_POST['parameter_rencana'])){
            $parameter_rencana = $_POST['parameter_rencana'];
        } else { $parameter_rencana = null; }

        if(isset($_POST['TANGGALDARI'])){
            $TANGGALDARI = $_POST['TANGGALDARI'];
        } else { $TANGGALDARI = null; }

        if(isset($_POST['TANGGALSAMPAI'])){
            $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
        } else { $TANGGALSAMPAI = null; }

        if(isset($_POST['STAFFDARI'])){
            $STAFFDARI = $_POST['STAFFDARI'];
        } else { $STAFFDARI = null; }

        if(isset($_POST['STAFFSAMPAI'])){
            $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
        } else { $STAFFSAMPAI = null; }


        if($parameter_rencana){
            $sql = "SELECT tm.NOMORBUKTI AS NOMORBUKTI, tm.KODESALESMAN AS KODESALESMAN, tm.DATETRANSACTION AS DATETRANSACTION, tm.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on (mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE";
            if($STAFFDARI){
                $sql .= " tm.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
            }

            if($STAFFSAMPAI){
                $sql .= " tm.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
            }

            if($TANGGALDARI){
                $sql .= " tm.DATETRANSACTION >= '" . date('Y-m-d',strtotime($TANGGALDARI)) ."' AND ";
            }

            if($TANGGALSAMPAI){
                $sql .= " tm.DATETRANSACTION <= '" . date('Y-m-d',strtotime($TANGGALSAMPAI)) ."' AND ";
            }
            $tampungSql = substr($sql,-5);
            if($tampungSql == " AND " || $tampungSql == "WHERE"){
                $sql=substr($sql,0,-5);
            }
        } else {
            $sql = "SELECT tm.NOMORBUKTI AS NOMORBUKTI, tm.KODESALESMAN AS KODESALESMAN, tm.DATETRANSACTION AS DATETRANSACTION, tm.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE tm.KODESALESMAN = '".$USERID."'";
        }

        $array = array();
        $id = 0;
        $stmt = $pdo->prepare($sql);
        if($stmt ->execute() == 1){
            $array['hasil'] = "ada";
            while($rencana = $stmt->fetch()){

                $array['RENCANA'][$id] = [
                    'NOMORBUKTI' =>$rencana['NOMORBUKTI'],
                    'KODESALESMAN' =>$rencana['KODESALESMAN'],
                    'DATETRANSACTION' =>$rencana['DATETRANSACTION'],
                    'KODEPELANGGAN' =>$rencana['KODEPELANGGAN'],
                    'NAMAPELANGGAN' =>$rencana['NAMAPELANGGAN'],
                ];
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }
    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "EXPORT_KUNJUNGAN":
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];

    try{

        if(isset($_POST['TANGGALDARI'])){
            $TANGGALDARI = $_POST['TANGGALDARI'];
        } else { $TANGGALDARI = null; }

        if(isset($_POST['TANGGALSAMPAI'])){
            $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
        } else { $TANGGALSAMPAI = null; }

        if(isset($_POST['STAFFDARI'])){
            $STAFFDARI = $_POST['STAFFDARI'];
        } else { $STAFFDARI = null; }

        if(isset($_POST['STAFFSAMPAI'])){
            $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
        } else { $STAFFSAMPAI = null; }


        $sql = "SELECT tm.NOMORBUKTI AS NOMORBUKTI, tm.KODESALESMAN AS KODESALESMAN, tm.DATETRANSACTION AS DATETRANSACTION, tm.KODEPELANGGAN AS KODEPELANGGAN, mp.NAMA AS NAMAPELANGGAN FROM tmkunjungan tm INNER JOIN mpelanggan mp on (mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE tm.NOMORBUKTI LIKE 'KS%' AND ";
        if($STAFFDARI){
            $sql .= " tm.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
        }

        if($STAFFSAMPAI){
            $sql .= " tm.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
        }

        if($TANGGALDARI){
            $sql .= " tm.DATETRANSACTION >= '" . date('Y-m-d 00:00:00',strtotime($TANGGALDARI)) ."' AND ";
        }

        if($TANGGALSAMPAI){
            $sql .= " tm.DATETRANSACTION < '" . date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI))) ."' AND ";
        }
        $tampungSql = substr($sql,-5);
        if($tampungSql == " AND " || $tampungSql == "WHERE"){
            $sql=substr($sql,0,-5);
        }

        $array = array();
        $id = 0;
        $stmt = $pdo->prepare($sql);
        if($stmt ->execute() == 1){
            $array['hasil'] = "ada";
            while($kunjungan = $stmt->fetch()){

                $array['KUNJUNGAN'][$id] = [
                    'NOMORBUKTI' =>$kunjungan['NOMORBUKTI'],
                    'KODESALESMAN' =>$kunjungan['KODESALESMAN'],
                    'DATETRANSACTION' =>$kunjungan['DATETRANSACTION'],
                    'KODEPELANGGAN' =>$kunjungan['KODEPELANGGAN'],
                    'NAMAPELANGGAN' =>$kunjungan['NAMAPELANGGAN'],
                ];
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }
    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "LAPORAN_RENCANA_KUNJUNGAN":
    try {
        $USERID = $_POST['USERID'];
        $KODECABANG = $_POST['KODECABANG'];

        if(isset($_POST['TANGGALDARI'])){
            $TANGGALDARI = $_POST['TANGGALDARI'];
        } else { $TANGGALDARI = null; }

        if(isset($_POST['TANGGALSAMPAI'])){
            $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
        } else { $TANGGALSAMPAI = null; }

        if(isset($_POST['STAFFDARI'])){
            $STAFFDARI = $_POST['STAFFDARI'];
        } else { $STAFFDARI = null; }

        if(isset($_POST['STAFFSAMPAI'])){
            $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
        } else { $STAFFSAMPAI = null; }

        $sql = "SELECT tm.DATETRANSACTION AS DATETRANSACTION, tm.NOMORBUKTI AS NOMORBUKTI, tm.KODESALESMAN AS KODESALESMAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE tm.NOMORBUKTI LIKE 'RK%' AND ";
        if($STAFFDARI){
            $sql .= " tm.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
        }

        if($STAFFSAMPAI){
            $sql .= " tm.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
        }

        if($TANGGALDARI){
            $sql .= " tm.DATETRANSACTION >= '" . date('Y-m-d',strtotime($TANGGALDARI)) ."' AND ";
        }

        if($TANGGALSAMPAI){
            $sql .= " tm.DATETRANSACTION <= '" . date('Y-m-d',strtotime($TANGGALSAMPAI)) ."' AND ";
        }

        $tampungSql = substr($sql,-5);
        if($tampungSql == " AND " || $tampungSql == "WHERE"){
            $sql=substr($sql,0,-5);
        }
        $sql .= " ORDER BY tm.DATETRANSACTION ASC, tm.KODESALESMAN ASC";
        $array = array();
        $id = 0;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $array['hasil'] = "ada";
            while($rencana = $stmt->fetch()){
                $array['RENCANA'][$id] = [
                    'DATETRANSACTION'=>$rencana['DATETRANSACTION'],
                    'NOMORBUKTI'=>$rencana['NOMORBUKTI'],
                    'KODESALESMAN'=>$rencana['KODESALESMAN'],
                    'NAMAPELANGGAN'=>$rencana['NAMAPELANGGAN'],
                    'KOTA'=>$rencana['KOTA'],
                ];

                $array['KODESALESMAN'][$id] = [
                    'KODESALESMAN'=>$rencana['KODESALESMAN'],
                    'DATETRANSACTION'=>$rencana['DATETRANSACTION'],
                    'JUMLAH'=>1,
                ];
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }


        $sql = "SELECT * FROM pemakai WHERE KODEJABATAN = 'SALESMAN'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $array['SALESMAN'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        $id = 0;
        if(!$TANGGALSAMPAI){
            $TANGGALSAMPAI = $TANGGALDARI;
        }
        while(date('Y-m-d',strtotime($TANGGALDARI)) <= date('Y-m-d',strtotime($TANGGALSAMPAI))){
            foreach ($array['SALESMAN'] as $key => $value) {
                $sql = "SELECT * FROM tmrencanakunjungan WHERE KODESALESMAN = :KODESALESMAN AND DATETRANSACTION=:DATETRANSACTION";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODESALESMAN' => $value->USERID,
                    'DATETRANSACTION' => date('Y-m-d',strtotime($TANGGALDARI))
                ]);
                if($stmt->rowCount() == 0){
                    $array['TIDAK_ADA_RENCANA'][$id] = [
                        'KODESALESMAN' => $value->USERID,
                        'JUMLAH_RENCANA' => 0,
                        'TANGGAL' => date('Y-m-d',strtotime($TANGGALDARI))
                    ];
                    $id++;
                }
            }
            
                $TANGGALDARI = date('Y-m-d',strtotime('+1days', strtotime($TANGGALDARI)));
        }
    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "LAPORAN_KUNJUNGAN":
    try {
        $USERID = $_POST['USERID'];
        $KODECABANG = $_POST['KODECABANG'];

        if(isset($_POST['TANGGALDARI'])){
            $TANGGALDARI = $_POST['TANGGALDARI'];
        } else { $TANGGALDARI = null; }

        if(isset($_POST['TANGGALSAMPAI'])){
            $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
        } else { $TANGGALSAMPAI = null; }

        if(isset($_POST['STAFFDARI'])){
            $STAFFDARI = $_POST['STAFFDARI'];
        } else { $STAFFDARI = null; }

        if(isset($_POST['STAFFSAMPAI'])){
            $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
        } else { $STAFFSAMPAI = null; }

        
        $sql = "SELECT  tm.KODEPELANGGAN AS KODEPELANGGAN,date(tm.DATETRANSACTION) AS DATETRANSACTION, tm.NOMORBUKTI AS NOMORBUKTI, tm.KODESALESMAN AS KODESALESMAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmkunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE tm.NOMORBUKTI LIKE 'KS%' AND ";
        if($STAFFDARI){
            $sql .= " tm.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
        }

        if($STAFFSAMPAI){
            $sql .= " tm.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
        }

        if($TANGGALDARI){
            $sql .= " tm.DATETRANSACTION >= '" . date('Y-m-d 00:00:00',strtotime($TANGGALDARI)) ."' AND ";
        }

        if($TANGGALSAMPAI){
            $sql .= " tm.DATETRANSACTION < '" . date('Y-m-d 00:00:00',strtotime('+1days', strtotime($TANGGALSAMPAI))) ."' AND ";
        }

        $tampungSql = substr($sql,-5);
        if($tampungSql == " AND " || $tampungSql == "WHERE"){
            $sql=substr($sql,0,-5);
        }
        $sql .= " ORDER BY DATETRANSACTION ASC, tm.KODESALESMAN ASC";
        $array = array();
        $id = 0;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $array['hasil'] = "ada";
            while($rencana = $stmt->fetch()){
                $array['KUNJUNGAN'][$id] = [
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($rencana['DATETRANSACTION'])),
                    'NOMORBUKTI'=>$rencana['NOMORBUKTI'],
                    'KODESALESMAN'=>$rencana['KODESALESMAN'],
                    'NAMAPELANGGAN'=>$rencana['NAMAPELANGGAN'],
                    'KOTA'=>$rencana['KOTA'],
                    'KODEPELANGGAN'=>$rencana['KODEPELANGGAN']

                ];

                $array['KODESALESMAN'][$id] = [
                    'KODESALESMAN'=>$rencana['KODESALESMAN'],
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($rencana['DATETRANSACTION'])),
                    'JUMLAH'=>1,
                ];
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }



        if($array['KUNJUNGAN']){
            foreach ($array['KUNJUNGAN'] as $key => $value) {
                $sql = "SELECT * FROM tmrencanakunjungan WHERE KODEPELANGGAN=:KODEPELANGGAN AND KODESALESMAN=:KODESALESMAN AND DATETRANSACTION=:DATETRANSACTION";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'KODEPELANGGAN'=>$value['KODEPELANGGAN'],
                    'KODESALESMAN'=>$value['KODESALESMAN'],
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($value['DATETRANSACTION']))
                ]);
                if($sesuaikunjungan = $stmt->fetch()){
                    $array['KUNJUNGAN'][$key]['SESUAI_KUNJUNGAN'] = "ya";
                } else {
                    $array['KUNJUNGAN'][$key]['SESUAI_KUNJUNGAN'] = "tidak";
                }


            }
        }


    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "DETAIL_ISI_REALISASI_KUNJUNGAN":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $NOMORBUKTI = $_POST['NOMORBUKTI'];
    try {

        $sql = "SELECT mp.NAMA AS NAMAPELANGGAN, mp.KODEPELANGGAN AS KODEPELANGGAN, k.NOMORBUKTI AS NOMORBUKTI, k.DATETRANSACTION AS DATETRANSACTION, k.KETERANGAN AS KETERANGAN, k.KESIMPULAN AS KESIMPULAN, k.PERMINTAANKHUSUS AS PERMINTAANKHUSUS, k.WAKTUMASUK AS WAKTUMASUK FROM mpelanggan mp INNER JOIN tmkunjungan k on(k.KODEPELANGGAN = mp.KODEPELANGGAN) WHERE k.NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $array = array();
        $id = 0;
        $stmt->execute(['NOMORBUKTI'=>$NOMORBUKTI]);
        if($kunjungan = $stmt->fetch()){
            $array['hasil'] = "ada";
            $array['kunjungan'] = [
                'NAMAPELANGGAN' =>$kunjungan['NAMAPELANGGAN'],
                'KODEPELANGGAN' =>$kunjungan['KODEPELANGGAN'],
                'NOMORBUKTI' =>$kunjungan['NOMORBUKTI'],
                'WAKTUMASUK' =>$kunjungan['WAKTUMASUK'],
                'DATETRANSACTION' =>$kunjungan['DATETRANSACTION'],
                'KETERANGAN' =>$kunjungan['KETERANGAN'],
                'KESIMPULAN' =>$kunjungan['KESIMPULAN'],
                'PERMINTAANKHUSUS' =>$kunjungan['PERMINTAANKHUSUS'],
            ];
        } else {
            $array['hasil'] = "tidakada";
        }

        $sql = "SELECT * FROM gambarkunjungan WHERE NAMAGAMBAR LIKE :NAMAGAMBAR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NAMAGAMBAR' => $NOMORBUKTI.'%']);
        if($stmt->rowCount()>0){
            $array['gambar'] = "ada";
            while($gambar = $stmt->fetch()){
                $array['NAMAGAMBAR'][$id] = [
                    'NAMAGAMBAR' => $gambar['NAMAGAMBAR']
                ];
                $id++;
            }
        } else {
            $array['gambar'] = "tidakada";
        }

    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "IMPORT_TM_FAKTUR":
    try {
        $DATA = $_POST['data'];
        $array = array();
        $pdo->beginTransaction();
        foreach ($DATA as $key => $value) {
            $sql = "SELECT * FROM tmfaktur WHERE NOMORFAKTUR = :NOMORFAKTUR";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['NOMORFAKTUR'=>$value['NOMORFAKTUR']]);
            if(empty($value['KODESOPIR']) || !$value['KODESOPIR']){
                $value['KODESOPIR'] = "";
            }
            if(empty($value['KETERANGAN']) || !$value['KETERANGAN']){
                $value['KETERANGAN'] = "";
            }
            if(empty($value['NPWP']) || !$value['NPWP']){
                $value['NPWP'] = "";
            }
            if(empty($value['KETERANGAN2']) || !$value['KETERANGAN2']){
                $value['KETERANGAN2'] = "";
            }
            if(empty($value['KETERANGANDISC']) || !$value['KETERANGANDISC']){
                $value['KETERANGANDISC'] = "";
            }
            if(empty($value['NOMORBUKTILUNAS']) || !$value['NOMORBUKTILUNAS']){
                $value['NOMORBUKTILUNAS'] = "";
            }
            if(empty($value['USERIDCETAK']) || !$value['USERIDCETAK']){
                $value['USERIDCETAK'] = "";
            }

            if($stmt->rowCount() > 0){

            } else {
                $sql = "INSERT INTO tmfaktur(NOMORFAKTUR,DATETRANSACTION,KODEPELANGGAN,KODESALESMAN,KODESOPIR,CASH,TERM,TGLJTP,TGLKIRIM,PPN,DISCTOTAL,TOTAL,BAYAR,KETERANGAN,CETAK,ITEMNO,LUNAS,REKPIUTANG,REKJASA,REKPENJUALAN,REKHPP,REKPERSEDIAAN,STATUS,USERID,NOMORFAKTURPAJAK,TERKIRIM,NPWP,USERIDCETAK,KETERANGAN2,USERIDKIRIM,USERIDGUDANG,HASILKIRIM,TGLKIRIMGUDANG,TERMMAX,USERIDUBAH,TGLJTPCETAK,STANDARD,DISCREBATE,REKDISC,KETERANGANDISC,MODIFIKASI,TGLLUNAS,TOTALLUNAS,NOMORBUKTILUNAS) 
                values(:NOMORFAKTUR,:DATETRANSACTION,:KODEPELANGGAN,:KODESALESMAN,:KODESOPIR,:CASH,:TERM,:TGLJTP,:TGLKIRIM,:PPN,:DISCTOTAL,:TOTAL,:BAYAR,:KETERANGAN,:CETAK,:ITEMNO,:LUNAS,:REKPIUTANG,:REKJASA,:REKPENJUALAN,:REKHPP,:REKPERSEDIAAN,:STATUS,:USERID,:NOMORFAKTURPAJAK,:TERKIRIM,:NPWP,:USERIDCETAK,:KETERANGAN2,:USERIDKIRIM,:USERIDGUDANG,:HASILKIRIM,:TGLKIRIMGUDANG,:TERMMAX,:USERIDUBAH,:TGLJTPCETAK,:STANDARD,:DISCREBATE,:REKDISC,:KETERANGANDISC,:MODIFIKASI,:TGLLUNAS,:TOTALLUNAS,:NOMORBUKTILUNAS)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NOMORFAKTUR'=>$value['NOMORFAKTUR'],
                    'DATETRANSACTION'=>date('Y-m-d',strtotime($value['DATETRANSACTION'])),
                    'KODEPELANGGAN'=>$value['KODEPELANGGAN'],
                    'KODESALESMAN'=>$value['KODESALESMAN'],
                    'KODESOPIR'=>$value['KODESOPIR'],
                    'CASH'=>$value['CASH'],
                    'TERM'=>$value['TERM'],
                    'TGLJTP'=>date('Y-m-d',strtotime($value['TGLJTP'])),
                    'TGLKIRIM'=>date('Y-m-d',strtotime($value['TGLKIRIM'])),
                    'PPN'=>$value['PPN'],
                    'DISCTOTAL'=>$value['DISCTOTAL'],
                    'TOTAL'=>$value['TOTAL'],
                    'BAYAR'=>$value['BAYAR'],
                    'KETERANGAN'=>$value['KETERANGAN'],
                    'CETAK'=>$value['CETAK'],
                    'ITEMNO'=>$value['ITEMNO'],
                    'LUNAS'=>$value['LUNAS'],
                    'REKPIUTANG'=>$value['REKPIUTANG'],
                    'REKJASA'=>$value['REKJASA'],
                    'REKPENJUALAN'=>$value['REKPENJUALAN'],
                    'REKHPP'=>$value['REKHPP'],
                    'REKPERSEDIAAN'=>$value['REKPERSEDIAAN'],
                    'STATUS'=>$value['STATUS'],
                    'USERID'=>$value['USERID'],
                    'NOMORFAKTURPAJAK'=>$value['NOMORFAKTURPAJAK'],
                    'TERKIRIM'=>$value['TERKIRIM'],
                    'NPWP'=>$value['NPWP'],
                    'USERIDCETAK'=>$value['USERIDCETAK'],
                    'KETERANGAN2'=>$value['KETERANGAN2'],
                    'USERIDKIRIM'=>$value['USERIDKIRIM'],
                    'USERIDGUDANG'=>$value['USERIDGUDANG'],
                    'HASILKIRIM'=>$value['HASILKIRIM'],
                    'TGLKIRIMGUDANG'=>date('Y-m-d H:i:s',strtotime($value['TGLKIRIMGUDANG'])),
                    'TERMMAX'=>$value['TERMMAX'],
                    'USERIDUBAH'=>$value['USERIDUBAH'],
                    'TGLJTPCETAK'=>date('Y-m-d',strtotime($value['TGLJTPCETAK'])),
                    'STANDARD'=>$value['STANDARD'],
                    'DISCREBATE'=>$value['DISCREBATE'],
                    'REKDISC'=>$value['REKDISC'],
                    'KETERANGANDISC'=>$value['KETERANGANDISC'],
                    'MODIFIKASI'=>$value['MODIFIKASI'],
                    'TGLLUNAS'=>date('Y-m-d',strtotime($value['TGLLUNAS'])),
                    'TOTALLUNAS'=>$value['TOTALLUNAS'],
                    'NOMORBUKTILUNAS'=>$value['NOMORBUKTILUNAS'],

                ]);
            }
        }
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch(Exception $e){
        $array['hasil'] = "error";
        $pdo->rollBack();
    }
    echo json_encode($array);
    break;

    case "IMPORT_TD_FAKTUR":
    try {
        $DATA = $_POST['data'];
        $array = array();
        $pdo->beginTransaction();
        foreach ($DATA as $key => $value) {
            $sql = "SELECT * FROM tdfaktur WHERE NOMORFAKTUR = :NOMORFAKTUR AND KODEBARANG=:KODEBARANG";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'NOMORFAKTUR'=>$value['NOMORFAKTUR'],
                'KODEBARANG'=>$value['KODEBARANG']
            ]);

            if(empty($value['KODESOPIR']) || !$value['KODESOPIR']){
                $value['KODESOPIR'] = NULL;
            }
            if(empty($value['NOMORBUKTI']) || !$value['NOMORBUKTI']){
                $value['NOMORBUKTI'] = NULL;
            }
            if(empty($value['DPP']) || !$value['DPP']){
                $value['DPP'] = NULL;
            }
            if(empty($value['PPNRP']) || !$value['PPNRP']){
                $value['PPNRP'] = NULL;
            }
            
            if($stmt->rowCount() > 0){

            } else {
                $sql = "INSERT INTO tdfaktur(NOMORFAKTUR,KODELOKASI,KODEKATEGORI,KODEBARANG,QUANTITY,HARGA,DISC,RETUR,CETAKPAJAK,KODESOPIR,NOMORBUKTI,DPP,PPNRP) values(:NOMORFAKTUR,:KODELOKASI,:KODEKATEGORI,:KODEBARANG,:QUANTITY,:HARGA,:DISC,:RETUR,:CETAKPAJAK,:KODESOPIR,:NOMORBUKTI,:DPP,:PPNRP)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'NOMORFAKTUR'=>$value['NOMORFAKTUR'],
                    'KODELOKASI'=>$value['KODELOKASI'],
                    'KODEKATEGORI'=>$value['KODEKATEGORI'],
                    'KODEBARANG'=>$value['KODEBARANG'],
                    'QUANTITY'=>$value['QUANTITY'],
                    'HARGA'=>$value['HARGA'],
                    'DISC'=>$value['DISC'],
                    'RETUR'=>$value['RETUR'],
                    'CETAKPAJAK'=>$value['CETAKPAJAK'],
                    'KODESOPIR'=>$value['KODESOPIR'],
                    'NOMORBUKTI'=>$value['NOMORBUKTI'],
                    'DPP'=>$value['DPP'],
                    'PPNRP'=>$value['PPNRP'],
                ]);
            }
        }
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch(Exception $e){
        echo "asd";
        $array['hasil'] = "error";
        $pdo->rollBack();
    }
    echo json_encode($array);
    break;

    case "EXPORT_PRICELIST":
    $sql = "SELECT * FROM mbrg";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($pricelist = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['PRICELIST'][$id] = [
            'kodekategori'=>$pricelist['kodekategori'],
            'kodebarang'=>$pricelist['kodebarang'],
            'nama '=>$pricelist['nama'],
            'stok'=>$pricelist['stok'],
            'keterangan'=>$pricelist['keterangan'],
            'satuan'=>$pricelist['satuan'],
            'discmax'=>$pricelist['discmax'],
            'minbrg'=>$pricelist['minbrg'],
            'maxbrg'=>$pricelist['maxbrg'],
            'hargabeli'=>$pricelist['hargabeli'],
            'hargajual'=>$pricelist['hargajual'],
            'pricelistb1'=>$pricelist['pricelistb1'],
            'pricelistb2'=>$pricelist['pricelistb2'],
            'pricelistj1'=>$pricelist['pricelistj1'],
            'pricelistj2'=>$pricelist['pricelistj2'],
            'pricelistj3'=>$pricelist['pricelistj3'],
            'userid'=>$pricelist['userid'],
            'status'=>$pricelist['status'],
            'kodegolongan'=>$pricelist['kodegolongan'],
            'kodegolonganb'=>$pricelist['kodegolonganb'],
            'kodegolonganc'=>$pricelist['kodegolonganc'],
            'kodegolongand'=>$pricelist['kodegolongand'],
            'pengali'=>$pricelist['pengali'],
            'ring'=>$pricelist['ring'],
            'hpt'=>$pricelist['hpt'],
            'pointbeli'=>$pricelist['pointbeli'],
            'pointjual'=>$pricelist['pointjual'],
            'pricelistb3'=>$pricelist['pricelistb3'],
        ];
        $id++;
        while($pricelist = $stmt->fetch()){
            $array['PRICELIST'][$id] = [
                'kodekategori'=>$pricelist['kodekategori'],
                'kodebarang'=>$pricelist['kodebarang'],
                'nama '=>$pricelist['nama'],
                'stok'=>$pricelist['stok'],
                'keterangan'=>$pricelist['keterangan'],
                'satuan'=>$pricelist['satuan'],
                'discmax'=>$pricelist['discmax'],
                'minbrg'=>$pricelist['minbrg'],
                'maxbrg'=>$pricelist['maxbrg'],
                'hargabeli'=>$pricelist['hargabeli'],
                'hargajual'=>$pricelist['hargajual'],
                'pricelistb1'=>$pricelist['pricelistb1'],
                'pricelistb2'=>$pricelist['pricelistb2'],
                'pricelistj1'=>$pricelist['pricelistj1'],
                'pricelistj2'=>$pricelist['pricelistj2'],
                'pricelistj3'=>$pricelist['pricelistj3'],
                'userid'=>$pricelist['userid'],
                'status'=>$pricelist['status'],
                'kodegolongan'=>$pricelist['kodegolongan'],
                'kodegolonganb'=>$pricelist['kodegolonganb'],
                'kodegolonganc'=>$pricelist['kodegolonganc'],
                'kodegolongand'=>$pricelist['kodegolongand'],
                'pengali'=>$pricelist['pengali'],
                'ring'=>$pricelist['ring'],
                'hpt'=>$pricelist['hpt'],
                'pointbeli'=>$pricelist['pointbeli'],
                'pointjual'=>$pricelist['pointjual'],
                'pricelistb3'=>$pricelist['pricelistb3'],

            ];
            $id++;  
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "CEK_STATUS_INPUT_ABSENSI":
    try {
        $USERID = $_POST['USERID'];
        $array = array();
        $sql = "SELECT * FROM mabsensi WHERE userid=:USERID AND tanggal=:TANGGAL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID' => $USERID,
            'TANGGAL' => date('Y-m-d')
        ]);
        if($absensi = $stmt->fetch()){
            $array['hasil'] = "LOGOUT";
            $array['id'] = $absensi['id'];
        } else {
            $array['hasil'] = "LOGIN";
            $array['id'] = $absensi['id'];
        }
    } catch(Exception $e){

    }
    echo json_encode($array);
    break;

    case "INPUT_ABSENSI":
    $pdo->beginTransaction();
    try {
        $array = array();
        $USERID = $_POST['USERID'];
        $jenis = $_POST['jenis'];
        $id_absensi = "";
        $id_history_absensi = "";

        if(isset($_POST["foto"])){
            $foto = $_POST["foto"];  
            $namaFoto = $_POST['namaFoto'];
        } else {
            //throw new Exception("Foto Harus Di Isi Terlebih Dahulu");
        }

        if(isset($_POST["latitude"])){
            $latitude = $_POST["latitude"];  
            $longitude = $_POST['longitude'];
        } else {
            throw new Exception("Silahkan Menghubungi Admin. (112)");
        }


        if(isset($_POST["id_absensi"])){
            $id_absensi = $_POST["id_absensi"];  
        } else {
            $id_absensi = null;
        }

        if($jenis == "LOGIN"){
            $sql = "SELECT * FROM mabsensi WHERE userid=:USERID AND tanggal=:TANGGAL";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID'=>$USERID,
                'TANGGAL'=>date('Y-m-d'),
            ]);
            if($stmt->rowCount()==0){
                $sql = "INSERT INTO mabsensi (tanggal,waktu_login,latitude_login,longitude_login,userid) values('".date('Y-m-d')."', '".date('Y-m-d H:i:s')."' , :latitude,:longitude, :USERID)"; 
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'latitude'=>$latitude,
                    'longitude'=>$longitude,
                    'USERID'=>$USERID
                ]);
                $id_absensi = $pdo->lastInsertId();
                $array['lastid'] = $id_absensi;
            } else {
                $sql = "UPDATE mabsensi set waktu_login = :waktu , latitude_login=:latitude, longitude_login=:longitude WHERE id=:id_absensi";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'waktu' => date('Y-m-d H:i:s'),
                    'latitude' => $latitude,
                    'longitude'=>$longitude,
                    'id_absensi'=>$id_absensi
                ]);
                $array['lastid'] = $id_absensi;
            }

            $sql = "INSERT INTO history_mabsensi (id_absensi,tanggal,waktu,jenis,latitude,longitude,userid) values(:id_absensi,'".date('Y-m-d')."', '".date('Y-m-d H:i:s')."' ,:jenis, :latitude,:longitude, :USERID)"; 
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_absensi'=>$id_absensi,
                'jenis'=>"LOGIN",
                'latitude'=>$latitude,
                'longitude'=>$longitude,
                'USERID'=>$USERID
            ]);
            $id_history_absensi = $pdo->lastInsertId();
            $array['lastidhistory'] = $id_history_absensi;
        } else {

            $sql = "SELECT * FROM mabsensi WHERE userid=:USERID AND tanggal=:TANGGAL";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'USERID'=>$USERID,
                'TANGGAL'=>date('Y-m-d'),
            ]);
            if($stmt->rowCount()>0){
                $sql = "UPDATE mabsensi set waktu_logout =:waktu_logout,latitude_logout=:latitude,longitude_logout=:longitude WHERE id=:id_absensi";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'latitude'=>$latitude,
                    'longitude'=>$longitude,
                    'waktu_logout'=>date('Y-m-d H:i:s'),
                    'id_absensi'=>$id_absensi
                ]);
                $array['lastid'] = $id_absensi;
            } else {
                /*$sql = "UPDATE mabsensi set waktu_logout = :waktu , latitude_logout =:latitude, longitude_logout=:longitude WHERE id=:id_absensi";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'waktu' => date('Y-m-d H:i:s'),
                    'latitude' => $latitude,
                    'longitude'=>$longitude,
                    'id_absensi'=>$id_absensi
                ]);*/
                /*throw new Exception('Anda Harus Melakukan Absen Masuk Terlebih Dahulu');*/
                $sql = "INSERT INTO mabsensi (tanggal,waktu_logout,latitude_logout,longitude_logout,userid) values('".date('Y-m-d')."', '".date('Y-m-d H:i:s')."' , :latitude,:longitude, :USERID)"; 
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'latitude'=>$latitude,
                    'longitude'=>$longitude,
                    'USERID'=>$USERID
                ]);
                $id_absensi = $pdo->lastInsertId();
                $array['lastid'] = $id_absensi;
            }


            $sql = "INSERT INTO history_mabsensi (id_absensi,tanggal,waktu,jenis,latitude,longitude,userid) values(:id_absensi,'".date('Y-m-d')."', '".date('Y-m-d H:i:s')."' ,:jenis, :latitude,:longitude, :USERID)"; 
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_absensi'=>$id_absensi,
                'jenis'=>"LOGOUT",
                'latitude'=>$latitude,
                'longitude'=>$longitude,
                'USERID'=>$USERID
            ]);
            $id_history_absensi = $pdo->lastInsertId();
            $array['lastidhistory'] = $id_history_absensi;
        }

        if(isset($namaFoto)){
            foreach ($namaFoto as $key => $value) {
                $sql = "INSERT INTO gambarabsensi (id_absensi,jenis,nama) values (:id_absensi,:jenis,:nama)";
                $stmt = $pdo->prepare($sql);
                $kode = "";
                if($jenis == "LOGIN"){
                    $kode = "IN_" . $id_absensi . "_" . $value;
                } else {
                    $kode = "OUT_" . $id_absensi . "_" . $value;
                }
                $stmt->execute([
                    'id_absensi' => $id_absensi,
                    'jenis' => $jenis,
                    'nama' => $kode
                ]);

                $sql = "INSERT INTO history_gambarabsensi (id_history_absensi,jenis,nama) values(:id_history_absensi,:jenis,:nama)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'id_history_absensi'=>$id_history_absensi,
                    'jenis'=>$jenis,
                    'nama'=> $id_history_absensi . "_" . $value
                ]);
            }
        }

        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch(Exception $e){
        $array['hasil'] = $e->getMessage();
        $pdo->rollBack();
    }

    

    echo json_encode($array);
    break;

    case "MASTER_ABSENSI":
    try {
        $array = array();
        $array['hasil'] = "";
        $USERID = $_POST['USERID'];
        $tampil_hari_absensi = TampilHariAbsensi($pdo);
        $jamMasuk = JamMasuk($pdo);
        $jamPulang = JamPulang($pdo);
        $jamLembur = JamLembur($pdo);
        $array['TAMPIL_HARI_ABSENSI'] = $tampil_hari_absensi;
        $array['JAM_SERVER'] = date('Y-m-d H:i:s');

        $array['DIAGRAM_JUMLAH'] = [
            'MASUK'=>0,
            'LIBUR'=>$tampil_hari_absensi,
            'LEMBUR'=>0
        ];

        $array['DIAGRAM_JUMLAH_KETEPATAN'] = [
            'TEPAT_WAKTU'=>0,
            'TIDAK_TEPAT_WAKTU'=>0,
        ];

        $id = 0;
        $sql = "SELECT * FROM mabsensi WHERE tanggal>=:tanggalmulai AND tanggal<=:tanggalselesai AND userid = :USERID ORDER BY tanggal ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID' => $USERID,
            'tanggalmulai' => date('Y-m-d',strtotime('-'.$tampil_hari_absensi.'days')),
            'tanggalselesai' => date('Y-m-d')
        ]);
        if($stmt->rowCount() > 0){
            while($absensi = $stmt->fetch()){
                $array['MASTER_ABSENSI'][$id] = [
                    'tanggal'=>$absensi['tanggal'],
                    'waktu_login'=>$absensi['waktu_login'],
                    'latitude_login'=>$absensi['latitude_login'],
                    'longitude_login'=>$absensi['longitude_login'],
                    'waktu_logout'=>$absensi['waktu_logout'],
                    'latitude_logout'=>$absensi['latitude_logout'],
                    'longitude_logout'=>$absensi['longitude_logout'],
                ];

                if($absensi['waktu_logout'] != null){
                    if(date('H:i:s',strtotime($absensi['waktu_logout'])) <= date('H:i:s',strtotime($jamLembur))){
                        $array['DIAGRAM_JUMLAH']['MASUK']++;
                    } else if(date('H:i:s',strtotime($absensi['waktu_logout'])) > date('H:i:s',strtotime($jamLembur))){
                        $array['DIAGRAM_JUMLAH']['LEMBUR']++;
                    }
                }

                if($absensi['waktu_logout'] != null){
                    if(date('H:i:s',strtotime($absensi['waktu_login'])) <=  date('H:i:s',strtotime($jamMasuk)) && date('H:i:s',strtotime($absensi['waktu_logout'])) >=  date('H:i:s',strtotime($jamMasuk))){
                        $array['DIAGRAM_JUMLAH_KETEPATAN']['TEPAT_WAKTU']++;
                    } else {
                        $array['DIAGRAM_JUMLAH_KETEPATAN']['TIDAK_TEPAT_WAKTU']++;
                    }
                }
                $id++;
            }
        }

        $array['DIAGRAM_JUMLAH']['LIBUR'] = $array['DIAGRAM_JUMLAH']['LIBUR'] - $array['DIAGRAM_JUMLAH']['MASUK'] - $array['DIAGRAM_JUMLAH']['LEMBUR'];
        $array['hasil'] = "berhasil";
    } catch(Exception $e){
        if($array['hasil'] == ""){
            $array['hasil'] = "error";
        } else {
            $array['hasil'] = $e->getMessage();
        }
    }

    echo json_encode($array);
    break;

    case "LAPORAN_ABSENSI":
    try {
        $array = array();
        $array['hasil'] = "";
        $USERID = $_POST['USERID'];
        $KODESALESMAN = $_POST['KODESALESMAN'];
        $DARITANGGAL = $_POST['DARITANGGAL'];
        $SAMPAITANGGAL = $_POST['SAMPAITANGGAL'];
        $array['hasil_gambar_absensi'] = 'tidakada';

        if(isset($_POST["KODESALESMAN"])){
            $KODESALESMAN = $_POST["KODESALESMAN"];  
        } else {
            throw new Exception("Staff Harus Diisi");
        }


        if(isset($_POST["DARITANGGAL"]) && $_POST['DARITANGGAL'] != ""){
            $DARITANGGAL = $_POST["DARITANGGAL"];
        } else {
            throw new Exception("Tanggal Mulai Harus Diisi");
        }


        if(isset($_POST["SAMPAITANGGAL"]) && $_POST['SAMPAITANGGAL'] != ""){
            $SAMPAITANGGAL = $_POST["SAMPAITANGGAL"];  
        } else {
            throw new Exception("Tanggal Akhir Harus Diisi");
        }


        $sql = "SELECT * FROM mabsensi WHERE userid=:KODESALESMAN AND tanggal >= :TANGGALMULAI AND tanggal <= :TANGGALAKHIR ORDER BY tanggal ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'KODESALESMAN' => $KODESALESMAN,
            'TANGGALMULAI' => date('Y-m-d',strtotime($DARITANGGAL)),
            'TANGGALAKHIR' => date('Y-m-d',strtotime($SAMPAITANGGAL))
        ]);
        if($stmt->rowCount() > 0){
            $id = 0;
            $array['hasil'] = "berhasil";
            $array['ABSENSI'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            $array['hasil'] = "kosong";
        }

        if(isset($array['ABSENSI'])){
            foreach ($array['ABSENSI'] as $key => $value) {
                $sql = "SELECT * FROM gambarabsensi WHERE id_absensi = :id_absensi";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id_absensi'=>$value->id]);
                if($stmt->rowCount() > 0){
                    $array['hasil_gambar_absensi'] = "ada";
                    while($gambar = $stmt->fetch()){
                        if($gambar['jenis'] == "LOGIN"){
                            $array['gambar_login'][$gambar['id_absensi']] = [
                                'nama' => $gambar['nama']
                            ];
                        } else {
                            $array['gambar_logout'][$gambar['id_absensi']] = [
                                'nama' => $gambar['nama']
                            ];
                        }
                    }
                }
            }
        }

    } catch(Exception $e){
        $array['hasil'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "LAPORAN_POINT_KUNJUNGAN":
    try {
        $array = array();
        $KODESALESMAN = $_POST['KODESALESMAN'];
        $USERID = $_POST['USERID'];
        $DARITANGGAL = $_POST['DARITANGGAL'];
        $SAMPAITANGGAL = $_POST['SAMPAITANGGAL'];
        $timeDiff = abs(strtotime($DARITANGGAL) - strtotime($SAMPAITANGGAL));
        $numberDays = $timeDiff/86400;
        $numberDays = intval($numberDays);
        $id = 0;

        $array['hasil_point_rencana_vs_kunjungan'] = "tidakada";

        for($i = 0; $i<$numberDays; $i++){
            $sql = "SELECT mp.NAMA AS NAMAPELANGGAN, tk.KODEPELANGGAN AS KODEPELANGGAN, tk.DATETRANSACTION AS DATETRANSACTION_KUNJUNGAN, tk.NOMORBUKTI AS NOMORBUKTI_KUNJUNGAN FROM tmkunjungan tk INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tk.KODEPELANGGAN) WHERE tk.DATETRANSACTION >'".date('Y-m-d 00:00:00',strtotime('+'.$i.'days',strtotime($DARITANGGAL)))."' AND tk.DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+'.($i+1).'days',strtotime($DARITANGGAL)))."' AND tk.KODESALESMAN =:KODESALESMAN ORDER BY tk.DATETRANSACTION ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'KODESALESMAN'=>$KODESALESMAN
            ]);

            if($stmt->rowCount() > 0){
                $array['hasil_point_rencana_vs_kunjungan'] = "ada";
                while($point = $stmt->fetch()){
                    $sql = "SELECT tr.DATETRANSACTION AS DATETRANSACTION_RENCANA, mp.NAMA as NAMAPELANGGAN, tr.NOMORBUKTI AS NOMORBUKTI_RENCANA, tr.CREATED_AT AS CREATED_AT FROM tmrencanakunjungan tr INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tr.KODEPELANGGAN) WHERE tr.KODEPELANGGAN = :KODEPELANGGAN AND tr.DATETRANSACTION = :DATETRANSACTION AND KODESALESMAN = :KODESALESMAN";
                    $stmt2 = $pdo->prepare($sql);
                    $stmt2->execute([
                        'KODEPELANGGAN'=>$point['KODEPELANGGAN'],
                        'DATETRANSACTION'=>date('Y-m-d',strtotime($point['DATETRANSACTION_KUNJUNGAN'])),
                        'KODESALESMAN'=>$KODESALESMAN
                    ]);

                    if($stmt2->rowCount() > 0){
                        while($point2 = $stmt2->fetch()){
                            if(date('Y-m-d',strtotime($point2['CREATED_AT'])) < date('Y-m-d',strtotime($point2['DATETRANSACTION_RENCANA']))){
                                $array['laporan_point_kunjungan'][$id] = [
                                    'KODEPELANGGAN'=>$point['KODEPELANGGAN'],
                                    'NAMAPELANGGAN'=>$point['NAMAPELANGGAN'],
                                    'DATETRANSACTION_KUNJUNGAN'=>$point['DATETRANSACTION_KUNJUNGAN'],
                                    'DATETRANSACTION_RENCANA'=>$point2['DATETRANSACTION_RENCANA'],
                                    'NOMORBUKTI_RENCANA'=>$point2['NOMORBUKTI_RENCANA'],
                                    'CREATED_AT'=>$point2['CREATED_AT'],
                                    'NOMORBUKTI_KUNJUNGAN'=>$point['NOMORBUKTI_KUNJUNGAN'],
                                    'POINT'=>2,
                                    'STATUS'=>'sesuai_rencana_sebelumnya'
                                ];
                            } else {
                                $array['laporan_point_kunjungan'][$id] = [
                                    'KODEPELANGGAN'=>$point['KODEPELANGGAN'],
                                    'NAMAPELANGGAN'=>$point['NAMAPELANGGAN'],
                                    'DATETRANSACTION_KUNJUNGAN'=>$point['DATETRANSACTION_KUNJUNGAN'],
                                    'DATETRANSACTION_RENCANA'=>$point2['DATETRANSACTION_RENCANA'],
                                    'NOMORBUKTI_RENCANA'=>$point2['NOMORBUKTI_RENCANA'],
                                    'CREATED_AT'=>$point2['CREATED_AT'],
                                    'NOMORBUKTI_KUNJUNGAN'=>$point['NOMORBUKTI_KUNJUNGAN'],
                                    'POINT'=>1,
                                    'STATUS'=>'rencana_dibuat_hari_itu'
                                ];
                            }

                        }
                    } else {
                        $array['laporan_point_kunjungan'][$id] = [
                            'KODEPELANGGAN'=>$point['KODEPELANGGAN'],
                            'NAMAPELANGGAN'=>$point['NAMAPELANGGAN'],
                            'DATETRANSACTION_KUNJUNGAN'=>$point['DATETRANSACTION_KUNJUNGAN'],
                            'DATETRANSACTION_RENCANA'=>null,
                            'NOMORBUKTI_RENCANA'=>null,
                            'CREATED_AT'=>null,
                            'NOMORBUKTI_KUNJUNGAN'=>$point['NOMORBUKTI_KUNJUNGAN'],
                            'POINT'=>1,
                            'STATUS'=>'rencana_dibuat_hari_itu'
                        ];
                    }
                    $id++;
                }
            }
        }

    } catch (Exception $e){
        $array['hasil'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_INBOX":
    try {
        $USERID = $_POST['USERID'];
        $KODEJABATAN = $_POST['KODEJABATAN'];
        $KODECABANG = $_POST['KODECABANG'];
        $array = array();
        $sql = "SELECT * FROM inbox inb LEFT JOIN mkaryawan mk on(mk.KODEKARYAWAN = inb.pengirim) WHERE inb.penerima = :USERID AND isnull(deleted_at) ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID]);
        if($stmt -> rowCount() > 0){
            $array['hasil'] = "ada";
            $array['inbox'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            $array['hasil'] = "tidakada";
        }
    } catch(Exception $e){
        if(!$array['hasil'])
            $array['hasil'] = $e->getMessage();
    }
    echo json_encode($array);
    break;

    case "OPEN_INBOX":
    try {
        $pdo->beginTransaction();
        $id = $_POST['id'];
        $USERID = $_POST['USERID'];
        $KODEJABATAN = $_POST['KODEJABATAN'];
        $KODECABANG = $_POST['KODECABANG'];
        $array = array();
        $sql = "UPDATE inbox set status =:status, read_at=:read_at WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'status'=>'sudah_dibaca',
            'read_at'=>date('Y-m-d H:i:s'),
            'id'=>$id
        ]);
        $array['hasil'] = "berhasil";
        
        $pdo->commit();
    } catch(Exception $e){
        $pdo->rollBack();
        $array['hasil'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "DETAIL_INBOX":
    try {
        $array = array();
        $USERID = $_POST['USERID'];
        $id = $_POST['id'];
        $KODECABANG = $_POST['KODECABANG'];
        $KODEJABATAN = $_POST['KODEJABATAN'];
        $sql = "SELECT * FROM inbox inb LEFT JOIN mkaryawan mk on(mk.KODEKARYAWAN = inb.pengirim) WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id'=>$id]);
        if($stmt->rowCount()>0){
            $array['hasil'] = "ada";
            $array['inbox'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            $array['hasil'] = "tidakada";
        }
    } catch(Exception $e){
        $array['hasil'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "POPUP_INBOX_KODEJABATAN_KODEKARYAWAN":
    try {
        $array = array();
        $USERID = $_POST['USERID'];
        $KODECABANG = $_POST['KODECABANG'];
        $KODEJABATAN = $_POST['KODEJABATAN'];
        $sql = "SELECT * FROM mjabatan";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $array['hasil_jabatan'] = "ada";
            $array['JABATAN'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            $array['hasil_jabatan'] = "tidakada";
        }

        $sql = "SELECT * FROM pemakai p INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE p.KODECABANG = :KODECABANG";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['KODECABANG'=>$KODECABANG]);
        if($stmt->rowCount() > 0){
            $array['hasil_karyawan'] = "ada";
            $array['KARYAWAN'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            $array['hasil_karyawan'] = "tidakada";
        }
    } catch (Exception $e){
        $array['hasil'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "KIRIM_MESSAGE":
    try {
        $pdo->beginTransaction();
        $array = array();
        $USERID = $_POST['USERID'];
        $KODEJABATAN = $_POST['KODEJABATAN'];
        $KODECABANG = $_POST['KODECABANG'];
        $tipe = $_POST['tipe'];
        $arrKode = $_POST['arrKode'];
        $isi = $_POST['isi'];
        $judul = $_POST['judul'];
        $jenis = 'notification';

        if(!isset($judul)){
            throw new Exception("Judul Harus Di Isi");
        }

        if(!isset($isi)){
            throw new Exception("Isi Message Harus Di Isi");
        }

        if(!isset($tipe)){
            throw new Exception("Tidak Ada Tipe");
        }

        if(!isset($arrKode)){
            throw new Exception("Anda Harus Memilih Jabatan / Karyawan Terlebih Dahulu");
        }

        $id = 0;
        if($tipe == "KODEJABATAN"){
            foreach ($arrKode as $key => $value) {
                $sql = "SELECT * FROM pemakai WHERE KODEJABATAN =:KODEJABATAN";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['KODEJABATAN'=>$value]);
                if($stmt->rowCount()>0){
                    while($pemakai = $stmt->fetch()){
                        if($pemakai['GCM_ID'] != null || $pemakai['GCM_ID'] !=""){
                            $array['KARYAWAN'][$id] = $pemakai['GCM_ID'];
                            $id++;
                        }

                        $sql2 = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([
                            'pengirim'=>$USERID,
                            'penerima'=>$pemakai['USERID'],
                            'title'=>$judul,
                            'jenis'=>$jenis,
                            'isi'=>$isi
                        ]);
                    }
                }
            }
        } else {
            foreach ($arrKode as $key => $value) {
                $sql = "SELECT * FROM pemakai WHERE USERID=:VALUE";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['VALUE'=>$value]);
                if($stmt->rowCount()>0){
                    $pemakai = $stmt->fetch();
                    if($pemakai['GCM_ID'] != null || $pemakai['GCM_ID'] !=""){
                        $array['KARYAWAN'][$id] = $pemakai['GCM_ID'];
                        $id++;
                    }
                }

                $sql = "INSERT INTO inbox (pengirim,penerima,title,jenis,isi) values(:pengirim,:penerima,:title,:jenis,:isi)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'pengirim'=>$USERID,
                    'penerima'=>$value,
                    'title'=>$judul,
                    'jenis'=>$jenis,
                    'isi'=>$isi
                ]);
            }
        }
        /*Kirim Push Notif*/
        if(isset($array['KARYAWAN'])){
            $content = array(
                "en" => $isi
            );  

            $headings = array(
                "en" =>$judul
            );

            $fields = array(
                'app_id' => "ec4cf440-afa6-4896-8350-d4e493082179",
                'include_player_ids' => $array['KARYAWAN'],
                'data' => array("foo" => "bar"),
                'headings' => $headings,
                'contents' => $content
            );
            $fields = json_encode($fields);

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
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch (Exception $e){
        $pdo->rollBack();
        $array['hasil'] = $e->getMessage();
    }
    echo json_encode($array);
    break;

    case "INPUT_RENCANA_ADMIN":

    try {
        $pdo->beginTransaction();
        $array = array();
        $counter = 0;
        if(isset($_POST['DATETRANSACTION'])){
            $DATETRANSACTION = $_POST['DATETRANSACTION'];
        } else { 
            throw new Exception("Tanggal Harus Di Isi"); 
        }

        if(isset($_POST['KODEPELANGGAN'])){
            $KODEPELANGGAN = $_POST['KODEPELANGGAN'];
        } else { 
            throw new Exception("Kode Pelanggan Harus Di Isi"); 
        }


        $USERID = $_POST['USERID'];
        $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION = :DATETRANSACTION AND NOMORBUKTI LIKE 'RT%' ORDER BY NOMORBUKTI DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION))]);
        
        if($stmt->rowCount() > 0){
            $counter = $stmt->fetch();
            $counter = substr($counter['NOMORBUKTI'], -3);
            $counter++;
            
        } else {
            $counter = 1;
        }

        foreach ($KODEPELANGGAN as $key => $value) {

            $sql = "SELECT * FROM tmrencanakunjungan WHERE DATETRANSACTION = :DATETRANSACTION AND NOMORBUKTI LIKE 'RT%' AND KODEPELANGGAN =:KODEPELANGGAN";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                'KODEPELANGGAN'=>$value
            ]);
            if($stmt->rowCount() > 0){
                throw new Exception("PELANGGAN " . $value . " SUDAH ADA RENCANA TAGIHAN PADA TANGGAL TERSEBUT");
            }

            $NOMORBUKTI = "RT".date('ymd',strtotime($DATETRANSACTION));
            if(strlen($counter)==1){
                $NOMORBUKTI .= "00".$counter;
            } else if(strlen($counter)==2){
                $NOMORBUKTI .= "0".$counter;
            } else {
                $NOMORBUKTI .= $counter;
            }
            $sql = "INSERT INTO tmrencanakunjungan (NOMORBUKTI,DATETRANSACTION,KODESALESMAN,KODEPELANGGAN) values (:NOMORBUKTI,:DATETRANSACTION, :KODESALESMAN, :KODEPELANGGAN)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'NOMORBUKTI'=>$NOMORBUKTI,
                'DATETRANSACTION'=>date('Y-m-d',strtotime($DATETRANSACTION)),
                'KODESALESMAN'=>$USERID,
                'KODEPELANGGAN'=>$value
            ]);
            $counter++;
        }
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch (Exception $e){
        $array['hasil'] = $e->getMessage();
        $pdo->rollBack();
        
    }
    echo json_encode($array);
    break;

    case "TAMPIL_MASTER_RENCANA_TAGIHAN":
    $USERID = $_POST['USERID'];
    $KODECABANG = $_POST['KODECABANG'];
    $sql = "SELECT count(NOMORBUKTI) as JUMLAHOUTLET, DATETRANSACTION as DATETRANSACTION FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE KODESALESMAN =:USERID AND mp.KODECABANG =:KODECABANG AND NOMORBUKTI LIKE 'RT%' GROUP BY DATETRANSACTION ORDER BY DATETRANSACTION DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID,'KODECABANG'=>$KODECABANG]);
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
        $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
        $id++;
        while($row = $stmt->fetch()){ 
            $array['JUMLAHOUTLET'][$id] = $row['JUMLAHOUTLET'];
            $array['DATETRANSACTION'][$id] = $row['DATETRANSACTION'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "TAMPIL_POSTING_HAPUS_TAGIHAN":
    $DATETRANSACTION = $_POST['DATETRANSACTION'];
    $USERID = $_POST['USERID'];
    $KODEJABATAN = $_POST['KODEJABATAN'];
    $KODECABANG = $_POST['KODECABANG'];

    if(isset($_POST['parameter_rencana'])){
        $parameter_rencana = $_POST['parameter_rencana'];
    } else { $parameter_rencana = null; }

    if(isset($_POST['TANGGALDARI'])){
        $TANGGALDARI = $_POST['TANGGALDARI'];
    } else { $TANGGALDARI = null; }

    if(isset($_POST['TANGGALSAMPAI'])){
        $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
    } else { $TANGGALSAMPAI = null; }

    if(isset($_POST['STAFFDARI'])){
        $STAFFDARI = $_POST['STAFFDARI'];
    } else { $STAFFDARI = null; }

    if(isset($_POST['STAFFSAMPAI'])){
        $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
    } else { $STAFFSAMPAI = null; }
    

    $sql = "";

    $sql = "SELECT p.NAMA as NAMA, k.NOMORBUKTI as NOMORBUKTI FROM tmrencanakunjungan k INNER JOIN mpelanggan p on(k.KODEPELANGGAN = p.KODEPELANGGAN) WHERE DATETRANSACTION >='".date('Y-m-d 00:00:00',strtotime($DATETRANSACTION))."' AND DATETRANSACTION<'".date('Y-m-d 00:00:00',strtotime('+1days', strtotime($DATETRANSACTION)))."' AND k.STATUS ='OPEN' AND p.KODECABANG = '".$KODECABANG."' AND ";
    if($parameter_rencana == "ada"){
        if($STAFFDARI){
            $sql.= " k.KODESALESMAN >='".$STAFFDARI."' AND ";
        }

        if($STAFFSAMPAI){
            $sql.= " k.KODESALESMAN <='".$STAFFSAMPAI."' AND ";
        }



    } else {
        $sql.= " k.KODESALESMAN ='".$USERID."'";
    }

    $tampungSql = substr($sql,-5);
    if($tampungSql == " AND " || $tampungSql == "WHERE"){
        $sql=substr($sql,0,-5);
    }


    $sql.= " ORDER BY NOMORBUKTI";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array = array();
    $id = 0;
    if($row = $stmt->fetch()){
        $array['hasil'] = "ada";
        $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
        $array['NAMA'][$id] = $row['NAMA'];
        $id++;
        while($row = $stmt->fetch()){
            $array['NOMORBUKTI'][$id] = $row['NOMORBUKTI'];
            $array['NAMA'][$id] = $row['NAMA'];
            $id++;
        }
    } else {
        $array['hasil'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "POSTING_TERPILIH_TAGIHAN":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];

    foreach ($postingHapusValue as $key => $value) {
        $sql = "UPDATE tmrencanakunjungan set STATUS ='CLOSE',USERID =:USERID WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID,
            'NOMORBUKTI'=>$value
        ]);
    }

    break;

    case "HAPUS_TERPILIH_TAGIHAN":
    $USERID = $_POST['USERID'];
    $postingHapusValue = $_POST['postingHapusValue'];

    $id = 0;
    $arrayFotoDihapus = array();
    foreach ($postingHapusValue as $key => $value) {
        $sql = "DELETE FROM tmrencanakunjungan WHERE NOMORBUKTI=:NOMORBUKTI";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['NOMORBUKTI'=>$value]);
    }
    break;


    case "LAPORAN_RENCANA_TAGIHAN":
    try {
        $USERID = $_POST['USERID'];
        $KODECABANG = $_POST['KODECABANG'];

        if(isset($_POST['TANGGALDARI'])){
            $TANGGALDARI = $_POST['TANGGALDARI'];
        } else { $TANGGALDARI = null; }

        if(isset($_POST['TANGGALSAMPAI'])){
            $TANGGALSAMPAI = $_POST['TANGGALSAMPAI'];
        } else { $TANGGALSAMPAI = null; }

        if(isset($_POST['STAFFDARI'])){
            $STAFFDARI = $_POST['STAFFDARI'];
        } else { $STAFFDARI = null; }

        if(isset($_POST['STAFFSAMPAI'])){
            $STAFFSAMPAI = $_POST['STAFFSAMPAI'];
        } else { $STAFFSAMPAI = null; }

        $sql = "SELECT tm.DATETRANSACTION AS DATETRANSACTION, tm.NOMORBUKTI AS NOMORBUKTI, tm.KODESALESMAN AS KODESALESMAN, mp.NAMA AS NAMAPELANGGAN, mp.KOTA AS KOTA FROM tmrencanakunjungan tm INNER JOIN mpelanggan mp on(mp.KODEPELANGGAN = tm.KODEPELANGGAN) WHERE tm.NOMORBUKTI LIKE 'RT%' AND ";
        if($STAFFDARI){
            $sql .= " tm.KODESALESMAN >= '" . $STAFFDARI ."' AND ";
        }

        if($STAFFSAMPAI){
            $sql .= " tm.KODESALESMAN <= '" . $STAFFSAMPAI ."' AND ";
        }

        if($TANGGALDARI){
            $sql .= " tm.DATETRANSACTION >= '" . date('Y-m-d',strtotime($TANGGALDARI)) ."' AND ";
        }

        if($TANGGALSAMPAI){
            $sql .= " tm.DATETRANSACTION <= '" . date('Y-m-d',strtotime($TANGGALSAMPAI)) ."' AND ";
        }

        $tampungSql = substr($sql,-5);
        if($tampungSql == " AND " || $tampungSql == "WHERE"){
            $sql=substr($sql,0,-5);
        }
        $sql .= " ORDER BY tm.DATETRANSACTION ASC, tm.KODESALESMAN ASC";
        $array = array();
        $id = 0;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $array['hasil'] = "ada";
            while($rencana = $stmt->fetch()){
                $array['RENCANA'][$id] = [
                    'DATETRANSACTION'=>$rencana['DATETRANSACTION'],
                    'NOMORBUKTI'=>$rencana['NOMORBUKTI'],
                    'KODESALESMAN'=>$rencana['KODESALESMAN'],
                    'NAMAPELANGGAN'=>$rencana['NAMAPELANGGAN'],
                    'KOTA'=>$rencana['KOTA'],
                ];

                $array['KODESALESMAN'][$id] = [
                    'KODESALESMAN'=>$rencana['KODESALESMAN'],
                    'DATETRANSACTION'=>$rencana['DATETRANSACTION'],
                    'JUMLAH'=>1,
                ];
                $id++;
            }
        } else {
            $array['hasil'] = "tidakada";
        }
    } catch(Exception $e){
        if(!$array['hasil']){
            $array['hasil'] = "error";
        }
    }

    echo json_encode($array);
    break;

    case "HAPUS_INBOX":
    try {
        $array = array();
        $pdo->beginTransaction();
        if(isset($_POST['CheckedInbox'])){
            $CheckedInbox = $_POST['CheckedInbox'];
        } else {
            throw new Exception("Pilih Inbox yang ingin dihapus terlebih dahulu");
        }
        foreach ($CheckedInbox as $key => $value) {
            $sql = "UPDATE inbox set deleted_at=:deleted_at WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'deleted_at'=>date('Y-m-d H:i:s'),
                'id'=>$value
            ]);
        }
        $array['hasil'] = "berhasil";

        $pdo->commit();
    } catch (Exception $e){
        $pdo->rollBack();
        $array['hasil'] = $e->getMessage();
    }
    echo json_encode($array);
    break;

    case "LOGOUT":
    try {
        if(isset($_POST['USERID'])){
            $USERID = $_POST['USERID'];
        } else {
            throw new Exception("Tidak Login");
        }
        $sql = "UPDATE pemakai set GCM_ID = NULL WHERE USERID = :USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID]);
    } catch (Exception $e){
        echo $e->getMessage();
    }
    break;

    case "MASTER_UNIT_KENDARAAN":
    $array = array();
    $sql = "SELECT * FROM munitkendaraan mu INNER JOIN mjenis_kendaraan mk on(mk.id = mu.id_jenis_kendaraan)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $array['hasil'] = "ada";
        $array['munitkendaraan'] = $stmt->fetchAll(PDO::FETCH_CLASS);
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "GET_JENIS_KENDARAAN":
    $array = array();
    $sql = "SELECT * from mjenis_kendaraan";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $array['hasil'] = "ada";
        $array['jenis_kendaraan'] = $stmt->fetchAll(PDO::FETCH_CLASS);
    } else {
        $array['hasil'] = "tidakada";
    }
    echo json_encode($array);
    break;

    case "SIMPAN_UNIT_KENDARAAN":
    try {
        $pdo->beginTransaction();
        $array = array();

        if(!$_POST['id_jenis_kendaraan']){
            throw new Exception("Jenis Kendaraan Harus Di Isi");
        } else {
            $id_jenis_kendaraan = $_POST['id_jenis_kendaraan'];
        } 

        if(!$_POST['nomor_plat']){
            throw new Exception("Nomor Plat Harus Di Isi");
        } else {

            $nomor_plat = $_POST['nomor_plat'];
        } 

        if(!$_POST['kodesalesman']){
            throw new Exception("Kode Salesman Harus Di Isi");
        } else {
            $kodesalesman = $_POST['kodesalesman'];
        }

        $sql = "SELECT * FROM munitkendaraan WHERE nomor_plat=:nomor_plat";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nomor_plat'=>$nomor_plat]);
        if($stmt->rowCount() > 0){
            throw new Exception("Nomor Plat ".$nomor_plat." Sudah Terdaftar");
        }

        $sql = "INSERT INTO munitkendaraan (nomor_plat,id_jenis_kendaraan,kodesalesman) values(:nomor_plat,:id_jenis_kendaraan,:kodesalesman)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nomor_plat' => $nomor_plat,
            'id_jenis_kendaraan' => $id_jenis_kendaraan,
            'kodesalesman' => $kodesalesman
        ]);
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch (Exception $e){
        $pdo->rollBack();
        $array['hasil'] = "tidak";
        $array['message'] = $e->getMessage();

    }
    echo json_encode($array);
    break;

    case "EDIT_UNIT_KENDARAAN":
    try {
        $pdo->beginTransaction();
        $array = array();

        if(!$_POST['id_jenis_kendaraan']){
            throw new Exception("Jenis Kendaraan Harus Di Isi");
        } else {
            $id_jenis_kendaraan = $_POST['id_jenis_kendaraan'];
        } 

        if(!$_POST['nomor_plat']){
            throw new Exception("Nomor Plat Harus Di Isi");
        } else {

            $nomor_plat = $_POST['nomor_plat'];
        } 

        if(!$_POST['kodesalesman']){
            throw new Exception("Kode Salesman Harus Di Isi");
        } else {
            $kodesalesman = $_POST['kodesalesman'];
        }

        $nomor_plat_lama = $_POST['nomor_plat_lama'];
        if($nomor_plat != $nomor_plat_lama){    
            $sql = "SELECT * FROM munitkendaraan WHERE nomor_plat=:nomor_plat";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nomor_plat'=>$nomor_plat]);
            if($stmt->rowCount() > 0){
                throw new Exception("Nomor Plat ".$nomor_plat." Sudah Terdaftar");
            }
        }

        $sql = "UPDATE munitkendaraan set nomor_plat=:nomor_plat,id_jenis_kendaraan=:id_jenis_kendaraan, kodesalesman=:kodesalesman WHERE nomor_plat=:nomor_plat_where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nomor_plat' => $nomor_plat,
            'id_jenis_kendaraan' => $id_jenis_kendaraan,
            'kodesalesman' => $kodesalesman,
            'nomor_plat_where'=>$nomor_plat_lama
        ]);
        $array['hasil'] = "berhasil";
        $pdo->commit();
    } catch (Exception $e){
        $pdo->rollBack();
        $array['hasil'] = "tidak";
        $array['message'] = $e->getMessage();

    }
    echo json_encode($array);
    break;

    case "GET_PROPERTY_PENGELUARAN":
    $array = array();
    $kodesalesman = $_POST['kodesalesman'];
    $sql = "SELECT * from munitkendaraan WHERE kodesalesman= :kodesalesman";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kodesalesman'=>$kodesalesman]);
    if($stmt->rowCount() > 0){
        $array['hasil_unit'] = "ada";
        $array['unit'] = $stmt->fetchAll(PDO::FETCH_CLASS);
    } else {
        $array['hasil_unit'] = "tidakada";
    }

    $sql = "SELECT * from mjenis_pengeluaran";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $array['hasil_jenis_pengeluaran'] = "ada";
        $array['jenis_pengeluaran'] = $stmt->fetchAll(PDO::FETCH_CLASS);
    } else {
        $array['hasil_jenis_pengeluaran'] = "tidakada";
    }

    echo json_encode($array);
    break;

    case "SIMPAN_PENGELUARAN":
    $array = array();
    $params = $_POST;
    $pdo->beginTransaction();
    try {
        if(!$params['nomor_plat_unit']){
            throw new Exception("Pilih Unit Terlebih Dahulu");
        } else if(!$params['total_pengeluaran']){
            throw new Exception("Total Pengeluaran Harus Di Isi");
        } else if(!$params['latitude']){
            throw new Exception("Silahkan Hubungi Admin !");
        } else if(!$params['longitude']){
            throw new Exception("Silahkan Hubungi Admin !");
        } else if(!$params['kodesalesman']){
            throw new Exception("Silahkan Hubungi Admin !");
        } else if(!isset($params['foto_nomor_plat'])){
            throw new Exception("Anda Harus Memfoto Nomor Plat Terlebih Dahulu");
        } else if(!isset($params['nama_foto_biaya_pengeluaran'])){
            throw new Exception("Anda Harus Memfoto Biaya Pengeluaran Terlebih Dahulu");
        }

        if($params['jenis_pengeluaran'] == "1"){
            if(!$params['odometer']){
                throw new Exception("Odometer Harus Di Isi");
            } else if(!$params['lokasi_spbu']){
                throw new Exception("Lokasi SPBU Harus Di Isi");   
            } else if(!isset($params['foto_odometer'])){
                throw new Exception("Anda Harus Memfoto Odometer Terlebih Dahulu");
            } else if(!$params['jumlah_liter']){
                throw new Exception("Jumlah Liter Harus Di Isi");
            } else if($params['jumlah_liter'] == ""){
                throw new Exception("Isi Jumlah Liter Dengan Nominal Angka Saja");
            } else if(ctype_alpha($params['jumlah_liter'])){
                throw new Exception("Isi Jumlah Liter Dengan Nominal Angka Saja");
            }
        }

        if(ctype_alpha($params['total_pengeluaran'])){
            throw new Exception("Isi Total Pengeluaran dengan Nominal Angka saja");
        }

        $sql = "SELECT * FROM mpengeluaran WHERE kodesalesman =:kodesalesman AND odometer >= :odometer";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'kodesalesman' => $params['kodesalesman'],
            'odometer' => $params['odometer']
        ]);
        $row = $stmt->fetch();
        if($stmt->rowCount() > 0){
            throw new Exception("Batas Akhir Odometer Anda ".$row['odometer']);
        }

        $sql = "SELECT * FROM mpengeluaran WHERE tanggal = :tanggal  ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'tanggal'=>date('Y-m-d')
        ]);
        $nomorbukti = "NP".date('ymd');;
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            $counter = substr($row['nomorbukti'], -3);
            $counter++;
            if(strlen($counter) == 1){
                $nomorbukti .= "00" .$counter;

            } else if (strlen($counter) == 2){
                $nomorbukti .= "0" .$counter;
            } else {
                $nomorbukti .= $counter;
            }
        } else {
            $nomorbukti .= "001";
        }

        

        

        $foto_odometer = null;
        if($params['odometer']){
            $foto_odometer = $nomorbukti . "_" ."1";
        }

        $foto_nomor_plat = $nomorbukti ."_"."1";
        $array['nama_foto_odometer'] = $foto_odometer;
        $array['nama_foto_nomor_plat'] = $foto_nomor_plat;

        $sql = "INSERT INTO mpengeluaran (nomorbukti,tanggal,nomor_plat_unit,odometer,foto_odometer,foto_nomor_plat, total_pengeluaran, lokasi_spbu, latitude, longitude, jenis_pengeluaran, kodesalesman, userid,jumlah_liter) values(:nomorbukti,:tanggal,:nomor_plat_unit,:odometer,:foto_odometer,:foto_nomor_plat,:total_pengeluaran,:lokasi_spbu,:latitude,:longitude,:jenis_pengeluaran,:kodesalesman,:userid,:jumlah_liter)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nomorbukti'=>$nomorbukti,
            'tanggal'=>date('Y-m-d'),
            'nomor_plat_unit'=>$params['nomor_plat_unit'],
            'odometer'=>$params['odometer'],
            'total_pengeluaran'=>$params['total_pengeluaran'],
            'lokasi_spbu'=>$params['lokasi_spbu'],
            'jumlah_liter'=>$params['jumlah_liter'],
            'latitude'=>$params['latitude'],
            'longitude'=>$params['longitude'],
            'jenis_pengeluaran'=>$params['jenis_pengeluaran'],
            'kodesalesman'=>$params['kodesalesman'],
            'userid'=>$params['kodesalesman'],
            'foto_odometer'=>$foto_odometer,
            'foto_nomor_plat'=>$foto_nomor_plat
        ]);

        $array['foto_nota'] = "tidakada";
        $sql = "INSERT INTO gambar_nota_pengeluaran (nomorbukti_pengeluaran,url) values(:nomorbukti,:url)";
        $stmt = $pdo->prepare($sql);
        if(isset($params['nama_foto_biaya_pengeluaran'])){;
            foreach ($params['nama_foto_biaya_pengeluaran'] as $key => $value) {
                $foto_nota_pengeluaran = $nomorbukti . "_" . $value;
                $array['nama_foto_nota'][$key] = $foto_nota_pengeluaran;
                $stmt->execute([
                    'nomorbukti'=>$nomorbukti,
                    'url' => $foto_nota_pengeluaran
                ]);
            }
            $array['foto_nota'] = "ada";
        }


        $array['hasil'] = "berhasil";
        $array['message'] = "Berhasil Simpan Pengeluaran";
        $pdo->commit();
    } catch (Exception $e){
        $pdo->rollBack();
        $array['hasil'] = "gagal";
        $array['message'] = $e->getMessage();
    }

    echo json_encode($array);
    break;

    case "MASTER_PENGELUARAN":
    $array = array();
    $USERID = $_POST['USERID'];

    $jabatan = "";
    $sql = "SELECT * FROM pemakai WHERE USERID=:USERID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['USERID'=>$USERID]);
    $row = $stmt->fetch();
    $jabatan = $row['KODEJABATAN'];

    if($jabatan == "OWNER" || $jabatan == "ADMIN" || $jabatan == "FINANCE"){
        $sql = "SELECT * FROM mpengeluaran ORDER BY tanggal desc, kodesalesman asc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $array['pengeluaran'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        }
    } else {
        $sql = "SELECT * FROM mpengeluaran WHERE kodesalesman=:kodesalesman ORDER BY tanggal desc, kodesalesman asc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['kodesalesman'=>$USERID]);
        if($stmt->rowCount() > 0){
            $array['pengeluaran'] = $stmt->fetchAll(PDO::FETCH_CLASS);
        }
    }
    echo json_encode($array);
    break;

    case "DETAIL_PENGELUARAN":
    $array = array();
    $USERID = $_POST['USERID'];
    $nomorbukti = $_POST['nomorbukti'];
    $sql = "SELECT mp.*, mj.nama AS nama_pengeluaran FROM mpengeluaran mp INNER JOIN mjenis_pengeluaran mj on(mj.id = mp.jenis_pengeluaran) WHERE nomorbukti=:nomorbukti";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nomorbukti'=>$nomorbukti
    ]);
    if($stmt->rowCount()>0){
        $array['hasil'] = "ada";
        $array['pengeluaran'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $array['hasil'] = "tidakada";
        $array['message'] = "Tidak Ada Detail Pengeluaran";
    }

    $sql = "SELECT * FROM gambar_nota_pengeluaran WHERE nomorbukti_pengeluaran = :nomorbukti";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nomorbukti' => $nomorbukti
    ]);
    if($stmt->rowCount() > 0){
        $array['foto_nota_pengeluaran'] = $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    echo json_encode($array);
    break;

    case "DETAIL_UNIT_KENDARAAN":
    $array = array();
    $nomor_plat = $_POST['nomor_plat'];
    $sql = "SELECT *,mk.NAMA AS nama_karyawan, mjk.nama AS nama_jenis_kendaraan FROM munitkendaraan mu INNER JOIN mjenis_kendaraan mjk on(mjk.id = mu.id_jenis_kendaraan) INNER JOIN pemakai p on (p.USERID = mu.kodesalesman) INNER JOIN mkaryawan mk on(mk.KODEKARYAWAN = p.USERID) WHERE mu.nomor_plat = :nomor_plat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nomor_plat'=>$nomor_plat]);
    if($stmt->rowCount() > 0){
        $array['hasil'] = "ada";
        $array['detail'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $array['hasil'] = "tidakada";
        $array['message'] = "Tidak Ada Detail Pengeluaran";
    }

    $sql = "SELECT * FROM mjenis_kendaraan";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $array['unit'] = $stmt->fetchAll(PDO::FETCH_CLASS);

    $sql = "SELECT * from mpengeluaran WHERE nomor_plat_unit = :nomor_plat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nomor_plat'=>$nomor_plat]);
    if($stmt->rowCount() > 0){
        $array['edit'] = "tidak_bisa";
    }

    echo json_encode($array);
    break;

    case "DETAIL_REALISASI_KUNJUNGAN_SEMUA":
    $array = array();
    $TANGGAL = $_POST['TANGGAL'];

    echo json_encode($array);
    break;
}

function TampilHariAbsensi($pdo){
    $sql = "SELECT * FROM setting where nama = 'tampil_hari_absensi'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $value = $stmt->fetch();

    return $value['value'];
}

function JamMasuk($pdo){
    $sql = "SELECT * FROM setting where nama = 'jam_masuk'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $value = $stmt->fetch();

    return $value['value'];
}

function JamPulang($pdo){
    $sql = "SELECT * FROM setting where nama = 'jam_pulang'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $value = $stmt->fetch();

    return $value['value'];
}

function JamLembur($pdo){
    $sql = "SELECT * FROM setting where nama = 'jam_lembur'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $value = $stmt->fetch();

    return $value['value'];
}


function IndexHari($waktu){
    $datetime = new DateTime($waktu);
    return $datetime->format('w');
}



?>