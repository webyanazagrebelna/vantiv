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
require_once realpath(dirname(__FILE__)) . '/UrlMapper.php';

function writeConfig($line,$handle)
{
    foreach ($line as $keys => $values) {
        fwrite($handle, $keys. '');
        if (is_array($values)) {
            foreach ($values as $key2 => $value2) {
                fwrite($handle,"['" . $key2 . "'] =" . $value2 .  PHP_EOL);
            }
        } else {
            fwrite($handle,' =' . $values);
            fwrite($handle, PHP_EOL);
        }
    }
}

function initialize()
{
    $line = array();

    $handle = @fopen('./cnp_SDK_config.ini', "w");
    if ($handle) {
        print "Welcome to Vantiv eCommerce PHP_SDK" . PHP_EOL;
        print "Please input your user name: ";
        $line['user'] = formatConfigValue(STDIN);
        print "Please input your password: ";
        $line['password'] = formatConfigValue(STDIN);
        print "Please input your merchantId: ";
        $line['currency_merchant_map ']['DEFAULT'] = formatConfigValue(STDIN);
        print "Please choose Cnp url from the following list (example: 'sandbox') or directly input another URL: \n" .
            "sandbox => 'https://www.testvantivcnp.com/sandbox/communicator/online https://www.testvantivcnp.com/sandbox/new/sandbox/communicator/online https://www.testvantivcnp.com/sandbox/new/sandbox/communicator/online' \n" .
            "postlive => https://payments.vantivpostlive.com/vap/communicator/online https://payments.east.vantivpostlive.com/vap/communicator/online https://payments.west.vantivpostlive.com/vap/communicator/online \n" .
            "transact-postlive => https://transact.vantivpostlive.com/vap/communicator/online \n" .
            "production => https://payments.vantivcnp.com/vap/communicator/online https://payments.east.vantivcnp.com/vap/communicator/online https://payments.west.vantivcnp.com/vap/communicator/online \n" .
            "production-transact => https://transact.vantivcnp.com/vap/communicator/online \n" .
            "prelive => https://payments.vantivprelive.com/vap/communicator/online https://payments.east.vantivprelive.com/vap/communicator/online https://payments.west.vantivprelive.com/vap/communicator/online \n" .
            "transact-prelive => https://transact.vantivprelive.com/vap/communicator/online" . PHP_EOL;
        $url = UrlMapper::getUrl(trim(fgets(STDIN)));


        if (is_array($url)){
            $line['url'] = $url[0];
            $line['multiSiteUrl1'] = $url[1];
            $line['multiSiteUrl2'] = $url[2];
        }else {
            $line['url'] = $url;
        }
        print "Please input the proxy, if no proxy hit enter key: ";
        $line['proxy'] = formatConfigValue(STDIN);

        print "Batch processing saves files to disk. \n";
        print "Please input a directory to save these files. " .
            "If you are not using batch processing, you may hit enter. ";
        $dir = formatConfigValue(STDIN);
        $line['batch_requests_path'] = $dir;
        $line['cnp_requests_path'] = $dir;

        print "Please input your SFTP username. If you are not using SFTP, you may hit enter. ";
        $line['sftp_username'] = formatConfigValue(STDIN);
        print "Please input your SFTP password. If you are not using SFTP, you may hit enter. ";
        $line['sftp_password'] = formatConfigValue(STDIN);
        print "Please input the URL for batch processing. If you are not using batch processing, you may hit enter. ";
        $line['batch_url'] = formatConfigValue(STDIN);
        print "Please input the port for stream batch delivery. " .
            "If you are not using stream batch delivery, you may hit enter. ";
        $line['tcp_port'] = formatConfigValue(STDIN);
        print "Please input the timeout (in seconds) for stream batch delivery. " .
            "If you are not using stream batch delivery, you may hit enter. ";
        $line['tcp_timeout'] = formatConfigValue(STDIN);
        print "Please input the timeout (in seconds) for batch response. " .
            "If you are not using stream batch delivery, you may hit enter. ";
        $line['sftp_timeout'] = formatConfigValue(STDIN);
        # ssl should be usd by default
        $line['tcp_ssl'] = '1';
        $line['print_xml'] = '0';
        print "Use PGP encryption for batch files? (y/n) (No encryption by default): ";
        $useEncryption = formatConfigValue(STDIN);
        if(("y" == $useEncryption) || ("true" == $useEncryption) || ("yes" == $useEncryption)){
            $line['useEncryption'] = "true";
            print "Import Vantiv's public key to gpg key ring? (y/n): ";
            $import = formatConfigValue(STDIN);
            if(("y" == $import) || ("yes" == $import) || ("true" == $import)) {
                print "Please input path to Vantiv's public key (for encryption of batch requests) :";
                $keyFile = formatConfigValue(STDIN);
                $line['vantivPublicKeyID'] = PgpHelper::importKey($keyFile);
            }
            else{
                print "Please input key ID for Vantiv's public key (imported to your key ring) :";
                $line['vantivPublicKeyID'] = formatConfigValue(STDIN);
            }
            print "Please input passphrase for decryption :";
            $line['gpgPassphrase'] = formatConfigValue(STDIN);
        }
        else{
            $line['useEncryption'] = "false";
            $line['vantivPublicKeyID'] = "";
            $line['gpgPassphrase'] = "";
        }

        $line['multiSiteUrl1'] = "";
        $line['multiSiteUrl2'] = "";
        $line['multiSite'] = "false";
        $line['printMultiSiteDebug'] = "false";
        $line['multiSiteErrorThreshold'] = '5';
        $line['maxHoursWithoutSwitch'] = '48';
        $line['deleteBatchFiles'] = "";
        writeConfig($line,$handle);
        #default http timeout set to 500 ms
        fwrite($handle, "timeout =  500".  PHP_EOL);
        fwrite($handle, "reportGroup = Default Report Group".  PHP_EOL);
    }
    fclose($handle);
    print "The Vantiv eCommerce configuration file has been generated, " .
        "the file is located in the lib directory". PHP_EOL;
}

function formatConfigValue($str){
    return "\"" . trim(fgets($str)) . "\"";
}

initialize();
