<?php

require_once(__DIR__ . '/../../config.php');
$CFG->debug = 32767;
$CFG->debugdeveloper = true;
$CFG->debugdisplay = true;

// https://moodle.levinsky.ac.il/local/ws_rashim/get_from_rashim.php

$user = 'MICHAPI';
$pass = '+cDm1H5t';

// Student info
$inputdata = '<?xml version="1.0" encoding="utf-8" ?>' .
    "<PARAMS>" .
        "<STUDENTID>205490493</STUDENTID>" .
    "</PARAMS>";
$RequestID = 60;


// Academic stuff
$inputdata = '<?xml version="1.0" encoding="utf-8" ?>' .
    "<PARAMS>" .
        "<SNL>-1</SNL>" .
        "<SHL>-1</SHL>" .
        "<MHLK>-1</MHLK>" .
        "<MSL>-1</MSL>" .
        "<HIT>-1</HIT>" .
        "<ID>038228979</ID>" . // galit
        //"<ID>59836866</ID>" . // yishay
        "<TAKZIV>-1</TAKZIV>" .
    "</PARAMS>";
$RequestID = 9;


$param = array(
        'P_RequestParams' => array(
            'RequestID' => $RequestID, // API number
            'InputData' => $inputdata // the data - as XML - according to the API
          ),
        'Authenticator' => array(
            'UserName' => $user,
            'Password' => $pass
        )
    );

try {

    $wsdlUrl = 'https://172.26.147.100/WsM3Api/MichlolApi.asmx?WSDL';
    $soapClientOptions = array(
        'stream_context' => stream_context_create(
            array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ),
                'http' => array(
                    'user_agent' => 'PHPSoapClient'
                )
            )
        ),
        'exceptions' => true,
        'trace' => true,
        'soap_version' => SOAP_1_2,
        'encoding' => 'UTF-8',
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
        'cache_wsdl' => WSDL_CACHE_NONE
    );

    //libxml_disable_entity_loader(false);
    $client = new SoapClient($wsdlUrl, $soapClientOptions);

    $result = $client->ProcessRequest($param);
    // debug
    //print_object($result);

    $xml=simplexml_load_string($result->ProcessRequestResult->OutputData);
    // debug
    print_object($xml);

    $ok = $DB->set_field_select('user', 'department', (string)$xml->RECORD->KODTYPE,
        "idnumber LIKE '%38228979%' ");//, array('38228979'));

    // debug
    //echo $xml->RECORD->KODTYPE;

}
catch(Exception $e) {
    echo $e->getMessage();
}