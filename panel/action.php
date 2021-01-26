<?

include_once($_SERVER['DOCUMENT_ROOT'] . '/loader.php');

set_time_limit(0);

//Войти
if (isset($_POST['login'])) {
    //ini_set('session.cookie_lifetime', 3600); //0 - сессия умирает после закрытия браузера	

    $username = db()->escape($_POST['username'], false, true);
    $password = md5($_POST['password']);

    db()->select('users')->where('name', $username)->_and('password', $password)->apply();
    //db()->query("SELECT * FROM `users` WHERE `name` = '".$username."' AND `password` = '".$password."'");
    $userdata = db()->get_row();

    if (db()->num_rows() > 0) {

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/panel/sessions/' . $userdata->name)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/panel/sessions/' . $userdata->name, 0777);
        }
        ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/panel/sessions/' . $userdata->name);


        session_start();
        $_SESSION['id'] = $userdata->id;
        $_SESSION['name'] = $userdata->name;

        db()->query("INSERT INTO `sessions_panel` SET `user_id` = '" . $_SESSION['id'] . "', `session_id` = '" . session_id() . "', `browser` = '', `ip` = '" . ip2long($_SERVER['REMOTE_ADDR']) . "', `date` = now()");

        $all = '{ "error":' . json_encode(false) . ' }';
    } else {
        $answer = '<div id="offline">
                        <input type="text" name="username" placeholder="Логин">
                        <br>
                        <input type="password" name="password" placeholder="Пароль">
                        <br>
                        <input type="button" value="Войти" name="login" onclick="login();">
                        <input type="button" value="Регистрация" onclick="link_to(\'register\');">
                        <br>
                        <span style="color: red;">Неверный логин или пароль</span>
                        <br>
                        <span style="">Забыли пароль? Нажмите <a href="">сюда</a> чтобы получить новый</span>
                    </div>';

        $all = '{ "error":' . json_encode(true) . ', "answer":' . json_encode($answer) . ' }';
    }

    echo $all;
}

//Выйти
if (isset($_POST['logout'])) {
    db()->query("SELECT `name` FROM `sessions_panel`, `users` WHERE `sessions_panel`.`session_id` = '" . $_COOKIE['PHPSESSID'] . "' AND `users`.`id` = `sessions_panel`.`user_id`");
    $data = db()->get_row();
    $username = $data->name;
    db()->query("DELETE FROM `sessions_panel` WHERE `session_id` = '" . $_COOKIE['PHPSESSID'] . "'"); //Удаляем запись в базе
    unlink($_SERVER['DOCUMENT_ROOT'] . '/panel/sessions/' . $username . '/sess_' . $_COOKIE['PHPSESSID']); //Удаляем файл с сессией
    setcookie('PHPSESSID', "", time() - 1, "/"); //Удаляем куки
    session_destroy(); //Убиваем сессию
    //header('Location: '.home(false));
}

/**/
//Новая группа
if (isset($_POST['new_group'])) {
    $name = db()->escape($_POST['new_group']);
    $description = db()->escape($_POST['group_description']);

    db()->query("INSERT INTO `group` SET `name` = '" . $name . "', `description` = '" . $description . "'");

    echo 123;
}

//Удалить группу
if (isset($_POST['delete_group'])) {
    $id = intval($_POST['delete_group']);

    if ($id > 2) {
        db()->query("DELETE FROM `group` WHERE `id` = '" . $id . "'");
    }
}

/**/
//Редактировать пользователя
if (isset($_POST['edit_user'])) {
    $user_id = intval($_POST['edit_user']);
    $email = db()->escape($_POST['email']);
    $group_id = intval($_POST['group_id']);
    $new_password = ($_POST['new_password'] != '') ? md5($_POST['new_password']) : '';
    $avatar = db()->escape($_POST['avatar']);

    if ($new_password != '') {
        db()->query("UPDATE `users` SET `group_id` = '" . $group_id . "', `avatar` = '".$avatar."', `email` = '".$email."', `password` = '".$new_password."' WHERE `id` = '" . $user_id . "'");
    } else {
        db()->query("UPDATE `users` SET `group_id` = '" . $group_id . "', `avatar` = '".$avatar."', `email` = '".$email."' WHERE `id` = '" . $user_id . "'");
    }
}

//Удалить пользователя
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['delete_user']);

    if ($user_id > 1) {
        db()->query("DELETE FROM `users` WHERE `id` = '" . $user_id . "'");
    }
}

//Найти пользователя по имени
if (isset($_POST['search_user'])) {
    $name = db()->escape($_POST['search_user']);

    $get_user = db()->query("SELECT * FROM `users` WHERE `name` LIKE '%" . $name . "%'");
    while ($user = db()->get_row($get_user)) {
        $user_array[] = array(
            'id' => $user->id,
            'name' => $user->name,
            'group_id' => $user->group_id,
        );
    }

    $get_group = db()->select('group')->apply();
    while ($group = db()->get_row($get_group)) {
        $group_array[$group->id] = array(
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
        );
    }

    $answer .= '
		<div class="row">
			<div class="group_div row">
				<div class="name">' . $user_array[0]['name'] . '</div>
                                <a class="edit" href="' . functions::home() . '/panel/edit_user/?id=' . $user_array[0]['id'] . '"><img src="../img/edit.png"></a>
				<div class="edit"><img src="../img/cross.png" onclick="delete_user(\'' . $user_array[0]['id'] . '\')"></div>
			</div>			
			
			<div class="group_div">    
                            '.$group_array[$user_array[0]['group_id']]['name'].'
			</div>			
		</div>';

    echo $answer;
}



/**/
if (isset($_POST['create_new_category'])) {
    $name = db()->escape($_POST['name']);
    //$name = functions::delete_symbols($name);

    $notice = db()->escape($_POST['notice']);
    $description = db()->escape($_POST['description']);
    $access = explode('&', $_POST['create_new_category']);

    for ($i = 0; $i < sizeof($access); $i++) {
        $access[$i] = intval(str_replace('=on', '', $access[$i]));
        $a .= $access[$i] . ' ';
    }

    $a = trim($a);
    $a = str_replace(' ', ', ', $a);

    db()->query("INSERT INTO `category` SET `name` = '" . $name . "', `description` = '" . $description . "', `access` = '" . $a . "', `notice` = '" . $notice . "'");
    $category_id = db()->insert_id();

    $html = '
		<div class="category row" id="' . $category_id . '">
			<div class="name">' . $name . '</div>
			<div class="edit"><img src="../img/edit.png" onclick="location.href=\'' . functions::home() . '/panel/edit_category/?id=' . $category_id . '\'"></div>
			<div class="edit"><img src="../img/cross.png" onclick="delete_category(\'' . $category_id . '\')"></div>
		</div>';

    $answer = '{ "category_id":' . json_encode($category_id) . ', "html":' . json_encode($html) . ' }';

    echo $answer;
}

if (isset($_POST['edit_category'])) {
    $id = intval($_POST['edit_category']);
    $name = db()->escape($_POST['name']);
    $description = db()->escape($_POST['description']);
    $notice = db()->escape($_POST['notice']);
    $access = explode('&', $_POST['access']);

    for ($i = 0; $i < sizeof($access); $i++) {
        $access[$i] = intval(str_replace('=on', '', $access[$i]));
        $a .= $access[$i] . ' ';
    }

    $a = trim($a);
    $a = str_replace(' ', ', ', $a);

    db()->query("UPDATE `category` SET `name` = '" . $name . "', `description` = '" . $description . "', `access` = '" . $a . "', `notice` = '" . $notice . "' WHERE `id` = '" . $id . "'");
}

if (isset($_POST['delete_category'])) {
    $id = intval($_POST['delete_category']);

    db()->query("DELETE FROM `category` WHERE `id` = '" . $id . "'");
}

if (isset($_POST['create_new_section'])) {
    $name = db()->escape($_POST['name']);
    //$name = functions::delete_symbols($name);

    $category_id = intval($_POST['category_id']);
    $section_id = intval($_POST['section_id']);
    $access = explode('&', $_POST['access']);

    for ($i = 0; $i < sizeof($access); $i++) {
        $access[$i] = str_replace('=on', '', $access[$i]);
        
        $count_a = preg_match_all("/a\_([0-9])+/iu", $access[$i]);
        if ($count_a > 0) {
            $a .= str_replace('a_', '', $access[$i]) . ', ';
        }
        
        $count_c = preg_match_all("/c\_([0-9])+/iu", $access[$i]);
        if ($count_c > 0) {
            $c .= str_replace('c_', '', $access[$i]) . ', ';
        }
        
        $count_m = preg_match_all("/m\_([0-9])+/iu", $access[$i]);
        if ($count_m > 0) {
            $m .= str_replace('m_', '', $access[$i]) . ', ';
        }
    }
    
    $a = substr($a, 0, -2);
    $c = substr($c, 0, -2);
    $m = substr($m, 0, -2);

    $get_last_section_id = db()->select('section')->where('category_id', $category_id)->order('id', false)->limit(1)->get();
    if ($get_last_section_id[0]->id != '') {
        $last_section_id = $get_last_section_id[0]->id;
    } else {
        $last_section_id = false;
    }


    db()->query("INSERT INTO `section` SET `name` = '" . $name . "', `category_id` = '" . $category_id . "', `parent_section_id` = '" . $section_id . "', `access` = '" . $a . "', `create` = '" . $c . "', `moder` = '" . $m . "'");
    $section_id = db()->insert_id();

    $html = '
		<div class="section row" id="' . $section_id . '">
			<div class="name">' . $name . '</div>
			<div class="edit"><img src="../img/edit.png" onclick="location.href=\'' . functions::home() . '/panel/edit_section/?id=' . $section_id . '\'"></div>
			<div class="edit"><img src="../img/cross.png" onclick="delete_section(\'' . $section_id . '\')"></div>
		</div>';

    $answer = '{ "section_id":' . json_encode($last_section_id) . ', "html":' . json_encode($html) . ' }';

    echo $answer;
}

if (isset($_POST['edit_section'])) {
    $id = intval($_POST['edit_section']);
    $name = db()->escape($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $access = explode('&', $_POST['access']);

    for ($i = 0; $i < sizeof($access); $i++) {
        $access[$i] = str_replace('=on', '', $access[$i]);
        
        $count_a = preg_match_all("/a\_([0-9])+/iu", $access[$i]);
        if ($count_a > 0) {
            $a .= str_replace('a_', '', $access[$i]) . ', ';
        }
        
        $count_c = preg_match_all("/c\_([0-9])+/iu", $access[$i]);
        if ($count_c > 0) {
            $c .= str_replace('c_', '', $access[$i]) . ', ';
        }
        
        $count_m = preg_match_all("/m\_([0-9])+/iu", $access[$i]);
        if ($count_m > 0) {
            $m .= str_replace('m_', '', $access[$i]) . ', ';
        }
    }
    
    $a = substr($a, 0, -2);
    $c = substr($c, 0, -2);
    $m = substr($m, 0, -2);
    
    db()->query("UPDATE `section` SET `name` = '" . $name . "', `category_id` = '" . $category_id . "', `access` = '" . $a . "', `create` = '" . $c . "', `moder` = '" . $m . "' WHERE `id` = '" . $id . "'");
    db()->query("UPDATE `thread` SET `access` = '" . $a . "' WHERE `section_id` = '" . $id . "'");
}

if (isset($_POST['delete_section'])) {
    $id = intval($_POST['delete_section']);

    db()->query("DELETE FROM `section` WHERE `id` = '" . $id . "'");
}

if (isset($_POST['edit_chat'])) {
    $access = explode('&', $_POST['access']);

    for ($i = 0; $i < sizeof($access); $i++) {
        $access[$i] = intval(str_replace('=on', '', $access[$i]));
        $a .= $access[$i] . ' ';
    }

    $a = trim($a);
    $a = str_replace(' ', ', ', $a);

    db()->query("UPDATE `chat_settings` SET `access` = '" . $a . "' WHERE `id` = '1'");
}

if (isset($_POST['erase_chat'])) {
    db()->query("TRUNCATE TABLE `chat`");
    db()->query("UPDATE `users` SET `last_message_id` = 0");
}

/*Меню*/
if (isset($_POST['add_menu'])) {
    $name = db()->escape($_POST['name']);
    $url = db()->escape($_POST['url']);
    
    db()->query("INSERT INTO `menu` SET `name` = '" . $name . "', `url` = '" . $url . "'");
}

if (isset($_POST['delete_menu'])) {
    $id = intval($_POST['delete_menu']);
    
    db()->query("DELETE FROM `menu` WHERE `id` = '" . $id . "'");
}
