<?

class functions {

    public static function home() {
        return 'https://rtd-titan.pw';
    }

    //Добавляет к файлу его время изменения в виде get-запроса
    public static function dynamic_file_name($file_path) {
        return self::home() . $file_path . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $file_path);
    }

    //Меняет формат даты
    public static function format_date($date) {
        $new_date_format = date("d.m.Y", strtotime($date));

        return $new_date_format;
    }

    //Менет формат времени
    public static function format_time($time) {
        $new_time_format = date("H:i", strtotime($time));

        return $new_time_format;
    }

    //Меняет формат даты и времени
    public static function format_datetime($datetime) {
        $new_datetime_format = date("d.m.Y", strtotime($datetime)) . ' в ' . date("H:i", strtotime($datetime));

        return $new_datetime_format;
    }

    //Проверка на вход
    public static function is_logined() {
        if (isset($_SESSION['id'])) {
            return true;
        } else {
            return false;
        }
    }

    //Проверка на старт новой сессии
    public static function new_session(): void {
        db()->query("SELECT `name` FROM `sessions`, `users` WHERE `sessions`.`session_id` = '" . $_COOKIE['PHPSESSID'] . "' AND `users`.`id` = `sessions`.`user_id`");
        $data = db()->get_row();
        $username = $data->name;

        ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/sessions/' . $username . '/');

        if (isset($_COOKIE['PHPSESSID']) and empty($_POST)) {
            db()->query("SELECT * FROM `sessions` WHERE `session_id` = '" . $_COOKIE['PHPSESSID'] . "'");

            if (db()->num_rows() > 0) {
                session_start();
            }
        }

        //unset($db->num_rows);
    }

    //Выбор одного из множества
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
        $post = str_replace("\n", "<br>", $post);
        $post = str_replace('&nbsp;', '', $post);

        //Замена bb-кода [img]
        $post = preg_replace("/(\[img\])(.*?)(\[\/img\])/iu", "<br><img style=\"max-width: 100%; margin-bottom: 10px;\" src=\"$2\">", $post);

        //Замена bb-кода [b]
        $post = preg_replace("/(\[b\])(.*?)(\[\/b\])/iu", "<b>$2</b>", $post);

        //Замена bb-кода [i]
        $post = preg_replace("/(\[i\])(.*?)(\[\/i\])/iu", "<i>$2</i>", $post);

        //Замена bb-кода [u]
        $post = preg_replace("/(\[u\])(.*?)(\[\/u\])/iu", "<u>$2</u>", $post);

        //Замена bb-кода [s]
        $post = preg_replace("/(\[s\])(.*?)(\[\/s\])/iu", "<s>$2</s>", $post);

        //Замена bb-кода [left]
        $post = preg_replace("/(\[left\])(.*?)(\[\/left\])/iu", "<div style=\"text-align: left\">$2</div>", $post);

        //Замена bb-кода [right]
        $post = preg_replace("/(\[right\])(.*?)(\[\/right\])/iu", "<div style=\"text-align: right\">$2</div>", $post);

        //Замена bb-кода [center]
        $post = preg_replace("/(\[center\])(.*?)(\[\/center\])/iu", "<div style=\"text-align: center\">$2</div>", $post);

        //Замена bb-кода [youtube]
        $post = preg_replace("/(\[youtube\])(.*?)(\[\/youtube\])/iu", "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/$2\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>", $post);

        //Замена bb-кода [spoiler]
        $post = preg_replace("/\[spoiler\=\"(.*?)\"\](.*?)\[\/spoiler\]/ium", "<div class=\"spoiler\"><div class=\"spoiler_title\">$1</div><div class=\"spoiler_content\">$2</div></div>", $post);
        $post = preg_replace("/\[spoiler=(.*?)\](.*?)\[\/spoiler\]/ium", "<div class=\"spoiler\"><div class=\"spoiler_title\">$1</div><div class=\"spoiler_content\">$2</div></div>", $post);

        //Замена bb-кода [url]
        $post = preg_replace("/\[url\=\"(.*?)\"\](.*?)\[\/url\]/iu", "<a class=\"a-thread\" href=\"$1\" target=\"_blank\">$2</a>", $post);
        $post = preg_replace("/\[url\=(.*?)\](.*?)\[\/url\]/iu", "<a class=\"a-thread\" href=\"$1\" target=\"_blank\">$2</a>", $post);

        //Замена bb-кода [color]
        $post = preg_replace("/\[color\=\"(.*?)\"\](.*?)\[\/color\]/iu", "<span style=\"color: $1\">$2</span>", $post);
        $post = preg_replace("/\[color\=(.*?)\](.*?)\[\/color\]/iu", "<span style=\"color: $1\">$2</span>", $post);

        //Замена bb-кода [size]
        $post = preg_replace("/\[size\=\"(.*?)\"\](.*?)\[\/size\]/iu", "<span style=\"font-size: $1\">$2</span>", $post);
        $post = preg_replace("/\[size\=(.*?)\](.*?)\[\/size\]/iu", "<span style=\"font-size: $1\">$2</span>", $post);

        //Замена bb-кода [quote]
        $post = preg_replace("/\[quote\=\"(.*)\"\](.*)\[\/quote\]/iuUm", "<div class=\"quote\"><div class=\"quote_title\">$1 сказал(а):</div><div class=\"quote_body\">$2</div></div>", $post);
        $post = preg_replace("/\[quote\=(.*)\](.*)\[\/quote\]/iuUm", "<div class=\"quote\"><div class=\"quote_title\">$1 сказал(а):</div><div class=\"quote_body\">$2</div></div>", $post);

        //замена ссылок на картинки
        $post = preg_replace("/(^)(http)(\S*.*)(jpg|jpeg|png|bmp|webm|webp|gif)/iumU", "<br><img style=\"max-width: 100%; margin-bottom: 10px;\" src=\"$2$3$4\">", $post);
        $post = preg_replace("/(\>)(http)(\S*.*)(jpg|jpeg|png|bmp|webm|webp|gif)/iumU", "><br><img style=\"max-width: 100%; margin-bottom: 10px;\" src=\"$2$3$4\">", $post);

        //Замена ссылок на ютуб
        $post = preg_replace("/(https\:\/\/youtu\.be\/|https\:\/\/www\.youtube\.com\/watch\?v\=)(\w.+)/iu", "<iframe width=\"100%\" height=\"600\" src=\"https://www.youtube.com/embed/$2\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>", $post);

        //Замена ссылок на коуб
        $post = preg_replace("/(https\:\/\/coub\.com\/view\/)(\w+)/iu", "<iframe src=\"//coub.com/embed/$2\" allowfullscreen frameborder=\"0\" width=\"640\" height=\"360\" allow=\"autoplay\"></iframe>", $post);

        //Списки
        //Маркированный 1
        $post = preg_replace("/\[ul\](.*?)\[\/ul\]/ium", "<ul>$1</ul>", $post);
        //Удаляем лишний <br>
        $post = preg_replace("/\<ul\>\<br\>/ium", '<ul>', $post);
        $post = preg_replace("/\<\/ul\>\<br\>/ium", '</ul>', $post);
        $post = preg_replace("/\<br\>\<ul\>/ium", '<ul>', $post);
        //Маркированный 2
        $post = preg_replace("/\[list\](.*?)\[\/list\]/ium", "<ul>$1</ul>", $post);

        //Нумерованный
        $post = preg_replace("/\[ol\](.*?)\[\/ol\]/ium", "<ol>$1</ol>", $post);
        //Удаляем лишний <br>
        $post = preg_replace("/\<\/ol\>\<br\>/ium", '</ol>', $post);
        //Пункты списка 1
        $post = preg_replace("/\[li\](.*?)\[\/li\]/ium", "<li>$1</li>", $post);
        //Удаляем лишний <br>
        $post = preg_replace("/\<\/li\>\<br\>/ium", '</li>', $post);
        //Пункты списка 2
        //$post = preg_replace("/\[\*\](.*?)(\[\*\])|$/imu", "<li>$1</li>", $post);


        return $post;
    }

    public static function render_post_reverse($post) {
        $post = str_replace('<br>', "\n", $post);

        //Списки
        //Маркированный
        $post = preg_replace("/\<ul\>(.*?)\<\/ul\>/ium", "[ul]$1[/ul]", $post);
        //Нумерованный
        $post = preg_replace("/\<ol\>(.*?)\<\/ol\>/ium", "[ol]$1[/ol]", $post);
        //Пункты списка
        $post = preg_replace("/\<li\>(.*?)\<\/li\>/ium", "[li]$1[/li]", $post);

        return $post;
    }

    public static function render_post_edit($post) {
        $post = str_replace("\n", '<br>', $post);

        //Списки
        //Маркированный 1
        $post = preg_replace("/\[ul\](.*?)\[\/ul\]/ium", "<ul>$1</ul>", $post);
        //Удаляем лишний <br>
        $post = preg_replace("/\<ul\>\<br\>/ium", '<ul>', $post);
        $post = preg_replace("/\<\/ul\>\<br\>/ium", '</ul>', $post);
        $post = preg_replace("/\<br\>\<ul\>/ium", '<ul>', $post);
        //Маркированный 2
        $post = preg_replace("/\[list\](.*?)\[\/list\]/ium", "<ul>$1</ul>", $post);

        //Нумерованный
        $post = preg_replace("/\[ol\](.*?)\[\/ol\]/ium", "<ol>$1</ol>", $post);
        //Удаляем лишний <br>
        $post = preg_replace("/\<\/ol\>\<br\>/ium", '</ol>', $post);
        //Пункты списка 1
        $post = preg_replace("/\[li\](.*?)\[\/li\]/ium", "<li>$1</li>", $post);
        //Удаляем лишний <br>
        $post = preg_replace("/\<\/li\>\<br\>/ium", '</li>', $post);
        //Пункты списка 2
        //$post = preg_replace("/\[\*\](.*?)(\[\*\])|$/imu", "<li>$1</li>", $post);


        return $post;
    }

    //Преобразование падежней для множественных чисел
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

    //Удаляет левые символы для формирования адреса
    public static function delete_symbols($s) {
        $s = str_replace(' ', '_', $s);
        //$s = preg_replace('/\W+|^[а-яе]|^\_/iu', '', $s);
        $s = preg_replace('/\W+|^\_/iu', '', $s);

        return $s;
    }

    //Информация о браузере пользователя
    public static function get_user_browser() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (stristr($user_agent, 'Edge'))
            return 'Microsoft Edge';
        if (stristr($user_agent, 'MSIE'))
            return 'Internet Explorer';
        if (stristr($user_agent, 'Firefox'))
            return 'Firefox';
        if (stristr($user_agent, 'Opera'))
            return 'Opera';
        if (stristr($user_agent, 'Chrome'))
            return 'Google Chrome';
        if (stristr($user_agent, 'Safari'))
            return 'Safari';
        if (stristr($user_agent, 'Konqueror'))
            return 'Konqueror';
        if (stristr($user_agent, 'Iceweasel'))
            return 'Debian Iceweasel';
        if (stristr($user_agent, 'SeaMonkey'))
            return 'SeaMonkey';
    }

    //Антимат
    public static function antimat($str) {
        $wrong_words = array(
            '( ж|^ж)([а-яёa-z]){0,}(п)([а-яёa-z])', //{0,} = *
            '( х|^х| x|^x)(у|y|u){0,}(е|e|й|ем|ец|йня|йней)',
            '( бл|^бл)(я)',
            '( п|^п)(и|u|е|ё)(зд|д)([а-яёa-z])',
            '( ох|^ох)(у|y|е|e|и|u)(еть|реть|[а-яёa-z])',
            '( c|^c| с|^с)(y|у)(k|к)([а-яёa-z])',
            '( г|^г)(о|o|а|a)(вн)([а-яёa-z])',
            '( п|^п)(и|u|е|ё|e)(д)([а-яёa-z])',
            '( еб|^еб|еб| eб|^eб|eб| ёб|^ёб|ёб)(а|a|у|y|ё|о|o|с|с)([а-яёa-z])',
                //'( з|^з|з)([а-яёa-z])([а-яёa-z])',
        );

        foreach ($wrong_words as $key => $value) {
            $limit = preg_match_all("/" . $value . "/iu", $str, $ar) + $limit;
            $str = preg_replace("/" . $value . "/iu", "$1*$3$4", $str);
        }

        if ($limit >= 5) {
            $str = '{Censured}';
        }

        $str = preg_replace("/\:([0-9]+)\:/iumU", "<img class=\"sticker_chat\" src=\"" . self::home() . '/img/stickers/' . "$1" . '.png' . "\">", $str);

        return $str;
    }

    public static function redirect($address, $time = 0): void {
        echo '<meta http-equiv="refresh" content="' . $time . '; URL=' . $address . '">';
    }

    public static function to_https(): void {
        if ($_SERVER['REQUEST_SCHEME'] == 'http') {
            self::redirect('https://rtd-titan.pw');
        }
    }

}
