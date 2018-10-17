<?php

$metadata['https://is.levinsky.ac.il/nidp/saml2/metadata'] = array (
  'entityid' => 'https://is.levinsky.ac.il/nidp/saml2/metadata',
  'sign.logout' => TRUE,
  'encryption.blacklisted-algorithms' => array(),
  'description' => 
  array (
    'en' => 'Levinsky',
  ),
  'OrganizationName' => 
  array (
    'en' => 'Levinsky',
  ),
  'name' => 
  array (
    'en' => 'Levinsky IDS Cluster',
  ),
  'OrganizationDisplayName' => 
  array (
    'en' => 'Levinsky IDS Cluster',
  ),
  'url' => 
  array (
    'en' => 'www.levinsky.ac.il',
  ),
  'OrganizationURL' => 
  array (
    'en' => 'www.levinsky.ac.il',
  ),
  'contacts' => 
  array (
    0 => 
    array (
      'contactType' => 'other',
    ),
  ),
  'metadata-set' => 'saml20-idp-remote',
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://is.levinsky.ac.il/nidp/saml2/sso',
    ),
    1 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://is.levinsky.ac.il/nidp/saml2/sso',
    ),
  ),
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://is.levinsky.ac.il/nidp/saml2/slo',
      'ResponseLocation' => 'https://is.levinsky.ac.il/nidp/saml2/slo_return',
    ),
    1 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://is.levinsky.ac.il/nidp/saml2/slo',
      'ResponseLocation' => 'https://is.levinsky.ac.il/nidp/saml2/slo_return',
    ),
    2 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://is.levinsky.ac.il/nidp/saml2/soap',
    ),
  ),
  'ArtifactResolutionService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://is.levinsky.ac.il/nidp/saml2/soap',
      'index' => 0,
      'isDefault' => true,
    ),
  ),
  'keys' => 
  array (
    0 => 
    array (
      'encryption' => false,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIIGuTCCBaGgAwIBAgIJAJsLUE/wHAyNMA0GCSqGSIb3DQEBCwUAMIG0MQswCQYDVQQGEwJVUzEQ
MA4GA1UECBMHQXJpem9uYTETMBEGA1UEBxMKU2NvdHRzZGFsZTEaMBgGA1UEChMRR29EYWRkeS5j
b20sIEluYy4xLTArBgNVBAsTJGh0dHA6Ly9jZXJ0cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzEz
MDEGA1UEAxMqR28gRGFkZHkgU2VjdXJlIENlcnRpZmljYXRlIEF1dGhvcml0eSAtIEcyMB4XDTE4
MTAxNDA5NTIwOVoXDTIwMTAxODEyMDAzOFowPjEhMB8GA1UECxMYRG9tYWluIENvbnRyb2wgVmFs
aWRhdGVkMRkwFwYDVQQDDBAqLmxldmluc2t5LmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
MIIBCgKCAQEAqy1DTDztw+cqRfODqPeyt8YA61rctVTAFOtX/Tw259gUJ03W3YQ4P+J31DmyoeNW
sNde3Zo94bZQqcmJ8CbL7FmZUm9GeRGYtvDIgV1+linOT9fK0Mnpq7GLu6CZeDcYKUypq1PJeE8e
ONclVQhSDUPO7fgtKykrriSfRPiDUqPqFa8l34meYzh2Z/y1obawS3+SxRW3EkJBMoJuMctxW0xG
frTyYF4AQLpDcoELqN7TvvmmhZwKY+Ohg9f8C4ssZXDGhF5W/P1F4g8mhSichfQwXEWk8L0NKkOx
/7D+3Z/2pXfR/zbjYhoHAz4HTry/rDrKEaxAskqug00zbpyCHQIDAQABo4IDQTCCAz0wDAYDVR0T
AQH/BAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwDgYDVR0PAQH/BAQDAgWgMDcG
A1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly9jcmwuZ29kYWRkeS5jb20vZ2RpZzJzMS04NzcuY3JsMF0G
A1UdIARWMFQwSAYLYIZIAYb9bQEHFwEwOTA3BggrBgEFBQcCARYraHR0cDovL2NlcnRpZmljYXRl
cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzAIBgZngQwBAgEwdgYIKwYBBQUHAQEEajBoMCQGCCsG
AQUFBzABhhhodHRwOi8vb2NzcC5nb2RhZGR5LmNvbS8wQAYIKwYBBQUHMAKGNGh0dHA6Ly9jZXJ0
aWZpY2F0ZXMuZ29kYWRkeS5jb20vcmVwb3NpdG9yeS9nZGlnMi5jcnQwHwYDVR0jBBgwFoAUQMK9
J47MNIMwojPX+2yz8LQsgM4wKwYDVR0RBCQwIoIQKi5sZXZpbnNreS5hYy5pbIIObGV2aW5za3ku
YWMuaWwwHQYDVR0OBBYEFLR8w/z7lShB0su4YqtyhA2w/jabMIIBfwYKKwYBBAHWeQIEAgSCAW8E
ggFrAWkAdgCkuQmQtBhYFIe7E6LMZ3AKPDWYBPkb37jjd80OyA3cEAAAAWZx/XwgAAAEAwBHMEUC
IQC4P7Na21Ossd/NYIbRUs8N8hmscBBdwyA43kaV+LCE7wIgU6zgWkhbN1jW9/xgymvNZ+bwUdQI
4fYyHhOHvMc585wAdwDuS723dc5guuFCaR+r4Z5mow9+X7By2IMAxHuJeqj9ywAAAWZx/YA7AAAE
AwBIMEYCIQDe3V55jTo3nLRazViuWLbYZA+ikMrYubvtVH+zz5+HbwIhAIel2dWRu9J8zL4D9v4M
aRRFAD5FNQ1LVkGQIFIb4K+1AHYAXqdz+d9WwOe1Nkh90EngMnqRmgyEoRIShBh1loFxRVgAAAFm
cf2BCQAABAMARzBFAiEA+jBExquaTtDqhkLgynIJlJEc6kWSGpi5Jh5gIEcl4coCIEjzYW5Dk95E
v6fLrLe0nI2bcUyBiHabulpfwRf7834IMA0GCSqGSIb3DQEBCwUAA4IBAQBq3jWPsRFsv9o770N/
Rx53NKeUru4kqIiGOWylVDd9f2Pe7y+JVRx3eDNZ0Ks6C3oOCtuV3jH1vdZzAGUdrlhV2SGlc6ne
HPiQceI7jjQ7/UazGJ/s6o1ct/9PoEPOVSuaAvDImG0g8qpTRF+K2IJmeEMPbkITgQjMXaEg7Acn
DXkGrVT8a/TFY5SVC3Hifcu9hmtyrhgAXk5UonIdgOuHuQQaZi/3yq9qa5B4BwO7YZSavxiUPiU8
Ti8Zjw28ZZ4EhVasBF/pkAa5YBHakxwYOWvNPAxJyAhvnAC5PWLusfZCejoDC13L7k7FuIGidJbq
STq7eIH+XsoeM1IqmwDu
',
    ),
    1 => 
    array (
      'encryption' => true,
      'signing' => false,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIIGuTCCBaGgAwIBAgIJAJsLUE/wHAyNMA0GCSqGSIb3DQEBCwUAMIG0MQswCQYDVQQGEwJVUzEQ
MA4GA1UECBMHQXJpem9uYTETMBEGA1UEBxMKU2NvdHRzZGFsZTEaMBgGA1UEChMRR29EYWRkeS5j
b20sIEluYy4xLTArBgNVBAsTJGh0dHA6Ly9jZXJ0cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzEz
MDEGA1UEAxMqR28gRGFkZHkgU2VjdXJlIENlcnRpZmljYXRlIEF1dGhvcml0eSAtIEcyMB4XDTE4
MTAxNDA5NTIwOVoXDTIwMTAxODEyMDAzOFowPjEhMB8GA1UECxMYRG9tYWluIENvbnRyb2wgVmFs
aWRhdGVkMRkwFwYDVQQDDBAqLmxldmluc2t5LmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
MIIBCgKCAQEAqy1DTDztw+cqRfODqPeyt8YA61rctVTAFOtX/Tw259gUJ03W3YQ4P+J31DmyoeNW
sNde3Zo94bZQqcmJ8CbL7FmZUm9GeRGYtvDIgV1+linOT9fK0Mnpq7GLu6CZeDcYKUypq1PJeE8e
ONclVQhSDUPO7fgtKykrriSfRPiDUqPqFa8l34meYzh2Z/y1obawS3+SxRW3EkJBMoJuMctxW0xG
frTyYF4AQLpDcoELqN7TvvmmhZwKY+Ohg9f8C4ssZXDGhF5W/P1F4g8mhSichfQwXEWk8L0NKkOx
/7D+3Z/2pXfR/zbjYhoHAz4HTry/rDrKEaxAskqug00zbpyCHQIDAQABo4IDQTCCAz0wDAYDVR0T
AQH/BAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwDgYDVR0PAQH/BAQDAgWgMDcG
A1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly9jcmwuZ29kYWRkeS5jb20vZ2RpZzJzMS04NzcuY3JsMF0G
A1UdIARWMFQwSAYLYIZIAYb9bQEHFwEwOTA3BggrBgEFBQcCARYraHR0cDovL2NlcnRpZmljYXRl
cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzAIBgZngQwBAgEwdgYIKwYBBQUHAQEEajBoMCQGCCsG
AQUFBzABhhhodHRwOi8vb2NzcC5nb2RhZGR5LmNvbS8wQAYIKwYBBQUHMAKGNGh0dHA6Ly9jZXJ0
aWZpY2F0ZXMuZ29kYWRkeS5jb20vcmVwb3NpdG9yeS9nZGlnMi5jcnQwHwYDVR0jBBgwFoAUQMK9
J47MNIMwojPX+2yz8LQsgM4wKwYDVR0RBCQwIoIQKi5sZXZpbnNreS5hYy5pbIIObGV2aW5za3ku
YWMuaWwwHQYDVR0OBBYEFLR8w/z7lShB0su4YqtyhA2w/jabMIIBfwYKKwYBBAHWeQIEAgSCAW8E
ggFrAWkAdgCkuQmQtBhYFIe7E6LMZ3AKPDWYBPkb37jjd80OyA3cEAAAAWZx/XwgAAAEAwBHMEUC
IQC4P7Na21Ossd/NYIbRUs8N8hmscBBdwyA43kaV+LCE7wIgU6zgWkhbN1jW9/xgymvNZ+bwUdQI
4fYyHhOHvMc585wAdwDuS723dc5guuFCaR+r4Z5mow9+X7By2IMAxHuJeqj9ywAAAWZx/YA7AAAE
AwBIMEYCIQDe3V55jTo3nLRazViuWLbYZA+ikMrYubvtVH+zz5+HbwIhAIel2dWRu9J8zL4D9v4M
aRRFAD5FNQ1LVkGQIFIb4K+1AHYAXqdz+d9WwOe1Nkh90EngMnqRmgyEoRIShBh1loFxRVgAAAFm
cf2BCQAABAMARzBFAiEA+jBExquaTtDqhkLgynIJlJEc6kWSGpi5Jh5gIEcl4coCIEjzYW5Dk95E
v6fLrLe0nI2bcUyBiHabulpfwRf7834IMA0GCSqGSIb3DQEBCwUAA4IBAQBq3jWPsRFsv9o770N/
Rx53NKeUru4kqIiGOWylVDd9f2Pe7y+JVRx3eDNZ0Ks6C3oOCtuV3jH1vdZzAGUdrlhV2SGlc6ne
HPiQceI7jjQ7/UazGJ/s6o1ct/9PoEPOVSuaAvDImG0g8qpTRF+K2IJmeEMPbkITgQjMXaEg7Acn
DXkGrVT8a/TFY5SVC3Hifcu9hmtyrhgAXk5UonIdgOuHuQQaZi/3yq9qa5B4BwO7YZSavxiUPiU8
Ti8Zjw28ZZ4EhVasBF/pkAa5YBHakxwYOWvNPAxJyAhvnAC5PWLusfZCejoDC13L7k7FuIGidJbq
STq7eIH+XsoeM1IqmwDu
',
    ),
  ),
);
