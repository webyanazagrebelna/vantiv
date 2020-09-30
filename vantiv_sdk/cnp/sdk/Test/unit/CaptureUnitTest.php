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
namespace cnp\sdk\Test\unit;
use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;

class CaptureUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_capture()
    {
        $hash_in = array('cnpTxnId'=> '12312312', 'amount'=>'123', 'id' => 'id');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>12312312.*<amount>123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureRequest($hash_in);
    }

    public function test_no_txnid()
    {
        $hash_in =array('reportGroup'=>'Planets','amount'=>'106','id' => 'id');
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $cnpTest->captureRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'cnpTxnId'=> '12312312',
        		'id' => 'id',
                'merchantSdk'=>'PHP;10.1.0',
                'amount'=>'123',
                'loggedInUser'=>'gdake');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureRequest($hash_in);
    }

    public function test_surchargeAmount()
    {
        $hash_in = array(
            'cnpTxnId'=>'3',
        		'id' => 'id',
            'amount'=>'2',
            'surchargeAmount'=>'1',
            'payPalNotes'=>'notes',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
            ->expects($this->once())
            ->method('request')
            ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><surchargeAmount>1<\/surchargeAmount><payPalNotes>notes<\/payPalNotes>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureRequest($hash_in);
    }

    public function test_surchargeAmount_optional()
    {
        $hash_in = array(
                'cnpTxnId'=>'3',
        		'id' => 'id',
                'amount'=>'2',
                'payPalNotes'=>'notes',
        );
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock
        ->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<amount>2<\/amount><payPalNotes>notes<\/payPalNotes>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->captureRequest($hash_in);
    }
  
    public function test_simple_capture_withPin()
    {
    	$hash_in = array('cnpTxnId'=> '12312312', 'amount'=>'123', 'id' => 'id','pin' => '02139');
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<pin>02139.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->captureRequest($hash_in);
    }
    
}
