$(function(){
    //original field values
    var field_values = {
            //id        :  value
            'username'  : '',
            'password'  : '',
            'cpassword' : '',
            'firstname'  : '',
            'lastname'  : '',
            'email'  : '',
            'teamname'  : ''
    };


    //inputfocus
    $('input#username').inputfocus({ value: field_values['username'] });
    $('input#password').inputfocus({ value: field_values['password'] });
    $('input#cpassword').inputfocus({ value: field_values['cpassword'] }); 
    $('input#lastname').inputfocus({ value: field_values['lastname'] });
    $('input#firstname').inputfocus({ value: field_values['firstname'] });
    $('input#email').inputfocus({ value: field_values['email'] }); 
    $('input#teamname').inputfocus({ value: field_values['teamname'] }); 



    //reset progress bar
    $('#progress').css('width','0');
    $('#progress_text').html('0% Complete');

    //first_step
    $('form').submit(function(){ return false; });
    $('#submit_first').click(function(){
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');
  
        var fields = $('#first_step input[type=text]');
        var error = 0;

        //Email check
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        fields.each(function(){
            if($(this).attr('id') =='email') {

                var value = $(this).val();
                //if( value.length<1 && value==field_values[$(this).attr('id')] &&  $(this).attr('id') =='email' && !emailPattern.test(value)  ) {
                if( value.length<1 || ( $(this).attr('id') == 'email' && !emailPattern.test(value) ) ) {
                    $(this).addClass('error');
                    $(this).effect("shake", { times:1 }, 50);
                    
                    error++;
                } else {
                    $(this).addClass('valid');
                }
            }
        });        

        //Teamname check
        fields.each(function(){
            if($(this).attr('id') =='teamname'){
                var value = $(this).val();
            
                if( (value.length<1 || value.length>10) &&  $(this).attr('id') =='teamname') {
                    $(this).addClass('error');
                    $(this).effect("shake", { times:1 }, 50);
                    
                    error++;
                } else {
                    $(this).addClass('valid');
                }
            }
        });        
        
        if(!error) {
                //update progress bar
                $('#progress_text').html('33% Complete');
                $('#progress').css('width','113px');
                
                //slide steps
                $('#first_step').slideUp();
                $('#second_step').slideDown();     
            }               
            else return false;
    });

    $('#submit_prev2').click(function(){
        //remove classes
        //$('#second_step input').removeClass('error').removeClass('valid');
        $('#first_step input').removeClass('error').removeClass('valid');

        //slide steps
        $('#first_step').slideDown();  
        $('#second_step').slideUp();

        //progress bar
        $('#progress_text').html('0% Complete');
        $('#progress').css('width','0px');

    });

    $('#submit_second').click(function(){
        var fields = $('#second_step input[type=text]');

        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');
        var error = 0;

        fields.each(function(){
            if( $(this).attr('id') == 'firstname' || $(this).attr('id') == 'lastname' || $(this).attr('id') == 'nickname' ){
                var value = $(this).val();
            
                if( (value.length<1) && $(this).attr('id') == 'firstname' || $(this).attr('id') == 'lastname' || $(this).attr('id') == 'nickname' ) {
                    $(this).addClass('error');
                    $(this).effect("shake", { times:1 }, 50);
                    
                    error++;
                } else {
                    $(this).addClass('valid');
                }
            }
        }); 

        if(!error) {
                //update progress bar
                $('#progress_text').html('66% Complete');
                $('#progress').css('width','226px');
                
                //slide steps
                $('#second_step').slideUp();
                $('#third_step').slideDown();     
        } else return false;

    });

    $('#submit_prev3').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        //slide steps
        $('#second_step').slideDown();  
        $('#third_step').slideUp();

        //progress bar
        $('#progress_text').html('33% Complete');
        $('#progress').css('width','113px');

    });

    $('#submit_third').click(function(){
        //update progress bar
        $('#progress_text').html('100% Complete');
        $('#progress').css('width','250px');

        //prepare the fourth step
        var fields = new Array(
            $('#username').val(),
            $('#password').val(),
            $('#email').val(),
            $('#firstname').val() + ' ' + $('#lastname').val(),
            $('#age').val(),
            $('#gender').val(),
            $('#country').val()                       
        );
        var tr = $('#fourth_step tr');
        tr.each(function(){
            //alert( fields[$(this).index()] )
            $(this).children('td:nth-child(2)').html(fields[$(this).index()]);
        });
                
        //slide steps
        $('#third_step').slideUp();
        $('#fourth_step').slideDown();            
    });


    $('#submit_fourth').click(function(){
        //send information to server
        alert('Data sent');
    });

    $('#submit_prev4').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        // var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        // var fields = $('#second_step input[type=text]');
        // var error = 0;
        // fields.each(function(){
        //     var value = $(this).val();
        //     if( value.length<1 || value==field_values[$(this).attr('id')] || ( $(this).attr('id')=='email' && !emailPattern.test(value) ) ) {
        //         $(this).addClass('error');
        //         $(this).effect("shake", { times:3 }, 50);
                
        //         error++;
        //     } else {
        //         $(this).addClass('valid');
        //     }
        // });

        // if(!error) {
        //         //update progress bar
        //         $('#progress_text').html('66% Complete');
        //         $('#progress').css('width','226px');
                
        //         //slide steps
        //         $('#second_step').slideDown();
        //         $('#first_step').slideUp();     
        // } else return false;

        //slide steps
        $('#third_step').slideDown();  
        $('#fourth_step').slideUp();

        //progress bar
        $('#progress_text').html('66% Complete');
        $('#progress').css('width','113px');

    });    

});
