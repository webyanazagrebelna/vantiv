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

class QueryTransactionFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function testSimpleQueryTransaction()
    {
        $hash_in = array(
            'id' => 'id',
            'origId' => '2111',
            'origActionType' => 'A');

        $initialize = new CnpOnlineRequest();
        $queryTransactionResponse = $initialize->queryTransaction($hash_in);
        $response = XmlParser::getNodeWithChildren($queryTransactionResponse, 'response');
        $this->assertEquals('150', $response->nodeValue);
        $matchCount = XmlParser::getNode($queryTransactionResponse, 'matchCount');
        $this->assertEquals('1', $matchCount);
        $location = XmlParser::getNode($queryTransactionResponse, 'location');
        $this->assertEquals('sandbox', $location);
        $resultsMax10 = XmlParser::getNodeWithChildren($queryTransactionResponse, 'results_max10');
        foreach ($resultsMax10->getElementsByTagName('authorizationResponse') as $child) {
            $childResponse = XmlParser::getNode($child, 'response');
            $childMessage = XmlParser::getNode($child, 'message');
            $childOrderId = XmlParser::getNode($child, 'orderId');
            $this->assertEquals('000', $childResponse);
            $this->assertEquals('Approved', $childMessage);
            $this->assertEquals('GenericOrderId', $childOrderId);
        }
    }


    public function testSimpleQueryTransaction_with_showStatusOnly()
    {
        $hash_in = array(
            'id' => 'id',
            'origId' => '2111',
            'origActionType' => 'A',
            'origCnpTxnId' => '1234',
            'showStatusOnly' => 'Y'
        );

        $initialize = new CnpOnlineRequest();
        $queryTransactionResponse = $initialize->queryTransaction($hash_in);
        $response = XmlParser::getNodeWithChildren($queryTransactionResponse, 'response');
        $this->assertEquals('150', $response->nodeValue);
        $matchCount = XmlParser::getNode($queryTransactionResponse, 'matchCount');
        $this->assertEquals('1', $matchCount);
        $location = XmlParser::getNode($queryTransactionResponse, 'location');
        $this->assertEquals('sandbox', $location);
        $resultsMax10 = XmlParser::getNodeWithChildren($queryTransactionResponse, 'results_max10');
        foreach ($resultsMax10->getElementsByTagName('authorizationResponse') as $child) {
            $childResponse = XmlParser::getNode($child, 'response');
            $childMessage = XmlParser::getNode($child, 'message');
            $childOrderId = XmlParser::getNode($child, 'orderId');
            $this->assertEquals('000', $childResponse);
            $this->assertEquals('Approved', $childMessage);
            $this->assertEquals('GenericOrderId', $childOrderId);
        }
    }

    public function testSimpleQueryTransaction_responseUnavailable()
    {
        $hash_in = array(
            'id' => 'id',
            'origId' => 'ABC',
            'origActionType' => 'A');

        $initialize = new CnpOnlineRequest();
        $queryTransactionResponse = $initialize->queryTransaction($hash_in);
        $response = XmlParser::getNode($queryTransactionResponse, 'response');
        $message = XmlParser::getNode($queryTransactionResponse, 'message');
        $this->assertEquals('152', $response);
        $this->assertEquals('Original transaction found but response not yet available', $message);
    }

    public function testSimpleQueryTransaction_multipleFound()
    {
        $hash_in = array(
            'id' => 'id',
            'origId' => '2112',
            'origActionType' => 'A');

        $initialize = new CnpOnlineRequest();
        $queryTransactionResponse = $initialize->queryTransaction($hash_in);
        $response = XmlParser::getNodeWithChildren($queryTransactionResponse, 'response');
        $matchCount = XmlParser::getNode($queryTransactionResponse, 'matchCount');
        $this->assertEquals('150', $response->nodeValue);
        $this->assertEquals('2', $matchCount);
        $location = XmlParser::getNode($queryTransactionResponse, 'location');
        $this->assertEquals('sandbox', $location);
        $resultsMax10 = XmlParser::getNodeWithChildren($queryTransactionResponse, 'results_max10');
        foreach ($resultsMax10->getElementsByTagName('authorizationResponse') as $child) {
            $childResponse = XmlParser::getNode($child, 'response');
            $childMessage = XmlParser::getNode($child, 'message');
            $childOrderId = XmlParser::getNode($child, 'orderId');
            $this->assertEquals('000', $childResponse);
            $this->assertEquals('Approved', $childMessage);
            $this->assertEquals('GenericOrderId', $childOrderId);
        }
    }

    public function testSimpleQueryTransaction_notFound()
    {
        $hash_in = array(
            'id' => 'id',
            'origId' => 'ABCD0',
            'origActionType' => 'A');

        $initialize = new CnpOnlineRequest();
        $queryTransactionResponse = $initialize->queryTransaction($hash_in);
        $response = XmlParser::getNode($queryTransactionResponse, 'response');
        $message = XmlParser::getNode($queryTransactionResponse, 'message');
        $this->assertEquals('151', $response);
        $this->assertEquals('Original transaction not found', $message);
    }


}
