
//  Email validation
var cf_validation_email = function(dto, success, error){
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if(re.test(dto.text))
        return success();
    return error();
};

// Phone validation
var cf_validation_tel = function(dto, success, error){
    var phoneno = /^\d{10}$/;  
    var inputtxt = dto.text
    if(inputtxt.match(phoneno))  
    {  
      return success();  
    }  
    return error();
};


// Redirection function
function redirect_to(link,timeout = 2000){
    if(link.length>0){
        setTimeout(function(){window.location.href = link;},timeout);
    }
}

// When page loaded
window.onload = function(){

    if(!window.ConversationalForm){
        // Setting conversational form
        window.ConversationalForm= new cf.ConversationalForm({
            formEl: document.getElementById(cf_datas.slug),
            context: document.getElementById(cf_datas.slug+"Container"),
            dictionaryData: cf_datas.dictionaryData,
            dictionaryRobot: cf_datas.dictionaryRobot,
            submitCallback: function(){
                var data = window.ConversationalForm.getFormData();

                function add_robot_message(message){
                    if(message.length>0){
                        var msg = message;
                    }
                    else{
                        var msg = cf_datas.form_sent_message;
                    }

                    window.ConversationalForm.addRobotChatResponse(msg);
                }

                if(cf_datas.send_data_to.length>0){

                    // Sending datas to receiver
                    var request = new XMLHttpRequest();
                    request.open('POST', cf_datas.send_data_to, true);
                    request.onerror = function(e){
                        add_robot_message(cf_datas.form_not_sent_message);
                    };
                    request.onload = function(e){
                        add_robot_message(cf_datas.confirmation_message);
                        redirect_to(cf_datas.redirect_link);
                    };
                    request.send(data);

                }
                else{
                    add_robot_message(cf_datas.confirmation_message);
                    redirect_to(cf_datas.redirect_link);
                }


                if(cf_datas.mailto.length>0){
                    
                    data.append("action","mailto");
                    data.append("slug",cf_datas.slug);

                    // Ajax call to mail function
                    var request = new XMLHttpRequest();
                    request.open('POST', '/wp-admin/admin-ajax.php', true);
                    request.onerror = function(e){
                        console.log(e);
                    };
                    request.onload = function(e){
                        console.log(e);
                    };
                    request.send(data);
                }
            },
            userImage:cf_datas.user_image,
            robotImage:cf_datas.robot_image
        });
    }



    
};

