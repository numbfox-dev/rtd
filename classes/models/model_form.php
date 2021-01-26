<?

class model_form {

    public function get_data($action) {
        $get_class = db()->select('class')->apply();
        while ($class = db()->get_row($get_class)) {
            $array['class'][$class->id] = $class->name;
        }
        
        $action_array = explode('-', $action);
	$id = intval(end($action_array));
        
        $form = db()->select('form')->where('id', $id)->get();
        $array['form']['id'] = $form[0]->id;
        $array['form']['name'] = $form[0]->name;
        
        $section = db()->select('section')->where('form_id', $form[0]->id)->get();
        $array['section']['id'] = $section[0]->id;
        

        return $array;
    }

}
