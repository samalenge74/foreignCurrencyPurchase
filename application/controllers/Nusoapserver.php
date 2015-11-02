<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Nusoapserver extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        //$ns = base_url();
        $this->load->library("Nusoap_library");
        $this->load->library("Master");
        $this->server = new soap_server();
        $this->server->configureWSDL('Nusoapserver_WSDL', 'urn:Nusoapserver_WSDL');
        $this->server->soap_defencoding = 'utf-8';
        //$this->server->wsdl->schemaTargetNamespace = $ns;

        $this->server->wsdl->addComplexType('values', 'complexType', 'struct', 'all', '', 
                array(
                        'total' =>array('name' => 'total', 'type' => 'xsd:decimal'), 
                        'surchage' => array('name' => 'surchage', 'type' => 'xsd:decimal')
                )
        );

        $this->server->wsdl->addComplexType('valuesArray', 'complexType', 'array', '', 'SOAP-ENC:Array', array(),
                                        array(
                                            array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:values[]')
                                        ),
                                        'tns:values'

        );
                

    }
        
        public function index()
        {
            if($this->uri->rsegment(3) == "wsdl") {
                $_SERVER['QUERY_STRING'] = "wsdl";
            } else {
                $_SERVER['QUERY_STRING'] = "";
            }   
            $this->server->register(
                'Master.ZARTotalAmount', //Name of function
                array('abreviation' => 'xsd:string', 'amount' => 'xsd:decimal'), //Input Values
                array('return' => 'tns:valuesArray'), //Output Values
                'urn:ForeignCurrencyPurchasewsdl', //Namespace
                'urn:ForeignCurrencyPurchasewsdl#ZARTotalAmount', //SoapAction
                'rpc', //style
                'literal', //can be encoded but it doesn't work with silverlight
                'Returns the total amount the client has to pay in ZAR Currency'
            );

            $this->server->register(
                'Master.ZARWishAmount', //Name of function
                array('abreviation' => 'xsd:string', 'amount' => 'xsd:decimal'), //Input Values
                array('return' => 'tns:valuesArray'), //Output Values
                'urn:ForeignCurrencyPurchasewsdl', //Namespace
                'urn:ForeignCurrencyPurchasewsdl#ZARWishAmount', //SoapAction
                'rpc', //style
                'literal', //can be encoded but it doesn't work with silverlight
                'Returns the amount of Foreign currency based on the amount of ZAR currency the client wishes to pay'
            );

            $this->server->register(
                'Master.SaveOrder', //Name of function
                array('abreviation' => 'xsd:string', 'amount' => 'xsd:decimal', 'total' => 'xsd:decimal', 'surcharge' => 'xsd:decimal'), //Input Values
                array('return' => 'xsd:boolean'), //Output Values
                'urn:ForeignCurrencyPurchasewsdl', //Namespace
                'urn:ForeignCurrencyPurchasewsdl#SaveOrder', //SoapAction
                'rpc', //style
                'literal', //can be encoded but it doesn't work with silverlight
                'The client order get saved in the database'
            );
            $this->server->service(file_get_contents("php://input"));
            
        }
        
        public function amountFCurrency()
        {
            $abv = $this->input->post('abv'); 
            $amt = floatval($this->input->post('amt'));
            
            $this->load->library("Nusoap_library");
            $client = new nusoap_client(site_url("Nusoapserver/index/wsdl"), true);
            $error = $client->getError();

            if ($error) {
                echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
            }

            $result = $client->call("Master.ZARWishAmount", array('abreviation' => $abv, 'amount' => $amt));

            if ($client->fault) {
                echo "<h2>Fault</h2><pre>";
                print_r($result);
                echo "</pre>";
            } else {
                $error = $client->getError();
                if ($error) {
                    echo "<h2>Error</h2><pre>" . $error . "</pre>";
                } else {
                    $values  = $result['item'];
                    echo($values['total'] . '|' . $values['surchage']);
                }
            }
            
            // show soap request and response
            echo "<h2>Request</h2>";
            echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
            echo "<h2>Response</h2>";
            echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";
            
        }
        
        public function saveOrder()
        {
            $abv = $this->input->post('abv'); $amt = floatval($this->input->post('amt'));
            $total = floatval($this->input->post('total')); $surcharge = floatval($this->input->post('surchage'));

            $this->load->library("Nusoap_library"); $client = new nusoap_client(site_url("Nusoapserver/index/wsdl"), true);
            
            $error = $client->getError();

            if ($error) {
                echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
            }

            $result = $client->call("Master.SaveOrder", array('abreviation' => $abv, 'amount' => $amt, 'total' => $total, 'surcharge' => $surcharge));

            if ($client->fault) {
                echo "<h2>Fault</h2><pre>";
                print_r($result);
                echo "</pre>";
            } else {
                $error = $client->getError();
                if ($error) {
                    echo "<h2>Error</h2><pre>" . $error . "</pre>";
                } else {
                    echo $result;
                }
            }
        }

        public function totalAmountinZAR()
        {
            $amt = floatval($this->input->post('amt'));
            $abv = $this->input->post('abv');

            $this->load->library("Nusoap_library"); $client = new nusoap_client(site_url("Nusoapserver/index/wsdl"), true);
            
            $error = $client->getError();

            if ($error) {
                echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
            }

            $result = $client->call("Master.ZARTotalAmount", array('abreviation' => $abv, 'amount' => $amt));

            if ($client->fault) {
                echo "<h2>Fault</h2><pre>";
                print_r($result);
                echo "</pre>";
            } else {
                $error = $client->getError();
                if ($error) {
                    echo "<h2>Error</h2><pre>" . $error . "</pre>";
                } else {
                    $values  = $result['item'];
                    echo($values['total'] . '|' . $values['surchage']);
                }
            }
            
           // show soap request and response
            echo "<h2>Request</h2>";
            echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
            echo "<h2>Response</h2>";
            echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>"; 
        }
        
    
}
