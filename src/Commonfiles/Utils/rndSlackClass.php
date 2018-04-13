<?php

namespace Commonfiles\Utils;

//use Commonfiles\Utils\XML2Array;
/**
 * This class has all the methods related to XML parsing and
 * other XML operations
 *
 * @author Dhiraj Bastwade
 */
class rndSlackClass
{

    public function curlCall($method, $data = array()){
		
        $url	=	'https://slack.com/api/'.$method;
        
        $requestTime    = date('r');

        $urlData	=	http_build_query($data);

        $urlToCall	=	$url.'?'.$urlData;
        #CURL REQUEST PROCESS-START#

        //initiate curl transfer
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //set the URL to connect to
        curl_setopt($ch, CURLOPT_URL, $urlToCall);

        //do not include headers in the response
        curl_setopt($ch, CURLOPT_HEADER, 1);

        //Some servers (like Lighttpd) will not process the curl request without this header and will return error code 417 instead. 
        //Apache does not need it, but it is safe to use it there as well.
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //Response will be read in chunks of 64000 bytes
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 64000);

        //Tell curl to use POST
        curl_setopt($ch, CURLOPT_POST, 1);

        //Tell curl to write the response to a variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //curl_setopt($ch, CURLOPT_USERPWD, trim($this->agentId).":".trim($this->password)); // username and password - declared at the top of the doc
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        //Register the data to be submitted via POST
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlStr);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);

        //Execute request
        $response = curl_exec($ch);
        // prx($response);
        //close connection
        curl_close($ch);
		
		
	$parts = explode("\r\n\r\nHTTP/", $response);
        $parts = (count($parts) > 1 ? 'HTTP/' : '').array_pop($parts);
        list($headers, $body) = explode("\r\n\r\n", $parts, 2);
        $headerTemp         =   $headers;
        $responseBody        =   $body;
		
	return $responseBody;
		
    }
      
}
