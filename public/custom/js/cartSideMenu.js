"use strict";
function openNav() {
    //document.getElementById("cartSideNav").style.width = "450px";
    $("#cartSideNav").addClass('sidenav-cart-open').removeClass('sidenav-cart-close');
}

function closeNav() {
   // document.getElementById("cartSideNav").style.width = "0";
   $("#cartSideNav").addClass('sidenav-cart-close').removeClass('sidenav-cart-open');
}

$(window).scroll(function(){
    if ($(window).scrollTop() >= 1) {
        $('#navbar-main').addClass('custom-nav');
    }
    else {
        $('#navbar-main').removeClass('custom-nav');
    }
});
