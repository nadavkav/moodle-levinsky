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
MIIFLDCCBBSgAwIBAgIJANMQkrJbJ0gHMA0GCSqGSIb3DQEBCwUAMIG0MQswCQYDVQQGEwJVUzEQ
MA4GA1UECBMHQXJpem9uYTETMBEGA1UEBxMKU2NvdHRzZGFsZTEaMBgGA1UEChMRR29EYWRkeS5j
b20sIEluYy4xLTArBgNVBAsTJGh0dHA6Ly9jZXJ0cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzEz
MDEGA1UEAxMqR28gRGFkZHkgU2VjdXJlIENlcnRpZmljYXRlIEF1dGhvcml0eSAtIEcyMB4XDTE1
MTAxODEyMDAzOFoXDTE4MTAxODEyMDAzOFowPjEhMB8GA1UECxMYRG9tYWluIENvbnRyb2wgVmFs
aWRhdGVkMRkwFwYDVQQDDBAqLmxldmluc2t5LmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
MIIBCgKCAQEAxfyREQjEThVZLQ8FlT/qgScz7tl969s7fyl/FeV+CZfc0nCLSwZxAxYLtVL8BAJ+
9DmoRKIf9+s91Dao8vWIClXsSM/RYjRU1/TXiCCi61lE3uPTGTYCjoUEAKGR2b0/YKurGs5CZ7BE
JoxuMI8Lbn/+Og8uyKHHX1ZcfaxWwctPK+y8IKyHe8CAXQLy/8ZnnF9PnuO7efobwjhGPD9M0dQA
cVsSaRnHRi6alvYnwaccaIee0p48vnOeBwMGLar5OcORjrj7O0yHtZIfe23tF6baxbZkS9LHLQ8r
MiuwIXza11CVxKeJnr+sz+6Qh9mx10JZ8s0w6eFN5DpkIpXtzQIDAQABo4IBtDCCAbAwDAYDVR0T
AQH/BAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwDgYDVR0PAQH/BAQDAgWgMDcG
A1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly9jcmwuZ29kYWRkeS5jb20vZ2RpZzJzMS0xNDAuY3JsMFMG
A1UdIARMMEowSAYLYIZIAYb9bQEHFwEwOTA3BggrBgEFBQcCARYraHR0cDovL2NlcnRpZmljYXRl
cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzB2BggrBgEFBQcBAQRqMGgwJAYIKwYBBQUHMAGGGGh0
dHA6Ly9vY3NwLmdvZGFkZHkuY29tLzBABggrBgEFBQcwAoY0aHR0cDovL2NlcnRpZmljYXRlcy5n
b2RhZGR5LmNvbS9yZXBvc2l0b3J5L2dkaWcyLmNydDAfBgNVHSMEGDAWgBRAwr0njsw0gzCiM9f7
bLPwtCyAzjArBgNVHREEJDAighAqLmxldmluc2t5LmFjLmlsgg5sZXZpbnNreS5hYy5pbDAdBgNV
HQ4EFgQUzfMeu9Ck4AVjVD4xHQp/QZy75SkwDQYJKoZIhvcNAQELBQADggEBAETkcGeBLsmSdT8Z
6E0ROeunb7q72nyBt14E3TMPLWd7frVeRIHCo1hZs9hNZXjYMjpLUfwqVSai288I5qvkocnbkskO
8NtuKqxI5E+9i0rKNBq6hYAXMOysQI0h9LLVUbEMHoz/5BF+OOauiWKL51+tHxp6H7VwMKrzeOE8
YWmsQSMbOeayNVkwzXOG3GFyoWpHoAqBzcHFU6ImyApfnjNAWAGBaLbOU3vd5Awnvxob2K8TvMpt
qi0XcYevOI4gjSGtVbqpZV5DN2OvxsDMx907EI11ssHJ79GZgYya7xbOB92vFA55WO0GWKF6PVfk
hy1a51gss+lgQ2qdrKQWyy0=
',
    ),
    1 => 
    array (
      'encryption' => true,
      'signing' => false,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIIFLDCCBBSgAwIBAgIJANMQkrJbJ0gHMA0GCSqGSIb3DQEBCwUAMIG0MQswCQYDVQQGEwJVUzEQ
MA4GA1UECBMHQXJpem9uYTETMBEGA1UEBxMKU2NvdHRzZGFsZTEaMBgGA1UEChMRR29EYWRkeS5j
b20sIEluYy4xLTArBgNVBAsTJGh0dHA6Ly9jZXJ0cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzEz
MDEGA1UEAxMqR28gRGFkZHkgU2VjdXJlIENlcnRpZmljYXRlIEF1dGhvcml0eSAtIEcyMB4XDTE1
MTAxODEyMDAzOFoXDTE4MTAxODEyMDAzOFowPjEhMB8GA1UECxMYRG9tYWluIENvbnRyb2wgVmFs
aWRhdGVkMRkwFwYDVQQDDBAqLmxldmluc2t5LmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
MIIBCgKCAQEAxfyREQjEThVZLQ8FlT/qgScz7tl969s7fyl/FeV+CZfc0nCLSwZxAxYLtVL8BAJ+
9DmoRKIf9+s91Dao8vWIClXsSM/RYjRU1/TXiCCi61lE3uPTGTYCjoUEAKGR2b0/YKurGs5CZ7BE
JoxuMI8Lbn/+Og8uyKHHX1ZcfaxWwctPK+y8IKyHe8CAXQLy/8ZnnF9PnuO7efobwjhGPD9M0dQA
cVsSaRnHRi6alvYnwaccaIee0p48vnOeBwMGLar5OcORjrj7O0yHtZIfe23tF6baxbZkS9LHLQ8r
MiuwIXza11CVxKeJnr+sz+6Qh9mx10JZ8s0w6eFN5DpkIpXtzQIDAQABo4IBtDCCAbAwDAYDVR0T
AQH/BAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwDgYDVR0PAQH/BAQDAgWgMDcG
A1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly9jcmwuZ29kYWRkeS5jb20vZ2RpZzJzMS0xNDAuY3JsMFMG
A1UdIARMMEowSAYLYIZIAYb9bQEHFwEwOTA3BggrBgEFBQcCARYraHR0cDovL2NlcnRpZmljYXRl
cy5nb2RhZGR5LmNvbS9yZXBvc2l0b3J5LzB2BggrBgEFBQcBAQRqMGgwJAYIKwYBBQUHMAGGGGh0
dHA6Ly9vY3NwLmdvZGFkZHkuY29tLzBABggrBgEFBQcwAoY0aHR0cDovL2NlcnRpZmljYXRlcy5n
b2RhZGR5LmNvbS9yZXBvc2l0b3J5L2dkaWcyLmNydDAfBgNVHSMEGDAWgBRAwr0njsw0gzCiM9f7
bLPwtCyAzjArBgNVHREEJDAighAqLmxldmluc2t5LmFjLmlsgg5sZXZpbnNreS5hYy5pbDAdBgNV
HQ4EFgQUzfMeu9Ck4AVjVD4xHQp/QZy75SkwDQYJKoZIhvcNAQELBQADggEBAETkcGeBLsmSdT8Z
6E0ROeunb7q72nyBt14E3TMPLWd7frVeRIHCo1hZs9hNZXjYMjpLUfwqVSai288I5qvkocnbkskO
8NtuKqxI5E+9i0rKNBq6hYAXMOysQI0h9LLVUbEMHoz/5BF+OOauiWKL51+tHxp6H7VwMKrzeOE8
YWmsQSMbOeayNVkwzXOG3GFyoWpHoAqBzcHFU6ImyApfnjNAWAGBaLbOU3vd5Awnvxob2K8TvMpt
qi0XcYevOI4gjSGtVbqpZV5DN2OvxsDMx907EI11ssHJ79GZgYya7xbOB92vFA55WO0GWKF6PVfk
hy1a51gss+lgQ2qdrKQWyy0=
',
    ),
  ),
);