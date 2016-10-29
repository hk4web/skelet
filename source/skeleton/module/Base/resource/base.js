$(function() {
    //  Focus
    $('.focus').focus();

    $('body').on('click', '.go-to-previous', function(e) {
        history.back();
    });

    //  Display Tab
    $('.nav-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    //  Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    //  Ajax Modal
    $('body').on('click', '.ajaxModal', function(e) {
        e.preventDefault();
        $('#modal').remove();
        $('body').append('<div id="modal" class="modal"></div>');
        $('#modal').load($(this).attr('href'));
        $('#modal').modal();
    });

    //  Ajax Form Submit
    $('body').on('click', '.ajaxFormSubmit', function(e) {
        e.preventDefault();
        $('#'+$(this).attr('data-target')).submit();
    });

    //  Date & Time Picker
    $('.date-element').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $('.time-element').datetimepicker({
        format: 'HH:mm'
    });

    //  Tristate Checkbox (Based on Font Awesome Icon)
    $('body').on('click', '.tristate', function(e) {
        if ($(this).hasClass('fa-minus-square-o')) {
            $(this).removeClass('fa-minus-square-o')
            $(this).addClass('fa-square-o');
            $('#'+$(this).attr('data-target')).val("0");
        } else if ($(this).hasClass('fa-square-o')) {
            $(this).removeClass('fa-square-o')
            $(this).addClass('fa-check-square-o');
            $('#'+$(this).attr('data-target')).val("1");
        } else if ($(this).hasClass('fa-check-square-o')) {
            $(this).removeClass('fa-check-square-o')
            $(this).addClass('fa-minus-square-o');
            $('#'+$(this).attr('data-target')).val("");
        }
    });
});