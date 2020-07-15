<?php
///error_reporting(E_ALL);
//ini_set('display_errors', 1);

include('admin/lib/dbcon.php'); 
dbcon(); 


$curl = curl_init();

$email = $_POST['emailx'];
$amount = $_POST['total'] * 100;  //the amount in kobo. This value is actually NGN 300

// url to go to after payment
//$callback_url = 'myapp.com/pay/callback.php';  
$callback_url = host().'callback.php'; 
  //$callback_url = 'edu.smartdelta.com.ng/COEA/callback.php';
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'amount'=>$amount,
    'email'=>$email,
     "reference" => $_POST['merchant_ref1'],
    'callback_url' => $callback_url
  ]),
  CURLOPT_HTTPHEADER => [
    //"authorization: Bearer sk_test_5a19822c308f7d12f9f64f19cecac63796ec6816", //replace this with your own test key
    "authorization: Bearer ".t_gate, //replace this with your own test key
    "content-type: application/json",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
  // there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}

$tranx = json_decode($response, true);
//$refno1 = $tranx->data->reference ;//$_POST['merchant_ref1'];
if(!$tranx->status){
// there was an error from the API
//$mand  = sha1($refno1);
  print_r('API returned error: ' . $tranx['message']);
$mand2  = $_POST['merchant_ref1'];
//$del_rec22 = mysqli_query($condb,"DELETE FROM pin WHERE trans_id = '".safee($condb,$mand2)."'");
//$del_rec22 = mysqli_query($condb,"DELETE FROM fshop_tb WHERE ftrans_id = '".safee($condb,$mand2)."'");
//message("Payment not completed please try Again!", "error");
		       //redirect('apply_b.php?view=f_select');
  
 
}

// comment out this line if you want to redirect the user to the payment page
//print_r($tranx);
// redirect to page so User can pay
// uncomment this line to allow the user redirect to the payment page
header('Location: ' . $tranx['data']['authorization_url']);



?>