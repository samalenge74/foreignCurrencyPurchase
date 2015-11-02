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
            url: 'assets/nusoap/totalAmountinZAR.php',
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
            url: 'assets/nusoap/saveOrder.php',
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