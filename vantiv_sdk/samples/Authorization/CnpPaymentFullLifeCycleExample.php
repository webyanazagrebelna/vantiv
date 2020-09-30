<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Authorization
#Puts a hold on the fund
$auth_info = array(
                      'id'=> '456',
        	      'orderId' => '1',
		      'amount' => '10010',
		      'orderSource'=>'ecommerce',
		      'billToAddress'=>array(
		      'name' => 'John Smith',
		      'addressLine1' => '1 Main St.',
		      'city' => 'Burlington',
		      'state' => 'MA',
		      'zip' => '01803-3747',
		      'country' => 'US'),
		      'card'=>array(
		      'number' =>'4457010000000009',
		      'expDate' => '0112',
		      'cardValidationNum' => '349',
		      'type' => 'VI')
			);
 
$initialize = new CnpOnlineRequest();
$authResponse = $initialize->authorizationRequest($auth_info);

if(XmlParser::getNode($authResponse,'message')!='Approved')
 throw new \Exception('CnpPaymentFullLifeCycleExample authResponse does not get the right response');
#Capture
#Captures the authorization and results in money movement
$capture_hash =  array('cnpTxnId' =>(XmlParser::getNode($authResponse,'cnpTxnId')),'id'=> '456',);
$initialize = new CnpOnlineRequest();
$captureResponse = $initialize->captureRequest($capture_hash);

if(XmlParser::getNode($captureResponse,'message')!='Approved')
 throw new \Exception('CnpPaymentFullLifeCycleExample captureResponse does not get the right response');
#Credit
#Refund the customer
$credit_hash =  array('cnpTxnId' =>(XmlParser::getNode($captureResponse,'cnpTxnId')),'id'=> '456',);
$initialize = new CnpOnlineRequest();
$creditResponse = $initialize->creditRequest($credit_hash);

if(XmlParser::getNode($creditResponse,'message')!='Approved')
 throw new \Exception('CnpPaymentFullLifeCycleExample creditResponse does not get the right response');
#Void
#Cancel the refund, note that a deposit can be Voided as well
$void_hash =  array('cnpTxnId' =>(XmlParser::getNode($creditResponse,'cnpTxnId')),'id'=> '456',);
$initialize = new CnpOnlineRequest();
$voidResponse = $initialize->voidRequest($void_hash);

if(XmlParser::getNode($voidResponse,'message')!='Approved')
 throw new \Exception('CnpPaymentFullLifeCycleExample voidResponse does not get the right response');