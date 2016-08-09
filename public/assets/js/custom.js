function toggle_fields(){
    if ($('input, select').attr('disabled')) {
        $('input, select').removeAttr('disabled');
        $('#edit, #save').toggleClass('hidden');
        $('#submit-btn').fadeOut('fast');
    } else {
        $('input, select').attr('disabled', 6);
        $('#edit, #save').toggleClass('hidden');
    }
}