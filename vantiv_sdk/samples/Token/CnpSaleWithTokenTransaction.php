<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
 
#Sale using a previously registered token
$sale_info = array(
        	      'orderId' => '1',
                      'id'=> '456',
		      'amount' => '10010',
		      'orderSource'=>'ecommerce',
		      'billToAddress'=>array(
		      'name' => 'John Smith',
		      'addressLine1' => '1 Main St.',
		      'city' => 'Burlington',
		      'state' => 'MA',
		      'zip' => '01803-3747',
		      'country' => 'US'),
		      'token'=>array(
		      'cnpToken' =>'5112010000000003',
		      'expDate' => '0112',
		      'cardValidationNum' => '349',
		      'type' => 'MC')
			);
 
$initialize = new CnpOnlineRequest();
$saleResponse = $initialize->saleRequest($sale_info);
 
#display results
echo ("Response: " . (XmlParser::getNode($saleResponse,'response')) . "<br>");
echo ("Message: " . XmlParser::getNode($saleResponse,'message') . "<br>");
echo ("Vantiv Transaction ID: " . XmlParser::getNode($saleResponse,'cnpTxnId'));

if(XmlParser::getNode($saleResponse,'message')!='Approved')
 throw new \Exception('CnpSaleWithTokenTransaction does not get the right response');
