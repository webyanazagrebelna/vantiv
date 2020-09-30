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
namespace cnp\sdk;
require_once realpath(dirname(__FILE__)) . '/CnpOnline.php';
class Obj2xml
{
    public static function toXml($data, $hash_config, $type, $rootNodeName = 'cnpOnlineRequest')
    {
        $config= Obj2xml::getConfig($hash_config, $type);
        $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        $xml-> addAttribute('merchantId',$config["merchantId"]);
        $xml-> addAttribute('version',CURRENT_XML_VERSION);
        $xml-> addAttribute('merchantSdk',$data['merchantSdk']);
        unset($data['merchantSdk']);
        if (isset($data['loggedInUser'])) {
            $xml->addAttribute('loggedInUser',$data["loggedInUser"]);
        };
        unset($data['loggedInUser']);
        $xml-> addAttribute('xmlns:xmlns','http://www.vantivcnp.com/schema');// does not show up on browser docs
        $authentication = $xml->addChild('authentication');
        $authentication->addChild('user',$config["user"]);
        $authentication->addChild('password',$config["password"]);



        $transacType = $xml->addChild($type);

        if (isset($data['partial'])) {
            $transacType-> addAttribute('partial',$data["partial"]);
        };
        unset($data['partial']);
        if (isset($data['customerId'])) {
            $transacType-> addAttribute('customerId',$data["customerId"]);
        };
        unset($data['customerId']);
        if (isset($config['reportGroup'])) {
            $transacType-> addAttribute('reportGroup',$config["reportGroup"]);
        };
        if (isset($data['id'])) {
            if ($data['id'] === "REQUIRED") {
                throw new \InvalidArgumentException("Missing Required Field: id");
            }
            else {
                $transacType-> addAttribute('id',$data["id"]);
            }
        };
        unset($data['id']);

        Obj2xml::iterateChildren($data,$transacType);
        
        return $xml->asXML();
    }

    public static function transactionShouldHaveReportGroup($transactionType)
    {
        $transactionsThatDontHaveReportGroup = array(
            'updateSubscription',
            'cancelSubscription',
            'createPlan',
            'updatePlan'
        );

        return (FALSE === array_search($transactionType, $transactionsThatDontHaveReportGroup));
    }

    public static function transactionToXml($data, $type, $report_group)
    {
        $transac = simplexml_load_string("<$type />");
        if (Obj2xml::transactionShouldHaveReportGroup($type)) {
            $transac->addAttribute('reportGroup', $report_group);
            if (isset($data['id'])) {
                if ($data['id'] === "REQUIRED") {
                    throw new \InvalidArgumentException("Missing Required Field: id");
                }
                else {
                    $transac-> addAttribute('id',$data["id"]);
                }
            };
            unset($data['id']);
        }

        Obj2xml::iterateChildren($data,$transac);

        return str_replace("<?xml version=\"1.0\"?>\n", "", $transac->asXML());
    }

    public static function rfrRequestToXml($hash_in)
    {
        $rfr = simplexml_load_string("<RFRRequest />");
        if (isset($hash_in['cnpSessionId'])) {
            $rfr->addChild('cnpSessionId', $hash_in['cnpSessionId']);
        } elseif (isset($hash_in['merchantId']) && isset($hash_in['postDay'])) {
            $auFileRequest = $rfr->addChild('accountUpdateFileRequestData');
            $auFileRequest->addChild('merchantId', $hash_in['merchantId']);
            $auFileRequest->addChild('postDay', $hash_in['postDay']);
        } else {
            throw new \RuntimeException('To add an RFR Request, either a cnpSessionId or a merchantId and a postDay must be set.');
        }

        return str_replace("<?xml version=\"1.0\"?>\n", "", $rfr->asXML());
    }

    public static function generateBatchHeader($counts_and_amounts)
    {
        $config= Obj2xml::getConfig(array());

        $xml = simplexml_load_string("<batchRequest />");
        $xml->addAttribute('merchantId', $config['merchantId']);
        $xml->addAttribute('merchantSdk', CURRENT_SDK_VERSION);

        $xml->addAttribute('authAmount', $counts_and_amounts['auth']['amount']);
        $xml->addAttribute('numAuths', $counts_and_amounts['auth']['count']);

        $xml->addAttribute('saleAmount', $counts_and_amounts['sale']['amount']);
        $xml->addAttribute('numSales', $counts_and_amounts['sale']['count']);

        $xml->addAttribute('creditAmount', $counts_and_amounts['credit']['amount']);
        $xml->addAttribute('numCredits', $counts_and_amounts['credit']['count']);

        $xml->addAttribute('giftCardCreditAmount', $counts_and_amounts['giftCardCredit']['amount']);
        $xml->addAttribute('numGiftCardCredits', $counts_and_amounts['giftCardCredit']['count']);

        $xml->addAttribute('numTokenRegistrations', $counts_and_amounts['tokenRegistration']['count']);

        $xml->addAttribute('numTranslateToLowValueTokenRequests', $counts_and_amounts['translateToLowValueTokenRequest']['count']);

        $xml->addAttribute('captureGivenAuthAmount', $counts_and_amounts['captureGivenAuth']['amount']);
        $xml->addAttribute('numCaptureGivenAuths', $counts_and_amounts['captureGivenAuth']['count']);

        $xml->addAttribute('forceCaptureAmount', $counts_and_amounts['forceCapture']['amount']);
        $xml->addAttribute('numForceCaptures', $counts_and_amounts['forceCapture']['count']);

        $xml->addAttribute('authReversalAmount', $counts_and_amounts['authReversal']['amount']);
        $xml->addAttribute('numAuthReversals', $counts_and_amounts['authReversal']['count']);

        $xml->addAttribute('giftCardAuthReversalOriginalAmount', $counts_and_amounts['giftCardAuthReversal']['amount']);
        $xml->addAttribute('numGiftCardAuthReversals', $counts_and_amounts['giftCardAuthReversal']['count']);

        $xml->addAttribute('captureAmount', $counts_and_amounts['capture']['amount']);
        $xml->addAttribute('numCaptures', $counts_and_amounts['capture']['count']);

        $xml->addAttribute('giftCardCaptureAmount', $counts_and_amounts['giftCardCapture']['amount']);
        $xml->addAttribute('numGiftCardCaptures', $counts_and_amounts['giftCardCapture']['count']);

        $xml->addAttribute('echeckVerificationAmount', $counts_and_amounts['echeckVerification']['amount']);
        $xml->addAttribute('numEcheckVerification', $counts_and_amounts['echeckVerification']['count']);

        $xml->addAttribute('echeckCreditAmount', $counts_and_amounts['echeckCredit']['amount']);
        $xml->addAttribute('numEcheckCredit', $counts_and_amounts['echeckCredit']['count']);

        $xml->addAttribute('numEcheckRedeposit', $counts_and_amounts['echeckRedeposit']['count']);

        $xml->addAttribute('echeckSalesAmount', $counts_and_amounts['echeckSale']['amount']);
        $xml->addAttribute('numEcheckSales', $counts_and_amounts['echeckSale']['count']);

        $xml->addAttribute('numUpdateCardValidationNumOnTokens', $counts_and_amounts['updateCardValidationNumOnToken']['count']);

        $xml->addAttribute('numUpdateSubscriptions', $counts_and_amounts['updateSubscription']['count']);

        $xml->addAttribute('numCancelSubscriptions', $counts_and_amounts['cancelSubscription']['count']);

        $xml->addAttribute('numCreatePlans', $counts_and_amounts['createPlan']['count']);
        $xml->addAttribute('numUpdatePlans', $counts_and_amounts['updatePlan']['count']);

        $xml->addAttribute('numActivates', $counts_and_amounts['activate']['count']);
        $xml->addAttribute('activateAmount', $counts_and_amounts['activate']['amount']);
        $xml->addAttribute('numDeactivates', $counts_and_amounts['deactivate']['count']);
        $xml->addAttribute('numLoads', $counts_and_amounts['load']['count']);
        $xml->addAttribute('loadAmount', $counts_and_amounts['load']['amount']);
        $xml->addAttribute('numUnloads', $counts_and_amounts['unload']['count']);
        $xml->addAttribute('unloadAmount', $counts_and_amounts['unload']['amount']);
        $xml->addAttribute('numBalanceInquirys', $counts_and_amounts['balanceInquiry']['count']);

        $xml->addAttribute('numAccountUpdates', $counts_and_amounts['accountUpdate']['count']);

        $xml->addAttribute('numEcheckPreNoteSale', $counts_and_amounts['echeckPreNoteSale']['count']);
        $xml->addAttribute('numEcheckPreNoteCredit', $counts_and_amounts['echeckPreNoteCredit']['count']);

        $xml->addAttribute('submerchantCreditAmount', $counts_and_amounts['submerchantCredit']['amount']);
        $xml->addAttribute('numSubmerchantCredit', $counts_and_amounts['submerchantCredit']['count']);
        $xml->addAttribute('payFacCreditAmount', $counts_and_amounts['payFacCredit']['amount']);
        $xml->addAttribute('numPayFacCredit', $counts_and_amounts['payFacCredit']['count']);
        $xml->addAttribute('payoutOrgCreditAmount', $counts_and_amounts['payoutOrgCredit']['amount']);
        $xml->addAttribute('numPayoutOrgCredit', $counts_and_amounts['payoutOrgCredit']['count']);
        $xml->addAttribute('reserveCreditAmount', $counts_and_amounts['reserveCredit']['amount']);
        $xml->addAttribute('numReserveCredit', $counts_and_amounts['reserveCredit']['count']);
        $xml->addAttribute('vendorCreditAmount', $counts_and_amounts['vendorCredit']['amount']);
        $xml->addAttribute('numVendorCredit', $counts_and_amounts['vendorCredit']['count']);
        $xml->addAttribute('customerCreditAmount', $counts_and_amounts['customerCredit']['amount']);
        $xml->addAttribute('numCustomerCredit', $counts_and_amounts['customerCredit']['count']);
        $xml->addAttribute('physicalCheckCreditAmount', $counts_and_amounts['physicalCheckCredit']['amount']);
        $xml->addAttribute('numPhysicalCheckCredit', $counts_and_amounts['physicalCheckCredit']['count']);
        $xml->addAttribute('submerchantDebitAmount', $counts_and_amounts['submerchantDebit']['amount']);
        $xml->addAttribute('numSubmerchantDebit', $counts_and_amounts['submerchantDebit']['count']);
        $xml->addAttribute('payFacDebitAmount', $counts_and_amounts['payFacDebit']['amount']);
        $xml->addAttribute('numPayFacDebit', $counts_and_amounts['payFacDebit']['count']);
        $xml->addAttribute('payoutOrgDebitAmount', $counts_and_amounts['payoutOrgDebit']['amount']);
        $xml->addAttribute('numPayoutOrgDebit', $counts_and_amounts['payoutOrgDebit']['count']);
        $xml->addAttribute('reserveDebitAmount', $counts_and_amounts['reserveDebit']['amount']);
        $xml->addAttribute('numReserveDebit', $counts_and_amounts['reserveDebit']['count']);
        $xml->addAttribute('vendorDebitAmount', $counts_and_amounts['vendorDebit']['amount']);
        $xml->addAttribute('numVendorDebit', $counts_and_amounts['vendorDebit']['count']);
        $xml->addAttribute('customerDebitAmount', $counts_and_amounts['customerDebit']['amount']);
        $xml->addAttribute('numCustomerDebit', $counts_and_amounts['customerDebit']['count']);
        $xml->addAttribute('physicalCheckDebitAmount', $counts_and_amounts['physicalCheckDebit']['amount']);
        $xml->addAttribute('numPhysicalCheckDebit', $counts_and_amounts['physicalCheckDebit']['count']);
        $xml->addAttribute('numFundingInstructionVoid', $counts_and_amounts['fundingInstructionVoid']['count']);
        $xml->addAttribute('numFastAccessFunding', $counts_and_amounts['fastAccessFunding']['count']);
        $xml->addAttribute('fastAccessFundingAmount', $counts_and_amounts['fastAccessFunding']['amount']);

        return str_replace("/>", ">", str_replace("<?xml version=\"1.0\"?>\n", "", $xml->asXML()));
    }

    public static function generateRequestHeader($config, $num_batch_requests)
    {
        $xml = simplexml_load_string("<cnpRequest />");

        $xml->addAttribute('numBatchRequests', $num_batch_requests);
        $xml->addAttribute('version', CURRENT_XML_VERSION);
        $xml->addAttribute('xmlns:xmlns','http://www.vantivcnp.com/schema');
        $authentication = $xml->addChild('authentication');
        $authentication->addChild('user',$config["user"]);
        $authentication->addChild('password',$config["password"]);

        return str_replace("<?xml version=\"1.0\"?>\n", "", str_replace("</cnpRequest>", "", $xml->asXML()));
    }

    private static function iterateChildren($data,$transacType)
    {

        foreach ($data as $key => $value) {
            //print $key . " " . $value . "\n";
            if ($value === "REQUIRED") {
                throw new \InvalidArgumentException("Missing Required Field: /$key/");
            } elseif (mb_substr($key, 0, 12) === 'lineItemData') {
                $temp_node = $transacType->addChild('lineItemData');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,-1) == 'detailTax') {
                $temp_node = $transacType->addChild('detailTax');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,14) == 'createDiscount' and $value != null) {
                $temp_node = $transacType->addChild('createDiscount');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,14) == 'updateDiscount') {
                $temp_node = $transacType->addChild('updateDiscount');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,14) == 'deleteDiscount') {
                $temp_node = $transacType->addChild('deleteDiscount');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,11) == 'createAddOn' and $value != null) {
                $temp_node = $transacType->addChild('createAddOn');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,11) == 'updateAddOn') {
                $temp_node = $transacType->addChild('updateAddOn');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,11) == 'deleteAddOn') {
                $temp_node = $transacType->addChild('deleteAddOn');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,-1) == 'lodgingCharge') {
                $temp_node = $transacType->addChild('lodgingCharge');
                Obj2xml::iterateChildren($value,$temp_node);
            } elseif (mb_substr($key,0,16) == 'debitNetworkName') {
                //$temp_node = $transacType->addChild('debitNetworkName');
                $transacType->addChild('debitNetworkName',str_replace('&','&amp;',$value));
            } elseif (((is_string($value)) || is_numeric($value))) {
                $transacType->addChild($key,str_replace('&','&amp;',$value));
            } elseif (mb_substr($key,0,22) == 'ctxPaymentInformation') {
                $temp_node = $transacType->addChild('ctxPaymentInformation');
                foreach ($value as $v){
                    $temp_node->addChild('ctxPaymentDetail',$v);
                }
            } elseif (is_array($value)) {
                $node = $transacType->addChild($key);
                Obj2xml::iterateChildren($value,$node);
            }
        }
    }

    public static function getConfig($data, $type=NULL)
    {
        $config_array = null;

        $ini_file = realpath(dirname(__FILE__)) . '/cnp_SDK_config.ini';
        if (file_exists($ini_file)) {
            @$config_array =parse_ini_file('cnp_SDK_config.ini');
        }

        if (empty($config_array)) {
            $config_array = array();
        }

        $names = explode(',', CNP_CONFIG_LIST);
        foreach ($names as $name) {
            if (isset($data[$name])) {
                $config[$name] = $data[$name];

            } else {
                if ($name == 'merchantId') {
                    $config['merchantId'] = $config_array['currency_merchant_map']['DEFAULT'];
                } elseif ($name == 'version') {
                    $config['version'] = isset($config_array['version'])? $config_array['version']:CURRENT_XML_VERSION;
                } elseif ($name == 'timeout') {
                    $config['timeout'] = isset($config_array['timeout'])? $config_array['timeout']:'65';
                } elseif ($name == 'sftp_timeout') {
                    $config['sftp_timeout'] = isset($config_array['sftp_timeout'])? $config_array['sftp_timeout']:'720';
                } else {
                    if ((!isset($config_array[$name])) and ($name != 'proxy')) {
                        throw new \InvalidArgumentException("Missing Field /$name/");
                    }
                    $config[$name] = $config_array[$name];
                }
            }
        }
        if ($type == 'updateSubscription' || $type == 'cancelSubscription' || $type == 'createPlan' || $type == 'updatePlan') {
            if (array_key_exists('reportGroup',$config)) {
                unset($config['reportGroup']);
                $config = array_filter($config);
            }
        }

        return $config;
    }
}