<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Register an account number to receive a Litle Token 
 
$token_info = array(
	      'orderId'=>'12344',
              'id'=> '456',
	      'accountNumber'=>'1233456789103801');
 
$initialize = new CnpOnlineRequest();
$tokenResponse = $initialize->registerTokenRequest($token_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($tokenResponse ,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($tokenResponse ,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($tokenResponse ,'cnpTxnId'). "<br>");
echo ("Litle Token: " . XmlParser::getNode($tokenResponse ,'cnpToken'));

if(XmlParser::getNode($tokenResponse,'message')!='Account number was successfully registered')
 throw new \Exception('CnpRegisterTokenTransaction does not get the right response');
