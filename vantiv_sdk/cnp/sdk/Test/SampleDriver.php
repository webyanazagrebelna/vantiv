<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../../vendor/autoload.php';
#Sale
$sale_info = array(
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
              'number' =>'5112010000000003',
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
echo ("All Response :"  . XmlParser::getAttribute($saleResponse,'cnpOnlineResponse','version'));
