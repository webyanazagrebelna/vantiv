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

class TreeResponseFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_auth()
    {
        $hash_in = array(
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->authorizationRequest($hash_in);
        $this->assertEquals('000', $response->authorizationResponse->response);
    }

    public function test_authReversal()
    {
        $hash_in = array('id' => '1211',
            'cnpTxnId' => '12345678000', 'amount' => '123',
            'payPalNotes' => 'Notes');

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->authReversalRequest($hash_in);
        $this->assertEquals('000', $response->authReversalResponse->response);
    }

    public function test_capture()
    {
        $hash_in = array('id' => '1211',
            'cnpTxnId' => '1234567891234567891',
            'amount' => '123');

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->captureRequest($hash_in);
        $this->assertEquals('000', $response->captureResponse->response);
    }

    public function test_captureGivenAuth()
    {
        $hash_in = array('id' => '1211',
            'orderId' => '12344',
            'amount' => '106',
            'authInformation' => array(
                'authDate' => '2002-10-09', 'authCode' => '543216',
                'authAmount' => '12345'),
            'orderSource' => 'ecommerce',
            'card' => array(
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'));

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->captureGivenAuthRequest($hash_in);
        $this->assertEquals('Approved', $response->captureGivenAuthResponse->message);
    }

    public function test_credit()
    {
        $hash_in = array('id' => '1211',
            'card' => array('type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1213',
                'cardValidationNum' => '1213'),
            'id' => '1211',
            'orderId' => '2111',
            'reportGroup' => 'Planets',
            'orderSource' => 'ecommerce',
            'amount' => '123');

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->creditRequest($hash_in);
        $this->assertEquals('000', $response->creditResponse->response);
    }

    public function test_echeckCredit()
    {
        $hash_in = array('id' => '1211',
            'cnpTxnId' => '123456789012345678',
            'amount' => '1000');

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->echeckCreditRequest($hash_in);
        $this->assertEquals('000', $response->echeckCreditResponse->response);
    }

    public function test_echeckRedepoist()
    {
        $hash_in = array('id' => '1211',
            'cnpTxnId' => '123456789012345678',
            'amount' => '123');

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->echeckRedepositRequest($hash_in);
        $this->assertEquals('000', $response->echeckRedepositResponse->response);
    }

    public function test_echeckSale()
    {
        $hash_in = array('id' => '1211',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->echeckSaleRequest($hash_in);
        $this->assertEquals('000', $response->echeckSalesResponse->response);
    }

    public function test_echeckVerification()
    {
        $hash_in = array('id' => '1211',
            'amount' => '123456',
            'verify' => 'true',
            'orderId' => '12345',
            'orderSource' => 'ecommerce',
            'echeck' => array('accType' => 'Checking', 'accNum' => '12345657890', 'routingNum' => '123456789', 'checkNum' => '123455'),
            'billToAddress' => array('name' => 'Bob', 'city' => 'lowell', 'state' => 'MA', 'email' => 'vantiv.com'));

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->echeckVerificationRequest($hash_in);
        $this->assertEquals('000', $response->echeckVerificationResponse->response);
    }

    public function test_echeckVoid()
    {
        $hash_in = array('cnpTxnId' => '123456789012345678', 'id' => '1211',);
        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->echeckVoidRequest($hash_in);
        $this->assertEquals('000', $response->echeckVoidResponse->response);
    }

    public function test_forceCapture()
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
                'type' => 'VI',
                'number' => '4100000000000000',
                'expDate' => '1210'
            ));

        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->forceCaptureRequest($hash_in);
        $this->assertEquals('000', $response->forceCaptureResponse->response);
    }

    public function test_void()
    {
        $hash_in = array('cnpTxnId' => '123456789012345678', 'id' => '1211',);
        $cnp = new CnpOnlineRequest($treeResponse = true);
        $response = $cnp->voidRequest($hash_in);
        $this->assertEquals('0', $response['response']);
    }

}
