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

class FraudCheckUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_no_customAttributes()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128)
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>128<\/threatMetrixSessionId><\/advancedFraudChecks>.*/'));
		
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_one_customAttributes()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128,
						'customAttribute1' => 'abc')
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>128<\/threatMetrixSessionId><customAttribute1>abc<\/customAttribute1><\/advancedFraudChecks>.*/'));
	
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_two_customAttributes()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128,
						'customAttribute1' => 'abc',
						'customAttribute2' => 'def')
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>128<\/threatMetrixSessionId><customAttribute1>abc<\/customAttribute1><customAttribute2>def<\/customAttribute2><\/advancedFraudChecks>.*/'));
	
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_three_customAttributes()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128,
						'customAttribute1' => 'abc',
						'customAttribute2' => 'def',
						'customAttribute3' => 'ghi')
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>128<\/threatMetrixSessionId><customAttribute1>abc<\/customAttribute1><customAttribute2>def<\/customAttribute2><customAttribute3>ghi<\/customAttribute3><\/advancedFraudChecks>.*/'));
	
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_four_customAttributes()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128,
						'customAttribute1' => 'abc',
						'customAttribute2' => 'def',
						'customAttribute3' => 'ghi',
						'customAttribute4' => 'jkl')
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>128<\/threatMetrixSessionId><customAttribute1>abc<\/customAttribute1><customAttribute2>def<\/customAttribute2><customAttribute3>ghi<\/customAttribute3><customAttribute4>jkl<\/customAttribute4><\/advancedFraudChecks>.*/'));
	
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_five_customAttributes()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128,
						'customAttribute1' => 'abc',
						'customAttribute2' => 'def',
						'customAttribute3' => 'ghi',
						'customAttribute4' => 'jkl',
						'customAttribute5' => 'mno')
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<advancedFraudChecks><threatMetrixSessionId>128<\/threatMetrixSessionId><customAttribute1>abc<\/customAttribute1><customAttribute2>def<\/customAttribute2><customAttribute3>ghi<\/customAttribute3><customAttribute4>jkl<\/customAttribute4><customAttribute5>mno<\/customAttribute5><\/advancedFraudChecks>.*/'));
	
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_amount()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128),
				'amount' => 100
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<amount>100<\/amount>.*/'));
		
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_billToAddress()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128),
				'billToAddress' => array(
						'firstName' => 'Fetty',
						'lastName' => 'Wap',
						'addressLine1' => '1738 Trap Street',
						'city' => 'Queens',
						'state' => 'New York',
						'zip' => '11412'
				),
				'shipToAddress' => array(
						'firstName' => 'Johnny',
						'lastName' => 'Appleseed',
						'addressLine1' => "16 Maple Way",
						'city' => 'Orchard',
						'state' => 'California',
						'zip' => '13579'
				)
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<billToAddress><firstName>Fetty<\/firstName><lastName>Wap<\/lastName><addressLine1>1738 Trap Street<\/addressLine1><city>Queens<\/city><state>New York<\/state><zip>11412<\/zip><\/billToAddress>.*/'));
		
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
	
	public function test_shipToAddress()
	{
		$hash_in = array(
				'id' => 'id',
				'advancedFraudChecks' => array(
						'threatMetrixSessionId' => 128),
				'shipToAddress' => array(
						'firstName' => 'Johnny',
						'lastName' => 'Appleseed',
						'addressLine1' => "16 Maple Way",
						'city' => 'Orchard',
						'state' => 'California',
						'zip' => '13579'
				)
		);
		$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
		$mock	->expects($this->once())
		->method('request')
		->with($this->matchesRegularExpression('/.*<shipToAddress><firstName>Johnny<\/firstName><lastName>Appleseed<\/lastName><addressLine1>16 Maple Way<\/addressLine1><city>Orchard<\/city><state>California<\/state><zip>13579<\/zip><\/shipToAddress>.*/'));
	
		$cnpTest = new CnpOnlineRequest();
		$cnpTest->newXML = $mock;
		$cnpTest->fraudCheck($hash_in);
	}
}