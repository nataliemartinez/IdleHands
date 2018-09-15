const $ = jQuery.noConflict();

var hourHeight = 0;

/*
 * Document ready functions
 */
$(document).ready( function() {
    /*--------------------------------*\
        HEADER FUNCTIONS
    \*--------------------------------*/
    
    /*
     * Add task modal
     */
    $('#add-task-button').click(function() {
        $('#add-task-modal').addClass('active');
        $('body').append('<div class="overlay"></div>');
        $('.overlay').fadeIn(500);
    });
    
    $('#add-task-button').keypress(function() {
        $('#add-task-button').click();
    });
    
    $('#close-task-modal').click(function() {
        $('#add-task-modal').removeClass('active');
        $('.overlay').fadeOut(500);
        setTimeout(function() {
            $('.overlay').remove();
        }, 500);
    });
    
    $('#close-task-modal').keypress(function() {
        $('#close-task-modal').click();
    });
    
    $('.overlay').click(function() {
        $('#close-task-modal').click();
    });
    
    $('#add-task-duration').durationPicker({
        days: {
            label: 'days',
            min: 0,
        }, hours: {
            label: 'hours',
            min: 0,
            max: 24
        },
        minutes: {
            label: 'minutes',
            min: 0,
            max: 60
        },
    });
    
    $('#add-task-deadline').datepicker({
        prevText: "<",
        nextText: ">"
    });
    
    $('#scheduled-checkbox').change(function() {
        if ($(this).prop('checked') == true) {
            $('#add-task-form .unique-fields').fadeOut(400);
            setTimeout(function() {
                $('#add-event-form .unique-fields').fadeIn(400);
            },400);
            $('#styled-scheduled-checkbox').addClass('active');
        } else {
            
            $('#add-event-form .unique-fields').fadeOut(400);
            setTimeout(function() {
                $('#add-task-form .unique-fields').fadeIn(400);
            },400);
            $('#styled-scheduled-checkbox').removeClass('active');
        }
    });
    
    $('#styled-scheduled-checkbox').click(function() {
        $('#scheduled-checkbox').click();
    });
    
    $('#styled-scheduled-checkbox').keypress(function() {
        $('#scheduled-checkbox').click();
    });
    
    $('#add-task-name').change(function() {
        $('#add-event-name').val($(this).val());
    });
    
    $('#add-event-name').change(function() {
        $('#add-task-name').val($(this).val());
    });
    
    $('#add-event-start-date').datepicker({
        prevText: "<",
        nextText: ">"
    });
    
    $('#add-event-end-date').datepicker({
        prevText: "<",
        nextText: ">"
    });
    
    // end header functions
    
    /*--------------------------------*\
        EVENTS / TASKS FUNCTIONS
    \*--------------------------------*/
    hourHeight = $('.hour').eq(0).outerHeight();
    
    /*
     * Starred events
     */
    $('.event .pentagram').click(function() {
        $(this).toggleClass('inactive');
        if ($(this).hasClass('inactive')) {
            $(this).parent().parent().attr('data-starred', 'false');
        } else {
            $(this).parent().parent().attr('data-starred', 'true');
        }
    });
    
    $('.event .pentagram').keypress(function() {
        $(this).click();
    });
    
    /*
     * Position events
     */
    $('.event').each(function() {
        position_event($(this));
    });
        
    // end events / tasks functions
    
    
});

function position_event( $event ) {
    let time = $event.attr('data-time');
    let minute = time % 100;
    let hour = (time - minute) / 100 - 1;
    
    let posY = ( hour * hourHeight ) + Math.floor( ( minute / 60 ) *  hourHeight );
    console.log(posY);
    
    $event.css('top', posY + 'px');
}