<?php

namespace Commonfiles\Utils;

use Commonfiles\Utils\XML2Array;
/**
 * This class has all the methods related to XML parsing and
 * other XML operations
 *
 * @author Dhiraj Bastwade
 */
class XmlUtilityClass extends XML2Array
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
    public function getXmlTagValue($xml = false, $tag = false)
    {
    
        $returnArr  = array();
            $doc = new \DOMDocument();
        if ($doc->loadXML($xml)) {
            $items  = $doc->getElementsByTagName($tag);
            foreach ($items as $item) {//foreach tag found
                $tagValue   = array();
                if ($item->childNodes->length) {//if its child exist
                    foreach ($item->childNodes as $i) {
                        $tagValue[$i->nodeName] = $i->nodeValue;
                    }
                }
                
                $returnArr[] = $tagValue;
            }//End of foreach
        }//getXmlTagValue
        return $returnArr;
    }
    
    /*
     * This method  beautifies the XML
     *
     * @author Dhiraj Bastwade
     * @author Dhiraj Bastwade <dhiraj@wholdings.travel>
     * 
     * @filesource xmlUtilityClass.php
     * 
     * @param string $xml  xml string that needs to be beautified
     * @return formatted beautified XML response
     */
    public function beautifyXML($xml = '<?xml version="1.0" encoding="UTF-8"?>')
    {
        
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml);
        $dom->formatOutput = true;
        $doRequest = $dom->saveXml();
        $doRequest = trim(str_replace('<?xml version="1.0"?>', '', $doRequest));
        return $doRequest;
    }
    
    /*
     * This method will make a log file of webservice calls in 
     * web folder of project
     *
     * @author Dhiraj Bastwade
     * @author Dhiraj Bastwade <dhiraj@wholdings.travel>
     * 
     * @filesource sabreClass.php
     * 
     */
    public function gdsLogger(
        $action = 'GDS ACTION',
        $gdsSrc = "SABRE",
        $requestXML = 'Empty Request XML',
        $responseXML = 'Empty Response XML',
        $requestTime,
        $reponseTime,
        $headers = 'No Headers Captured',
        $pcc
    ) {
        
        $userMachine = str_replace(array('.',':'), '_', $_SERVER['REMOTE_ADDR']);
        $userLog = __DIR__.'/../../../web/Logs/'.$userMachine.'_'.$gdsSrc.'Log_'.date("Y_m_d").'.txt';
        
        $fopen = \fopen($userLog, 'a');
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, '******************************'
                .$action.'  -->  '.$pcc.'REQUEST **** '.$requestTime.'  ***', FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, $requestXML, FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, '*******************************'
                .$action.'  -->  '.$pcc.'  RESPONSE **** '.$reponseTime.'  ***', FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, $headers, FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, $this->beautifyXML($responseXML), FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        
        \fclose($fopen);
        
        $userLog = '';
        $userMachine_orig = str_replace(array('.',':'), '_', $_SERVER['REMOTE_ADDR']);
        $userLog = __DIR__.'/../../../web/Logs/'.$userMachine_orig.'_'.$gdsSrc.'Log_'.date("Y_m_d").'_original.txt';
        
        $fopen = \fopen($userLog, 'a');
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, '******************************'
                . '** '.$action.'  REQUEST ** '.date('r').'  ***', FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, $requestXML, FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, '******************************** '
                .$action.'  RESPONSE ** '.date('r').'  ***', FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, $headers, FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, $responseXML, FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        file_put_contents($userLog, "\r\n", FILE_APPEND);
        
        \fclose($fopen);
        
    }
    
    public function loadXMLFromXMLFile($xmlFile = 'res.xml')
    {
        $doc = new \DOMDocument();
        $userLogdfg = __DIR__.'/../../../web/'.$xmlFile;
        $doc->load($userLogdfg);
        return $doc->saveXML();
    }
    
    public function get_string_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);   
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }
}
