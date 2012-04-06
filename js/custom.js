//Some definitions that will create namespaces as not to wreac havoc

var Coova = {
    p_status:   '',
    p_counter:  undefined,  //COUNTER: The counter object
    p_refresh:  20,     //COUNTER: The interval at which the refresh should happen
    p_time:     20,     //COUNTER: This should be the same as the interval and will count down until zero to do a refresh.
    p_url_use:  'http://10.1.0.1/c2/yfi_cake/third_parties/json_usage_check?key=12345&username=',  //This is the YFi Web service which will show the user's usage
    p_url_uam:  'http://10.1.0.1/coova_json/uam.php?challenge=',    //This us the web service which will return a uam encrypted hash using the challenge, password and UAM shared secret
    p_uamip:    '10.1.0.1',  //Use a default, override later
    p_uamport:  '3990',      //Use a default, override later
    p_default:  'http://google.com',
    p_realm:    '@ri',
    p_url_voucher_name: 'https://10.1.0.1/c2/yfi_cake/third_parties/json_voucher_name?key=12345&password=',
    p_username: '',
    p_password: '',
    p_debug:    true,
    f_refresh: function(){

        var queryObj    = new Object(); //How the page was called - Should be called with the following search sting:
                                    //?res=notyet&uamip=10.1.0.1&uamport=3660&challenge=ca91105b39c91d49cbfa61ef085a2488&mac=00-0C-F1-5F-58-0B&
                                    //ip=10.1.0.8&called=00-1D-7E-BC-02-AD&nasid=10.20.30.2&userurl=http%3a%2f%2f1.1.1.1%2f&md=50834AD406B33D3A2D771FF2B4C80499
        window.location.search.replace(new RegExp("([^?=&]+)(=([^&]*))?","g"), function($0,$1,$2,$3) { queryObj[$1] = $3; });
        //console.log(queryObj);

        if(queryObj.uamport != undefined){  //Override defaults
            Coova.p_uamport = queryObj.uamport;
        }

        if(queryObj.uamip != undefined){    //Override defaults
            Coova.p_uamip = queryObj.uamip;
        }

        //The refresh will query Coova Chill as well as the YFi server on usage
        var url_status = 'http://'+Coova.p_uamip+':'+Coova.p_uamport+'/json/status?callback=?';
        if(Coova.p_debug == true){
            console.log("Get latest status "+url_status);
        }
        $.getJSON(url_status, 
            function(data) {
                if(Coova.p_debug == true){
                    console.log(data);
                }
                //To be able to grab this from anywhere...
                Coova.p_status = data;
                 //If user is not connected they should see login screen
                if(data.clientState == 0){
                    $.mobile.changePage("connect.php");
                    return
                }
                if(data.clientState == 1){  //If connected we need to show the status page and repaint the detail
                    $.mobile.changePage("status.php");
                    Coova.f_screen();
                    Coova.f_usage();
                }                
        });       
    },
    f_usage:  function(){

        //We need to see if we can get padded json form the YFi server
        $.getJSON(Coova.p_url_use + escape(Coova.p_status.session.userName)+"&callback=?", 
            function(data) {
                //console.log(data);
                //If the time available is 'NA' we must hide the time div
                if(data.json.summary.time_avail == 'NA'){
                    $('#div_time').hide().trigger('updatelayout');


                }else{
                    $('#div_time').show().trigger('updatelayout');
                    var time_total     = data.json.summary.time_used + data.json.summary.time_avail;
			        var pers_time_used = (data.json.summary.time_used / time_total) * 100;

                    var l = $("#lbl_slider-time");
                    l.html("<strong>Used </strong>"+Coova.time(data.json.summary.time_used)+"<strong> Available </strong>"+ Coova.time(data.json.summary.time_avail));

                    var sd = $("#slider-time");
                    sd.val(pers_time_used).slider("refresh");

                    if( (pers_time_used >= 0)&(pers_time_used < 50)){
                        $('#div_time [aria-valuemin="0"]').removeClass('slider_bg_red');
                        $('#div_time [aria-valuemin="0"]').removeClass('slider_bg_yellow');
                        $('#div_time [aria-valuemin="0"]').addClass('slider_bg_green');
                        $('#div_time [role="application"]').removeClass('slider_bg_red');
                        $('#div_time [role="application"]').removeClass('slider_bg_yellow');
                        $('#div_time [role="application"]').addClass('slider_bg_green');
                    }

                    if( (pers_time_used >= 50)&&(pers_time_used <=75)){
                        $('#div_time [aria-valuemin="0"]').removeClass('slider_bg_green');
                        $('#div_time [aria-valuemin="0"]').removeClass('slider_bg_red');
                        $('#div_time [aria-valuemin="0"]').addClass('slider_bg_yellow');
                        $('#div_time [role="application"]').removeClass('slider_bg_green');
                        $('#div_time [role="application"]').removeClass('slider_bg_red');
                        $('#div_time [role="application"]').addClass('slider_bg_yellow');
                    }

                    if( (pers_time_used >= 75)&&(pers_time_used <=100)){
                        $('#div_time  [aria-valuemin="0"]').removeClass('slider_bg_green');
                        $('#div_time  [aria-valuemin="0"]').removeClass('slider_bg_yellow');
                        $('#div_time  [aria-valuemin="0"]').addClass('slider_bg_red');
                        $('#div_time  [role="application"]').removeClass('slider_bg_green');
                        $('#div_time  [role="application"]').removeClass('slider_bg_yellow');
                        $('#div_time  [role="application"]').addClass('slider_bg_red');
                    }
                }

                //If the data available is 'NA' we must hide the time div
                if(data.json.summary.data_avail == 'NA'){
                    $('#div_data').hide().trigger( 'updatelayout' );;
                }else{
                    $('#div_data').show().trigger( 'updatelayout' );;
                    var data_total     = data.json.summary.data_used + data.json.summary.data_avail;
			        var pers_data_used = (data.json.summary.data_used / data_total) * 100;

                    var l = $("#lbl_slider-data");
                    l.html("<strong>Used </strong>"+Coova.bytes(data.json.summary.data_used)+"<strong> Available </strong>"+ Coova.bytes(data.json.summary.data_avail));

                    var sd = $("#slider-data");
                    sd.val(pers_data_used).slider("refresh");

                    if( (pers_data_used >= 0)&(pers_data_used < 50)){
                        $('#div_data [aria-valuemin="0"]').removeClass('slider_bg_red');
                        $('#div_data [aria-valuemin="0"]').removeClass('slider_bg_yellow');
                        $('#div_data [aria-valuemin="0"]').addClass('slider_bg_green');
                        $('#div_data [role="application"]').removeClass('slider_bg_red');
                        $('#div_data [role="application"]').removeClass('slider_bg_yellow');
                        $('#div_data [role="application"]').addClass('slider_bg_green');
                    }

                    if( (pers_data_used >= 50)&&(pers_data_used <=75)){
                        $('#div_data [aria-valuemin="0"]').removeClass('slider_bg_green');
                        $('#div_data [aria-valuemin="0"]').removeClass('slider_bg_red');
                        $('#div_data [aria-valuemin="0"]').addClass('slider_bg_yellow');
                        $('#div_data [role="application"]').removeClass('slider_bg_green');
                        $('#div_data [role="application"]').removeClass('slider_bg_red');
                        $('#div_data [role="application"]').addClass('slider_bg_yellow');
                    }

                    if( (pers_data_used >= 75)&&(pers_data_used <=100)){
                        $('#div_data  [aria-valuemin="0"]').removeClass('slider_bg_green');
                        $('#div_data  [aria-valuemin="0"]').removeClass('slider_bg_yellow');
                        $('#div_data  [aria-valuemin="0"]').addClass('slider_bg_red');
                        $('#div_data  [role="application"]').removeClass('slider_bg_green');
                        $('#div_data  [role="application"]').removeClass('slider_bg_yellow');
                        $('#div_data  [role="application"]').addClass('slider_bg_red');
                    }
                }
            });
    },
    f_screen: function(){
        $('#acct_it').html(Coova.time(Coova.p_status.accounting.idleTime));
        $('#acct_st').html(Coova.time(Coova.p_status.accounting.sessionTime));
        $('#acct_di').html(Coova.bytes(Coova.p_status.accounting.inputOctets));
        $('#acct_do').html(Coova.bytes(Coova.p_status.accounting.outputOctets));
        var t = Coova.p_status.accounting.inputOctets + Coova.p_status.accounting.outputOctets;
        $('#acct_dt').html(Coova.bytes(t));

        //Top URL
        var str = Coova.p_status.redir.originalURL.match(/http:\/\/1\.0\.0\.0/);
        if(str != null){
            Coova.p_status.redir.originalURL = Coova.p_default;
        }
        $('#span_url_original').html(Coova.p_status.redir.originalURL);
        $('#a_url_original').attr('href',Coova.p_status.redir.originalURL);
    },
    f_cookie_check: function(){

        //We need to see if the cookies was set and then we have to use the cookie values to connect.
        //If the login with cookie values fail we need to clear the cookies...
        if(($.cookie("coova_un") == undefined)&&($.cookie("coova_pw") == undefined)){
            if(Coova.p_debug == true){
                console.log("cookie is NOT set");
            }
            
        }else{
            //Set the values and attempt to log in... if it fail it will clear the cookies...
            if(Coova.p_debug == true){
                console.log("Cookie is set ",$.cookie("coova_un"),$.cookie("coova_pw"));
            }
            
            
            Coova.p_username = $.cookie("coova_un");
            Coova.p_password = $.cookie("coova_pw");
            Coova.f_get_latest_challenge();
        }

    },
    f_connect: function(){

        //Before we do anything, test if username and password is not empty        
        if(($('#Username').val().length == 0)||($('#Password').val().length == 0)){
            $('#connect_fb').html("Required values missing - Please supply");
            return;

        }else{  //Set the username 
            //Test to see if the #Username contains an @<realm> and if not, add a default realm...
            Coova.p_username = escape($('#Username').val());
            if(!Coova.p_username.match(/^.+@.+/)) {
                    if(Coova.p_debug == true){
                        console.log("No realm specified");
                    }
                     
                    Coova.p_username = Coova.p_username+Coova.p_realm;
            }
            if(Coova.p_debug == true){
                console.log(Coova.p_username);
            }
            
            //Set the password
            Coova.p_password = $('#Password').val();
 
            //Check if we should store these values as cookies
            if($('#remember').is(':checked')){
                if(Coova.p_debug == true){
                    console.log("We need to remember");
                }  
                $.cookie("coova_un", Coova.p_username, { expires: 70 });
                $.cookie("coova_pw", Coova.p_password, { expires: 70 });
            }

            //Get latest Challenge and complete login attempt...
            Coova.f_get_latest_challenge();
        }  
    },
    f_get_latest_challenge: function(){

        //Get the latest challenge
        if(Coova.p_debug == true){
            console.log("Get latest Challenge form Captive Portal");
        }
        var url_status = 'http://'+Coova.p_uamip+':'+Coova.p_uamport+'/json/status?callback=?';
        $.getJSON(url_status, 
            function(data) {
                if(Coova.p_debug == true){
                    console.log(data);
                }
                Coova.f_enc_pwd(data.challenge);
                //If we have feedback we take the challenge and the password and ask the web service to encrypt it for us
        });
    },
    f_connect_voucher: function(){
         if($('#voucher_password').val().length == 0){
            $('#connect_fb_voucher').html("Required values missing - Please supply");
            return;
        }
        var password = escape($('#voucher_password').val());

        //We need to find the username for this voucher's 'code'
        $.getJSON(Coova.p_url_voucher_name+password+'&callback=?',
            function(data){
                //If it found a voucher username set it else return and error....
               // console.log(data);
                if(data.voucher.exists == true){
                    Coova.p_username = data.voucher.name;
                    Coova.p_password = $('#voucher_password').val();

                    //Check if we should store these values as cookies
                    if($('#voucher_remember').is(':checked')){
                        if(Coova.p_debug == true){
                            console.log("We need to remember");
                        }
                        
                        $.cookie("coova_un", Coova.p_username, { expires: 70 });
                        $.cookie("coova_pw", Coova.p_password, { expires: 70 });
                    }
                    Coova.f_get_latest_challenge();
                }else{
                    $('#connect_fb_voucher').html("Invalid code - Please double check");
                    return;
                } 
        });  
    },
    f_enc_pwd: function(challenge){

        var url_uam = Coova.p_url_uam + challenge + '&password=' + Coova.p_password + '&callback=?';
        if(Coova.p_debug == true){
            console.log("Get enc pwd from: "+url_uam);
        }
     
        $.getJSON(url_uam, 
            function(data) {
               if(Coova.p_debug == true){
                    console.log(data);
                }
               Coova.f_login(data.response);
        });
    },
    f_login: function(enc_pwd){

        var url_login = 'http://'+Coova.p_uamip+':'+Coova.p_uamport+'/json/logon?username=' + Coova.p_username + '&password='  + enc_pwd+'&callback=?';
        if(Coova.p_debug == true){
            console.log("Try to log in with: "+url_login);
        }
       
        $.getJSON(url_login, 
            function(data) {

                if(Coova.p_debug == true){
                    console.log(data);
                }
                if(data.clientState == 0){ //This means the authentication failed (We show in BOTH the message)
                    if(Coova.p_debug == true){
                        console.log("Login Failed");
                    }

                    //Clear any cookies since it may have caused failure
                    $.cookie("coova_un", null);
                    $.cookie("coova_pw", null);

                    if(data.message != undefined){
                        $('#connect_fb').html(data.message);
                        $('#connect_fb_voucher').html(data.message);
                    }else{
                        $('#connect_fb').html('Authentication failure please try again');
                        $('#connect_fb_voucher').html('Authentication failure please try again');
                    }
                }

                if(data.clientState == 1){  //Authentication passed, so we can show move on and show stats
                    if(Coova.p_debug == true){
                        console.log("Login Passed");
                    }
                    Coova.f_refresh();
                }
        });
    },

    time:   function ( t , zeroReturn ) {

        if ( typeof(t) == 'undefined' ) {
            return 'Not available';
        }

        t = parseInt ( t , 10 ) ;
        if ( (typeof (zeroReturn) !='undefined') && ( t === 0 ) ) {
            return zeroReturn;
        }

        var h = Math.floor( t/3600 ) ;
        var m = Math.floor( (t - 3600*h)/60 ) ;
        var s = t % 60  ;

        var s_str = s.toString();
        if (s < 10 ) { s_str = '0' + s_str;   }

        var m_str = m.toString();
        if (m < 10 ) { m_str= '0' + m_str;    }

        var h_str = h.toString();
        if (h < 10 ) { h_str= '0' + h_str;    }

        if      ( t < 60 )   { return s_str + 's' ; }
        else if ( t < 3600 ) { return m_str + 'm' + s_str + 's' ; }
        else                 { return h_str + 'h' + m_str + 'm' + s_str + 's'; }
    },
    bytes: function ( b , zeroReturn ) {

	    if(b == 'NA'){
		    return b;
	    }

        if ( typeof(b) == 'undefined' ) {
            b = 0;
        } else {
            b = parseInt ( b , 10 ) ;
        }

        if ( (typeof (zeroReturn) !='undefined') && ( b === 0 ) ) {
            return zeroReturn;
        }
        var kb = Math.round(b/1024);
        if (kb < 1) return b  + ' '+'Bytes';

        var mb = Math.round(kb/1024);
        if (mb < 1)  return kb + ' '+'Kilobytes';

        var gb = Math.round(mb/1024);
        if (gb < 1)  return mb + ' '+'Megabytes';

        return gb + ' '+'Gigabytes';
    }
  
};

var Gallery = {   
    p_url_img_scaler: '',   //We have to fill this before using PHP
    f_img_loader: function(page){
        var header  = $(page).find('[data-role="header"]');
        var footer  = $(page).find('[data-role="footer"]');
        var content = $(page).find('[data-role="content"]')
        var gal     = $(page).find('[data-type="gallery"]');
        var i       = $(page).find('img');
        //  This will calculate the available height for the content div
        var the_content_height = ($(window).height() - header.height() - footer.height());
        //  console.log("Window "+$(window).height()+ " Header "+ header.height() + " Footer "+ footer.height()+ " Gal Info "+ gal.height()+ " page "+$(this).find('[data-page-type="image"]').height());
        //  Set the height of the content div
        content.height(the_content_height);
        var the_image_height = the_content_height - gal.height();
        //  console.log("Image Height "+the_image_height);
        var the_width = gal.width();
        //  console.log(Gallery.p_url_img_scaler+'?height='+the_image_height+'&width='+the_width+'&image='+i.attr('alt'));
        i.attr("src",Gallery.p_url_img_scaler+'?height='+the_image_height+'&width='+the_width+'&image='+i.attr('alt'));
    }
};
        

