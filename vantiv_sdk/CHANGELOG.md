= CnpOnline CHANGELOG

==Version 12.13.0 (June 12, 2020)
* Feature: Added location as optional element of all online responses

==Version 12.11.0 (January 23, 2020)
* Feature: Added merchantCatagoryCode support to forceCapture, captureGivenAuth, sale, credit, authorization
* Feature: Added authenticationProtocolVersion support to fraudCheckType

==Version 12.10.0 (November 11, 2019)
* Feature: Added skipRealtimeAU support to authorization and sale
* Feature: Added support for accountUpdateSource and numAccountUpdates in responses

==Version 12.9.0 (October 28, 2019)
* Feature: Added support for customerCredit, customerDebit
* Feature: Added support for payoutOrgCredit, payoutOrgDebit
* Feature: Added fundingCustomerID support
* Feature: Enhancements to fastAccessFunding
* Feature: Added configurable timeout for getting batch responses

==Version 12.8.0 (Jun 24, 2019)
* Feature: TokenURL support

==Version 12.7.5 (Jul 22, 2019)
* BugFix: Modified debug_backtrace parameter to be compatible with both PHP pre and post 5.4.0

==Version 12.7.4 (Jul 16, 2019)
* BugFix: Added recycleId

==Version 12.7.3 (Jun 24, 2019)
* BugFix: Added CheckoutId
* BugFix: Removed Proxy settings for cert tests
* BugFix: Removed unused unit tests

==Version 12.7.1 (Jan 14, 2019)
* BugFix: Removed debug statements

==Version 12.7.0 (Dec 27, 2018)
* Feature: Full functions for XML v12.7
* Feature: add support for new type: echeckTypeCtx
* Feature: add 4 new types in cnpBatch: vendorCreditCtx, vendorDebitCtx, submerchantCreditCtx, submerchantDebitCtx

==Version 12.5.4 (Dec 11, 2018)
* Feature: User Exception handling support

==Version 12.5.3 (Dec 11, 2018)
* BugFix: Created custom exception handler replacing a test framework

==Version 12.5.2 (Dec 10, 2018)
* Feature: Multibyte encoding support for XML requests
* BugFix: Validation to terminate current execution of script

==Version 12.5.1 (Nov 7, 2018)
* BugFix: Removed debug statements

==Version 12.5.0 (Oct 11, 2018)
* Feature: Full functions for XML v12.5
* Feature: add a new type: encryptedCcAccountNumberType
* Feature: add support for encryption on registerTokenRequestType
* Feature: add paymentAccountReferenceNumber in authorizationResponse and saleResponse
* Feature: add disbursementTypeEnum in fastAccessFunding

==Version 12.3.0 (May 14, 2018)
* Feature: Added support for lodging information in authorization, sale, capture, captureGivenAuth, forceCapture and credit
* Feature: Added translateToLowValueToken transaction type
* Feature: Replaced routingPreference with pinlessDebitRequest in sale
* Feature: Added support for showStatusOnly in queryTransaction
* Feature: Added eventType, accountLogin and accountPasshash to fraudCheck
* Bug Fix: Added createDiscount, updateDiscount, deleteDiscount, createAddOn, updateAddOn and deleteAddOn 
           fields to recurring transactions.

==Version 12.1.0 (March 26, 2018)
* Feature: Added support for Visa card on file transactions
* Feature: Added fastAccessFunding transaction

==Version 12.0.1 (March 16, 2018)
* Feature: PGP support for batches

==Version 11.0.1 (May 4, 2017)
* Feature: update phpseclib to v2.0.4

==Version 11.0 (Apr 10, 2017)
* Feature: implement Vantiv eCommerce XMLv11.0
* Feature: SEPA Support
* Feature: iDEAL support
* Feature: new giftCard transactions
* Feature: giroPay support
* Feature: Network Enhancements mandates
* Feature: add support for raw network response
* Bug fix: specify char encoding in HTTP header

==Version 9.3.3
Update SSL version to 6

==Version 9.3.2
HTTP timeout set to 500ms

==Version 9.3.1 (March 9, 2015)
*Feature: PFIF instruction transaction support was added

== Version 9.03.0 (February 17, 2015)
* Feature: Applepay support was added
* Feature: Secondary amount support was added
* Feature: Add support for new Batch transaction in version 9.3 schema

==version 8.29.0 (January 22,2014)
* Feature: Apple pay and secondary amounts now available.

==version 8.27.0 (Auguest 26,2014)
* Feature: Added samples for each type of transactions

== version 8.25.0 (March 7, 2014)
* Feature: Added support for ROAM: Authorization, ForceCapture, CaptureGivenAuth, Sale, Credit

== version 8.24.0 (February 13, 2014)
* Feature: add triggered rules for advancedFraudCheckResult

== Version 8.23.0 (December 18, 2013)
* Feature: advancedFraudChecks now available in authorization and sale 
* Feature: catLevel (Cardholder Activated Terminal) now available in pos
* Feature: advancedFraudResults now available in fraudResult

== Version 8.22.0 (December 18, 2013)
* Feature: Support for the following new gift card transactions:
ActivateReversal, DeactivateReversal, LoadReversal, RefundReversal,
UnloadReversal, DepositReversal
* Feature: UpdateSubscription now can take token or paypage updates, and
can return token in the response
* Feature: Support for virtualGiftCard in giftCard activations and can be returned in EnhancedAuthResponses
* Feature: Gift Card responses can be returned from AuthReversal

== Version 8.21.0 (December 18, 2013)
* Feature: Support for the following new recurring transactions:
CreatePlan, UpdatePlan
* Feature: Support for the following new gift card transactions:
Activate, Deactivate, Load, Unload, Balance Inquiry
* Feature: Gift card responses are now returned as part of
authorzationResponse, authReversalResponse, captureResponse,
forceCapture, captureGivenAuthResponse, saleResponse, creditResponse
* Feature: fraudResult is now returned as part of captureResponse,
forceCaptureResponse, captureGivenAuthResponse, creditResponse

== Version 8.20.0 (December 17, 2013)
* Feature: More initial support for Recurring, including the ability to update or cancel subscriptions
* Cleanup: Move phpseclib to composer

== Version 8.19.0 (July 30, 2013)
* Feature: Added deptRepayment as a choice for authorizations, forceCapture, captureGivenAuth, sale
* Feature: More initial support for Recurring, including a backwards incompatible changing a field from numberOfPaymentsRemaining to numberOfPayments
* Feature: Add merchantData to forceRequest and captureGivenAuth

== Version 8.18.0 (July 15, 2013)
* Feature: Add initial support for recurring to sales

== Version 8.17.0 (March 29, 2013)
* Feature: Add recyling to voidResponse
* Feature: Add support for surcharging credit card transactions
* Feature: Add support for specifying the terminalId on a pos transaction
* Feature: Add support for pos transactions on tied refunds

== Version 8.15.0 (January 31, 2013)

* Feature: Add merchantData to echeck verifications and echeck redeposits

== Version 8.14.0 (January 30, 2013)

* Feature: Merged pull request #12 from iToto to make convert DOMDocument to string for debugging
* Bugfix: Merged pull request #14 from johnholden to truncate strings that are too long, thus preventing some xml validation errors
* Feature: Temporary storage of card validation num on tokens
* Feature: Updating card validation numbers on tokens

== Version 8.13.1 (June 15, 2012)

* Feature: Add support for accessing response as a tree using simplexml.  Access is provided if $treeResponse=true is passed to the LitleOnlineRequest constructor

== Version 8.13 (May 18, 2012)

* Feature: Authorizations and Sales can accept an optional fraudFilterOverride parameter

== Version 8.12.2 (May 8, 2012)

* Bugfix: Add support for line item data and tax detail to enhanced data

== Version 8.12.1 (April 23, 2012)

* Bugfix: Add support for id, customerid and reportgroup

== Version 8.12 (April 17, 2012)

* Bugfix: Add support for MerchantData on auth and sale
* Feature: Add support for actionReason on credit
* Feature: Track SDK Usage

== Version 8.10.0 (April 2, 2012)

* Initial release
