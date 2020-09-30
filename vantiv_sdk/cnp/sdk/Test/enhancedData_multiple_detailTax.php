<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../../vendor/autoload.php';
#Sale
$sale_info = array(
              'id' => 1,
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
              'type' => 'MC'
              ),
              'enhancedData' => array(
                  'detailTax0' => array(
                      'taxAmount' => '200',
                      'taxRate' => '0.06',
                      'taxIncludedInTotal' => true
                  ),
                  'detailTax1' => array(
                      'taxAmount' => '300',
                      'taxRate' => '0.10',
                      'taxIncludedInTotal' => true
                  ),
                  'salesTax' => '500',
                  'taxExempt' => false
              ),
            );
$initialize = new CnpOnlineRequest();
$saleResponse = $initialize->saleRequest($sale_info);
#display results
echo ("Response: " . (XmlParser::getNode($saleResponse,'response'))) . "\n";
echo ("Message: " . XmlParser::getNode($saleResponse,'message')) . "\n";
