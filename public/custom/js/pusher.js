
"use strict";
$(document).ready(function() {
    // Enable pusher logging - don't include this in production
    if(PUSHER_APP_KEY){

        var audio = new Audio('https://soundbible.com/mp3/old-fashioned-door-bell-daniel_simon.mp3');

        Pusher.logToConsole = true;

        var pusher = new Pusher(PUSHER_APP_KEY, {
            cluster: PUSHER_APP_CLUSTER
        });

        var channel = pusher.subscribe('user.'+USER_ID);
        channel.bind('callwaiter-event', function(data) {
            notify(data.msg + " " + data.table.name);
            audio.play();
        });

        channel.bind('neworder-event', function(data) {
            notify(data.msg + " #" + data.order.id);
            audio.play();
        });
    }
});

function notify(text){
    $.notify.addStyle('custom', {
        html: "<div><strong><span data-notify-text /></strong></div>",
        classes: {
            base: {
                "position": "relative",
                "margin-bottom": "1rem",
                "padding": "1rem 1.5rem",
                "border": "1px solid transparent",
                "border-radius": ".375rem",

                "color": "#fff",
                "border-color": "#37d5f2",
                "background-color": "#37d5f2",
            },
            success: {
                "color": "#fff",
                "border-color": "#37d5f2",
                "background-color": "#37d5f2",
            }
        }
        });

        $.notify(text,{
            position: "bottom right",
            style: 'custom',
            className: 'success',
            //autoHideDelay: 50000,
            autoHide: false,
        }
    );
  }
