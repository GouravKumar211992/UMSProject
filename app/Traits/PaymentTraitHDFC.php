<?php
namespace App\Traits;

use App\Models\ApplicationPayment;
use App\Models\MigrationPayment;
use App\Models\Migration;
use Auth;
use Razorpay\Api\Api;
use Carbon\Carbon;

trait PaymentTraitHDFC {

	function verifyPayment($txnid,$status){
        $key = $this->hdfcKey();
        $salt = $this->hdfcSalt();

        $command = "verify_payment"; //mandatory parameter
        
        $hash_str = $key  . '|' . $command . '|' . $txnid . '|' . $salt ;
        $hash = strtolower(hash('sha512', $hash_str)); //generate hash for verify payment request

        $r = array('key' => $key , 'hash' =>$hash , 'var1' => $txnid, 'command' => $command);
            
        $qs= http_build_query($r);
        //for production
        //$wsUrl = "https://info.payu.in/merchant/postservice.php?form=2";
    
        //for test
        $wsUrl = "https://test.payu.in/merchant/postservice.php?form=2";
        
        try 
        {		
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $wsUrl);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_SSLVERSION, 6); //TLS 1.2 mandatory
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            $o = curl_exec($c);
            if (curl_errno($c)) {
                $sad = curl_error($c);
                throw new Exception($sad);
            }
            curl_close($c);
            
            $response = json_decode($o,true);

            if(isset($response['status'])){
                // response is in Json format. Use the transaction_detailspart for status
                $response = $response['transaction_details'];
                $response = $response[$txnid];
                if($status=='all'){
                    return $response;
                }
                
                if($response['status'] == $status) //payment response status and verify status matched
                    return true;
                else
                    return false;
            }
            else {
                return false;
            }
        }
        catch (Exception $e){
            return false;	
        }
    }

    function hdfcKey(){
    	return $key = "7rnFly";
    } 

    function hdfcSalt(){
    	return $salt = "pjVQAWpA";
    } 

    function hdfcUrl(){
    	// return $url = 'https://secure.payu.in/_payment';
    	return $url = 'https://test.payu.in/_payment';
    } 



}

