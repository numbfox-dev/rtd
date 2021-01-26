<?

class model_template {

    public function get_data($page, $action) {
        if (isset($_SESSION['id'])) {
            $user = db()->select('users')->where('id', $_SESSION['id'])->get();

            $template_array['user'] = array(
                'id' => $user[0]->id,
                'name' => $user[0]->name,
                'avatar' => $user[0]->avatar,
                'class_id' => $user[0]->class_id,
                'group_id' => $user[0]->group_id,
                'rank' => $user[0]->rank,
                'answers' => $user[0]->answers,
                'threads' => $user[0]->threads,
            );
        } else {
            $template_array['user'] = array(
                'id' => 0,
                'group_id' => 0,
            );
        }

        $pages = array(
            'main' => 'Главная',
            'register' => 'Регистрация',
        );

        if ($action != null) {
            $template_array['title'] = urldecode(explode('-', $action)[0]);
        } else {
            $template_array['title'] = $pages[$page];
        }
        
        $get_menu = db()->select('menu')->apply();
        while ($menu = db()->get_row($get_menu)) {
            $menu_array[] = array(
                'id' => $menu->id,
                'name' => $menu->name,
                'url' => $menu->url,
            );
        }
        
        $template_array['menu'] = $menu_array;

        return $template_array;
    }

}
