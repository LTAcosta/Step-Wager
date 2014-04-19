
// Activate the correct nav page
$( document ).ready(function() {
    var url = window.location;
    // Will only work if string in href matches with location
    $('ul.nav a[href="'+ url +'"]').parent().addClass('active');

    // Will also work for relative and absolute hrefs
    $('ul.nav a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');
});

// Resize the wager boxes
$( document ).ready(function() {
    resize_wagers();
});

$(window).resize(function(){
    resize_wagers();
});

function resize_wagers(){
    if (window.matchMedia('(min-width: 992px)').matches) {
        var maxHeight = 0;

        $(".wager-group").each(function(){
            if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
        });

        $(".wager-group").height(maxHeight);
    } else {
        $(".wager-group").height("auto");
    }
}

// Setup pickers
$( document ).ready(function() {
    $("select").selectpicker({style: 'btn btn-primary btn-block'});
    if (window.selectedOppId) {
        $("#friend-picker").selectpicker('val', window.selectedOppId);
    }

    setup_start_date();
    setup_end_date();
    setup_step_counter();

    $("#friend-modal").modal({ keyboard: false, show: false });
    $("#friend-modal").on('hidden.bs.modal', function(){
        window.location = 'wagers.php';
    })
    if (window.noFriends) {
        $("#friend-modal").modal('show');
    }
});

function setup_start_date(){
    // jQuery UI Datepicker JS init
    var datepickerSelector = '#startdate';
    $(datepickerSelector).datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "d MM yy",
        yearRange: '-1:+1',
        minDate: 0,
        defaultDate: +1
    }).prev('.btn').on('click', function (e) {
        e && e.preventDefault();
        $(datepickerSelector).focus();
    });

    // Now let's align datepicker with the prepend button
    $(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).prev('.btn').outerWidth()});
}

function setup_end_date(){
    // jQuery UI Datepicker JS init
    var datepickerSelector = '#enddate';
    $(datepickerSelector).datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "d MM yy",
        yearRange: '-1:+1',
        minDate: 0,
        defaultDate: +2
    }).prev('.btn').on('click', function (e) {
        e && e.preventDefault();
        $(datepickerSelector).focus();
    });

// Now let's align datepicker with the prepend button
    $(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).prev('.btn').outerWidth()});
}

function setup_step_counter(){
    $( "#step-counter" ).customspinner({
        min: 0,
        step: 1000
    }).on('focus', function () {
        $(this).closest('.ui-spinner').addClass('focus');
    }).on('blur', function () {
        $(this).closest('.ui-spinner').removeClass('focus');
    });
}

function typeChanged(typeSelect){
    if (typeSelect.value == "min"){
        $('#type-column').switchClass("col-xs-12", "col-xs-7", 500);
        $('#type-column').css("overflow", "visible");
        setTimeout(function(){
            $('#spinner-column').fadeIn(200);
        }, 500);
    } else {
        $('#spinner-column').fadeOut(200);
        setTimeout(function(){
            $('#type-column').switchClass("col-xs-7", "col-xs-12", 500);
            $('#type-column').css("overflow", "visible");
        }, 200);
    }
}
