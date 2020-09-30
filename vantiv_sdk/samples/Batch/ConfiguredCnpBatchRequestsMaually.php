<?php
namespace cnp\sdk;
require_once realpath(__DIR__). '/../../vendor/autoload.php';
#Sale
$sale_info = array(
        	  'orderId' => '1',
		      'amount' => '10010',
		      'orderSource'=>'ecommerce',
		      'billToAddress'=>array(
		      'name' => 'John Smith',
		      'addressLine1' => '1 Main St.',
		      'city' => 'Burlington',
		      'state' => 'MA',
		      'zip' => '01803-3747',
		      'country' => 'US'),
		      'card'=>array(
		      'number' =>'5112010000000003',
		      'expDate' => '0112',
		      'cardValidationNum' => '349',
		      'type' => 'MC')
			);
 
 
$config_hash = array(
  'user' => 'BATCHSDKA',
  'password' => 'certpass',
  'merchantId' => '101',
  'sftp_username' => 'sdk',
  'sftp_password' => 'Zj8I8Ly5',
  'batch_url' => 'payments.vantivprelive.com ',
  'batch_requests_path' => '/usr/local/litle-home/twang/git/batches',
  'cnp_requests_path' => '/usr/local/litle-home/twang/git/batches'
);
 
$batch_dir = '/usr/local/litle-home/twang/git/batches';
 
$cnp_request = new CnpRequest($config_hash);
$batch_request = new BatchRequest($batch_dir);
 
# add a sale to the batch
$batch_request->addSale($sale_info);
# close the batch, indicating that we intend to add no more sales
$batch_request->closeRequest();
# add the batch to the litle request
$cnp_request->addBatchRequest($batch_request);
# close the litle request, indicating that we intend to add no more batches
$cnp_request->closeRequest();
# send the batch to litle via SFTP
$response_file = $cnp_request->sendToCnpStream();
# process the response file 
$processor = new CnpResponseProcessor($response_file);

while($txn = $processor->nextTransaction()){
	echo "Transaction Type : " . $txn->getName() . "\n";
	echo "Transaction Id: " . $txn->cnpTxnId ." \n";
	if($txn->message!='Approved')
 throw new \Exception('ConfiguredCnpBatchRequestsMaually does not get the right response');
}
