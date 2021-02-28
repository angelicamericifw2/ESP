<?php 

$servername ="localhost";
$username ="sumberre_bond2";
$password ="creativity_unlimited2020";
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



$json = file_get_contents('php://input');
$data = json_decode($json);

$act = $data->case;


switch($act) {
    case "HISTORY_PERJALANAN":
    $USERID = $data->USERID;
    if($USERID === "null"){
    
    } else {
        $LATITUDE = $data->location->coords->latitude;
        $LONGITUDE = $data->location->coords->longitude;
        $sql = "INSERT INTO history_perjalanan (USERID,LATITUDE,LONGITUDE,WAKTU) values(:USERID, :LATITUDE, :LONGITUDE ,'".date('Y-m-d H:i:s')."')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'USERID'=>$USERID,
            'LATITUDE'=>$LATITUDE,
            'LONGITUDE'=>$LONGITUDE
        ]);

        $sql = "SELECT * FROM live_history_perjalanan WHERE USERID = :USERID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['USERID'=>$USERID]);
        if($stmt->rowCount()>0){
            $sql = "UPDATE live_history_perjalanan set LATITUDE=:LATITUDE, LONGITUDE=:LONGITUDE, WAKTU=:WAKTU WHERE USERID =:USERID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'LATITUDE'=>$LATITUDE,
                'LONGITUDE'=>$LONGITUDE,
                'WAKTU'=>date('Y-m-d H:i:s'),
                'USERID'=>$USERID
            ]);
        } else {
            $sql = "INSERT INTO live_history_perjalanan (USERID,LATITUDE,LONGITUDE,WAKTU) values (:USERID, :LATITUDE, :LONGITUDE,NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'LATITUDE'=>$LATITUDE,
                'LONGITUDE'=>$LONGITUDE,
                'USERID'=>$USERID
            ]);
        }
    }
    
    break;
}

?>

