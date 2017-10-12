<?php


$config = array(

    // This is a authentication source which handles admin authentication.
    'admin' => array(
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ),
    
    'default-sp' => array(
        'saml:SP',
        'entityID' => null,
        'idp' => 'https://is.levinsky.ac.il/nidp/saml2/metadata',
        'discoURL' => null,
	'privatekey' => 'saml.pem',
	'certificate' => 'saml.crt',
    ),

);
