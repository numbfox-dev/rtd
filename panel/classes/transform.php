<?

//Класс преобразования слов
class transform {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new transform();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    public function __destruct() {
        
    }

    //Множественное число
    //num - число, one - единственное число, between - среднее значение, many - множественное число
    //пример:  many(1, комментарий, комментария, комментариев)
    public function many($num, $one = '', $between = '', $many = '') {
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

        return /*$num . */$temp;
    }
    
    public function transliterate($text, $lat2rus = false) {
        $rus = array();
        $lat = array();
        
        $text = mb_strtolower($text);

        $chars = array(
            'ё' => 'yo',
            'ж' => 'zh',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'э' => 'ye',
            'ю' => 'yu',
            'я' => 'ya',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            ' ' => '_',
            '-' => '_',
            ',' => '',
            '.' => '',
        );

        $i = 0;
        foreach ($chars as $key => $value) {
            $rus[$i] = $key;
            $lat[$i] = $value;

            $i++;
        }

        $text = trim($text);

        if ($lat2rus) {
            return str_replace($lat, $rus, $text);
        } else {
            return str_replace($rus, $lat, $text);
        }
    }

}

function many($num, $one = '', $between = '', $many = '') {
    return transform::many($num, $one, $between, $many);
}

function rus2lat($text) {
    return transform::transliterate($text);
}

function lat2rus($text) {
    return transform::transliterate($text, true);
}