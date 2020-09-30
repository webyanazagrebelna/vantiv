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

class EcheckSaleFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }


    public function test_echeckSale_with_echeck()
    {
        $hash_in = array('id' => 'id',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $response = XmlParser::getNode($echeckSaleResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($echeckSaleResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_echeckSale_with_echeckToken()
    {
        $hash_in = array('id' => 'id',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeckToken' => array('accType' => 'Checking', 'cnpToken' => '1234565789012', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $response = XmlParser::getNode($echeckSaleResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($echeckSaleResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_echeckSale_missing_amount()
    {
        $hash_in = array('id' => 'id',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeckToken' => array('accType' => 'Checking', 'cnpToken' => '1234565789012', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $message = XmlParser::getAttribute($echeckSaleResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_echeckSale_with_shipto()
    {
        $hash_in = array('id' => 'id',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'),
            'shipToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $response = XmlParser::getNode($echeckSaleResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($echeckSaleResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_echeckSale_secondaryAmount()
    {
        $hash_in = array('amount' => '123456', 'id' => 'id',
            'secondaryAmount' => '2000',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $response = XmlParser::getNode($echeckSaleResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($echeckSaleResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_echeckSale_secondaryAmount_With_CCD()
    {
        $hash_in = array('amount' => '123456', 'id' => 'id',
            'secondaryAmount' => '2000',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455', 'ccdPaymentInformation' => 'ccd'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $response = XmlParser::getNode($echeckSaleResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($echeckSaleResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

    public function test_simple_echeckSale_secondaryAmount_With_CCD_longerthan80()
    {
        $hash_in = array('amount' => '123456', 'id' => 'id',
            'secondaryAmount' => '2000',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455', 'ccdPaymentInformation' => '000000000000000000000000000000000000000000000000000000000000000000000000000000000'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $initialize = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $message = XmlParser::getAttribute($echeckSaleResponse, 'cnpOnlineResponse', 'message');
    }

    public function test_simple_echeckSale_with_merchantData_customIdentifier()
    {
        $hash_in = array('amount' => '123456', 'id' => 'id',
            'secondaryAmount' => '2000',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455', 'ccdPaymentInformation' => 'ccd'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'),
            'customBilling' => array('city' => 'Boston', 'descriptor' => 'descriptor'),
            'merchantData' => array('campaign' => 'camping'),
            'customIdentifier' => 'identifier'
        );

        $initialize = new CnpOnlineRequest();
        $echeckSaleResponse = $initialize->echeckSaleRequest($hash_in);
        $response = XmlParser::getNode($echeckSaleResponse, 'response');
        $this->assertEquals('000', $response);
        $location = XmlParser::getNode($echeckSaleResponse, 'location');
        $this->assertEquals('sandbox', $location);
    }

}
