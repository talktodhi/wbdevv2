<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<input type="file" name="file">
<input type="submit" name="btn_submit" value="Upload File" />
</form>
<?php
error_reporting(0);

function assoc_getcsv($csv_path) {
    $f = array();
    function parse_csv_assoc($str,&$f){
        if (empty($f)) { $f = str_getcsv($str); }
        return array_combine($f, str_getcsv($str));         
    }
    return array_values(array_slice(array_map('parse_csv_assoc', file($csv_path), $f),1));
} 

function change_key( $array, $old_key, $new_key ) {

    if( ! array_key_exists( $old_key, $array ) )
        return $array;

    $keys = array_keys( $array );
    $keys[ array_search( $old_key, $keys ) ] = $new_key;

    return array_combine( $keys, $array );
}

/*
//if(isset($_POST['btn_submit'])){
$fh = fopen($_FILES['file']['tmp_name'], 'r+');
$lines = array();
while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
	$lines[] = $row;
}
*/
function prx($arr){
	echo "<pre>";
	print_r($arr);
	exit;
}
if(isset($_FILES['file']['tmp_name'])){
	
	$lines = assoc_getcsv($_FILES['file']['tmp_name']);
	
	
	
	
	$fileTypeTemp = explode('.',$_FILES['file']['name']);
	$fileNameTemp = explode('_',$fileTypeTemp[0]);
	//prx(strtolower($fileNameTemp[0]));
	if(strtolower($fileNameTemp[0]) == 'playerlog'){
	//INSERT INTO `playerlogs` (`location_id`, `datetime`, `title`, `artist_name`, `playlist_name`, `category_name`, `file_ref`, `creation_time`) VALUES ('123', '2017-10-19 09:38:50', 'title', 'name of artist', 'name of playlist', 'name of category', 'asd/asdasd/qwe.csv', CURRENT_TIMESTAMP);

			$insert_qry_here = 'INSERT INTO playerlogs (location_id, datetime, title, artist_name, playlist_name, category_name) VALUES ';
	
					foreach($lines as $insert_qry_arr1_tempVal){
						$formated_date = '';
						
						$date = new DateTime($insert_qry_arr1_tempVal['DateTime']);
						$formated_date = $date->format('Y-m-d H:i:s');
						//echo $insert_qry_arr1_tempVal['DateTime']."    ----->>>   ".$formated_date;
						//echo "<br/>";
                        $insert_qry_data[] = "('".$insert_qry_arr1_tempVal['TokenId']."','".$formated_date."','".$insert_qry_arr1_tempVal['Title']."','".$insert_qry_arr1_tempVal['ArtistName']."','".$insert_qry_arr1_tempVal['PlaylistName']."','".$insert_qry_arr1_tempVal['CategoryName']."')";
                    }
                    $insert_qry_here    .=  implode(", ",$insert_qry_data);
                    $insert_qry_here    .=  ';';
	}
	
	
	if(strtolower($fileNameTemp[0]) == 'masterdata'){
		//INSERT INTO `doctors` (`location_id`, `network_id`, `name`, `mobile`, `ll_num1`, `ll_num2`, `receptionist_name`, `receptioist_mobile`, `city`, `state`, `country`, `pincode`, `address`, `morning_time_from`, `morning_time_to`, `evening_time_from`, `evening_time_to`, `file_ref`, `creation_time`) VALUES ('789', 'ewrwer324234', 'name', 'mobile', 'll1', 'll2', 'receptionist name', 'recepmobile', 'city', 'state', 'india', '123456', 'address', '07:32:00', '06:42:00', '15:47:00', '20:59:00', 'file_ref', CURRENT_TIMESTAMP);
		//echo "<pre>";
		//print_r($lines);
		$insert_qry_here = 'INSERT INTO doctors (location_id, network_id, name, mobile, ll_num1, ll_num2, receptionist_name, receptioist_mobile, city, state, country, pincode, address, morning_time_from, morning_time_to, evening_time_from, evening_time_to) VALUES ';
	
					foreach($lines as $insert_qry_arr1_tempVal){
						$formated_date = '';
						
						//$date = new DateTime($insert_qry_arr1_tempVal['DateTime']);
						//$formated_date = $date->format('Y-m-d H:i:s');
						//echo $insert_qry_arr1_tempVal['DateTime']."    ----->>>   ".$formated_date;
						//echo "<br/>";
						/*
						[Location-ID] => 892
            [Network-ID] => IAPTV0000146
            [Doctor Name] => Rakesh Sharma
            [Doctors Mobile Number] => 9818463207
            [Landline Number of clinic 1] => 1294126207
            [Landline Number of clinic 2] => 
            [Receptionst Name] => NA
            [Receptionst Mobile Number] => 9910911207
            [City] => Faridabad
            [State] => Haryana
            [Address] => 177 Ashoka enclave main sector 35
            [Clinic Timing (Morning)] => 10am-1pm
            [Clinic Timing (Evening)] => 6pm-8:30pm
            [Pincode] => 121003
						
						*/
                        $insert_qry_data[] = "('".$insert_qry_arr1_tempVal['Location-ID']."','".$insert_qry_arr1_tempVal['Network-ID']."','".$insert_qry_arr1_tempVal['Doctor Name']."','".$insert_qry_arr1_tempVal['Doctors Mobile Number']."','".$insert_qry_arr1_tempVal['Landline Number of clinic 1']."','".$insert_qry_arr1_tempVal['Landline Number of clinic 2']."','".$insert_qry_arr1_tempVal['Receptionst Name']."','".$insert_qry_arr1_tempVal['Receptionst Mobile Number']."','".$insert_qry_arr1_tempVal['City']."','".$insert_qry_arr1_tempVal['State']."','INDIA','".$insert_qry_arr1_tempVal['Pincode']."','".mysql_real_escape_string($insert_qry_arr1_tempVal['Address'])."','09:30:00','13:30:00','15:00:00','21:00:00')";
                    }
                    $insert_qry_here    .=  implode(", ",$insert_qry_data);
                    $insert_qry_here    .=  ';';
	}
	
	
	if(strtolower($fileNameTemp[0]) == 'iaptv'){
		$insert_qry_data = array();
		$insert_qry_here = "INSERT INTO device_file_count (location_id, network_id, status, download_count, total_count, player_version) VALUES ";
		//('982', 'SDASDASD', 'online', '39', '42', CURRENT_TIMESTAMP, '');
		foreach($lines as $insert_qry_arr1_tempVal){
			/*
			    [PlayerStatus] => Offline
    [TokenId] => 720
    [PlayerNo] => 720
    [PlayerName] => IAP TV Test 1
    [Location] => IAP TV Test 1
    [PlayerGroup] => 
    [FileCount] => 42/41
    [PlayerVersion] => 1.1
			*/
			//prx($insert_qry_arr1_tempVal);
			$filecountTemp	=	explode('/',$insert_qry_arr1_tempVal['FileCount']);
			if($insert_qry_arr1_tempVal['TokenId'] > 0){
			$insert_qry_data[] = "('".$insert_qry_arr1_tempVal['TokenId']."', '".$insert_qry_arr1_tempVal['PlayerNo']."', '".$insert_qry_arr1_tempVal['PlayerStatus']."', '". (int) $filecountTemp[0] ."', '".(int) $filecountTemp[1]."','".$insert_qry_arr1_tempVal['PlayerVersion']."')";
                        }
			
		}
		$insert_qry_here    .=  implode(", ",$insert_qry_data);
            $insert_qry_here    .=  ';';
	}
//	prx($lines);
}
//echo $insert_qry_here;
//exit;
//echo $insert_qry_here;
//}111

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wbcms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


if ($conn->query($insert_qry_here) === TRUE) {
    echo "New record created successfully  ".$_FILES['file']['name'];
} else {
    echo "Error: " . $insert_qry_here . "<br>" . $conn->error;
}

$conn->close();

?>