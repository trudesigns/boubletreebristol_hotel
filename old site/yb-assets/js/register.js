// JavaScript Document
//alert(sessiontoken);
$(document).ready(function() {

    $("#register").validate({
           onkeyup:false,//every time the data gets changed we re-validate
        onfocusout: function(element) {
             $(element).valid();
        },
        rules: {
            first: "required",
            last: "required",
            password: {
                required: true,
                minlength: 8
            },
            password_confirm: {
                equalTo: "#password",
                required: true
            },
            email: {
                required: true,
                email: true,
                maxlength: 127,
                remote:{
                     type:"post",
                    url:XHR_PUBLIC_PATH + 'emailAvailable',
                   data:{
                       ybr_token: sessiontoken,
                   }
                }
               
            }/*,
            username: {
                required: true,
                maxlength: 64,
                remote: '/request/usernameAvailable/'+sessiontoken
            }*/
        },
        messages: {
            email: {
                remote: 'An account already exists with this address'
            },
            /*username: {
                remote: 'This Username already exists'
            },*/
            password_confirm: {
                equalTo: 'Does not match the password above'
            }
        }
    });

}); // end document.ready()