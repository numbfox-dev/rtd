<?

class model_section {

    public function get_data($action) {
        $action_array = explode('-', $action);
        $id = intval(end($action_array));

        $user = db()->select('users')->where('id', $_SESSION['id'])->get();

        //$get_section = db()->select('section')->where('id', $id)->apply();
        $get_section = db()->query("SELECT * FROM `section` WHERE `id` = '" . $id . "' AND `access` LIKE '%" . $user[0]->group_id . "%'");
        while ($section = db()->get_row($get_section)) {
          
            $get_sticky_thread = db()->select('thread')->where('section_id', $section->id)->_and('hidden', '0')->_and('sticky', 1)->apply();
            while ($sticky_thread = db()->get_row($get_sticky_thread)) {
                $user = db()->select('users')->where('id', $sticky_thread->author_id)->get();

                $sticky_thread_array[] = array(
                    'id' => $sticky_thread->id,
                    'name' => $sticky_thread->name,
                    'url' => $sticky_thread->url,
                    'date' => functions::format_date($sticky_thread->date),
                    'datetime' => functions::format_datetime($sticky_thread->date),
                    'author' => $sticky_thread->author,
                    'author_id' => $sticky_thread->author_id,
                    'avatar' => $user[0]->avatar,
                    'status' => $sticky_thread->status,
                    'answers' => $sticky_thread->answers,
                    'views' => $sticky_thread->views,
                    'last_date' => functions::format_date($sticky_thread->last_date),
                    'last_datetime' => functions::format_datetime($sticky_thread->last_date),
                    'last_author' => $sticky_thread->last_author,
                    'last_author_id' => $sticky_thread->last_author_id,
                );
            }
            
            $get_thread = db()->select('thread')->where('section_id', $section->id)->_and('hidden', '0')->_and('sticky', 0)->order('last_message_date', false)->apply();
            while ($thread = db()->get_row($get_thread)) {
                $user = db()->select('users')->where('id', $thread->author_id)->get();

                $thread_array[] = array(
                    'id' => $thread->id,
                    'name' => $thread->name,
                    'url' => $thread->url,
                    'date' => functions::format_date($thread->date),
                    'datetime' => functions::format_datetime($thread->date),
                    'author' => $thread->author,
                    'author_id' => $thread->author_id,
                    'avatar' => $user[0]->avatar,
                    'status' => $thread->status,
                    'answers' => $thread->answers,
                    'views' => $thread->views,
                    'last_date' => functions::format_date($thread->last_date),
                    'last_datetime' => functions::format_datetime($thread->last_date),
                    'last_author' => $thread->last_author,
                    'last_author_id' => $thread->last_author_id,
                );
            }
            
            $form = db()->select('form')->where('id', $section->form_id)->get();

            $section_array[] = array(
                'id' => $section->id,
                'name' => $section->name,
                'category_id' => $section->category_id,
                'parent_section_id' => $section->parent_section_id,
                'access' => explode(',', $section->access),
                'create' => explode(',', $section->create),
                'moder' => explode(',', $section->moder),
                'form_id' => $section->form_id,
                'form_name' => $form[0]->name,
                'threads' => $thread_array,
                'sticky_threads' => $sticky_thread_array,
            );

            unset($thread_array);
            unset($sticky_thread_array);
        }

        $get_child_section = db()->query("SELECT * FROM `section` WHERE `parent_section_id` = '" . $id . "' AND `access` LIKE '%" . $user[0]->group_id . "%'");
        while ($child_section = db()->get_row($get_child_section)) {
            $form = db()->select('form')->where('id', $child_section->form_id)->get();
            
            $child_section_array[] = array(
                'id' => $child_section->id,
                'name' => $child_section->name,
                'access' => explode(',', $child_section->access),
                'create' => explode(',', $child_section->create),
                'moder' => explode(',', $child_section->moder),
                'form_id' => $child_section->form_id,
                'form_name' => $form[0]->name,
            );
        }

        $section_array['sections'] = $child_section_array;

        $category = db()->select('category')->where('id', $section_array[0]['category_id'])->get();
        $section_array['notice'] = $category[0]->notice;        
        $section_array['category'] = $category;
        
        $parent_for_breadcrumbs = $section_array[0]['parent_section_id'];
        $i = 0;
        while ($parent_for_breadcrumbs != 0) {
            $next_parent_for_breadcrumbs = db()->select('section')->where('id', $parent_for_breadcrumbs)->get();
            
            $parent_for_breadcrumbs = $next_parent_for_breadcrumbs[0]->parent_section_id;
            $breadcrums_array[$i] = array(
                'id' => $next_parent_for_breadcrumbs[0]->id,
                'name' => $next_parent_for_breadcrumbs[0]->name,
            );
            $i++;
        }
        
        $section_array['breadcrumbs'] = $breadcrums_array;
         
        
        return $section_array;
    }

}
