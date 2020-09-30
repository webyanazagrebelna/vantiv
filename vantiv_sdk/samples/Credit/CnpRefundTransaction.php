<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
# standalone credit
$credit_info = array(
        'id'=> '456',
	'card'=>array('type'=>'VI',
			'number'=>'4100000000000001',
			'expDate'=>'1213',
			'cardValidationNum' => '1213'),
        'orderSource'=>'ecommerce',
        'orderId'=>'12321',
        'amount'=>'123'
	);
 
$initialize = new CnpOnlineRequest();
$creditResponse = $initialize->creditRequest($credit_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($creditResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($creditResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($creditResponse,'cnpTxnId'));

if(XmlParser::getNode($creditResponse,'message')!='Approved')
 throw new \Exception('CnpRefundTransaction does not get the right response');

