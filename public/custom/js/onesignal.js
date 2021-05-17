"use strict";
$(document).ready(function() {
    
    var OneSignal = window.OneSignal || [];

   
    OneSignal.push(["init", {
        appId: ONESIGNAL_APP_ID,
        subdomainName: "",
        autoRegister: true,
        promptOptions: {
            actionMessage: "We'd like to show you notifications for the latest orders",
            acceptButtonText: "ALLOW",
            cancelButtonText: "NO THANKS"
        }
    }]);


    var OneSignal = OneSignal || [];
    OneSignal.push(function(){
        
        console.log("User id to save in OS: "+USER_ID)
        OneSignal.setExternalUserId(USER_ID);

        OneSignal.on('subscriptionChange', function(isSubscibed){
            console.log("The user's subscription state is now", isSubscibed);

            /*OneSignal.sendTag("user_id","4444", function(tagsSent) {
                console.log("Tags have finished sending!");
            });*/
        });

        var isPushSupported = OneSignal.isPushNotificationsSupported();
        if(isPushSupported)
        {
            OneSignal.getUserId( function(userId) {
                console.log("userId", userId);
            });

            OneSignal.isPushNotificationsEnabled().then(function(isEnabled)
            {
                if(isEnabled)
                {
                    //console.log(OneSignal)
                    console.log("Push nofitications are enabled");
                }else {
                    OneSignal.showHttpPrompt();
                    console.log("Push notifications are not enabled yet.");
                }
            });
        }else{
            console.log("Push notifications are not supported.");
        }
    });
});
