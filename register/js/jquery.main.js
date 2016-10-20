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
    var timezones_fetched = 0;

    //first_step////////////////////////////////////////////////////
    $('form').submit(function(){ return false; });
    $('#submit_first').click(function(){
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');
  
        var fields = $('#first_step input[type=text]');
        var error = 0;

        //Check is email already in RYouIN, after input field loses focus
        // $('#email').focusout(function() {
        //     $.getScript('js/checkmail.js', function() { });
        // });

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

        if(!error) {
            //Check is email already in RYouIN
            //$.getScript('js/checkmail.js', function() { });
            //$('#submit_first').addClass('spin');
            document.getElementById('submit_first').disabled = true;
            
            checkMail();                     

            //slide steps
            $('#first_step').slideUp();
            $('#second_step').slideDown();

            //update progress bar
            $('#progress_text').html('33% Complete');
            $('#progress').css('width','80px');

            setTimeout(function(){ stopSpinner3(); }, 1000);
              
        }               
        else {
            document.getElementById('submit_first').disabled = false;
            stopSpinner3();
            return false;
        }
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

    //second step////////////////////////////////////////////////////
    $('#submit_second').click(function(){

        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');
        
        var fields = $('#second_step input[type=text]');
        var error = 0;

        fields.each(function(){

                var value = $(this).val();

                //Teamname
                if( (value.length<1 || value.length>10) && ($(this).attr('id') == 'teamname') ) {
                    $(this).addClass('error');
                    $(this).effect("shake", { times:1 }, 50);
                    
                    error++;
                } else if($(this).attr('id') == 'teamname'){
                    $(this).addClass('valid');
                }                 

                if( (value.length<1) && ( $(this).attr('id') == 'firstname' || $(this).attr('id') == 'lastname' ) ) {
                    $(this).addClass('error');
                    $(this).effect("shake", { times:1 }, 50);
                    
                    error++;
                } else if ( $(this).attr('id') == 'firstname' || $(this).attr('id') == 'lastname' ) {
                    $(this).addClass('valid');
                } 

                //Nickname
                if( (value.length<1 || value.length>8) &&  ($(this).attr('id') == 'nickname') )  {
                        $(this).addClass('error');
                        $(this).effect("shake", { times:1 }, 50);

                        error++;
                } else if($(this).attr('id') == 'nickname'){
                    $(this).addClass('valid');
                } 


            
        }); 

        if(!error) {
                
                //Timezones
                // if(timezones_fetched == 0) {
                //     $.getScript('js/timezones.js', function() { });
                //     timezones_fetched++;
                // }
                getTimezones();


                //update progress bar
                $('#progress_text').html('66% Complete');
                $('#progress').css('width','160px');
                
                //slide steps
                $('#second_step').slideUp();
                $('#third_step').slideDown();

                setTimeout(function(){ stopSpinner2(); }, 1000);
        }
        else return false;

    });

    $('#submit_prev3').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        //slide steps
        $('#second_step').slideDown();  
        $('#third_step').slideUp();

        //progress bar
        $('#progress_text').html('33% Complete');
        $('#progress').css('width','80px');

    });

    //third step////////////////////////////////////////////////////
    $('#submit_third').click(function(){
        //update progress bar
        $('#progress_text').html('99% Complete');
        $('#progress').css('width','240px');

        //prepare the fourth step
        var fields = new Array(
            $('#teamname').val(),
            $('#email').val(),
            $('#firstname').val(),
            $('#lastname').val(),
            $('#nickname').val(),
            $('#timezone_select').val()              
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
        
        //Check if existing or new user
        // if(document.getElementById("playerid").value != 0)
        //     alert('Existing user: ' + document.getElementById("playerid").value);            
        // else
        //     alert('New user: ' + document.getElementById("playerid").value);

        //Create the team and add registrar for it (team, timezone, mail, nickname, firstname, lastnamem, playerid)
        createTeam( $('#teamname').val(), $('#timezone_select').val(), $('#email').val(), $('#nickname').val(), $('#firstname').val(), $('#lastname').val(), $('#playerid').val() );
        
        //Disable button
        document.getElementById('submit_fourth').disabled = true;

        //Show info on succeeded team creation and incomming mail
        $('#fourth_step_header').text("Registration completed");
        $('#summary_table').html("<br>");       
        $('#register_info').text("Your team registration is completed. Email containing your login information has been sent to the mail address you provided in registration. Thank you!");
         
        $('#submit_fourth').html("<br>");
        
        $('#progress_text').html('100% Complete');
        $('#progress').css('width','250px');
        
        //alert('Data sent');
    });

    $('#submit_prev4').click(function(){
        //remove classes
        $('#third_step input').removeClass('error').removeClass('valid');


        //slide steps
        $('#third_step').slideDown();  
        $('#fourth_step').slideUp();

        //progress bar
        $('#progress_text').html('66% Complete');
        $('#progress').css('width','180px');

    });    

});

