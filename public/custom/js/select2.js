"use strict";
$(document).ready(function() {

    $('.select2').select2({
        width: '100%',
    });

    $('#assign-driver-select').select2({
        width: '100%',
        allowClear: true,
        dropdownParent: $("#modal-asign-driver"),
    });

    $('#time_to_prepare_select').select2({
        width: '100%',
        allowClear: true,
        dropdownParent: $("#modal-time-to-prepare"),
    });

    $('#addressID').select2({
        width: '100%',
        allowClear: true,
    });

    $('#new_address_checkout').select2({
        dropdownParent: $("#modal-order-new-address"),
        placeholder: "",
        allowClear: true,
        width: '100%',
        ajax: {
            url: '/new/address/autocomplete',
            dataType: 'json',
            //delay: 200,
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.results,
                    pagination: {
                        more: (params.page * 10) < data.total
                    }
                };
            },
            minimumInputLength: 3,
        }
    });

    $('.select2').addClass('form-control');
    $('.select2-selection').css('border','0');
    $('.select2-selection').css('margin-bottom','10px');
    $('.select2').css('padding-bottom','35px');
    $('.select2-selection__arrow').css('top','10px');
    $('.select2-selection__rendered').css('color','#8898aa');
});
