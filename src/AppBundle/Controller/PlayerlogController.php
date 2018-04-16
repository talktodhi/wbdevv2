<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;

class PlayerlogController extends Controller
{
    /**
     * @Route("/playerlog", name="playerlog_listing")
     */
    public function indexAction(Request $request)
    {
        $session                        =   $request->getSession();
        $user                           =   $session->get('user');
        if($user['id'] < 1){
            //$this->redirectToRoute('login');
            return $this->redirect($this->generateUrl("logout"));
        }
        $params = array();
        $data = array();
        $router = $this->get('router');
        
        $data['main_menu']  =   'playerlog';
        $data['sub_menu']   =   'playerlog_listing';
        
        $limit = 20;  
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $start_from = ($page-1) * $limit;  

        $sql_data = "SELECT * FROM playerlogs";
        $where = array();
        if(isset($_GET['location_id'])){
            $_GET['location_id'] = array_filter($_GET['location_id']);
        }
        if(isset($_GET['location_id']) && (count($_GET['location_id']) > 0)){
            $where[] = "location_id IN (".implode(',',$_GET['location_id']).")";
        }
        
        if(isset($_GET['from_date'])){
        if($_GET['from_date'] != ''){
            $from_date = date('Y-m-d H:i:s', strtotime($_GET['from_date']));
            if(isset($_GET['to_date'])){
                $to_date = date('Y-m-d H:i:s', strtotime($_GET['to_date']));
            }else{
                $to_date = date('Y-m-d H:i:s');
            }
            $where[] = " datetime BETWEEN '".$from_date."' AND '".$to_date."'";
        }
        }
        /*
        if(isset($_GET['doctor_name']) > 0){
            $doctorNameTemp = array();
            foreach($_GET['doctor_name'] as $doctor_name_val){
                if($doctor_name_val != ""){
                    $doctorNameTemp[] = "'".$doctor_name_val."'";
                }
            }
            
            if(count($doctorNameTemp) > 0){
                $where[] .= "name IN (".implode(',',$doctorNameTemp).")";
            }
        }
        if(isset($_GET['city']) > 0){
            $cityTemp = array();
            foreach($_GET['city'] as $city_val){
                if($city_val != ''){
                $cityTemp[] = "'".$city_val."'";
                }
            }
            if(count($cityTemp) > 0){
                $where[] .= "city IN (".implode(',',$cityTemp).")";
            }
        }
        */
        $whereQry = '';
        if(count($where) >0){
            $whereQry .= " where ".implode(" AND ", $where);
        }
        $sql_data .= $whereQry;
        
        // AND `datetime` BETWEEN '2017-01-01 00:00:00.000000' AND '2017-11-30 00:00:00.000000'
        
        $sql_data .= " order by datetime DESC LIMIT ".$start_from.",".$limit;
        
        $dataSet    =   array();
        if(isset($_GET['location_id']) || isset($_GET['from_date'])){
            $em = $this->getDoctrine()->getManager();
            $dataSet = $em->getConnection()
                    ->fetchAll($sql_data);
        }
       
        $sql_data_count = 'SELECT DISTINCT location_id FROM playerlogs where location_id > 0';
        $data_connection2 = $this->getDoctrine()->getManager();
        $allDoctorData = $data_connection2->getConnection()
                    ->fetchAll($sql_data_count);
        $options['location_id'] = array();
        //prx($allDoctorData);
        foreach($allDoctorData as $allDoctorDataVal){
            $options['location_id'][$allDoctorDataVal['location_id']] = $allDoctorDataVal['location_id'];
          //  $options['doctor_name'][$allDoctorDataVal['name']] = $allDoctorDataVal['name'];
          //  $options['city'][$allDoctorDataVal['city']] = $allDoctorDataVal['city'];
        }
        
        $data['options']    =   $options;
        $sql_data_count2 = 'SELECT count(*) as cnt FROM playerlogs '.$whereQry;
        $data_connection3 = $this->getDoctrine()->getManager();
        $allDoctorDataCount = $data_connection3->getConnection()
                    ->fetchAll($sql_data_count2);
        $dataCount['count'] =   $allDoctorDataCount[0]['cnt'];
        $data['total_records']          =   $dataCount['count'];
        $data['playerlog_list']   =   $dataSet;
       
        if(isset($_GET)){
            $data['get']    = $_GET;
        }
        if(isset($_GET["page"])){
            $data['current_page']   =    $_GET["page"];
            $data['countSrNo']      =   (($_GET["page"] - 1)*$limit) + 1;
        }else{
            $data['current_page']  =    '1';
            $data['countSrNo']      =   1;
        }
        $total_records = $dataCount['count'];
        //$limit         =   1;
        $data['limit']   = $limit;
        $total_pages = ceil($total_records / $limit);  
        $pagLink = "<nav><ul class='pagination'>";  
        for ($i=1; $i<=$total_pages; $i++) {
            if(isset($_GET)){
                    $params = $_GET;
                }
                $params['page'] = $i;
                
                $rt = $router->generate('playerlog_listing', $params);
             $pagLink .= "<li><a href='".$rt."'>".$i."</a></li>";  
        }; 
        $pagLink .= "</ul></nav>";
        $data['paginations'] = '';
        if(isset($_GET['location_id']) || isset($_GET['from_date'])){
            $data['paginations'] = $pagLink;
        }else{
            $data['current_page']   =       '';
            $data['countSrNo']      =       '';
            $data['total_records']  =       0;
            
        }
        unset($params['page']);
        $params['page'] =   '';
        $data['url'] =  $router->generate('playerlog_listing', $params);;
        
        return $this->render('default/playerlog_listing.html.twig',array('data'=>$data));
    }
    
    /**
     * @Route("/playerlog_upload", name="playerlog_upload")
     */
    public function uploadAction(Request $request)
    {
        $data = array();
        $data['main_menu']  =   'playerlog';
        $data['sub_menu']   =   'playerlog_upload';
        
        return $this->render('default/playerlog_upload.html.twig',array('data'=>$data));
    }
    
    /**
     * @Route("/playerlog_upload_process", name="playerlog_upload_process")
     */
    public function uploadprocessAction(Request $request)
    {
        $data = array();
        $data['main_menu']  =   'doctors';
        $data['sub_menu']   =   'doctors_upload';
        
        if(!empty($_FILES)){
            $filename = basename($_FILES['file']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            
            $upload_dir = "";
            $fileName = $_FILES['file']['name'];
            $uploaded_file = '../web/uploaded_docs/playerlog/'.$fileName;
            
            $sql_data_count = 'SELECT * FROM datapincode ';
            $data_connection2 = $this->getDoctrine()->getManager();
            $allPincode = $data_connection2->getConnection()
                    ->fetchAll($sql_data_count);
            $pincode    =   array();
            foreach($allPincode as $allPincodeVal){
                $pincode[] = $allPincodeVal['pincode'];
            }
            if(move_uploaded_file($_FILES['file']['tmp_name'],$uploaded_file)){
                
                $lines = $this->fromCSVFile($uploaded_file);
                
                $insert_qry_here = 'INSERT INTO playerlogs (location_id, datetime, title, artist_name, playlist_name, category_name) VALUES ';
                $previousData = array();
                $insert_qry_data = array();
                $i = 0;
                foreach($lines as $insert_qry_arr1_tempVal){
                    /*
                    $date = new \DateTime($insert_qry_arr1_tempVal['DateTime']);
                    $just_date = $date->format('Y-m-d');
                    if(!isset($previousData[$insert_qry_arr1_tempVal['TokenId']][$just_date])){
                        $previousData[$insert_qry_arr1_tempVal['TokenId']][$just_date]  =   array();
                        $getData = 'SELECT * FROM playerlogs where location_id="'.$insert_qry_arr1_tempVal['TokenId'].'" AND  datetime LIKE "%'.$just_date.'%"';
                        $data_connection2 = $this->getDoctrine()->getManager();
                        $allGetData = $data_connection2->getConnection()
                                ->fetchAll($getData);

                        if(count($allGetData) > 0){
                            foreach($allGetData as $allGetDataVal){
                                $previousData[$insert_qry_arr1_tempVal['TokenId']][$just_date][$allGetDataVal['datetime']] = $allGetDataVal;
                            }
                        }
                    }
                    */
                    if($i == 0){
                        $insert_qry_data[] = "('".$insert_qry_arr1_tempVal['TokenId']."','0000-00-00 00:00:00','NULL','NULL','NULL','NULL')";
                    }
                    $formated_date = '';
                    $date = new \DateTime($insert_qry_arr1_tempVal['DateTime']);
                    $formated_date = $date->format('Y-m-d H:i:s');
                    //if(!isset($previousData[$insert_qry_arr1_tempVal['TokenId']][$just_date][$formated_date])){
                        $insert_qry_data[] = "('".$insert_qry_arr1_tempVal['TokenId']."','".$formated_date."','".$insert_qry_arr1_tempVal['Title']."','".$insert_qry_arr1_tempVal['ArtistName']."','".$insert_qry_arr1_tempVal['Playlistname']."','".$insert_qry_arr1_tempVal['CategoryName']."')";
                   // }
                   
                   $i++;
                }
                
                if(count($insert_qry_data) > 0){
                    $insert_qry_here    .=  implode(", ",$insert_qry_data);
                    $insert_qry_here    .= '  ON DUPLICATE KEY UPDATE title=VALUES(title), artist_name=VALUES(artist_name), playlist_name=VALUES(playlist_name), category_name=VALUES(category_name)';
                    $insert_qry_here    .=  ';';

                    $em = $this->getDoctrine()->getManager();
                    $conn = $em->getConnection();
                    $conn->prepare($insert_qry_here)
                     ->execute();
                }
                
                
            }
        }
       // echo "DOne success";
        exit;
    }
    
    private function fromCSVFile( $file) {
        // open the CVS file
        $handle = @fopen( $file, "r");
        if ( !$handle ) {
            throw new \Exception( "Couldn't open $file!" );
        }

        $result = [];

        // read the first line
        $first = fgets( $handle, 4096 );
        // get the keys
        $keys = str_getcsv( $first );
        //prx($keys);
        // read until the end of file
        while ( ($buffer = fgets( $handle, 4096 )) !== false ) {

            // read the next entry
            $array = str_getcsv ( $buffer );
            if ( empty( $array ) ) continue;

            $row = [];
            $i=0;

            // replace numeric indexes with keys for each entry
            if(count($keys) == count($array)){
                foreach ( $keys as $key ) {
                    $row[ $key ] = $array[ $i ];
                    $i++;
                }
            }
            // add relational array to final result
            $result[] = $row;
        }

        fclose( $handle );
        return $result;
    }
    
    /**
     * @Route("/daterangeanalytics", name="daterangeanalytics")
     */
    public function daterangeanalyticsAction(Request $request)
    {
        $session                        =   $request->getSession();
        $user                           =   $session->get('user');
        if($user['id'] < 1){
            //$this->redirectToRoute('login');
            return $this->redirect($this->generateUrl("logout"));
        }
        $data = array();
        
        $params = array();
        $data = array();
        $router = $this->get('router');
        
        $data['main_menu']  =   'analysis';
        $data['sub_menu']   =   'daterange_analysis';
        
        if(isset($_POST['date-range-picker1'])){
            $dataArr1 = array();
            $dataArr2 = array();
            
            $dateRange_1 = $_POST['date-range-picker1'];
            $dateRange_2 = $_POST['date-range-picker2'];
            //pr($_POST);
            $dateRange_1_temp = explode(' - ',$dateRange_1);
            $dateRange_2_temp = explode(' - ',$dateRange_2);
             
            $dateRange_1_from      = $dateRange_1_temp[0];
            $dateRange_1_to        = $dateRange_1_temp[1];
             
            $dateRange_2_from      = $dateRange_2_temp[0];
            $dateRange_2_to        = $dateRange_2_temp[1];
             //SELECT DISTINCT location_id FROM playerlogs WHERE datetime BETWEEN '2017-12-03' AND '2017-12-11' ORDER BY `id` ASC 
            $sql_set1 = "SELECT DISTINCT location_id  FROM playerlogs WHERE datetime BETWEEN '".$dateRange_1_from."' AND '".$dateRange_1_to."' order by location_id";
            $em = $this->getDoctrine()->getManager();
            $dataSet1 = $em->getConnection()
                ->fetchAll($sql_set1);
            foreach($dataSet1 as $dataSet1Val){
                $dataArr1[] =  $dataSet1Val['location_id'];
            }
            
            $sql_set2 = "SELECT DISTINCT location_id  FROM playerlogs WHERE datetime BETWEEN '".$dateRange_2_from."' AND '".$dateRange_2_to."' order by location_id";
            $em = $this->getDoctrine()->getManager();
            $dataSet2 = $em->getConnection()
                ->fetchAll($sql_set2);
            foreach($dataSet2 as $dataSet1Val2){
                $dataArr2[] =  $dataSet1Val2['location_id'];
            }

            foreach($dataArr1 as $dataArr1Val){
                if(!in_array($dataArr1Val, $dataArr2)){
                   $inactiveDevices[] =  $dataArr1Val;
                }
            }
            
            foreach($dataArr2 as $dataArr2Val){
                if(!in_array($dataArr2Val, $dataArr1)){
                   $newDevices[] =  $dataArr2Val;
                }
            }
            
            $sql_inactive = "SELECT *  FROM doctors WHERE location_id IN (".implode(',',$inactiveDevices).") order by state asc";
            $em = $this->getDoctrine()->getManager();
            $sql_inactiveData = $em->getConnection()
                ->fetchAll($sql_inactive);
            foreach($sql_inactiveData as $sql_inactiveDataVal){
                $sql_inactiveDataArr[$sql_inactiveDataVal['location_id']] =  $sql_inactiveDataVal;
            }
            
            $sql_newdevice = "SELECT *  FROM doctors WHERE location_id IN (".implode(',',$newDevices).") order by state asc";
            $em = $this->getDoctrine()->getManager();
            $sql_newDevicesData = $em->getConnection()
                ->fetchAll($sql_newdevice);
            foreach($sql_newDevicesData as $sql_newDevicesDataVal){
                $sql_newdeviceDataArr[$sql_newDevicesDataVal['location_id']] =  $sql_newDevicesDataVal;
            }
            
            $data['inactiveDeviceID']       =   $inactiveDevices;
            $data['inactiveDeviceData']     =   $sql_inactiveDataArr;
            
            $data['newDeviceID']            =   $newDevices;
            $data['newDeviceData']          =   $sql_newdeviceDataArr;
        }
        
      
        return $this->render('default/daterangeanalytics.html.twig',array('data'=>$data));
    }
 
    /**
     * @Route("/sendmailtest", name="sendmailtest")
     */
    public function sendmailtestAction(Request $request)
    {
		die('Here');
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('talktodhi@gmail.com')
        ->setTo('dhiraj.bastwade@gmail.com')
        ->setBody(
            "<p>Hi,</p>
                <p>Your new password for <strong>".$email."</strong> has been reset to&nbsp; <strong>".$newpassword."</strong>.</p>
                <p>Thanks,</p>
                <p>WB Analysis Portal</p>",
            'text/html'
        );
        $this->get('mailer')->send($message);
        echo "done";
        exit;
    }
}
