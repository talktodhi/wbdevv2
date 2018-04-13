<?php

namespace AppBundle\Controller;

use Commonfiles\Utils\UtilityClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;



class PlaylistController extends Controller
{
    /**
     * @Route("/playlist", name="playlist_listing")
     */
    public function indexAction(Request $request)
    {
        
        $session                        =   $request->getSession();
        $user                           =   $session->get('user');
        if($user['id'] < 1){
            //$this->redirectToRoute('login');
            return $this->redirect($this->generateUrl("logout"));
        }
        
        $data       =   array();
        $params     =   array();
        $router = $this->get('router');
        
        $data['main_menu']  =   'playlist';
        $data['sub_menu']   =   'playlist_listing';
        
        $limit = 20;  
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $start_from = ($page-1) * $limit;  

        $sql_data = "SELECT * FROM playlist_data";
        $where = array();
        if(isset($_GET['playlist_id'])){
            $_GET['playlist_id'] = array_filter($_GET['playlist_id']);
        }
        if(isset($_GET['playlist_id']) && (count($_GET['playlist_id']) > 0)){
            $where[] .= "playlist_id IN (".implode(',',$_GET['playlist_id']).")";
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
            $whereQry .= " where ".implode(" OR ", $where);
        }
        $sql_data .= $whereQry;
        $sql_data .= " LIMIT ".$start_from.",".$limit;
        //prx($sql_data);
        $em = $this->getDoctrine()->getManager();
        $dataSet = $em->getConnection()
                    ->fetchAll($sql_data);
        
        $sql_data_count = 'SELECT * FROM playlist_data ';
        $sql_data_count .= $whereQry;
        $data_connection2 = $this->getDoctrine()->getManager();
        $allDoctorData = $data_connection2->getConnection()
                    ->fetchAll($sql_data_count);
        $dataCount['count'] =   count($allDoctorData);
        $data['total_records']          =   $dataCount['count'];
        
        $sql_alldata_count = 'SELECT * FROM playlist_data ';
        $data_connection2 = $this->getDoctrine()->getManager();
        $allDoctorData = $data_connection2->getConnection()
                    ->fetchAll($sql_alldata_count);
        $options['playlist_id'] = array();
        foreach($allDoctorData as $allDoctorDataVal){
            $options['playlist_id'][$allDoctorDataVal['playlist_id']] = $allDoctorDataVal['playlist_name'];
          //  $options['doctor_name'][$allDoctorDataVal['name']] = $allDoctorDataVal['name'];
          //  $options['city'][$allDoctorDataVal['city']] = $allDoctorDataVal['city'];
        }
        
        $data['options']    =   $options;

        $data['playlist_list']   =   $dataSet;
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
                $rt = $router->generate('playlist_listing', $params);
             $pagLink .= "<li><a href='".$rt."'>".$i."</a></li>";  
        }; 
        $pagLink .= "</ul></nav>";
        $data['paginations'] = $pagLink;
        unset($params['page']);
        $params['page'] =   '';
        $data['url'] =  $router->generate('playlist_listing', $params);;
        return $this->render('default/playlist_listing.html.twig',array('data'=>$data));
    }
    
    /**
     * @Route("/playlist_upload", name="playlist_upload")
     */
    public function uploadAction(Request $request)
    {
        $data = array();
        $data['main_menu']  =   'playlist';
        $data['sub_menu']   =   'playlist_upload';
        
        return $this->render('default/playlist_upload.html.twig',array('data'=>$data));
    }
    
    /**
     * @Route("/playlist_upload_process", name="playlist_upload_process")
     */
    public function uploadprocessAction(Request $request)
    {
        $data = array();
        $data['main_menu']  =   'playlist';
        $data['sub_menu']   =   'playlist_upload';
        
        //$utilityClass = new UtilityClass();
        $playlistName = $_POST['playlist_name'];
        if(!empty($_FILES)){
            $filename = basename($_FILES['playlistfile']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            
            $upload_dir = "";
            $fileName = $_FILES['playlistfile']['name'];
            $uploaded_file = '../web/uploaded_docs/playlist/'.$fileName;
            
            //Check for same name in playlist master
            $sqlCount = 'Select * from playlist_master where name="'.$playlistName.'"';
            $data_connection2 = $this->getDoctrine()->getManager();
            $allData = $data_connection2->getConnection()
                    ->fetchAll($sqlCount);
            
            $actualCount = count($allData);
            ## to change == 0
            if($actualCount == 0){
            
            $requiredArr = array('S.No.','Film','Content ID','Group/Zone','Audio','Sub-Titles','Genre','Topic','Live/Animation','Brand','Product','Start Date','End Date');
                if(move_uploaded_file($_FILES['playlistfile']['tmp_name'],$uploaded_file)){
                    error_reporting(0);
                    $data_arr = $this->fromCSVFile($uploaded_file);

                    $csvKey = array_keys($data_arr[0]);

                    foreach($requiredArr as $requiredArrVal){
                        if(!in_array($requiredArrVal, $csvKey)){
                            $return['error']    =   'CSV not in proper format';
                        }
                    }
                    if(!isset($return['error'])){
                        $em = $this->getDoctrine()->getManager();
                        $conn = $em->getConnection();
                        $sql    = "INSERT INTO playlist_master (name) VALUES (:name)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':name', $playlistName);
                        $stmt->execute();

                        $lastInsertedId = $conn->lastInsertId();
                        $insert_qry_here = 'INSERT INTO playlist_data (playlist_id, sr_no, film, content_id, playlist_name, group_zone, audio, sub_titles, genre, topic, video_type,brand, product, start_date, end_date) VALUES ';

                        foreach($data_arr as $insert_qry_arr1_tempVal){
                                $insert_qry_data[] = "('".$lastInsertedId."','".$insert_qry_arr1_tempVal['S.No.']."','".$insert_qry_arr1_tempVal['Film']."','".$insert_qry_arr1_tempVal['Content ID']."','".$playlistName."','".$insert_qry_arr1_tempVal['Group/Zone']."','".$insert_qry_arr1_tempVal['Audio']."','".$insert_qry_arr1_tempVal['Sub-Titles']."','".$insert_qry_arr1_tempVal['Genre']."','".$insert_qry_arr1_tempVal['Topic']."','".$insert_qry_arr1_tempVal['Live/Animation']."','".$insert_qry_arr1_tempVal['Brand']."','".$insert_qry_arr1_tempVal['Product']."','".date('Y-m-d', strtotime($insert_qry_arr1_tempVal['Start Date']))."','".date('Y-m-d', strtotime($insert_qry_arr1_tempVal['End Date']))."')";
                        }

                        $insert_qry_here    .=  implode(", ",$insert_qry_data);
                        $insert_qry_here    .=  ';';
                        $em = $this->getDoctrine()->getManager();
                        $conn = $em->getConnection();
                        $conn->prepare($insert_qry_here)
                         ->execute();
                        /*
                        #trncate old data #
                        $truncateqry = 'TRUNCATE cq_brand_product_dynamic';
                        $em = $this->getDoctrine()->getManager();
                        $conn2 = $em->getConnection();
                        $conn2->prepare($truncateqry)
                         ->execute();
                        
                        ## create refreshed data #
                        $refreshQry = 'INSERT INTO cq_brand_product_dynamic (playlist_name, content_id, brand, product) SELECT playlist_name, content_id, brand, product FROM playlist_data  GROUP BY playlist_name, content_id';
                        $em = $this->getDoctrine()->getManager();
                        $conn3 = $em->getConnection();
                        $conn3->prepare($refreshQry)
                         ->execute();
                        */
                        $return['sucess'] = 'Playlist added successfully.';
                        
                        
                    }
                }
            
            }else{
                $return['error'] = 'Playlist already exists.';
            }
        }
        return new JsonResponse($return);
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
    
}
