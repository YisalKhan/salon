jQuery(function ($) {
    sln_createSelect2Full($);
});

function sln_createSelect2Full($) {
    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    });
    $('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    }).focus(function () {
        $(this).select2('open');
    });
    $('.sln-select select').each(function() {
        $(this).select2({
            containerCssClass: 'sln-select-rendered ' + ($(this).attr('data-containerCssClass') ? $(this).attr('data-containerCssClass') : ''),
            dropdownCssClass: 'sln-select-dropdown',
            theme: "sln",
            width: '100%',
            templateResult: function(state){ if(!state.id) return state.text;return $('<span data-value="'+state.id+'">'+state.text+'</span>');},
            placeholder: function(){
                $(this).data('placeholder');
            }
        }).focus(function () {
            $(this).select2('open');
        })
    });

    sln_createSelect2();
    sln_createSelect2NoSearch();
}

function sln_createSelect2() {
    jQuery('.sln-select-wrapper select').select2({
        tags: "true",
        width: '100%'
    });
}

function sln_createServiceLineSelect2() {
    jQuery('.sln-booking-service-line select').select2({
        tags: "true",
        width: '100%',
        theme: "sln",
        width: '100%',
        templateResult: function(state){ if(!state.id) return state.text;return $('<span data-value="'+state.id+'">'+state.text+'</span>');},
        placeholder: function(){
            $(this).data('placeholder');
        }
    });
}


function sln_createSelect2NoSearch() {
    jQuery('.sln-select-wrapper-no-search select').select2({
        tags: "true",
        width: '100%',
        minimumResultsForSearch: Infinity
    });
}