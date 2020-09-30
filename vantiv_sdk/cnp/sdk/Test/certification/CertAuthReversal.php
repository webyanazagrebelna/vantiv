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

namespace cnp\sdk\Test\certification;

use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;
USE cnp\sdk\XmlParser;

define('PRELIVE_URL', 'https://payments.vantivprelive.com/vap/communicator/online');

class CertAuthReversal extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_32()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '32',
            'amount' => '10010',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'John Smith',
                'addressLine1' => '1 Main St.',
                'city' => 'Burlington',
                'state' => 'MA',
                'zip' => '01803-3747',
                'country' => 'US'),
            'card' => array(
                'number' => '4457010000000009',
                'expDate' => '0112',
                'cardValidationNum' => '349',
                'type' => 'VI'),
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('11111 ', XmlParser::getNode($authorizationResponse, 'authCode'));
        $this->assertEquals('01', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 32A
        $capture_hash = array(
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'id' => '1211',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));
        //test32B
        $authReversal_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'amount' => '5005',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($authReversal_hash);
        $this->assertEquals('000', XmlParser::getNode($authReversalResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authReversalResponse, 'message'));
    }

    public function test_33()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '33',
            'amount' => '20020',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Mike J. Hammer',
                'addressLine1' => '2 Main St.',
                'addressLine2' => 'Apt. 222',
                'city' => 'Riverside',
                'state' => 'RI',
                'zip' => '02915',
                'country' => 'US'),
            'card' => array(
                'number' => '5112010000000003',
                'expDate' => '0212',
                'cardValidationNum' => '261',
                'type' => 'MC'),
            //TODO 3-D Secure transaction not supported by merchant
            'cardholderAuthentication' => array('authenticationValue' => 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='),
            'url' => PRELIVE_URL, 'proxy'=>''
        );
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('22222', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 33A
        $authReversal_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($authReversal_hash);
        $this->assertEquals('000', XmlParser::getNode($authReversalResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authReversalResponse, 'message'));
    }

    public function test_34()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '34',
            'amount' => '30030',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Eileen Jones',
                'addressLine1' => '3 Main St.',
                'city' => 'Bloomfield',
                'state' => 'CT',
                'zip' => '06002',
                'country' => 'US'),
            'card' => array(
                'number' => '6011010000000003',
                'expDate' => '0312',
                'cardValidationNum' => '758',
                'type' => 'DI'),
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('33333', trim(XmlParser::getNode($authorizationResponse, 'authCode')));
        $this->assertEquals('10', XmlParser::getNode($authorizationResponse, 'avsResult'));
        $this->assertEquals('M', XmlParser::getNode($authorizationResponse, 'cardValidationResult'));

        //test 34A
        $authReversal_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($authReversal_hash);
        $this->assertEquals('000', XmlParser::getNode($authReversalResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authReversalResponse, 'message'));
    }

    public function test_35()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '35',
            'amount' => '40040',
            'orderSource' => 'ecommerce',
            'billToAddress' => array(
                'name' => 'Bob Black',
                'addressLine1' => '4 Main St.',
                'city' => 'Laurel',
                'state' => 'MD',
                'zip' => '20708',
                'country' => 'US'),
            'card' => array(
                'number' => '375001000000005',
                'expDate' => '0412',
                'type' => 'AX'),
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));
        $this->assertEquals('44444', XmlParser::getNode($authorizationResponse, 'authCode'));
        $this->assertEquals('12', XmlParser::getNode($authorizationResponse, 'avsResult'));

        //test 35A
        $capture_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'amount' => '20020',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $captureResponse = $initialize->captureRequest($capture_hash);
        $this->assertEquals('000', XmlParser::getNode($captureResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($captureResponse, 'message'));
        //test35B
        $authReversal_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'amount' => '20020',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($authReversal_hash);
        $this->assertEquals('000', XmlParser::getNode($authReversalResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authReversalResponse, 'message'));
    }

    public function test_36()
    {
        $auth_hash = array('id' => '1211',
            'orderId' => '36',
            'amount' => '20500',
            'orderSource' => 'ecommerce',
            'card' => array(
                'number' => '375000026600004',
                'expDate' => '0512',
                'type' => 'AX'),
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authorizationResponse = $initialize->authorizationRequest($auth_hash);
        $this->assertEquals('000', XmlParser::getNode($authorizationResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authorizationResponse, 'message'));

        //test 33A
        $authReversal_hash = array('id' => '1211',
            'cnpTxnId' => (XmlParser::getNode($authorizationResponse, 'cnpTxnId')),
            'reportGroup' => 'planets', 'amount' => '10000',
            'url' => PRELIVE_URL, 'proxy'=>'');
        $initialize = new CnpOnlineRequest();
        $authReversalResponse = $initialize->authReversalRequest($authReversal_hash);
        $this->assertEquals('000', XmlParser::getNode($authReversalResponse, 'response'));
        $this->assertEquals('Approved', XmlParser::getNode($authReversalResponse, 'message'));
    }
}
