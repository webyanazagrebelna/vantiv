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

class CreditFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_credit_with_card()
    {
        $hash_in = array(
            'card' => array('type' => 'VI', 'id' => 'id',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_paypal()
    {
        $hash_in = array('id' => 'id',
            'paypal' => array("payerId" => '123', 'payerEmail' => '12321321',
                "transactionId" => '123123'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_simple_credit_with_cnpTxnId()
    {
        $hash_in = array('id' => 'id', 'reportGroup' => 'planets', 'cnpTxnId' => '1234567891234567891');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_paypal_notes()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'payPalNotes' => 'hello',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_secondary_amount()
    {
        $hash_in = array('id' => 'id',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'secondaryAmount' => '1234');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_cnpTxnId_AndSecondaryAmount()
    {
        $hash_in = array('id' => 'id', 'reportGroup' => 'planets', 'cnpTxnId' => '1234567891234567891', 'secondaryAmount' => '100');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_pin()
    {
        $hash_in = array(
            'cnpTxnId' => '12312312',
            'id' => 'id',
            'reportGroup' => 'Planets',
            'amount' => '123',
            'secondaryAmount' => '3214',
            'surchargeAmount' => '1',
            'pin' => '3333'
        );

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $message = XmlParser::getAttribute($creditResponse, 'cnpOnlineResponse', 'response');
        $this->assertEquals("0", $message);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_credit_with_card_with_MerchantCategoryCode()
    {
        $hash_in = array(
            'card' => array('type' => 'VI', 'id' => 'id',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123',
            'merchantCategoryCode' => '4567');

        $initialize = new CnpOnlineRequest();
        $creditResponse = $initialize->creditRequest($hash_in);
        $response = XmlParser::getNode($creditResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($creditResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }


}
