<?php

namespace cnp\sdk;

class BatchRequest
{
    private $counts_and_amounts;
    public $total_txns = 0;
    public $closed = false;

    // file name which holds the transaction markups during the batch process
    public $transaction_file;
    public $batch_file;

    public function isFull()
    {
        return $this->total_txns >= MAX_TXNS_PER_BATCH;
    }

    public function __construct($request_dir = NULL)
    {
        // initialize the counts and amounts
        $this->counts_and_amounts = array(
            'auth' => array(
                'count' => 0,
                'amount' => 0
            ),
            'sale' => array(
                'count' => 0,
                'amount' => 0
            ),
            'credit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'giftCardCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'tokenRegistration' => array(
                'count' => 0
            ),
            'captureGivenAuth' => array(
                'count' => 0,
                'amount' => 0
            ),
            'forceCapture' => array(
                'count' => 0,
                'amount' => 0
            ),
            'authReversal' => array(
                'count' => 0,
                'amount' => 0
            ),
            'giftCardAuthReversal' => array(
                'count' => 0,
                'amount' => 0
            ),
            'translateToLowValueTokenRequest' => array(
                'count' => 0
            ),
            'capture' => array(
                'count' => 0,
                'amount' => 0
            ),
            'giftCardCapture' => array(
                'count' => 0,
                'amount' => 0
            ),
            'echeckVerification' => array(
                'count' => 0,
                'amount' => 0
            ),
            'echeckCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'echeckRedeposit' => array(
                'count' => 0
            ),
            'echeckSale' => array(
                'count' => 0,
                'amount' => 0
            ),
            'updateCardValidationNumOnToken' => array(
                'count' => 0
            ),
            'updateSubscription' => array(
                'count' => 0
            ),
            'cancelSubscription' => array(
                'count' => 0
            ),
            'createPlan' => array(
                'count' => 0
            ),
            'updatePlan' => array(
                'count' => 0
            ),
            'activate' => array(
                'count' => 0,
                'amount' => 0
            ),
            'deactivate' => array(
                'count' => 0,
                'amount' => 0
            ),
            'load' => array(
                'count' => 0,
                'amount' => 0
            ),
            'unload' => array(
                'count' => 0,
                'amount' => 0
            ),
            'balanceInquiry' => array(
                'count' => 0,
                'amount' => 0
            ),
            'accountUpdate' => array(
                'count' => 0
            ),
            'echeckPreNoteSale' => array(
                'count' => 0
            ),
            'echeckPreNoteCredit' => array(
                'count' => 0
            ),
            'submerchantCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'payFacCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'payoutOrgCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'reserveCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'vendorCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'customerCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'physicalCheckCredit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'submerchantDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'payFacDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'payoutOrgDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'reserveDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'vendorDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'customerDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'physicalCheckDebit' => array(
                'count' => 0,
                'amount' => 0
            ),
            'fundingInstructionVoid' => array(
                'count' => 0,
                'amount' => 0
            ),
            'fastAccessFunding' => array(
                'count' => 0,
                'amount' => 0
            )
        );

        // if a dir to place the request file is not explicitly provided, grab it from the config file
        if (!$request_dir) {
            $conf = Obj2xml::getConfig(array());
            $request_dir = $conf ['batch_requests_path'];
        }

        if (mb_substr($request_dir, -1, 1) != DIRECTORY_SEPARATOR) {
            $request_dir = $request_dir . DIRECTORY_SEPARATOR;
        }

        $ts = str_replace(" ", "", mb_substr(microtime(), 2));
        $filename = $request_dir . "batch_" . $ts . "_txns";
        $batch_filename = $request_dir . "batch_" . $ts;

        if (!is_dir($request_dir)) {
            mkdir($request_dir);
        }
        // if either file already exists, let's try again!
        if (file_exists($filename) || file_exists($batch_filename)) {
            $this->__construct();
        }

        // if we were unable to write the file
        if (file_put_contents($filename, "") === FALSE) {
            throw new \RuntimeException ("A batch file could not be written at $filename. Please check your privilege.");
        }
        $this->transaction_file = $filename;

        // if we were unable to write the file
        if (file_put_contents($batch_filename, "") === FALSE) {
            throw new \RuntimeException ("A batch file could not be written at $batch_filename. Please check your privilege.");
        }
        $this->batch_file = $batch_filename;
    }

    /*
     * Extracts the appropriate values from the hash in and passes them along to the addTransaction function
     */
    public function addSale($hash_in)
    {
        $hash_out = Transactions::createSaleHash($hash_in);

        $choice_hash = array(
            $hash_out ['card'],
            $hash_out ['paypal'],
            $hash_out ['token'],
            $hash_out ['paypage']
        );
        $choice2_hash = array(
            $hash_out ['fraudCheck'],
            $hash_out ['cardholderAuthentication']
        );

        $this->addTransaction($hash_out, $hash_in, 'sale', $choice_hash, $choice2_hash);
        $this->counts_and_amounts ['sale'] ['count'] += 1;
        $this->counts_and_amounts ['sale'] ['amount'] += $hash_out ['amount'];
    }

    public function addAuth($hash_in)
    {
        $hash_out = Transactions::createAuthHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'authorization');
        $this->counts_and_amounts ['auth'] ['count'] += 1;
        $this->counts_and_amounts ['auth'] ['amount'] += $hash_out ['amount'];
    }

    public function addAuthReversal($hash_in)
    {
        $hash_out = Transactions::createAuthReversalHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, 'authReversal');
        $this->counts_and_amounts ['authReversal'] ['count'] += 1;
        $this->counts_and_amounts ['authReversal'] ['amount'] += $hash_out ['amount'];
    }

    public function addGiftCardAuthReversal($hash_in)
    {
        $hash_out = Transactions::createGiftCardAuthReversalHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, 'giftCardAuthReversal');
        $this->counts_and_amounts ['giftCardAuthReversal'] ['count'] += 1;
        $this->counts_and_amounts ['giftCardAuthReversal'] ['amount'] += $hash_out ['originalAmount'];
    }

    public function addCredit($hash_in)
    {
        $hash_out = Transactions::createCreditHash($hash_in);

        $choice_hash = array(
            $hash_out ['card'],
            $hash_out ['paypal'],
            $hash_out ['token'],
            $hash_out ['paypage']
        );

        $this->addTransaction($hash_out, $hash_in, 'credit', $choice_hash);
        $this->counts_and_amounts ['credit'] ['count'] += 1;
        $this->counts_and_amounts ['credit'] ['amount'] += $hash_out ['amount'];
    }

    public function addGiftCardCredit($hash_in)
    {
        $hash_out = Transactions::createGiftCardCreditHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, 'giftCardCredit');
        $this->counts_and_amounts ['giftCardCredit'] ['count'] += 1;
        $this->counts_and_amounts ['giftCardCredit'] ['amount'] += $hash_out ['creditAmount'];
    }

    public function addRegisterToken($hash_in)
    {
        $hash_out = Transactions::createRegisterTokenHash($hash_in);

        $choice_hash = array(
            $hash_out ['accountNumber'],
            $hash_out ['echeckForToken'],
            $hash_out ['paypageRegistrationId']
        );

        $this->addTransaction($hash_out, $hash_in, 'registerTokenRequest', $choice_hash);
        $this->counts_and_amounts ['tokenRegistration'] ['count'] += 1;
    }

    public function addForceCapture($hash_in)
    {
        $hash_out = Transactions::createForceCaptureHash($hash_in);

        $choice_hash = array(
            XmlFields::returnArrayValue($hash_out, 'card'),
            XmlFields::returnArrayValue($hash_out, 'paypal'),
            XmlFields::returnArrayValue($hash_out, 'token'),
            XmlFields::returnArrayValue($hash_out, 'paypage')
        );

        $this->addTransaction($hash_out, $hash_in, 'forceCapture', $choice_hash);
        $this->counts_and_amounts ['forceCapture'] ['count'] += 1;
        $this->counts_and_amounts ['forceCapture'] ['amount'] += $hash_out ['amount'];
    }

    public function addCapture($hash_in)
    {
        $hash_out = Transactions::createCaptureHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, 'capture');
        $this->counts_and_amounts ['capture'] ['count'] += 1;
        $this->counts_and_amounts ['capture'] ['amount'] += $hash_out ['amount'];
    }

    public function addGiftCardCapture($hash_in)
    {
        $hash_out = Transactions::createGiftCardCaptureHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, 'giftCardCapture');
        $this->counts_and_amounts ['giftCardCapture'] ['count'] += 1;
        $this->counts_and_amounts ['giftCardCapture'] ['amount'] += $hash_out ['captureAmount'];
    }

    public function addCaptureGivenAuth($hash_in)
    {
        $hash_out = Transactions::createCaptureGivenAuthHash($hash_in);

        $choice_hash = array(
            $hash_out ['card'],
            $hash_out ['token'],
            $hash_out ['paypage']
        );

        $this->addTransaction($hash_out, $hash_in, 'captureGivenAuth', $choice_hash);
        $this->counts_and_amounts ['captureGivenAuth'] ['count'] += 1;
        $this->counts_and_amounts ['captureGivenAuth'] ['amount'] += $hash_out ['amount'];
    }

    public function addEcheckRedeposit($hash_in)
    {
        $hash_out = Transactions::createEcheckRedepositHash($hash_in);

        $choice_hash = array(
            $hash_out ['echeck'],
            $hash_out ['echeckToken']
        );

        $this->addTransaction($hash_out, $hash_in, 'echeckRedeposit', $choice_hash);
        $this->counts_and_amounts ['echeckRedeposit'] ['count'] += 1;
    }

    public function addEcheckSale($hash_in)
    {
        $hash_out = Transactions::createEcheckSaleHash($hash_in);

        $choice_hash = array(
            $hash_out ['echeck'],
            $hash_out ['echeckToken']
        );

        $this->addTransaction($hash_out, $hash_in, 'echeckSale', $choice_hash);
        $this->counts_and_amounts ['echeckSale'] ['count'] += 1;
        $this->counts_and_amounts ['echeckSale'] ['amount'] += $hash_out ['amount'];
    }

    public function addEcheckCredit($hash_in)
    {
        $hash_out = Transactions::createEcheckCreditHash($hash_in);

        $choice_hash = array(
            $hash_out ['echeck'],
            $hash_out ['echeckToken']
        );

        $this->addTransaction($hash_out, $hash_in, 'echeckCredit', $choice_hash);
        $this->counts_and_amounts ['echeckCredit'] ['count'] += 1;
        $this->counts_and_amounts ['echeckCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addEcheckVerification($hash_in)
    {
        $hash_out = Transactions::createEcheckVerificationHash($hash_in);

        $choice_hash = array(
            $hash_out ['echeck'],
            $hash_out ['echeckToken']
        );

        $this->addTransaction($hash_out, $hash_in, 'echeckVerification', $choice_hash);
        $this->counts_and_amounts ['echeckVerification'] ['count'] += 1;
        $this->counts_and_amounts ['echeckVerification'] ['amount'] += $hash_out ['amount'];
    }

    public function addUpdateCardValidationNumOnToken($hash_in)
    {
        $hash_out = Transactions::createUpdateCardValidationNumOnTokenHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "updateCardValidationNumOnToken");
        $this->counts_and_amounts ['updateCardValidationNumOnToken'] ['count'] += 1;
    }

    public function addUpdateSubscription($hash_in)
    {
        $hash_out = Transactions::createUpdateSubscriptionHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "updateSubscription");
        $this->counts_and_amounts ['updateSubscription'] ['count'] += 1;
    }

    public function addCancelSubscription($hash_in)
    {
        $hash_out = Transactions::createCancelSubscriptionHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "cancelSubscription");
        $this->counts_and_amounts ['cancelSubscription'] ['count'] += 1;
    }

    public function addCreatePlan($hash_in)
    {
        $hash_out = Transactions::createCreatePlanHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "createPlan");
        $this->counts_and_amounts ['createPlan'] ['count'] += 1;
    }

    public function addUpdatePlan($hash_in)
    {
        $hash_out = Transactions::createUpdatePlanHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "updatePlan");
        $this->counts_and_amounts ['updatePlan'] ['count'] += 1;
    }

    public function addActivate($hash_in)
    {
        $hash_out = Transactions::createActivateHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "activate");
        $this->counts_and_amounts ['activate'] ['count'] += 1;
        $this->counts_and_amounts ['activate'] ['amount'] += $hash_out ['amount'];
    }

    public function addDeactivate($hash_in)
    {
        $hash_out = Transactions::createDeactivateHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "deactivate");
        $this->counts_and_amounts ['deactivate'] ['count'] += 1;
    }

    public function addLoad($hash_in)
    {
        $hash_out = Transactions::createLoadHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "load");
        $this->counts_and_amounts ['load'] ['count'] += 1;
        $this->counts_and_amounts ['load'] ['amount'] += $hash_out ['amount'];
    }

    public function addUnload($hash_in)
    {
        $hash_out = Transactions::createUnloadHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "unload");
        $this->counts_and_amounts ['unload'] ['count'] += 1;
        $this->counts_and_amounts ['unload'] ['amount'] += $hash_out ['amount'];
    }

    public function addBalanceInquiry($hash_in)
    {
        $hash_out = Transactions::createBalanceInquiryHash($hash_in);

        $this->addTransaction($hash_out, $hash_in, "balanceInquiry");
        $this->counts_and_amounts ['balanceInquiry'] ['count'] += 1;
    }

    public function addAccountUpdate($hash_in)
    {
        $hash_out = Transactions::createAccountUpdate($hash_in);

        $choice_hash = array(
            XmlFields::returnArrayValue($hash_out, 'card'),
            XmlFields::returnArrayValue($hash_out, 'token')
        );

        $this->addTransaction($hash_out, $hash_in, 'accountUpdate', $choice_hash);
        $this->counts_and_amounts ['accountUpdate'] ['count'] += 1;
    }

    public function addEcheckPreNoteSale($hash_in)
    {
        $hash_out = Transactions::createEcheckPreNoteSaleHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'echeckPreNoteSale');
        $this->counts_and_amounts ['echeckPreNoteSale'] ['count'] += 1;
    }

    public function addEcheckPreNoteCredit($hash_in)
    {
        $hash_out = Transactions::createEcheckPreNoteCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'echeckPreNoteCredit');
        $this->counts_and_amounts ['echeckPreNoteCredit'] ['count'] += 1;
    }

    public function addSubmerchantCredit($hash_in)
    {
        $hash_out = Transactions::createSubmerchantCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'submerchantCredit');
        $this->counts_and_amounts ['submerchantCredit'] ['count'] += 1;
        $this->counts_and_amounts ['submerchantCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addSubmerchantCreditCtx($hash_in)
    {
        $hash_out = Transactions::createSubmerchantCreditCtxHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'submerchantCreditCtx');
        $this->counts_and_amounts ['submerchantCredit'] ['count'] += 1;
        $this->counts_and_amounts ['submerchantCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addPayFacCredit($hash_in)
    {
        $hash_out = Transactions::createPayFacCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'payFacCredit');
        $this->counts_and_amounts ['payFacCredit'] ['count'] += 1;
        $this->counts_and_amounts ['payFacCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addPayoutOrgCredit($hash_in)
    {
        $hash_out = Transactions::createPayoutOrgCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'payoutOrgDebit');
        $this->counts_and_amounts ['payoutOrgCredit'] ['count'] += 1;
        $this->counts_and_amounts ['payoutOrgCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addReserveCredit($hash_in)
    {
        $hash_out = Transactions::createReserveCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'reserveCredit');
        $this->counts_and_amounts ['reserveCredit'] ['count'] += 1;
        $this->counts_and_amounts ['reserveCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addVendorCredit($hash_in)
    {
        $hash_out = Transactions::createVendorCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'vendorCredit');
        $this->counts_and_amounts ['vendorCredit'] ['count'] += 1;
        $this->counts_and_amounts ['vendorCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addVendorCreditCtx($hash_in)
    {
        $hash_out = Transactions::createVendorCreditCtxHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'vendorCreditCtx');
        $this->counts_and_amounts ['vendorCredit'] ['count'] += 1;
        $this->counts_and_amounts ['vendorCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addCustomerCredit($hash_in)
    {
        $hash_out = Transactions::createCustomerCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'customerDebit');
        $this->counts_and_amounts ['customerCredit'] ['count'] += 1;
        $this->counts_and_amounts ['customerCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addPhysicalCheckCredit($hash_in)
    {
        $hash_out = Transactions::createPhysicalCheckCreditHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'physicalCheckCredit');
        $this->counts_and_amounts ['physicalCheckCredit'] ['count'] += 1;
        $this->counts_and_amounts ['physicalCheckCredit'] ['amount'] += $hash_out ['amount'];
    }

    public function addTranslateToLowValueTokenRequest($hash_in)
    {
        $hash_out = Transactions::createTranslateToLowValueTokenHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'translateToLowValueTokenRequest');
        $this->counts_and_amounts ['translateToLowValueTokenRequest'] ['count'] += 1;
    }

    public function addSubmerchantDebit($hash_in)
    {
        $hash_out = Transactions::createSubmerchantDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'submerchantDebit');
        $this->counts_and_amounts ['submerchantDebit'] ['count'] += 1;
        $this->counts_and_amounts ['submerchantDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addSubmerchantDebitCtx($hash_in)
    {
        $hash_out = Transactions::createSubmerchantDebitCtxHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'submerchantDebitCtx');
        $this->counts_and_amounts ['submerchantDebit'] ['count'] += 1;
        $this->counts_and_amounts ['submerchantDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addPayFacDebit($hash_in)
    {
        $hash_out = Transactions::createPayFacDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'payFacDebit');
        $this->counts_and_amounts ['payFacDebit'] ['count'] += 1;
        $this->counts_and_amounts ['payFacDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addPayoutOrgDebit($hash_in)
    {
        $hash_out = Transactions::createPayoutOrgDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'payoutOrgDebit');
        $this->counts_and_amounts ['payoutOrgDebit'] ['count'] += 1;
        $this->counts_and_amounts ['payoutOrgDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addReserveDebit($hash_in)
    {
        $hash_out = Transactions::createReserveDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'reserveDebit');
        $this->counts_and_amounts ['reserveDebit'] ['count'] += 1;
        $this->counts_and_amounts ['reserveDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addVendorDebit($hash_in)
    {
        $hash_out = Transactions::createVendorDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'vendorDebit');
        $this->counts_and_amounts ['vendorDebit'] ['count'] += 1;
        $this->counts_and_amounts ['vendorDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addVendorDebitCtx($hash_in)
    {
        $hash_out = Transactions::createVendorDebitCtxHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'vendorDebitCtx');
        $this->counts_and_amounts ['vendorDebit'] ['count'] += 1;
        $this->counts_and_amounts ['vendorDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addCustomerDebit($hash_in)
    {
        $hash_out = Transactions::createCustomerDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'customerDebit');
        $this->counts_and_amounts ['customerDebit'] ['count'] += 1;
        $this->counts_and_amounts ['customerDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addPhysicalCheckDebit($hash_in)
    {
        $hash_out = Transactions::createPhysicalCheckDebitHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'physicalCheckDebit');
        $this->counts_and_amounts ['physicalCheckDebit'] ['count'] += 1;
        $this->counts_and_amounts ['physicalCheckDebit'] ['amount'] += $hash_out ['amount'];
    }

    public function addFundingInstructionVoid($hash_in)
    {
        $hash_out = Transactions::createFundingInstructionVoidHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'fundingInstructionVoid');
        $this->counts_and_amounts ['fundingInstructionVoid'] ['count'] += 1;
    }

    public function addFastAccessFunding($hash_in)
    {
        $hash_out = Transactions::createFastAccessFundingHash($hash_in);
        $this->addTransaction($hash_out, $hash_in, 'fastAccessFunding');
        $this->counts_and_amounts ['fastAccessFunding'] ['count'] += 1;
        $this->counts_and_amounts ['fastAccessFunding'] ['amount'] += $hash_out ['amount'];
    }

    /*
     * Adds the XML for the transaction given the appropriate data to the transactions file
     */
    private function addTransaction($hash_out, $hash_in, $type, $choice1 = null, $choice2 = null)
    {
        if ($this->closed) {
            throw new \RuntimeException ("Could not add the transaction. This batchRequest is closed.");
        }
        if ($this->isFull()) {
            throw new \RuntimeException ('The transaction could not be added to the batch. It is full.');
        }
        if ($type == 'accountUpdate' && $this->counts_and_amounts ['accountUpdate'] ['count'] != $this->total_txns) {
            throw new \RuntimeException ("The transaction could not be added to the batch. The transaction type $type cannot be mixed with non-Account Updates.");
        } elseif ($type != 'accountUpdate' && $this->counts_and_amounts ['accountUpdate'] ['count'] == $this->total_txns && $this->total_txns > 0) {
            throw new \RuntimeException ("The transaction could not be added to the batch. The transaction type $type cannot be mixed with AccountUpdates.");
        }

        if (isset ($hash_in ['reportGroup'])) {
            $report_group = $hash_in ['reportGroup'];
        } else {
            $conf = Obj2xml::getConfig(array());
            $report_group = $conf ['reportGroup'];
        }


        $request = Obj2xml::transactionToXml($hash_out, $type, $report_group);

        if (file_put_contents($this->transaction_file, $request, FILE_APPEND) === FALSE) {
            throw new \RuntimeException ("A transaction could not be written to the batch file at $this->transaction_file. Please check your privilege.");
        }

        $this->total_txns += 1;
    }

    /*
     * When no more transactions are to be added, the transactions file can be amended with the XML tags for the counts
     * and amounts of the batch request. Returns the filename of the complete batchrequest file
     */
    public function closeRequest()
    {
        $handle = @fopen($this->transaction_file, "r");
        if ($handle) {
            file_put_contents($this->batch_file, Obj2xml::generateBatchHeader($this->counts_and_amounts), FILE_APPEND);
            while (($buffer = fgets($handle, 4096)) !== false) {
                file_put_contents($this->batch_file, $buffer, FILE_APPEND);
            }
            if (!feof($handle)) {
                throw new \RuntimeException ("Error when reading transactions file at $this->transaction_file. Please check your privilege.");
            }
            fclose($handle);
            file_put_contents($this->batch_file, "</batchRequest>", FILE_APPEND);

            unlink($this->transaction_file);
            unset ($this->transaction_file);
            $this->closed = true;
        } else {
            throw new \RuntimeException ("Could not open transactions file at $this->transaction_file. Please check your privilege.");
        }
    }

    public function getCountsAndAmounts()
    {
        return $this->counts_and_amounts;
    }
}
