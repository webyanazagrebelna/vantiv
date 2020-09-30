<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Force Capture
$capture_info = array(
  'id'=> '456',
  'merchantId' => '101',
  'version'=>'8.8',
  'reportGroup'=>'Planets',
  'cnpTxnId'=>'123456',
  'orderId'=>'12344',
  'amount'=>'106',
  'orderSource'=>'ecommerce',
  'card'=>array(
    'type'=>'VI',
    'number' =>'4100000000000001',
    'expDate' =>'1210'
  )
);
 
$initialize = new CnpOnlineRequest();
$response = $initialize->forceCaptureRequest($capture_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($response,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($response,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($response,'cnpTxnId'));

if(XmlParser::getNode($response,'message')!='Approved')
 throw new \Exception('CnpForceCaptureTransaction does not get the right response');
