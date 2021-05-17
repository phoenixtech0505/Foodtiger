"use strict";
$(document).ready(function() {
    
    $('.select2').select2({
        width: '100%',
    });

    $('.select2').addClass('form-control');
    $('.select2-selection').css('border','0');
    $('.select2-selection__arrow').css('top','10px');
    $('.select2-selection__rendered').css('color','#8898aa');

    //pages links
    $(".showAsLink").on('change', function() {
        var value;
        if ($(this).is(':checked')) { value = 1 } else { value = 0 }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: '/change/'+$(this).attr("pageid"),
            dataType: 'json',
            data: { value: value},
            success:function(response){
                if(response.status){
                    $(this).attr("checked");
                }
            }, error: function (response) {
            }
        })
    });

    $('#btn-submit-time-prepare').hide()

    //modal time to prepare buttons
    $(".btn-time-to-prepare").on("click",function(){
        $(".btn-time-to-prepare").removeClass("active");
        $(this).addClass("active");

        $("#time_to_prepare").val(parseInt($(this).attr('value')));

        $('#btn-submit-time-prepare').show()
    });
});
