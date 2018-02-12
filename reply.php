<?php
require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
require __DIR__ . '/config/config.php';
use Twilio\Rest\Client;

$sid = $config['sid'];
$token = $config['token'];
$client = new Client($sid, $token);

//URLs for getting info
$KOREurl = 'https://bittrex.com/api/v1.1/public/getticker?market=BTC-KORE';
$KOREdata = json_decode( file_get_contents($KOREurl), true);
$BTCurl = 'https://bittrex.com/api/v1.1/public/getticker?market=USDT-BTC';
$BTCdata = json_decode( file_get_contents($BTCurl), true);
//curl to get chainz faux API
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://chainz.cryptoid.info/kore/api.dws?q=getblockcount",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
));
$chainz = curl_exec($curl);
curl_close($curl);

//possible message reply array
$responseMessages = array(
    'price'       => array('body' => "KORE Price Currently: ". $KOREdata['result']['Last']. " BTC, OR in USD: $". $BTCdata['result']['Last']*$KOREdata['result']['Last'] ),
    'blocks'      => array('body' => "Current block for chainz is ". $chainz ),
    'kore test'   => array('body' => "test success")
);

//default reply if command isnt parsed
$defaultMessage = "Current commands are price, or blocks, new commands will be added soon.";

/*
** Read the contents of the incoming message fields.
*/
$body = $_REQUEST['Body'];
$to = $_REQUEST['From'];
$from = $_REQUEST['To'];

$result = preg_replace("/[^A-Za-z0-9]/u", " ", $body);
$result = trim($result);
$result = strtolower($result);
$sendDefault = true; // Default message is sent unless key word is found in following loop.

//select reply from array
foreach ($responseMessages as $reply => $messages) {
    if ($reply == $result) {
        $body = $messages['body'];
        $sendDefault = false;
    }
}

// Send the correct response message.
if ($sendDefault != false) {
    $client->messages->create(
        $to,
        array(
            'from' => $from,
            'body' => $defaultMessage,
        )
    );
} else {
    $client->messages->create(
        $to,
        array(
            'from' => $from,
            'body' => $body,
        )
    );
}
