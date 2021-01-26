var home = 'https://rtd-titan.pw/panel';

function login() {
    var username = $('#username').val();
    var password = $('#password').val();

    $.post(home + '/action.php', {login: 0, username: username, password: password}, result);
    function result(data) {
        $(location).attr('href', home + '/');
    }
}

function logout() {
    $.post(home + '/action.php', {logout: 0}, result);
    function result() {
        $(location).attr('href', home + '/');
    }
}

/*groups*/
function new_group() {
    var group_name = $('#new_group_name').val();
    var group_description = $('#new_group_description').val();

    $.post(home + '/action.php', {new_group: group_name, group_description: group_description}, result);
    function result(data) {
        //$(location).attr('href', home);
    }
}

function delete_group(id) {
    $.post(home + '/action.php', {delete_group: id}, result);
    function result(data) {
        //$(location).attr('href', home);
    }
}

/*users*/
function delete_user(user_id) {
    $.post(home + '/action.php', {delete_user: user_id}, result);
    function result(data) {
        //$(location).attr('href', home);
    }
}

function search_user() {
    var name = $('#name').val();

    $.post(home + '/action.php', {search_user: name}, result);
    function result(data) {
        $('#users').html(data);
    }
}

function edit_user(user_id) {
    var email = $('#email').val();
    var group_id = $('#group[user="' + user_id + '"] option:selected').attr('id');
    var new_password = $('#new_password').val();
    var avatar = $('#avatar').val();
    
    $.post(home + '/action.php', {edit_user: user_id, email: email, group_id: group_id, new_password: new_password, avatar: avatar}, result);
    function result(data) {
        $(location).attr('href', home + '/users/');
    }
}

/*forums*/
function create_new_category() {
    var f = $('#create_new_category').serialize();
    var name = $('#create_new_category > div > #name').val();
    var description = $('#create_new_category > div > #description').val();
    var notice = $('#create_new_category > div > #notice').val();

    $.post(home + '/action.php', {create_new_category: f, name: name, description: description, notice: notice}, result);
    function result(data) {
        var obj = JSON.parse(data);

        $('#forums').append(obj.html);
        $('select[id="category"]').append($('<option id="' + obj.category_id + '">' + name + '</option>'));

    }
}

function edit_category(id) {

    var f = $('#edit_category').serialize();
    var name = $('#name').val();
    var description = $('#description').val();
    var notice = $('#notice').val();
    $.post(home + '/action.php', {edit_category: id, name: name, description: description, notice: notice, access: f}, result);
    function result(data) {
        $(location).attr('href', '/panel/forums/');
    }
}

function delete_category(id) {
    $.post(home + '/action.php', {delete_category: id}, result);
    function result(data) {
        $('.category[id="' + id + '"]').remove();
    }
}

function create_new_section() {
    var f = $('#create_new_section').serialize();
    var name = $('#create_new_section > div > #name').val();
    var category_id = $('#category option:selected').attr('id');
    var section_id = $('#section option:selected').attr('id');

    $.post(home + '/action.php', {create_new_section: 0, name: name, category_id: category_id, section_id: section_id, access: f}, result);
    function result(data) {
        obj = JSON.parse(data);

        section_id = obj.section_id;
        html = obj.html;

        if (!section_id) {
            $('.category[id="' + category_id + '"]').after(html);
        } else {
            $('.section[id="' + section_id + '"]').after(html);
        }
    }
}

function edit_section(id) {
    var f = $('#edit_section').serialize();
    var name = $('#name').val();
    var category_id = $('#category option:selected').attr('id');

    $.post(home + '/action.php', {edit_section: id, name: name, category_id: category_id, access: f}, result);
    function result() {
        $(location).attr('href', home + '/forums/');
    }
}

function delete_section(id) {
    $.post(home + '/action.php', {delete_section: id}, result);
    function result() {
        $('.section[id="' + id + '"]').remove();
    }
}

function edit_chat() {
    var f = $('#edit_chat').serialize();

    $.post(home + '/action.php', {edit_chat: 0, access: f}, result);
    function result() {
        window.location.reload();
    }
}

function erase_chat() {
    var answer = confirm('Действительно очистить чат?\nДействие необратимо');

    if (answer) {
        $.post(home + '/action.php', {erase_chat: 0}, result);
        function result() {
            window.location.reload();
        }
    }

    return false;
}

function add_menu() {
    var name = $('#name').val();
    var url = $('#url').val();
    
    $.post(home + '/action.php', {add_menu: 0, name: name, url: url}, result);
    function result(data) {
        
    }
}

function delete_menu(id) {
    $.post(home + '/action.php', {delete_menu: id});
}