<?

class model_users {

    public function get_data($action) {
        $action_array = explode('-', $action);
        $id = intval(end($action_array));

        if ($id == $_SESSION['id']) {
            
        }

        $user = db()->select('users')->where('id', $id)->get();

        $array['user'] = array(
            'id' => $user[0]->id,
            'name' => $user[0]->name,
            'avatar' => $user[0]->avatar,
            'class_id' => $user[0]->class_id,
            'group_id' => $user[0]->group_id,
            'rank' => $user[0]->rank,
            'answers' => $user[0]->answers,
            'threads' => $user[0]->threads,
        );

        $get_class = db()->select('class')->apply();
        while ($class = db()->get_row($get_class)) {
            $array['class'][$class->id] = array(
                'id' => $class->id,
                'name' => $class->name,
                'sign' => $class->sign
            );
        }

        return $array;
    }

}
