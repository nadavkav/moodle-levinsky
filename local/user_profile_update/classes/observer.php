<?php

namespace local_user_profile_update;

defined ( 'MOODLE_INTERNAL' ) || die ();

class observer {


	public static function user_updated_handler(\core\event\user_updated $eventdata) {
	    return self::update_usertype($eventdata->objectid);
	}

    public static function user_created_handler(\core\event\user_created $eventdata) {
        return self::update_usertype($eventdata->objectid);
    }

    public static function update_usertype($userid) {
        global $DB;

        // Get usertype from MICHLOL (types: 96 = admin, 3 = teacher, ? = student)
        $usertype = self::get_rashim_ws_user_type($userid);

        // Update user profile.
        $ok = $DB->set_field('user', 'department', $usertype, array('id' => $userid));

        return true;
    }

	protected static function get_rashim_ws_user_type($userid) {
        global $DB;

	    // TODO: Move somewhere safe (security)
        $user = 'MICHAPI';
        $pass = '+cDm1H5t';

        $user_record = $DB->get_record('user', array('id' => $userid));

        $proper_idnumber = str_pad($user_record->idnumber, 9, "0", STR_PAD_LEFT);
        unset($user_record);

        // Academic stuff
        $inputdata = '<?xml version="1.0" encoding="utf-8" ?>' .
            "<PARAMS>" .
            "<SNL>תשעט</SNL>" .
            "<SHL>-1</SHL>" .
            "<MHLK>-1</MHLK>" .
            "<MSL>-1</MSL>" .
            "<HIT>-1</HIT>" .
            "<ID>$proper_idnumber</ID>" .
            "<TAKZIV>-1</TAKZIV>" .
            "</PARAMS>";
        $RequestID = 9;


        $param = array(
            'P_RequestParams' => array(
                'RequestID' => $RequestID, // API number
                'InputData' => $inputdata  // Params - as XML - according to the API
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
            $client = new \SoapClient($wsdlUrl, $soapClientOptions);

            $result = $client->ProcessRequest($param);
            unset($client);

            $xml = simplexml_load_string($result->ProcessRequestResult->OutputData);
            $kodtype = $xml->RECORD->KODTYPE;
            unset($xml);

            if (null !== $kodtype) {
                switch ($kodtype) {
                    case 1:
                        return '1_מרצה';
                    case 2:
                        return '2_מרצה';
                    case 3:
                        return '3_מרצה';
                    case 4:
                        return '4_מרצה';
                    case 5:
                        return '5_מרצה';
                    case 9:
                        return '9_מרצה לשעבר';
                    case 10:
                        return '10_מרצה';
                    case 15:
                        return '15_מורה חונך';
                    case 16:
                        return '16_מורה מלווה';
                    case 17:
                        return '17_מורים מאמנים';
                    case 18:
                        return '18_מרצה';
                    case 20:
                        return '20_מרצה חותם';
                    case 22:
                        return '22_מורה מכינה אילת';
                    case 24:
                        return '24_מורה מדריך סטודנט';
                    case 27:
                        return '27_המורה החדש';
                    case 29:
                        return '29_מרצה פנסיונר';
                    case 81:
                        return '81_מרצה';
                        
                    //// Management
                    case 88:
                        return '88_סגל מנהלי';
                    case 89:
                        return '89_סגל מנהלי גמלאי';
                    case 96:
                        return '96_סגל מנהלי';

                    default:
                        return (string)$kodtype;
                }
            } else {
                // If nothing returns... probably 'student'
                return 'student'; // Student.
            }

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
