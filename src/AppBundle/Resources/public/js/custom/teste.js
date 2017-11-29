jQuery(document).ready(function ($) {

    $('#paginator').datepaginator({
        selectedDateFormat: 'DD-MM-YYYY'
    });

    $('#paginator').on('selectedDateChanged', function (event, date) {
        console.log(date._i);
    });

});