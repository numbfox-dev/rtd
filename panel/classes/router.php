<?

class router {

    private static $page;
    private static $action;

    public static function route() {
        $path = explode('/', $_SERVER['REQUEST_URI']);
        
        $default = 'pages/main.php';

        self::$page = $path[2];
        self::$action = $path[3];
        
        if (!empty($path[2])) {
            $page = 'pages/' . $path[2] . '.php';
        } else {
            $page = $default;
        }

        if (file_exists($page)) {
            include_once($page);
        } else {
            echo 'Страница не найдена';
        }
    }

    public static function get_page() {
        return self::$page;
    }

    public static function get_action() {
        return self::$action;
    }

}
