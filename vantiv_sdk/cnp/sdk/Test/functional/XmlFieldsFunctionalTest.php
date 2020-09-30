<?php
/*
* Copyright (c) 2011 Vantiv eCommerce Inc.
*
* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use,
* copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following
* conditions:
*
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
* OTHER DEALINGS IN THE SOFTWARE.
*/
namespace cnp\sdk\Test\functional;

use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;
use cnp\sdk\XmlParser;

class XmlFieldsFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_card_no_type_or_track()
    {
        $hash_in = array('id' => '1211',
            'merchantId' => '101',
            'version' => '8.8',
            'reportGroup' => 'Planets',

            'cnpTxnId' => '123456',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'card' => array(
                'number' => '4100000000000000',
                'expDate' => '1210',
                'cardValidationNum' => '123'
            ));
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($hash_in);
    }

    public function test_simple_customBilling()
    {
        $hash_in = array('id' => '1211',
            'merchantId' => '101',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'cnpTxnId' => '123456',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'customBilling' => array('phone' => '123456789', 'descriptor' => 'good'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210')
        );

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($hash_in);
        $message = XmlParser::getAttribute($saleResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals('Valid Format', $message);
    }

    public function test_simple_auth_with_cnpTxnId()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'cnpTxnId' => '123456',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'
            ));

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($hash_in);
        $response = XmlParser::getAttribute($saleResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("000", $response);
    }

    public function test_customerInfo()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'cnpTxnId' => '123456',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'CustomerInfo' => array('ssn' => '12345', 'incomeAmount' => '12345', 'incomeCurrency' => 'dollar', 'yearsAtResidence' => '2'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'
            ));

        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($hash_in);
        $message = XmlParser::getAttribute($saleResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_simple_billtoAddress()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_processingInstructions()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'processingInstructions' => array('bypassVelocityCheck' => 'true'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_pos()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'pos' => array('capability' => 'notused', 'entryMode' => 'track1', 'cardholderId' => 'pin'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($hash_in);
        $message = XmlParser::getAttribute($authorizationResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_pos_with_invalid_entryMode()
    {
        $hash_in = array(
            'merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'pos' => array('entryMode' => 'none', 'cardholderId' => 'pin', 'capability' => 'notused'),
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $initialize = new CnpOnlineRequest();
        $saleResponse = $initialize->saleRequest($hash_in);
        $message = XmlParser::getAttribute($saleResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_simple_enhancedData()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'orderSource' => 'ecommerce',
            'enhancedData' => array(
                'customerReference' => 'Litle',
                'salesTax' => '50',
                'deliveryType' => 'TBD',
                'restriction' => 'DIG',
                'shipFromPostalCode' => '01741',
                'destinationPostalCode' => '01742'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_simple_enhancedData_incorrect_enum_for_countryCode()
    {
        $hash_in = array(
            'merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'orderSource' => 'ecommerce',
            'enhancedData' => array(
                'destinationCountryCode' => '001',
                'customerReference' => 'Litle',
                'salesTax' => '50',
                'deliveryType' => 'TBD',
                'shipFromPostalCode' => '01741',
                'destinationPostalCode' => '01742'));

        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_enhancedData_with_detailtax()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'orderSource' => 'ecommerce',
            'enhancedData' => array(
                'detailtax' => array('taxAmount' => '1234', 'tax' => '50'),
                'customerReference' => 'Litle',
                'salesTax' => '50',
                'deliveryType' => 'TBD',
                'restriction' => 'DIG',
                'shipFromPostalCode' => '01741',
                'destinationPostalCode' => '01742'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_enhancedData_with_lineItem()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'LINEITEM',
            'orderId' => '12344',
            'amount' => '106',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'),
            'processingInstructions' => array('bypassVelocityCheck' => 'true'),
            'orderSource' => 'ecommerce',
            'lineItemData' => array(
                'itemSequenceNumber' => '98765',
                'itemDescription' => 'VERYnice',
                'productCode' => '10010100',
                'quantity' => '7',
                'unitOfMeasure' => 'pounds',
                'enhancedData' => array(
                    'detailtax' => array('taxAmount' => '1234', 'tax' => '50')),
                'customerReference' => 'Litle',
                'salesTax' => '50',
                'deliveryType' => 'TBD',
                'restriction' => 'DIG',
                'shipFromPostalCode' => '01741',
                'destinationPostalCode' => '01742'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_simple_token()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'token' => array(
                'cnpToken' => '123456789101112',
                'expDate' => '1210',
                'cardValidationNum' => '555',
                'type' => 'VI'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_token_with_incorrect_token_length()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'token' => array(
                'cnpToken' => '123456',
                'expDate' => '1210',
                'cardValidationNum' => '555',
                'type' => 'VI'));

        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_token_missing_expDat_and_validationNum()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'token' => array(
                'cnpToken' => '123456789101112',
                'type' => 'VI'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_simple_paypage()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'paypage' => array(
                'paypageRegistrationId' => '123456789101112',
                'expDate' => '1210',
                'cardValidationNum' => '555',
                'type' => 'VI'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_paypage_missing_expDate_and_validationNum()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'paypage' => array(
                'paypageRegistrationId' => '123456789101112',
                'type' => 'VI'));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
        $this->assertEquals("Valid Format", $message);
    }

    public function test_simple_token_with_checkout_id()
    {
        $hash_in = array('merchantId' => '101', 'id' => '1211',
            'version' => '8.8',
            'reportGroup' => 'Planets',
            'orderId' => '12344',
            'amount' => '106',
            'orderSource' => 'ecommerce',
            'token' => array(
                'cnpToken' => '123456789101112',
                'expDate' => '1210',
                'cardValidationNum' => '555',
                'type' => 'VI',
                'checkoutId' => '201234567891234567',
            ));

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');


        $this->assertEquals("Valid Format", $message);

      //  $this->assertEquals("801", XmlParser::getAttribute($creditResponse, 'tokenResponse','tokenResponseCode'));

    }

}
