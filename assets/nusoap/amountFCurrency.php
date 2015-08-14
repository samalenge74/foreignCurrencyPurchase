<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'lib/nusoap.php';

$amt = filter_input(INPUT_POST, "amt");
$abv = filter_input(INPUT_POST, "abv");

$wsdl = "http://localhost/foreignCurrencyPurchase/assets/nusoap/soap-server.php?wsdl";

$client = new nusoap_client($wsdl, 'wsdl');
$error = $client->getError();

if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

$result = $client->call("foreignCurrencyPurchase.ZARWishAmount", array('abreviation' => $abv, 'amount' => $amt));

if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
} else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    } else {
        echo round($result,2);
    }
}






