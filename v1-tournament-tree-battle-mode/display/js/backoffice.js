$(document).on('change', '.animateInput input', function () {
    var input = $(this);
    if (input.val().length) {
        input.addClass('populated');
    } else {
        input.removeClass('populated');
    }
});

$(document).on('change', '.animateInput textarea', function () {
    var textarea = $(this);
    if (textarea.val().length) {
        textarea.addClass('populated');
    } else {
        textarea.removeClass('populated');
    }
});