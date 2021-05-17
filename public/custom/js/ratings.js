"use strict";
$(document).ready(function(){

    checkIfRated();

    $('#success-box-ratings').hide()
    $('#save-ratings').hide()

    /* 1. Visualizing things on Hover - See next part for action on click */
    $('#stars li').on('mouseover', function(){
      var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

      // Now highlight all the stars that's not after the current hovered star
      $(this).parent().children('li.star').each(function(e){
        if (e < onStar) {
          $(this).addClass('hover');
        }
        else {
          $(this).removeClass('hover');
        }
      });

    }).on('mouseout', function(){
      $(this).parent().children('li.star').each(function(e){
        $(this).removeClass('hover');
      });
    });


    /* 2. Action to perform on click */
    $('#stars li').on('click', function(){
      var onStar = parseInt($(this).data('value'), 10); // The star currently selected
      var stars = $(this).parent().children('li.star');

      for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass('selected');
      }

      for (var i = 0; i < onStar; i++) {
        $(stars[i]).addClass('selected');
      }

      // JUST RESPONSE (Not needed)
      var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
      $("#rating_value").val(ratingValue);

      var msg = "";
      /*if (ratingValue > 1) {
          msg = "Thanks! You rated this " + ratingValue + " stars.";
      }
      else {
          msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
      }*/
      msg = "Thanks! You rated this " + ratingValue + " stars.";

      //responseMessage(msg);

      $('#save-ratings').show()

    });
  });

  function responseMessage(msg) {
    $('.success-box').fadeIn(200);
    $('.success-box div.text-message').html("<span>" + msg + "</span>");
  }

  function isRated(rating){
    $('#stars li').unbind();

    var stars = $('#stars li').parent().children('li.star');

    for (var i = 0; i < parseInt(rating); i++) {
      $(stars[i]).addClass('selected');
    }

    $('#order_comment').hide()
  }

  function checkIfRated(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'GET',
        url: '/check/rating/'+$("#order_id").val(),
        dataType: 'json',
        //data: { rating: ratingValue},
        success:function(response){
            if(response.is_rated){
                isRated(response.rating);
            }
        }, error: function (response) {
           //alert(response.responseJSON.errMsg);
        }
    })


  }
