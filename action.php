<?

include_once($_SERVER['DOCUMENT_ROOT'] . '/loader.php');

//Войти
if (isset($_POST['login'])) {
    //ini_set('session.cookie_lifetime', 3600); //0 - сессия умирает после закрытия браузера	

    $username = db()->escape($_POST['username'], false, true);
    $password = md5($_POST['password']);

    db()->select('users')->where('name', $username)->_and('password', $password)->apply();
    if (db()->num_rows() == 0) {
        db()->select('users')->where('email', $username)->_and('password', $password)->apply();
    }
    //db()->query("SELECT * FROM `users` WHERE `name` = '".$username."' AND `password` = '".$password."'");
    $userdata = db()->get_row();

    if (db()->num_rows() > 0) {
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $userdata->name)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $userdata->name, 0777);
        }
        ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $userdata->name);

        session_start();
        $_SESSION['id'] = $userdata->id;
        $_SESSION['name'] = $userdata->name;

        db()->query("INSERT INTO `sessions` SET `user_id` = '" . $_SESSION['id'] . "', `session_id` = '" . session_id() . "', `browser` = '', `ip` = '" . ip2long($_SERVER['REMOTE_ADDR']) . "', `date` = now()");

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
    db()->query("SELECT `name` FROM `sessions`, `users` WHERE sessions.session_id = '" . $_COOKIE['PHPSESSID'] . "' AND users.id = sessions.user_id");
    $data = db()->get_row();
    $username = $data->name;
    db()->query("DELETE FROM `sessions` WHERE `session_id` = '" . $_COOKIE['PHPSESSID'] . "'"); //Удаляем запись в базе
    unlink($_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $username . '/sess_' . $_COOKIE['PHPSESSID']); //Удаляем файл с сессией
    setcookie('PHPSESSID', "", time() - 1, "/"); //Удаляем куки
    session_destroy(); //Убиваем сессию
    //header('Location: '.home(false));
}

//Регистрация
if (isset($_POST['register'])) {
    $name = db()->escape($_POST['register'], true, true);
    $email = db()->escape($_POST['email']);
    $class_id = intval($_POST['class_id']);
    $avatar = db()->escape($_POST['avatar']);

    if ($avatar == '') {
        $avatar = functions::home() . '/img/avatars/noavatar.jpg';
    }

    if (!empty($_POST['password1'])) {
        $password1 = md5($_POST['password1']);
    }

    if (!empty($_POST['password2'])) {
        $password2 = md5($_POST['password2']);
    }

    if (empty($name) or empty($email) or empty($password1) or empty($password2)) {
        $answer = 'Заполните все поля';
    } elseif (preg_match_all("/\\\\|\:|\*|\?|\"|\<|\>|\/|\#|\|/iu", $name, $array) > 0) {
        $answer = 'Некорректное имя: нельзя использовать символы \ / : * ? " < > | #';
    } elseif (preg_match_all("/\@|\./iu", $email, $ar) < 2) {
        $answer = 'Некорректный адрес почты';
    } elseif ($password1 != $password2) {
        $answer = 'Пароли не совпадают';
    } else {
        $user1 = db()->select('users')->where('name', $name)->get(); //Запрос по имени
        $user2 = db()->select('users')->where('email', $email)->get(); //Запрос по почте

        if (sizeof($user1) > 0 or sizeof($user2) > 0) {
            if (sizeof($user1) > 0) {
                $answer = 'Имя \'' . $user1[0]->name . '\' занято';
            } elseif (sizeof($user2) > 0) {
                $answer = 'Почта \'' . $user2[0]->email . '\' уже используется';
            }
        } else {
            db()->query("INSERT INTO `users` SET `name` = '" . $name . "', `password` = '" . $password1 . "', `email` = '" . $email . "', `class_id` = '" . $class_id . "', `avatar` = '" . $avatar . "', `date` = now(), `ip` = '" . ip2long($_SERVER['REMOTE_ADDR']) . "'");
            $new_user_id = db()->insert_id();

            $statistic = db()->select('statistic')->get();
            $statistic_update_users = $statistic[0]->users + 1;
            db()->query("UPDATE `statistic` SET `users` = '" . $statistic_update_users . "', `new_user` = '" . $name . "', `new_user_id` = '" . $new_user_id . "'");

            $get_user = db()->select('users')->where('name', $name)->_and('password', $password1)->apply();
            $user = db()->get_row($get_user);

            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $user->name)) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $user->name, 0777);
            }
            ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $user->name);

            session_start();
            $_SESSION['id'] = $user->id;
            $_SESSION['name'] = $user->name;

            db()->query("INSERT INTO `sessions` SET `user_id` = '" . $_SESSION['id'] . "', `session_id` = '" . session_id() . "', `browser` = '" . functions::get_user_browser() . "', `ip` = '" . ip2long($_SERVER['REMOTE_ADDR']) . "', `date` = now()");
        }
    }

    echo $answer;
}

//Создать новую тему
if (isset($_POST['create_new_thread'])) {
    $section_id = intval($_POST['create_new_thread']);
    $name = db()->escape($_POST['name'], false, true);
    $url = functions::delete_symbols($name);

    $content = functions::render_post_reverse($_POST['content']);
    $content = str_replace('<div>', "\n", $content);
    $content = str_replace('&nbsp;', '', $content);
    $content = db()->escape($content, false, true);

    session_start();
    $author = $_SESSION['name'];
    $author_id = $_SESSION['id'];

    //Обновляем количество тем в секции
    $section = db()->select('section')->where('id', $section_id)->get();
    $section_update_threads = $section[0]->threads + 1;
    db()->query("UPDATE `section` SET `threads` = '" . $section_update_threads . "' WHERE `id` = '" . $section[0]->id . "'");

    /*
      //Обновляем количество тем в категории
      $category = db()->select('category')->where('id', $section[0]->category_id)->get();
      $category_update_threads = $category[0]->threads + 1;
      db()->query("UPDATE `category` SET `threads` = '" . $category_update_threads . "' WHERE `id` = '" . $category[0]->id . "'");
     */

    //Обновляем статистику
    $statistic = db()->select('statistic')->get();
    $statistic_update_threads = $statistic[0]->threads + 1;
    db()->query("UPDATE `statistic` SET `threads` = '" . $statistic_update_threads . "'");

    $date = date('Y-m-d H:i:s');

    //Создаем новую тему
    db()->query("INSERT INTO `thread` SET `section_id` = '" . $section_id . "', `name` = '" . $name . "', `url` = '" . $url . "', `access` = '" . $section[0]->access . "', `date` = '" . $date . "', `author` = '" . $author . "', `author_id` = '" . $author_id . "', `last_date` = now(),  `last_author` = '" . $author . "', `last_author_id` = '" . $author_id . "', `last_message_date` = '" . $date . "'");
    $thread_id = db()->insert_id();

    //И первый пост в ней
    db()->query("INSERT INTO `post` SET `thread_id` = '" . $thread_id . "', `date` = '" . $date . "', `content` = '" . $content . "', `author` = '" . $author . "', `author_id` = '" . $author_id . "'");

    //Обновляем количество созданных юзером тем
    $user = db()->select('users')->where('id', $author_id)->get();
    $user_update_threads = $user[0]->threads + 1;
    db()->query("UPDATE `users` SET `threads` = '" . $user_update_threads . "' WHERE `id` = '" . $author_id . "'");

    $answer = $url . '-' . $thread_id;

    echo $answer;
}

if (isset($_POST['create_new_thread_form'])) {
    $section_id = intval($_POST['create_new_thread_form']);
    $f = urldecode(db()->escape($_POST['f']));
    $f = explode('&', $f);

    for ($i = 0; $i < sizeof($f); $i++) {
        preg_match_all("/\w+\=/iu", $f[$i], $key);
        $key[0][0] = str_replace('=', '', $key[0][0]);

        preg_match_all("/\=.+/iu", $f[$i], $value);
        $value[0][0] = str_replace('=', '', $value[0][0]);

        $data[$key[0][0]] = $value[0][0];
    }

    $name = 'Заявка от ' . $data['nickname'];
    $url = functions::delete_symbols($name);

    session_start();
    $author = $_SESSION['name'];
    $author_id = $_SESSION['id'];

    $content = '
            Личная информация: 
            1. Имя, возраст - ' . $data['name'] . ', ' . $data['age'] . '
            2. Город/часовой пояс - ' . $data['location'] . '
                
            Общая информация:
            1. Игровой ник - ' . $data['nickname'] . '
            2. Класс - ' . $data['class'] . '
            3. Уровень - ' . $data['level'] . '
            4. Кукла - ' . $data['doll'] . '
            5. Скриншот - ' . $data['screenshot'] . '
            6. В каких гильдиях вы состояли и почему ушли? - ' . $data['guilds'] . '
            7. Кратко опишите задачи на замесе - ' . $data['tasks'] . '
            8. Есть ли у вас личный кос? - ' . $data['blacklist'] . '
            9. Почему вы хотите вступить в RTD? - ' . $data['reasons'] . '
            10. Кто из гильдии RTD мог бы вас отрекомендовать? - ' . $data['recommended'] . '';

    //Обновляем количество тем в секции
    $section = db()->select('section')->where('id', $section_id)->get();
    $section_update_threads = $section[0]->threads + 1;
    db()->query("UPDATE `section` SET `threads` = '" . $section_update_threads . "' WHERE `id` = '" . $section[0]->id . "'");

    //Обновляем статистику
    $statistic = db()->select('statistic')->get();
    $statistic_update_threads = $statistic[0]->threads + 1;
    db()->query("UPDATE `statistic` SET `threads` = '" . $statistic_update_threads . "'");

    $date = date('Y-m-d H:i:s');

    //Создаем новую тему
    db()->query("INSERT INTO `thread` SET `section_id` = '" . $section_id . "', `name` = '" . $name . "', `url` = '" . $url . "', `access` = '" . $section[0]->access . "', `date` = '" . $date . "', `author` = '" . $author . "', `author_id` = '" . $author_id . "', `last_date` = now(),  `last_author` = '" . $author . "', `last_author_id` = '" . $author_id . "'");
    $thread_id = db()->insert_id();

    //И первый пост в ней
    db()->query("INSERT INTO `post` SET `thread_id` = '" . $thread_id . "', `date` = '" . $date . "', `content` = '" . $content . "', `author` = '" . $author . "', `author_id` = '" . $author_id . "'");

    //Обновляем количество созданных юзером тем
    $user = db()->select('users')->where('id', $author_id)->get();
    $user_update_threads = $user[0]->threads + 1;
    db()->query("UPDATE `users` SET `threads` = '" . $user_update_threads . "' WHERE `id` = '" . $author_id . "'");


    //Дубль для обсуждения
    //Обновляем количество тем в секции
    $section = db()->select('section')->where('id', '22')->get();
    $section_update_threads = $section[0]->threads + 1;
    db()->query("UPDATE `section` SET `threads` = '" . $section_update_threads . "' WHERE `id` = '22'");

    //Обновляем статистику
    $statistic = db()->select('statistic')->get();
    $statistic_update_threads = $statistic[0]->threads + 1;
    db()->query("UPDATE `statistic` SET `threads` = '" . $statistic_update_threads . "'");

    $date = date('Y-m-d H:i:s');

    //Создаем новую тему
    db()->query("INSERT INTO `thread` SET `section_id` = '22', `name` = '" . $name . "', `url` = '" . $url . "', `access` = '" . $section[0]->access . "', `date` = '" . $date . "', `author` = 'Administrator', `author_id` = '1', `last_date` = now(),  `last_author` = 'Administrator', `last_author_id` = '1'");
    $thread_id2 = db()->insert_id();

    //И первый пост в ней
    db()->query("INSERT INTO `post` SET `thread_id` = '" . $thread_id2 . "', `date` = '" . $date . "', `content` = '" . $content . "', `author` = 'Administrator', `author_id` = '1'");

    //Обновляем количество созданных юзером тем
    $user = db()->select('users')->where('id', '1')->get();
    $user_update_threads = $user[0]->threads + 1;
    db()->query("UPDATE `users` SET `threads` = '" . $user_update_threads . "' WHERE `id` = '1'");

    $answer = $url . '-' . $thread_id;

    echo $answer;
}

if (isset($_POST['thread_moderate'])) {
    $id = intval($_POST['thread_moderate']);
    $title = db()->escape($_POST['title'], false, true);
    $status = ($_POST['status'] == 'true') ? true : false;
    $sticky = ($_POST['sticky'] == 'true') ? true : false;
    $status_name = db()->escape($_POST['status_name']);
    $section_id = intval($_POST['transfer']);

    if ($status_name == 'Закрыть') {
        $status = $status;
    } else {
        $status = !$status;
    }

    db()->query("UPDATE `thread` SET `section_id` = '" . $section_id . "', `name` = '" . $title . "', `status` = '" . $status . "', `sticky` = '" . $sticky . "' WHERE `id` = '" . $id . "'");
}

//Ответить в теме
if (isset($_POST['answer'])) {
    $thread_id = intval($_POST['answer']);

    $content = functions::render_post_reverse($_POST['content']);
    $content = str_replace('<div>', "\n", $content);
    $content = str_replace('&nbsp;', '', $content);
    $content = db()->escape($content, false, true);

    session_start();
    $author = $_SESSION['name'];
    $author_id = $_SESSION['id'];

    //Получаем параметры темы
    $thread = db()->select('thread')->where('id', $thread_id)->get();
    //Секции
    $section = db()->select('section')->where('id', $thread[0]->section_id)->get();
    $moder = explode(',', $section[0]->moder);
    //И юзера
    $user = db()->select('users')->where('id', $author_id)->get();
    //Чтобы обновить ответы юзера 
    $user_update_answers = $user[0]->answers + 1;
    //И темы
    $thread_update_answers = $thread[0]->answers + 1;

    $date = date('Y-m-d H:i:s');

    //Проверяем открыта ли тема или отвечает админ
    if (($thread[0]->status == 0 or /* $user->access == 1 */functions::one_of($moder, $user[0]->access)) and $content != '') {
        if (functions::one_of(explode(',', $thread[0]->access), $user[0]->group_id)) {

            //Добавляем ответ
            db()->query("INSERT INTO `post` SET `thread_id` = '" . $thread_id . "', `date` = '" . $date . "', `content` = '" . $content . "', `author` = '" . $author . "', `author_id` = '" . $author_id . "'");
            $post_id = db()->insert_id();

            //Обновляем дату последнего сообщения
            db()->query("UPDATE `thread` SET `last_message_date` = now() WHERE `id` = '" . $thread_id . "'");

            //Обновляем количество ответов в секции
            $section = db()->select('section')->where('id', $thread[0]->section_id)->get();
            $section_update_answers = $section[0]->answers + 1;
            db()->query("UPDATE `section` SET `answers` = '" . $section_update_answers . "' WHERE `id` = '" . $section[0]->id . "'");
            /*
              //Обновляем количество ответов в категории
              $category = db()->select('category')->where('id', $section[0]->category_id)->get();
              $category_update_answers = $category[0]->answers + 1;
              db()->query("UPDATE `category` SET `answers` = '" . $category_update_answers . "' WHERE `id` = '" . $category[0]->id . "'");
             */

            //Обновляем последний пост в теме
            db()->query("UPDATE `thread` SET `last_date` = '" . $date . "', `last_author` = '" . $user[0]->name . "', `last_author_id` = '" . $user[0]->id . "', `answers` = '" . $thread_update_answers . "' WHERE `id` = '" . $thread_id . "'");

            //Обновляем количество ответов юзера
            db()->query("UPDATE `users` SET `answers` = '" . $user_update_answers . "' WHERE `id` = '" . $user[0]->id . "'");

            //Обновляем статистику
            $statistic = db()->select('statistic')->get();
            $statistic_update_answers = $statistic[0]->answers + 1;
            db()->query("UPDATE `statistic` SET `answers` = '" . $statistic_update_answers . "'");

            //Получаем параметры группы доступа, класса и поста который был добавлен
            $group = db()->select('group')->where('id', $user[0]->group_id)->get();
            $class = db()->select('class')->where('id', $user[0]->class_id)->get();
            $post = db()->select('post')->where('id', $post_id)->get();

            //Запрашиваем количество постов, чтобы получить их порядок
            db()->query("SELECT * FROM `post` WHERE `id` > 0 AND `id` <= '" . $post_id . "' AND `thread_id` = '" . $thread_id . "'");
            $order = db()->num_rows();

            //Отправляем ответ через ajax
            $answer .= '
			<div class="thread_content row" post="' . $post[0]->id . '">
				<div class="user">
					<div class="avatar"><img src="' . $user[0]->avatar . '"></div>
					<div class="name">' . $user[0]->name . '</div>
					<div class="rank">' . $user[0]->rank . '</div>
					<div class="banner" style="background: ' . $group[0]->background . '; border: 1px solid ' . $group[0]->border . '; color: ' . $group[0]->font . '">' . $group[0]->name . '</div>
					<div class="class"><img src="' . $class[0]->sign . '" title="' . $class[0]->name . '"></div>
				</div>
				<div class="answer column">
					<div class="info row">
						<div class="date" title="' . functions::format_datetime($post[0]->date) . '">' . functions::format_date($post[0]->date) . '</div>
						<div class="order">#' . $order . '</div>
					</div>
					<div class="text">' . functions::render_post($post[0]->content) . '</div>
					<div class="buttons">                                        
						<div class="button" onclick="quote(\'' . $post_id . '\')">Ответить</div>
						<div class="button" onclick="edit(\'' . $post_id . '\')">Редактировать</div>
					</div>
				</div>
			</div>';
        }
    }

    echo $answer;
}

//Цитата
if (isset($_POST['quote'])) {
    $post_id = intval($_POST['quote']);
    $post = db()->select('post')->where('id', $post_id)->get();

    echo $post[0]->content;
}

//Изменить данные профиля
if (isset($_POST['change_user_info'])) {
    $user_id = intval($_POST['change_user_info']);
    $class_id = intval($_POST['class_id']);
    $avatar = db()->escape($_POST['avatar']);

    session_start();
    if ($user_id == $_SESSION['id']) {
        if ($avatar != '') {
            db()->query("UPDATE `users` SET `class_id` = '" . $class_id . "', `avatar` = '" . $avatar . "' WHERE `id` = '" . $user_id . "'");
        } else {
            db()->query("UPDATE `users` SET `class_id` = '" . $class_id . "' WHERE `id` = '" . $user_id . "'");
        }
    }

    echo true;
}

//Отправить сообщение в чат
if (isset($_POST['send_message'])) {
    $message = db()->escape($_POST['send_message']);
    $date = date('Y-m-d H:i:s');

    $get_group = db()->select('group')->apply();
    while ($group = db()->get_row($get_group)) {
        $group_array[$group->id] = array(
            'background' => $group->background,
            'font' => $group->font,
            'border' => $group->border,
        );
    }

    session_start();
    $chat_settings = db()->select('chat_settings')->get();
    $user = db()->select('users')->where('id', $_SESSION['id'])->get();
    if (functions::one_of(explode(',', $chat_settings[0]->access), $user[0]->group_id) and $message != '') {
        db()->query("INSERT INTO `chat` SET `author` = '" . $_SESSION['name'] . "', `author_id` = '" . $_SESSION['id'] . "', `message` = '" . $message . "', `date` = '" . $date . "'");

        $new_last_id = db()->insert_id();
        db()->query("UPDATE `users` SET `last_message_id` = '" . $new_last_id . "' WHERE `id` = '" . $_SESSION['id'] . "'");

        $answer .= '
                    <div class="chat_message row">
                        <div class="chat_message_author"><a href="' . functions::home() . '/users/' . $user[0]->name . '-' . $user[0]->id . '"  style="color: ' . $group_array[$user[0]->group_id]['background'] . '; ">' . $user[0]->name . ':' . '</a></div>
                        <div class="chat_message_text">' . functions::antimat($message) . '</div>
                        <div class="chat_message_date">' . functions::format_time($date) . '</div>
                    </div>';

        echo $answer;
    }
}

//Обновить чат
if (isset($_POST['update_chat'])) {


    $get_group = db()->select('group')->apply();
    while ($group = db()->get_row($get_group)) {
        $group_array[$group->id] = array(
            'background' => $group->background,
            'font' => $group->font,
            'border' => $group->border,
        );
    }

    $get_chat_settings = db()->select('chat_settings')->get();

    session_start();
    $user = db()->select('users')->where('id', $_SESSION['id'])->get();
    session_write_close();

    $last_message_id = $user[0]->last_message_id;
    if (functions::one_of(explode(',', $get_chat_settings[0]->access), $user[0]->group_id)) {
        $get_chat = db()->select('chat')->where('id', $last_message_id, '>')->_and('date', date("Y-m-d 00:00:00"), '>=')->apply();

        if (db()->num_rows() > 0) {
            while ($chat = db()->get_row($get_chat)) {
                $user1 = db()->select('users')->where('id', $chat->author_id)->get();
                $answer .= '
                            <div class="chat_message row">
                                <div class="chat_message_author"><a href="' . functions::home() . '/users/' . $chat->author . '-' . $chat->author_id . '"  style="color: ' . $group_array[$user1[0]->group_id]['background'] . '; ">' . $chat->author . ':' . '</a></div>
                                <div class="chat_message_text">' . functions::antimat($chat->message) . '</div>
                                <div class="chat_message_date">' . functions::format_time($chat->date) . '</div>
                            </div>';

                $new_last_id = $chat->id;
            }

            db()->query("UPDATE `users` SET `last_message_id` = '" . $new_last_id . "' WHERE `id` = '" . $_SESSION['id'] . "'");

            echo $answer;
        }
    }
}

/**/
if (isset($_POST['edit'])) {
    $post_id = intval($_POST['edit']);

    $post = db()->select('post')->where('id', $post_id)->get();

    session_start();

    $user = db()->select('users')->where('id', $post[0]->author_id)->get();

    $thread = db()->select('thread')->where('id', $post[0]->thread_id)->get();
    $section = db()->select('section')->where('id', $thread[0]->section_id)->get();
    $access = explode(',', $section[0]->moder);

    $admin = db()->select('users')->where('id', $_SESSION['id'])->get();

    if ($post[0]->author_id == $_SESSION['id'] or functions::one_of($access, $admin[0]->group_id)) {
        $answer = '
                <div id="answer">
                    <div id="border">
                        <div id="tools">                                
                            <div class="button fa fa-bold bb-code" bb-code="b" title="Жирный текст"></div>
                            <div class="button fa fa-italic bb-code" bb-code="i" title="Курсивный текст"></div>
                            <div class="button fa fa-underline bb-code" bb-code="u" title="Подчеркнутый текст"></div>
                            <div class="button fa fa-strikethrough bb-code" bb-code="s" title="Зачеркнутый текст"></div>            
                            <div class="button fa fa-link bb-code" bb-code="url" title="Ссылка"></div>
                            <div class="button fa fa-align-left bb-code" bb-code="left" title="Текст слева"></div>
                            <div class="button fa fa-align-center bb-code" bb-code="center" title="Текст в центре"></div>								
                            <div class="button fa fa-align-right bb-code" bb-code="right" title="Текст справа"></div>
                            <div class="button fa fa-flag bb-code" bb-code="spoiler" title="Спойлер"></div>
                            <div class="button fa fa-list-ul bb-code" bb-code="ul" title="Маркированный список"></div>
                            <div class="button fa fa-list-ol bb-code" bb-code="ol" title="Нумерованный список"></div>
                        </div>
                        <hr>
                        <div id="text" contenteditable="true">' . functions::render_post_edit($post[0]->content) . '</div>
                    </div>
                    <div id="buttons">
                        <div class="button" onclick="edit_change(' . $post[0]->id . ')">Изменить</div>
                        <div class="button" onclick="edit_cancel(' . $post[0]->id . ')">Отмена</div>
                    </div>
                </div>';
    }

    echo $answer;
}

if (isset($_POST['hide'])) {
    $post_id = intval($_POST['hide']);

    db()->query("UPDATE `post` SET `hidden` = '1' WHERE `id` = '" . $post_id . "'");
}

if (isset($_POST['show'])) {
    $post_id = intval($_POST['show']);

    db()->query("UPDATE `post` SET `hidden` = '0' WHERE `id` = '" . $post_id . "'");
}

if (isset($_POST['edit_cancel'])) {
    $post_id = intval($_POST['edit_cancel']);
    $post = db()->select('post')->where('id', $post_id)->get();

    if ($post[0]->hidden == 1) {
        $hidden = ' Скрыт';
    }

    $i = 1;
    $get_post_count = db()->select('post')->where('thread_id', $post[0]->thread_id)->apply();
    while ($post_count = db()->get_row($get_post_count)) {
        if ($post_count->id != $post[0]->id) {
            $i++;
        } else {
            break;
        }
    }

    $answer = '    
                <div class="info row">
                    <div class="date" title="' . functions::format_datetime($post[0]->date) . '">' . functions::format_date($post[0]->date) . $hidden . '</div>
                    <div class="order">#' . $i . '</div>
                </div>
                <div class="text">' . functions::render_post($post[0]->content) . '</div>
                <div class="buttons">
                    <div class="button" onclick="quote(\'' . $post[0]->id . '\')">Ответить</div>
                    <div class="button" onclick="edit(\'' . $post[0]->id . '\')">Редактировать</div>
                </div>';

    echo $answer;
}

if (isset($_POST['edit_change'])) {
    $post_id = intval($_POST['edit_change']);

    $content = functions::render_post_reverse($_POST['content']);
    $content = str_replace('<div>', "\n", $content);
    $content = str_replace('&nbsp;', '', $content);
    $content = db()->escape($content, false, true);

    session_start();
    db()->query("UPDATE `post` set `content` = '" . $content . "', `last_date` = now(), `last_author` = '" . $_SESSION['name'] . "', `last_author_id` = '" . $_SESSION['id'] . "' WHERE `id` = '" . $post_id . "'");

    $post = db()->select('post')->where('id', $post_id)->get();

    if ($post[0]->hidden == 1) {
        $hidden = ' Скрыт';
    }

    $i = 1;
    $get_post_count = db()->select('post')->where('thread_id', $post[0]->thread_id)->apply();
    while ($post_count = db()->get_row($get_post_count)) {
        if ($post_count->id != $post[0]->id) {
            $i++;
        } else {
            break;
        }
    }

    //Обновляем дату последнего сообщения
    db()->query("UPDATE `thread` SET `last_message_date` = now() WHERE `id` = '" . $post[0]->thread_id . "'");

    $answer = '    
                <div class="info row">
                    <div class="date" title="' . functions::format_datetime($post[0]->date) . '">' . functions::format_date($post[0]->date) . $hidden . '</div>
                    <div class="order">#' . $i . '</div>
                </div>
                <div class="text">' . functions::render_post($post[0]->content) . '</div>
                <div class="buttons">
                    <div class="button" onclick="quote(\'' . $post[0]->id . '\')">Ответить</div>
                    <div class="button" onclick="edit(\'' . $post[0]->id . '\')">Редактировать</div>
                </div>';

    echo $answer;
}

if (isset($_POST['edit_thread_title'])) {
    $id = intval($_POST['edit_thread_title']);

    $thread = db()->select('thread')->where('id', $id)->get();

    session_start();

    $user = db()->select('users')->where('id', $thread[0]->author_id)->get();
    $admin = db()->select('users')->where('id', $_SESSION['id'])->get();

    $section = db()->select('section')->where('id', $thread[0]->section_id)->get();
    $access = explode(',', $section[0]->moder);

    if ($thread[0]->author_id == $_SESSION['id'] or functions::one_of($access, $admin[0]->group_id)) {
        echo '
            <div class="row">
                <input type="text" class="edit" id="new_thread_title" value="' . $thread[0]->name . '">
                <div class="edit_buttons fa fa-check" onclick="edit_thread_title_change(\'' . $thread[0]->id . '\')"></div>
                <div class="edit_buttons fa fa-close" onclick="edit_thread_title_cancel(\'' . $thread[0]->id . '\')"></div>
            </div>';
    }
}

if (isset($_POST['edit_thread_title_cancel'])) {
    $id = intval($_POST['edit_thread_title_cancel']);
    $thread = db()->select('thread')->where('id', $id)->get();

    echo '
        <div>' . $thread[0]->name . '</div>
        <div class="fa fa-edit" title="Изменить название темы" style="margin: 9px 0 0 10px; font-size: 22px; cursor: pointer;" onclick="edit_thread_title(\'' . $thread[0]->id . '\')"></div>';
}

if (isset($_POST['edit_thread_title_change'])) {
    $id = intval($_POST['edit_thread_title_change']);
    $title = db()->escape($_POST['title'], false, true);

    db()->query("UPDATE `thread` SET `name` = '" . $title . "' WHERE `id` = '" . $id . "'");

    echo '
        <div>' . $title . '</div>
        <div class="fa fa-edit" title="Изменить название темы" style="margin: 9px 0 0 10px; font-size: 22px; cursor: pointer;" onclick="edit_thread_title(\'' . $id . '\')"></div>';
}

if (isset($_POST['thread_hide'])) {
    $id = intval($_POST['thread_hide']);

    db()->query("UPDATE `thread` SET `hidden` = '1' WHERE `id` = '" . $id . "'");
}