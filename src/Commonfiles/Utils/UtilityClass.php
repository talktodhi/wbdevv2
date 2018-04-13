<?php

namespace Commonfiles\Utils;

/**
 * This class has all the methods related to utility like date format conversion
 *
 * @author Dhiraj Bastwade
 */
class UtilityClass
{
 
    /*
     * This method  provides value of child tags of an xml tag. It works for 
     * single parent child level..
     *
     * @author Dhiraj Bastwade
     * @author Dhiraj Bastwade <dhiraj@wholdings.travel>
     * 
     * @filesource xmlUtilityClass.php
     * 
     * @param string $xml  xml string to be formated
     * @param string $tag  xml tag for which value need  to be fetched
     * @return tag value in form of array
     * 
     */
    public function changeDateFormat($date, $oldFormat, $newFormat)
    {
        $oldDate    =   $date;
        $DateTime =  new \DateTime();
        $date = $DateTime->createFromFormat($oldFormat, $date);
        if(trim($oldDate) == ""){
            return "";
        }else{
            return $date->format($newFormat);
        }
    }
    
    /*
     * This method  provides value of child tags of an xml tag. It works for 
     * single parent child level..
     *
     * @author Dhiraj Bastwade
     * @author Dhiraj Bastwade <dhiraj@wholdings.travel>
     * 
     * @filesource xmlUtilityClass.php
     * 
     * @param string $xml  xml string to be formated
     * @param string $tag  xml tag for which value need  to be fetched
     * @return tag value in form of array
     * 
     */
    public function generateBookingFile($bookingNumber, $pnrData)
    {
        $jsonFile = __DIR__.'/../../../web/bookingFiles/'.$bookingNumber.'.json';
        
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($pnrData));
        fclose($fp);
        
    }
    /*

     
     */
}
