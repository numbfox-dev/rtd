<?

class model_category {

    public function get_data($action) {
        $action_array = explode('-', $action);
        $id = intval(end($action_array));

        $user = db()->select('users')->where('id', $_SESSION['id'])->get();

        $get_category = db()->query("SELECT * FROM `category` WHERE `id` = '" . $id . "' AND `access` LIKE '%" . $user[0]->group_id . "%'");
        while ($category = db()->get_row($get_category)) {

            $get_section = db()->query("SELECT * FROM `section` WHERE `category_id` = '" . $category->id . "' AND `access` LIKE '%" . $user[0]->group_id . "%'  AND `parent_section_id` = '0'");
            while ($section = db()->get_row($get_section)) {
                $get_thread_last = db()->query("SELECT * FROM `thread` WHERE `section_id` = '" . $section->id . "' AND `access` LIKE '%" . $user[0]->group_id . "%' AND `hidden` = '0' ORDER BY `last_date` DESC LIMIT 1");
                $thread_last = db()->get_row($get_thread_last);

                if ($user[0]->group_id > 0) {
                    $child_section = db()->select('section')->where('parent_section_id', $section->id)->_and('access', $user[0]->group_id, 'LIKE%%')->get();
                }  
                
                $section_array[] = array(
                    'id' => $section->id,
                    'name' => $section->name,
                    'access' => explode(',', $section->access),
                    'threads' => $section->threads,
                    'answers' => $section->answers,
                    'last_name' => $thread_last->name,
                    'last_url' => $thread_last->url,
                    'last_id' => $thread_last->id,
                    'last_date' => functions::format_date($thread_last->last_date),
                    'last_author' => $thread_last->last_author,
                    'last_author_id' => $thread_last->last_author_id,
                    'child_sections' => $child_section,
                );
            }

            $category_array[] = array(
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'threads' => $threads_count,
                'answers' => $answers_count,
                'access' => explode(',', $category->access),
                'sections' => $section_array,
                'notice' => $category->notice,
            );

            unset($section_array);
        }

        return $category_array;
    }

}
