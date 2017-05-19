<?php

class cMaterialiseAPI {
  
  protected $applicationName;
  protected $clientId;
  protected $clientSecret;
  protected $url;
  
  function __construct($applicationName, $clientId, $clientSecret, $url) {
    // My Sandbox Credentials
    $this->applicationName = $applicationName;
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->url = $url;
  }

  function httpRequest($data) {
    // use key 'http' even if you send the request to https://...
    $options = array(
      'http' => array(
       'method'  => 'POST',
       'header'  => 
         "Authorisation: Basic ".base64_encode($this->clientId.":".$this->clientSecret)."\r\n".
         "Content-type: application/x-www-form-urlencoded\r\n".
         "Host: api.cloud.materialise.com\r\n",
       'content' => http_build_query($data)
      )
    );
    $context  = stream_context_create($options);
    return file_get_contents($this->url, false, $context);
  }
}

$materialiseAPI = new cMaterialiseAPI("licyt", "licyt1", "5789cc87-5080-4b0d-b16e-8ddd45f6812f", 'https://api.cloud.materialise.com/Token');
  
$data = array(
  'grant_type' => 'password', 
  'username' => 'hmat@hmat.sk',
  'password' => 'Mindfold1'
);
$result = $materialiseAPI->httpRequest($data);
if ($result === FALSE) { /* Handle error */ } 
var_dump($result);
 
?>/