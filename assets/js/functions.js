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
                $('form#purchase_currency input#total').val(msg);
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
            url: 'assets/nusoap/amountFCurrency.php',
            data: dataString,
            success: function(msg){
                $('form#purchase_currency input#fc').val(msg);
                $('form#purchase_currency input#total').val(amt);
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
        
        $.ajaxSetup ({
            cache: false
        });
        
                
        var dataString = {abv: abv, amt: amt, total: total};
        $.ajax({
            type: 'post',
            url: 'assets/nusoap/saveOrder.php',
            data: dataString,
            success: function(msg){
                if (msg === 'true'){
                    alert('Your order have been captured successfully');
                    $('form#purchase_currency').trigger('reset');
                }else{
                    alert('There was a problem capturing your. Please contact the service provider.')
                }
            },
            error: function(ob,errStr) {
                alert('There was an error in your request.');
            }
        });
        
    });
        
     

});