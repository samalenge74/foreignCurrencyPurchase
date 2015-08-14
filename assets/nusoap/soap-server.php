<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'lib/nusoap.php';

class foreignCurrencyPurchase {
    
    private $_connection;
    private static $_instance; //The single instance
    private $_host = ""; // Database server hostname
    private $_username = ""; // Database user name
    private $_password = ""; // Database user password
    private $_database = ""; // Database name
    private $_email = ""; // email address to where the GBP orders will be mailed to


    /*
	Get an instance of the Database
	@return Instance
	*/
    private static function getInstance() {
            if(!self::$_instance) { // If no instance then make one
                    self::$_instance = new self();
            }
            return self::$_instance;
    }
    
    // Constructor
    public function __construct() {
            $this->_connection = new mysqli($this->_host, $this->_username, 
                    $this->_password, $this->_database);

            // Error handling
            if(mysqli_connect_error()) {
                    trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
                             E_USER_ERROR);
            }
    }
    
    // Get mysqli connection
    private function getConnection() {
            return $this->_connection;
    }
    
    private function sendMail($message){
        $to = $this->_email;
        $headers = "MIME-Version: 1.0" . "\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\n";
        $headers .= "From: Foreign Currency Market <noreply@foreigncurrencymarket.co.za>" . "\n" .
        "X-Mailer: PHP/" . phpversion();  
        
        $subject = " Foreign Currency Purchase Order Details";
        
        return $this->mail($to, $subject, $message, $headers);
    }
    
    function ZARWishAmount($abv, $amt){
        $db = foreignCurrencyPurchase::getInstance();
        $mysqli = $db->getConnection();
        $res = $mysqli->query("select rates, surchage from fcurrency where abreviation = '{$abv}'");
        $row = $res->fetch_assoc();

        $amount = floatval($amt);
        $rates = floatval($row['rates']);
        $surchage = floatval($row['surchage']);

        $subtotal = $amount * $rates;
        $surchageAmount = $subtotal * $surchage;
        $total = $subtotal - $surchageAmount;

        return $total;
    }
           
    function ZARTotalAmount($abv, $amt){
        $db = foreignCurrencyPurchase::getInstance();
        $mysqli = $db->getConnection();
        $res = $mysqli->query("select rates, surchage from fcurrency where abreviation = '{$abv}'");
        $row = $res->fetch_assoc();

        $amount = floatval($amt);
        $rates = floatval($row['rates']);
        $surchage = floatval($row['surchage']);

        $subtotal = $amount / $rates;
        $surchageAmount = round(($subtotal * $surchage),2);
        $total = round(($subtotal + $surchageAmount),2);

        return $total;
    }

    function SaveOrder($abv, $amt, $total){
        
        $db = foreignCurrencyPurchase::getInstance();
        $mysqli = $db->getConnection();
        $res = $mysqli->query("select id, name, rates, surchage from fcurrency where abreviation = '{$abv}'");
        $row = $res->fetch_assoc();
        $fCurrencyID = $row['id']; $name = $row['name']; $rate = floatval($row['rates']); $ZARAmount = round(($amt / $rate),2); $surchageAmount = $total - $ZARAmount;  
                  
        $r = $mysqli->query("insert into purchase_order (amount_purchased, amount_to_be_paid, amount_surcharged, date_created, fcurrency_id) values ('$amt', '$total', '$surchageAmount', NOW(), '$fCurrencyID')");
        $n = $mysqli->affected_rows;
        if ($n == 1){
            $orderID = $mysqli->insert_id;
            
            if ($abv == "GBP"){
                                
                $message = "Foreign Currency purchased: " . $name . "\n Amount Purchased = " . $amt . "\n Amount to pay in ZAR = " . $total . "\n";
                $mail = $db->sendMail($message);
            }
            
            if ($abv == "EUR"){
                $afterDiscount = $total - ($total * 0.02);
                $results = $mysqli->query("update purchase_order set after_discount = $afterDiscount where id = '{$orderID}'");
                
            }
            return TRUE;
        }
       
        return FALSE;    
    }
}

$server = new soap_server();

$server->configureWSDL('ForeignCurrencyPurchase', 'urn:ForeignCurrencyPurchase');
$server->soap_defencoding = 'utf-8';

$server->register(
    'foreignCurrencyPurchase.ZARTotalAmount', //Name of function
        array('abreviation' => 'xsd:string', 'amount' => 'xsd:decimal'), //Input Values
        array('return' => 'xsd:decimal'), //Output Values
        'urn:ForeignCurrencyPurchasewsdl', //Namespace
        'urn:ForeignCurrencyPurchasewsdl#ZARTotalAmount', //SoapAction
        'rpc', //style
        'literal', //can be encoded but it doesn't work with silverlight
        'Returns the total amount the client has to pay in ZAR Currency'
);

$server->register(
    'foreignCurrencyPurchase.ZARWishAmount', //Name of function
        array('abreviation' => 'xsd:string', 'amount' => 'xsd:decimal'), //Input Values
        array('return' => 'xsd:decimal'), //Output Values
        'urn:ForeignCurrencyPurchasewsdl', //Namespace
        'urn:ForeignCurrencyPurchasewsdl#ZARWishAmount', //SoapAction
        'rpc', //style
        'literal', //can be encoded but it doesn't work with silverlight
        'Returns the amount of Foreign currency based on the amount of ZAR currency the client wishes to pay'
);

$server->register(
    'foreignCurrencyPurchase.SaveOrder', //Name of function
        array('abreviation' => 'xsd:string', 'amount' => 'xsd:decimal', 'total' => 'xsd:decimal'), //Input Values
        array('return' => 'xsd:boolean'), //Output Values
        'urn:ForeignCurrencyPurchasewsdl', //Namespace
        'urn:ForeignCurrencyPurchasewsdl#SaveOrder', //SoapAction
        'rpc', //style
        'literal', //can be encoded but it doesn't work with silverlight
        'The client order get saved in the database'
);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);




