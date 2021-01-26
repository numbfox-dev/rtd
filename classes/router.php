<?

class router {

    private static $instance = null;
    private $page = 'main';
    private $action = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new router();
        }

        return self::$instance;
    }

    private function __construct() {
        $path = substr($_SERVER['REQUEST_URI'], 1);

        //Если в конце строки 2 слэша, то обозначем это для дальнейшего редиректа
        if ($path[strlen($path) - 1] == '/' and $path[strlen($path) - 2] == '/') {
            $flag = true;
        }

        //Удаляем последний слэш, если он есть
        if ($path[strlen($path) - 1] == '/') {
            $path = substr($path, 0, -1);
        }

        $pages = explode('/', $path);

        //Если страниц больше или заспамили слэшами, то выкидываем на главную
        if (sizeof($pages) > 2 or $flag) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: /');
        }

        //Если не пустой первый элемент массива, то это новый контроллер
        if (!empty($pages[0])) {
            $this->page = $pages[0];
        }

        //Если не пустой второй элемент массива, то это новое действие
        if (!empty($pages[1])) {
            $this->action = $pages[1];
        } else {
            $this->action = null;
        }
    }

    public function __destruct() {
        
    }

    public function run() {
        //Подключаем модель шаблона, она есть всегда
        include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/models/model_template.php');

        //Подключаем модель страницы, если она существует
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/classes/models/model_' . $this->page . '.php')) {
            $model = 'model_' . $this->page;
            include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/models/' . $model . '.php');
        } else {
            header('HTTP/1.1 404 Not Found');
            header("Status: 404 Not Found");
            //$this->page = ;
        }


        //Создаем контроллер и запускаем
        $controller = new controller();
        $controller->run($this->page, $model, $this->action);
    }

}

function router() {
    return router::get_instance();
}
