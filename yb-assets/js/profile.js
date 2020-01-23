// JavaScript Document
function checkEmail() {
    if ($("#current_email").val() == $("#email").val()) {
        return false;
    } else {
        return '/request/emailAvailable';
    }

}

$(document).ready(function () {
    $("#profile").validate({
        rules: {
            first: "required",
            last: "required",
            password: {
                required: false, // not required 
                minlength: 8	 // but if it has a value it must be valid
            },
            password_confirm: {
                equalTo: "#password",
                required: false
            },
            email: {
                required: true,
                email: true,
                maxlength: 127,
                remote:{
                            type:"post",
                            url:XHR_PUBLIC_PATH + 'emailAvailable',
                             //success:function(d){console.log(d);console.log(typeof d);},
                                   data:{
                                       ybr_token: sessiontoken,
                                       email:function(){
                                          
                                               return $('#profile').find('input[name=email]').val();
                                           
                                                
                                        },
                                        userid:function(){
                                            return $('#profile').find('input[name=userid]').val();
                                        }
                                       
                                   }
                               }
            }
//            ,
//            username: {
//                required: true,
//                maxlength: 64,
//                remote: '/request/usernameAvailable'
//            }
        },
        messages: {
            email: {
                remote: 'An account already exists with this address'
            },
//            username: {
//                remote: 'This Username already exists'
//            },
            password_confirm: {
                equalTo: 'Does not match the password above'
            }
        }
    });

});// end document.ready()