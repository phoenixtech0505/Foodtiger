
"use strict";
$(document).ready(function() { 
    $("#show-hide-filters").on("click",function(){

        if($(".orders-filters").is(":visible")){
            $("#button-filters").removeClass("ni ni-bold-up")
            $("#button-filters").addClass("ni ni-bold-down")
        }else if($(".orders-filters").is(":hidden")){
            $("#button-filters").removeClass("ni ni-bold-down")
            $("#button-filters").addClass("ni ni-bold-up")
        }

        $(".orders-filters").slideToggle();
    });
});
