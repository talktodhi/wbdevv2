<?php

// src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('getHourMin', array($this, 'getHourMinFilter')),
            new \Twig_SimpleFilter('getDateTime', array($this, 'getDateTimeFilter')),
            new \Twig_SimpleFilter('prx', array($this, 'prxFilter')),
            new \Twig_SimpleFilter('getLogoUrl', array($this, 'getLogoUrlFilter')),
            new \Twig_SimpleFilter('getStopOverTime', array($this, 'getStopOverTimeFilter')),
            new \Twig_SimpleFilter('prxTwig', array($this, 'prxTwig')),
            new \Twig_SimpleFilter('jsonDecode', array($this, 'jsonDecode')),
            new \Twig_SimpleFilter('getHotelRatings', array($this, 'getHotelRatings')),
            new \Twig_SimpleFilter('addDecimal', array($this, 'addDecimal')),
            new \Twig_SimpleFilter('getClassName', array($this, 'getClassName')),
            new \Twig_SimpleFilter('getValue', array($this, 'getValue')),
            new \Twig_SimpleFilter('checkTerminal', array($this, 'checkTerminal')),
            new \Twig_SimpleFilter('getOnlyTicketNumbers', array($this, 'getOnlyTicketNumbers')),
            new \Twig_SimpleFilter('appendArray', array($this, 'appendArray')),
            new \Twig_SimpleFilter('getWebAsset2', array($this, 'getWebAsset2')),
            new \Twig_SimpleFilter('getWebAsset3', array($this, 'getWebAsset3')),
            new \Twig_SimpleFilter('removecollun', array($this, 'removecollun')),
            new \Twig_SimpleFilter('getStopsFilterForMultiPCCSrchResult', array($this, 'getStopsFilterForMultiPCCSrchResult')),
            new \Twig_SimpleFilter('getMinMaxForMultiPCCSrchResult', array($this, 'getMinMaxForMultiPCCSrchResult')),
            new \Twig_SimpleFilter('checkBooingFileExists', array($this, 'checkBooingFileExists')),
            new \Twig_SimpleFilter('getDepartFilterForMultiPCCSrchResult', array($this, 'getDepartFilterForMultiPCCSrchResult')),
            new \Twig_SimpleFilter('checkWifiInSegment', array($this, 'checkWifiInSegment')),
            new \Twig_SimpleFilter('dateChangeFomat', array($this, 'dateChangeFomat')),
            new \Twig_SimpleFilter('twigCheckInArry', array($this, 'twigCheckInArry')),
            //getDepartFilterForMultiPCCSrchResult
        );
    }
    
    
    public function twigCheckInArry($value, $array){
        if(in_array($value,$array)){
            $return =   '1';
        }else{
            $return =   '0';
        }
        return $return;
    }
    
    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function getName()
    {
        return 'app_extension';
    }
    
    public function getHourMinFilter($time, $format = '%dh %sm')
    {
        settype($time, 'integer');
        
        if ($time < 0 || $time >= 1440) {
            return;
        }
        
        $hours = floor($time/60);
        $minutes = $time%60;
        
        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        
        return sprintf($format, $hours, $minutes);
    }
    
    public function getDateTimeFilter($string,$format = 'd M, H:i')
    {
        return $new_date_format = date($format, strtotime($string));
    }
    
    public function getLogoUrlFilter($string)
    {

		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$tempURLExtract	=	explode('web',$url);
		$logo =	$tempURLExtract[0].'web/assets2/img/logo/'.strtoupper($string).'.jpg';
        return $logo;
    }
    
    public function getWebAsset2($string)
    {

		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$tempURLExtract	=	explode('web',$url);
		$logo =	$tempURLExtract[0].'web/assets2/'.$string;
        return $logo;
    }
    
    public function getStopOverTimeFilter($sec1ArrivalDT,$sec2DepartureDT){
        
        $datetime1 = new \DateTime(str_replace('T', ' ', $sec1ArrivalDT));
        $datetime2 = new \DateTime(str_replace('T', ' ', $sec2DepartureDT));
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%Hh %imin');
    }
    
    public function addDecimal($actualNumber, $afterDecimal){
        $divideBy   =   pow(10, $afterDecimal);
        $newNumber  =   number_format( $actualNumber / $divideBy , 2, '.','');
        return $newNumber;
    }
    
    public function createTableFilter($array)
    {
        $table = true;
        $out = '';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!isset($tableHeader)) {
                    $tableHeader =
                        '<th>' .
                        implode('</th><th>', array_keys($value)) .
                        '</th>';
                }
                array_keys($value);
                $out .= '<tr>';
                $out .= array2Html($value, false);
                $out .= '</tr>';
            } else {
                $out .= "<td>$value</td>";
            }
        }

        if ($table) {
            return '<table>' . $tableHeader . $out . '</table>';
        } else {
            return $out;
        }
    }
    
    public function jsonDecode($str) {
        return json_decode($str,true);
    }
    
    public function prxTwig($arr) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        exit;
    }
    
    public function getHotelRatings($str = ''){
        $tempArr = explode(' ',$str);
        return $tempArr[0];        
    }

    public function getClassName($classCode){
        //F = First class C = Business class Y = Coach class. W = Premium
        if(trim(strtoupper($classCode)) == 'Y'){
            $className = 'Economy';
        }elseif(trim(strtoupper($classCode)) == 'F'){
            $className = 'First';
        }elseif(trim(strtoupper($classCode)) == 'C'){
            $className = 'Business';
        }elseif(trim(strtoupper($classCode)) == 'W'){
            $className = 'Premium';
        }else{
            $className = 'Un-Known';
        }

        return $className;
    }
    
    public function getValue($array, $format = 'max')
    {
        if($format == 'max'){
            return max($array) + 50;
        }  elseif($format == 'min'){
            
            $minVal =    min($array) - 50;
            if($minVal < 0){
                return min($array);
            }else{
                return $minVal;
            }
            
        }
    }
    
    public function checkTerminal($data = '')
    {
        if(trim($data) != ''){
            return "Terminal ".$data;
        }else{
            return false;
        }
    }
    
    public function getOnlyTicketNumbers($ticketArr = array(), $paxID = '')
    {
        $allTickets =   array();
        foreach($ticketArr as $ticketArrKey => $ticketArrVal){
            if($ticketArrVal['TravelerElementNumber'] == $paxID){
                $allTickets[]   =   $ticketArrVal['ticketNum'];
            }
        }
        
        if(count($allTickets) > 0){
            return implode('<br/>',$allTickets);
        }else{
            return 'Not Available';
        }
        
    }
    
    public function appendArray($array= array(), $string){
        
        $stringNew = implode($string, $array);
        return $stringNew;
        
    }
    
    public function removecollun($str){
        $arrToReplace   =   array(':','|');
        $newArr         =   array('_','_');
        return str_replace($arrToReplace,$newArr,$str);
    }
    
    public function getWebAsset3($string)
    {

		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$tempURLExtract	=	explode('web',$url);
		$logo =	$tempURLExtract[0].'web/assets3/'.$string;
        return $logo;
    }
    
    
    public function getStopsFilterForMultiPCCSrchResult($pccDataArr = array()){
        $newStops = array();
        foreach($pccDataArr as $pccKey => $pccVal){
            foreach($pccDataArr[$pccKey]['stops'] as $stops => $stopsArr){
		if(!isset($newStops[$stops])){
			$newStops[$stops]	=	$stopsArr[0];
		}else{
			if($stopsArr[0] < $newStops[$stops]){
				$newStops[$stops]	=	$stopsArr[0];
			}
		}
            }
        }
        return $newStops;
    }
    
    public function getDepartFilterForMultiPCCSrchResult($pccDataArr = array()){
        $departArr = array();
        foreach($pccDataArr as $pccKey => $pccVal){
            foreach($pccVal['departSectors'] as $departKey => $departVal){
                if(!in_array($departVal, $departArr)){
                    $departArr[$departKey]  =   $departVal;
                }
            }
        }
        
        return $departArr;
    }
    
    public function getMinMaxForMultiPCCSrchResult($pccDataArr){
        $pccAmount  =   array();
        foreach($pccDataArr as $pccKey => $pccVal){
            if(!isset($pccAmount['min'])){
                $pccAmount['min']	=	$pccVal['amounts'][0];
            }else{
                if($pccVal['amounts'][0] < $pccAmount['min']){
                    $pccAmount['min']	=	$pccVal['amounts'][0];
                }
            }
	
            if(!isset($pccAmount['max'])){
                $pccAmount['max']	=	$pccVal['amounts'][count($pccVal['amounts']) - 1];
            }else{
                if($pccVal['amounts'][count($pccVal['amounts']) - 1] > $pccAmount['max']){
                    $pccAmount['max']	=	$pccVal['amounts'][count($pccVal['amounts']) - 1];
                }
            }
        }
        return $pccAmount;
    }
    
    public function checkBooingFileExists($bookingNumber){
        $jsonFile = __DIR__.'/../../../web/bookingFiles/'.$bookingNumber.'.json';
        if(file_exists($jsonFile)){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function checkWifiInSegment($segmentArr){
        $wifiFlag = 0;
        foreach($segmentArr as $segmentArrval){
            if(isset($segmentArrval['FlightCharacteristic'])){
                foreach ($segmentArrval['FlightCharacteristic'] as $characArr){
                    if(strtoupper($characArr['@attributes']['Description']) == 'WI-FI'){
                        $wifiFlag = 1;
                        break;
                    }
                }
            }
            if($wifiFlag == 1){
                     break;
            }
        }
        return $wifiFlag;
    }
    
    public function dateChangeFomat($date,$oldFormat,$newFormat){
        $DateTime =  new \DateTime();
        $date = $DateTime->createFromFormat($oldFormat, $date);
        return $date->format($newFormat);
    }
}