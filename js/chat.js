//chat
function send_message() {
    var message = $('#chat_text').val();

    $.post('../action.php', {send_message: message}, result);
    function result(data) {
        $('#chat_box').append(data);
        $('#chat_box')[0].scrollTop = $('#chat_box')[0].scrollHeight;
        $('#chat_text').val('');
    }
}

setInterval(function () {
    $.post('../action.php', {update_chat: 0}, result);
    function result(data) {
        if ($('#chat_box')[0].scrollHeight - $('#chat_box')[0].scrollTop === 210) {
            $('#chat_box').append(data);
            $('#chat_box')[0].scrollTop = $('#chat_box')[0].scrollHeight;
        } else {
            $('#chat_box').append(data);
        }
    }
}, 5000);

function show_stickers() {
    if ($('#smile_box').css('display') === 'none') {
        $('#chat').css('height', 437);
        $('#smile_box').css('display', 'block');
    } else {
        $('#chat').css('height', 307);
        $('#smile_box').css('display', 'none');
    }
}

function insert_sticker(num) {
    $('#chat_text').val($('#chat_text').val() + ' :' + num + ':');
    $('#chat_text').focus();
}












