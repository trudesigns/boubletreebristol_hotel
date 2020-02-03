$(function(){
     /***
      * CMS SECURITY
      */
     var delay =  20*60*1000;//20MIN
   // var delay = 2000;
    setInterval(function(){
        
            if($('main#yb-wrap').length>0){
                $.ajax({
                    type:"post",
                    url:XHR_PUBLIC_PATH + 'extendSession',
                    data:{ybr_loggedin: sessiontoken},
                    dataType:"json",
                    success:function(data){
                        if(!data){
                                $('#reenter-password').modal('show');
                               // reenterPassword();
                        } 
                    }
                });
            }
            if($('main#wrapper').length>0){
               // alert("Sddssdf");
                $.ajax({
                    type:"post",
                    url:XHR_PUBLIC_PATH + 'extendSession',
                    data:{ybr_token: sessiontoken},
                    dataType:"json",
                    success:function(data){
                        if(!data){
//                                $('#reenter-password').modal('show');
                               // reenterPassword();
                               window.location.reload();
                        } 
                        
                    }
                });
            }
        
   },delay);
})