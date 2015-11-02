<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Foreign Currency Purchase</title>

    <!-- Bootstrap -->
    <link type="text/css" href="<?php echo base_url().'css/bootstrap.css'; ?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div id="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">   
            
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Welcome To Foreign Currency Market</div>
                        
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="purchase_currency" class="form-horizontal" role="form" autocomplete="off">
                                    
                            <div class="row">
                                <div class="col-md-6">
                                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                                <p>Select a currency to purchase</p> 
                                <label class="radio-inline"><input type="radio" name="currency" title="US Dollars" value="USD">USD</label>
                                <label class="radio-inline"><input type="radio" name="currency" title="British Pound" value="GBP">GBP</label>
                                <label class="radio-inline"><input type="radio" name="currency" title="Euro" value="EUR">EUR</label> 
                                <label class="radio-inline"><input type="radio" name="currency" title="Kenyan Shilling" value="KES">KES</label> 
                                </div>
                                <div class="col-md-6">
                                   
                                        
                                       
                                    
                                </div>
                            </div>
                    <div class="row">
                        <br>
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-addon" id="fclabel"></span>
                                <input type="text" id="fc" class="form-control" placeholder="amount of foreign currency" aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <span style="margin-left:8px;">or</span> 
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-addon" >ZAR</span>
                                <input type="text" id="zar" class="form-control" placeholder="amount of ZAR" aria-describedby="basic-addon1">
                                
                            </div>
                            
                        </div>
                        
                            
                      </div>
                       <br> 
                       <div class="row">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-addon" >Total in ZAR</span>
                                <input type="text" id="total" class="form-control" placeholder="amount to be paid" aria-describedby="basic-addon1">
                                <input type="hidden" id="surcharge" class="form-control" placeholder="amount to be paid" aria-describedby="basic-addon1" value="">
                            </div>
                            
                        </div>
                        <div class="col-md-2">
                            
                        </div>
                        <div class="col-md-5">
                            <button id="purchase" type="button" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Purchase</button>
                            
                        </div>
                            
                      </div>
                       <br>
                            </form>     



                        </div>                     
                    </div>  
        </div>
    </div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="<?php echo base_url().'js/jquery-1.11.3.min.js'; ?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="<?php echo base_url().'js/bootstrap.min.js'; ?>"></script>
    <script type="text/javascript">
        $(document).ready(function () {
        
            $('form#purchase_currency input#fc').on('keyup', function(){
                var abv = $("input:radio[name=currency]:checked").val();

                if ($("input:radio[name=currency]:checked").length === 0){
                    alert('select a foreign currency please.');
                    $('form#purchase_currency').trigger("reset");
                    return false;
                }

                var abv = $("input:radio[name=currency]:checked").val();

                var amt = $(this).val();

                $.ajaxSetup ({
                    cache: false
                });


                var dataString = {abv: abv, amt: amt};
                $.ajax({
                    type: 'post',
                    url: '<?php echo site_url('nusoapserver/totalAmountinZAR'); ?> ',
                    data: dataString,
                    success: function(msg){
                        var values = msg.split('|');
                        $('form#purchase_currency input#total').val(values[0]);
                        $('form#purchase_currency input#surcharge').val(values[1]);
                    },
                    error: function(ob,errStr) {
                        alert('There was an error in your request.');
                    }
                });



            });

            $('form#purchase_currency input#zar').on('keyup', function(){
                var abv = $("input:radio[name=currency]:checked").val();

                if ($("input:radio[name=currency]:checked").length === 0){
                    alert('select a foreign currency please.');
                    $('form#purchase_currency').trigger("reset");
                    return false;
                }

                var amt = $(this).val();

                $.ajaxSetup ({
                    cache: false
                });


                var dataString = {abv: abv, amt: amt};
                $.ajax({
                    type: 'post',
                    url: '<?php echo site_url("nusoapserver/amountFCurrency"); ?>',
                    data: dataString,
                    success: function(msg){
                        var values = msg.split('|');
                        $('form#purchase_currency input#fc').val(values[0]);
                        $('form#purchase_currency input#total').val(amt);
                        $('form#purchase_currency input#surcharge').val(values[1]);
                    },
                    error: function(ob,errStr) {
                        alert('There was an error in your request.');
                    }
                });



            });

            $('input[type=radio][name=currency]').on('change', function(){

               var abv = this.value;
               if (abv === 'GBP') {
                   $('input#email').show();
               }else{
                   $('input#email').hide();
               }
               $('form#purchase_currency input#fc').val("");
               $('form#purchase_currency input#zar').val("");
               $('form#purchase_currency input#total').val("");

               $('span#fclabel').html(abv);

            });

            $('form#purchase_currency button#purchase').on('click', function(e){
                e.preventDefault();

                var amt = $('form#purchase_currency input#fc').val();
                var abv = $("input:radio[name=currency]:checked").val();
                var total = $('form#purchase_currency input#total').val();
                var surcharge = $('form#purchase_currency input#surcharge').val();

                $.ajaxSetup ({
                    cache: false
                });


                var dataString = {abv: abv, amt: amt, total: total, surcharge: surcharge};
                $.ajax({
                    type: 'post',
                    url: '<?php echo site_url('nusoapserver/saveOrder'); ?> ',
                    data: dataString,
                    success: function(msg){
                        if (msg === 'true'){
                            alert('Your order have been captured successfully');
                            $('form#purchase_currency').trigger('reset');
                        }else{
                            alert('There was a problem capturing your order. Please contact the service provider.')
                        }
                    },
                    error: function(ob,errStr) {
                        alert('There was an error in your request.');
                    }
                });

            });



        });
    </script>
</body>
</html>