
function clear_field (field) {
    $(field).val('');
}

function previousStep () {
    $('#first_step').slideUp();  
    $('#second_step').slideDown();
}