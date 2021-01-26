function login() {
    var username = $('#username').val();
    var password = $('#password').val();

    $.post('../action.php', {login: 0, username: username, password: password}, result);
    function result(data) {
        /*
         var obj = JSON.parse(data);
         
         if (obj.error) {
         $('#account').html(obj.answer);
         } else {
         $(location).attr('href', '/');
         }
         */
        $(location).attr('href', '/');
    }
}

function login1() {
    var username = $('#username11').val();
    var password = $('#password11').val();

    $.post('../action.php', {login: 0, username: username, password: password}, result);
    function result(data) {
        $(location).attr('href', '/');
    }
}

function logout() {
    $.post('../action.php', {logout: 0}, result);
    function result() {
        $(location).attr('href', '/');
    }
}

function register() {
    var name = $('#name').val();
    var email = $('#email').val();
    var password1 = $('#password1').val();
    var password2 = $('#password2').val();
    var class_id = $("#class option:selected").attr('id');
    var avatar = $('#avatar_link').val();

    $.post('../action.php', {register: name, email: email, password1: password1, password2: password2, class_id: class_id, avatar: avatar}, result);
    function result(data) {
        if (data != '') {
            $('#error').html(data);
        } else {
            $(location).attr('href', '/');
        }
    }
}

function create_new_thread_link(id) {
    $(location).attr('href', '/new_thread/' + id);
}

function create_new_thread_link_form(id) {
    $(location).attr('href', '/form/' + id);
}

function create_new_thread(section_id) {
    var name = $('#title').html();
    var content = $('#text').html();

    if (name !== '') {
        $.post('../action.php', {create_new_thread: section_id, name: name, content: content})
                .done(function (data) {
                    $(location).attr('href', '/thread/' + data);
                });
    } else {
        alert('Введите название темы');
    }
}

function create_new_thread_form(section_id) {
    var f = $('#create_thread').serialize();
    $.post('../action.php', {create_new_thread_form: section_id, f: f})
            .done(function (data) {
                $(location).attr('href', '/thread/' + data);
                //console.log(data);
            });
}

function thread_moderate(id) {
    var title = $('#modal_thread_title > div > input[type="text"]').val();
    var status = $('#modal_thread_status > div > input[type="checkbox"]').is(':checked');
    var sticky = $('#modal_thread_sticky > div > input[type="checkbox"]').is(':checked');
    var status_name = $('#modal_thread_status > div > input[type="checkbox"]').val();
    var transfer = $('#modal_thread_tranfer > select > option:selected').attr('id');

    $.post('../action.php', {thread_moderate: id, title: title, status: status, sticky: sticky, status_name: status_name, transfer: transfer}, result);
    function result(data) {
        //console.log(data);
    }
}


//Отправить ответ в тему
function answer(thread_id) {
    var content = $('#text').html();

    $.post('../action.php', {answer: thread_id, content: content})
            .done(function (data) {
                $('#text').html('');
                $('#write_answer').before(data);
            });
}

//Ответить(цитирование)
function quote(post_id) {
    //var content = $('div[post='+post_id+'] > .answer > .text').text();
    var name = $('div[post=' + post_id + '] > .user > .name').text();

    $.post('../action.php', {quote: post_id}, result);
    function result(data) {
        $('#text').append('[quote="' + name + '"]' + data + '[/quote]');
    }
}

//Редактировать сообщение
function edit(post_id) {
    $.post('../action.php', {edit: post_id}, result);
    function result(data) {
        $('.thread_content[post="' + post_id + '"] > .answer').html(data);
    }
}

function hide(post_id) {
    $.post('../action.php', {hide: post_id});
}

function show(post_id) {
    $.post('../action.php', {show: post_id});
}

function edit_change(post_id) {
    var content = $('.thread_content[post="' + post_id + '"] > .answer > #answer > #border > #text').html();

    $.post('../action.php', {edit_change: post_id, content: content}, result);
    function result(data) {
        $('.thread_content[post="' + post_id + '"] > .answer').html(data);
    }
}

function edit_cancel(post_id) {
    $.post('../action.php', {edit_cancel: post_id}, result);
    function result(data) {
        $('.thread_content[post="' + post_id + '"] > .answer').html(data);
    }
}

//Обновить данные профиля
function change_user_info(id) {
    var class_id = $("#class option:selected").attr('id');
    var avatar = $('#avatar_link').val();

    $.post('../action.php', {change_user_info: id, class_id: class_id, avatar: avatar})
            .done(function () {
                location.reload();
            });
}

function edit_thread_title(id) {
    $.post('../action.php', {edit_thread_title: id}, result);
    function result(data) {
        $('#title').html(data);
    }
}

function edit_thread_title_cancel(id) {
    $.post('../action.php', {edit_thread_title_cancel: id}, result);
    function result(data) {
        $('#title').html(data);
    }
}

function edit_thread_title_change(id) {
    var title = $('#new_thread_title').val();

    $.post('../action.php', {edit_thread_title_change: id, title: title}, result);
    function result(data) {
        $('#title').html(data);
    }
}

function thread_hide(id) {
    $.post('../action.php', {thread_hide: id}, result);
    function result() {
    }
}

$(document).ready(function () {
    $('#chat_box')[0].scrollTop = $('#chat_box')[0].scrollHeight;

    $('#chat_text').keydown(function (e) {
        if (e.keyCode === 13) {
            send_message();
        }
    });
});

//Спойлер
$(document).on('click', '.spoiler_title', function (e) {
    e.preventDefault();
    $(this).next('.spoiler_content').slideToggle(300);
});

//Кнопки тегов статичные
$(function () {
    $('.bb-code').on('click', function () {
        if (document.getSelection) {
            var tag = $(this).attr('bb-code');
            var txtElem = $(this).parent().next().next()[0];
            var txtVal = $(this).parent().next().next().html();

            if (tag === 'url') {
                url = '="Ссылка"';
            } else if (tag === 'spoiler') {
                url = '="Название"';
            } else if (tag === 'ol') {
                url = '<ol><li></li></ol>';
            } else if (tag === 'ul') {
                url = '<ul><li></li></ul>';
            } else {
                url = '';
            }

            if (txtElem.selectionStart !== txtElem.selectionEnd) {
                var prefix = txtVal.substr(0, txtElem.selectionStart);
                var select = txtVal.substr(txtElem.selectionStart, txtElem.selectionEnd - txtElem.selectionStart);
                var postfix = txtVal.substr(txtElem.selectionEnd);
                if (tag !== 'ol' && tag !== 'ul') {
                    $(this).parent().next().next().append(prefix + '[' + tag + url + ']' + select + '[/' + tag + ']' + postfix);
                } else {
                    $(this).parent().next().next().append(url);
                }
            } else {
                if (tag !== 'ol' && tag !== 'ul') {
                    $(this).parent().next().next().append($(this).parent().next().next().val() + '[' + tag + url + '][/' + tag + ']');
                } else {
                    $(this).parent().next().next().append(url);
                }
            }
        }
    });
});

//Кнопки тегов загруженные
$(function () {
    $('.thread_content > .answer').on('click', '.bb-code', function () {
        if (document.getSelection) {
            var tag = $(this).attr('bb-code');
            var txtElem = $(this).parent().next().next()[0];
            var txtVal = $(this).parent().next().next().html();

            if (tag === 'url') {
                url = '="Ссылка"';
            } else if (tag === 'spoiler') {
                url = '="Название"';
            } else if (tag === 'ol') {
                url = '<ol><li></li></ol>';
            } else if (tag === 'ul') {
                url = '<ul><li></li></ul>';
            } else {
                url = '';
            }

            if (txtElem.selectionStart !== txtElem.selectionEnd) {
                var prefix = txtVal.substr(0, txtElem.selectionStart);
                var select = txtVal.substr(txtElem.selectionStart, txtElem.selectionEnd - txtElem.selectionStart);
                var postfix = txtVal.substr(txtElem.selectionEnd);
                if (tag !== 'ol' && tag !== 'ul') {
                    $(this).parent().next().next().append(prefix + '[' + tag + url + ']' + select + '[/' + tag + ']' + postfix);
                } else {
                    $(this).parent().next().next().append(url);
                }
            } else {
                if (tag !== 'ol' && tag !== 'ul') {
                    $(this).parent().next().next().append($(this).parent().next().next().val() + '[' + tag + url + '][/' + tag + ']');
                } else {
                    $(this).parent().next().next().append(url);
                }
            }
        }
    });
});