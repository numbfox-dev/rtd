<?

class functions {

    public static function home() {
        return 'https://rtd-titan.pw';
    }

    public static function redirect($address, $time = 0): void {
        echo '<meta http-equiv="refresh" content="' . $time . '; URL=' . $address . '">';
    }

    public static function to_https(): void {
        if ($_SERVER['REQUEST_SCHEME'] == 'http') {
            self::redirect('https://rtd-titan.pw/panel/');
        }
    }

    public static function dynamic_file_name($file_path) {
        return self::home() . $file_path . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $file_path);
    }

    public static function format_date($date) {
        $new_date_format = date("d.m.Y", strtotime($date));

        return $new_date_format;
    }

    public static function format_datetime($datetime) {
        $new_datetime_format = date("d.m.Y", strtotime($datetime)) . ' в ' . date("H:i", strtotime($datetime));

        return $new_datetime_format;
    }

    public static function is_logined() {
        if (isset($_SESSION['id'])) {
            return true;
        } else {
            return false;
        }
    }

    //Проверка на старт новой сессии
    public static function new_session(): void {
        db()->query("SELECT `name` FROM `sessions_panel`, `users` WHERE `sessions_panel`.`session_id` = '" . $_COOKIE['PHPSESSID'] . "' AND `users`.`id` = `sessions_panel`.`user_id`");
        $data = db()->get_row();
        $username = $data->name;

        ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/panel/sessions/' . $username . '/');

        if (isset($_COOKIE['PHPSESSID']) and empty($_POST)) {
            db()->query("SELECT * FROM `sessions_panel` WHERE `session_id` = '" . $_COOKIE['PHPSESSID'] . "'");

            if (db()->num_rows() > 0) {
                session_start();
            }
        }

        //unset($db->num_rows);
    }

    public static function one_of($array, $result) {
        for ($i = 0; $i < sizeof($array); $i++) {
            if ($array[$i] == $result) {
                $flag = true;
                break;
            } else {
                $flag = false;
            }
        }

        return $flag;
    }

    public static function render_post($post) {
        //Замена bb-кода [img]
        $post = preg_replace("/(\[img\])(\w.+)(\[\/img\])/iu", "<br><img src=\"$2\" style=\"width: 100%;\">", $post);

        //Замена bb-кода [b]
        $post = preg_replace("/(\[b\])(\w.+)(\[\/b\])/iu", "<b>$2</b>", $post);

        //Замена bb-кода [i]
        $post = preg_replace("/(\[i\])(\w.+)(\[\/i\])/iu", "<i>$2</i>", $post);

        //Замена bb-кода [u]
        $post = preg_replace("/(\[u\])(\w.+)(\[\/u\])/iu", "<u>$2</u>", $post);

        //Замена bb-кода [s]
        $post = preg_replace("/(\[s\])(\w.+)(\[\/s\])/iu", "<s>$2</s>", $post);

        //Замена bb-кода [left]
        $post = preg_replace("/(\[left\])(\w.+)(\[\/left\])/iu", "<div style=\"text-align: left\">$2</div>", $post);

        //Замена bb-кода [right]
        $post = preg_replace("/(\[right\])(\w.+)(\[\/right\])/iu", "<div style=\"text-align: right\">$2</div>", $post);

        //Замена bb-кода [center]
        $post = preg_replace("/(\[center\])(\w.+)(\[\/center\])/iu", "<div style=\"text-align: center\">$2</div>", $post);

        //Замена bb-кода [youtube]
        $post = preg_replace("/(\[youtube\])(\w.+)(\[\/youtube\])/iu", "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/$2\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>", $post);

        //Замена bb-кода [spoiler]
        $post = preg_replace("/\[spoiler\=\"(.*)\"\](.*)\[\/spoiler\]/iU", "<div class=\"spoiler\"><div class=\"spoiler_title\">$1</div><div class=\"spoiler_content\">$2</div></div>", $post);
        $post = preg_replace("/\[spoiler=(.*)\](.*)\[\/spoiler\]/iU", "<div class=\"spoiler\"><div class=\"spoiler_title\">$1</div><div class=\"spoiler_content\">$2</div></div>", $post);

        //Замена bb-кода [url]
        $post = preg_replace("/\[url\=\"(.*)\"\](.*)\[\/url\]/iu", "<a href=\"$2\" target=\"_blank\">$1</a>", $post);
        $post = preg_replace("/\[url\=(.*)\](.*)\[\/url\]/iu", "<a href=\"$2\" target=\"_blank\">$1</a>", $post);

        //Замена bb-кода [quote]
        $post = preg_replace("/\[quote\=\"(.*)\"\](.*)\[\/quote\]/iU", "<div class=\"quote\"><div class=\"quote_title\">$1 сказал(а):</div><div class=\"quote_body\">$2</div></div>", $post);
        $post = preg_replace("/\[quote\=(.*)\](.*)\[\/quote\]/iU", "<div class=\"quote\"><div class=\"quote_title\">$1 сказал(а):</div><div class=\"quote_body\">$2</div></div>", $post);

        //замена ссылок на картинки
        $post = preg_replace("/(http)(\w.+\S+)(jpg|jpeg|png|bmp|webm|webp|gif)/iu", "<br><img src=\"$1$2$3\" style=\"width: 100%;\">", $post);

        //Замена ссылок на ютуб
        $post = preg_replace("/(https\:\/\/youtu\.be\/|https\:\/\/www\.youtube\.com\/watch\?v\=)(\w+)/iu", "<iframe width=\"100%\" height=\"600\" src=\"https://www.youtube.com/embed/$2\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>", $post);

        //Замена ссылок на коуб
        $post = preg_replace("/(https\:\/\/coub\.com\/view\/)(\w+)/iu", "<iframe src=\"//coub.com/embed/$2\" allowfullscreen frameborder=\"0\" width=\"640\" height=\"360\" allow=\"autoplay\"></iframe>", $post);

        $post = str_replace("\n", '<br>', $post);

        return $post;
    }

    public static function many($num, $one = '', $between = '', $many = '') {
        $num = intval($num);
        $temp = '';

        if (($num >= 5 and $num <= 20)) {
            $temp = $many;
        } else {
            $change = $num - floor($num / 10) * 10;

            if ($change == 0) {
                $temp = $many;
            } elseif ($change == 1) {
                $temp = $one;
            } elseif ($change >= 2 and $change <= 4) {
                $temp = $between;
            } else {
                $temp = $many;
            }
        }

        return $temp;
    }

    public static function get_var($var) {
        $access = array(
            0 => 'Пользователь',
            1 => 'Модератор',
            2 => '<span style="color: red;">Администратор</span>'
        );

        $lg = array(
            'bad_pass' => 'Неверный логин или пароль',
            'no_antispam' => 'Не отмечен антиспам',
        );

        $array_boolean = array(
            '0' => '<span style="color: red;">Нет</span>',
            '1' => '<span style="color: #8BC34A;">Да</span>'
        );

        if (isset(${$var})) {
            return ${$var};
        } else {
            echo 'Переменная ' . ${$var} . ' не существует';
        }
    }

    public static function delete_symbols($s) {
        $s = str_replace(' ', '_', $s);
        $s = preg_replace('/\W+|^[а-яе]|^\_/iu', '', $s);

        return $s;
    }

}
