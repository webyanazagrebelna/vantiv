<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Capture Given Auth
 
$capture_info = array(
  'id'=> '456',
  'orderId'=>'12344',
  'amount'=>'106',
  'authInformation' => array(
    'authDate'=>'2002-10-09',
    'authCode'=>'543216',
    'authAmount'=>'12345'
  ),
  'orderSource'=>'ecommerce',
  'card'=>array(
    'type'=>'VI',
    'number' =>'4100000000000001',
    'expDate' =>'1210'
  )
);
 
$initialize = new CnpOnlineRequest();
$response = $initialize->captureGivenAuthRequest($capture_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($response,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($response,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($response,'cnpTxnId'));


if(XmlParser::getNode($response,'message')!='Approved')
 throw new \Exception('CnpCaptureGivenAuthTransaction does not get the right response');