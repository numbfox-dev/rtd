<?

class model_thread {

    public function get_data($action) {
        $action_array = explode('-', $action);
        $id = intval(end($action_array));
        
        if (isset($_SESSION['id'])) {
            $user = db()->select('users')->where('id', $_SESSION['id'])->get();

            $user_array[] = array(
                'id' => $user[0]->id,
                'name' => $user[0]->name,
                'avatar' => $user[0]->avatar,
                'class_id' => $user[0]->class_id,
                'group_id' => $user[0]->group_id,
                'rank' => $user[0]->rank,
                'answers' => $user[0]->answers,
                'threads' => $user[0]->threads,
                'signature' => $user[0]->signature,
            );
        } else {
            $user_array[] = array(
                'id' => 0,
                'group_id' => 0,
            );
        }        

        $get_thread = db()->select('thread')->where('id', $id)->apply();
        while ($thread = db()->get_row($get_thread)) {

            //Пагинация
            /*
              $page = intval($_GET['page']) or $page = 1;
              $limit = 10; //Количество постов на странице

              //Получение количества страниц в теме
              db()->query("SELECT * FROM `post` WHERE `thread_id` = '" . $thread->id . "'");
              $page_count = ceil(db()->num_rows() / $limit);

              $first_prev = $page - 1;
              $second_prev = $page - 2;

              $first_next = $page + 1;
              $second_next = $page + 2;

              if ($page == 1 and $page_count == 1) {
              db()->query("SELECT * FROM `post` WHERE `thread_id` = '" . $thread->id . "' ORDER BY `id` ASC LIMIT 1");
              $a = db()->get_row();
              $id = $a->id;
              } elseif ($page > 1 and $page_count > 1) {
              db()->query("SELECT * FROM `post` WHERE `thread_id` = '" . $thread->id . "' ORDER BY `id` ASC LIMIT " . $limit * ($page) . "");

              while ($a = db()->get_row()) {
              $id = $a->id;
              }
              }

              $get_post = db()->query("SELECT * FROM `post` WHERE `id` >= '" . $id . "' AND `thread_id` = '" . $thread->id . "' ORDER BY `id` ASC LIMIT " . $limit . "");
             */

            $section = db()->select('section')->where('id', $thread->section_id)->get();
            $moder = explode(',', $section[0]->moder);
            
            $thread_array['moder'] = $moder;
            
            if (functions::one_of($moder, $user_array[0]['group_id'])) {                
                $get_post = db()->select('post')->where('thread_id', $thread->id)->apply();
            } else {                
                $get_post = db()->select('post')->where('thread_id', $thread->id)->_and('hidden', '0')->apply();
            }
            
            while ($post = db()->get_row($get_post)) {
                $user = db()->select('users')->where('id', $post->author_id)->get();
                $group = db()->select('group')->where('id', $user[0]->group_id)->get();
                $class = db()->select('class')->where('id', $user[0]->class_id)->get();

                $post_array[] = array(
                    'id' => $post->id,
                    'date' => functions::format_date($post->date),
                    'datetime' => functions::format_datetime($post->date),
                    'content' => $post->content,
                    'author' => $post->author,
                    'author_id' => $post->author_id,
                    'avatar' => $user[0]->avatar,
                    'class' => $class[0]->name,
                    'sign' => $class[0]->sign,
                    'rank' => $user[0]->rank,
                    'group' => $group[0]->name,
                    'group_id' => $group[0]->id,
                    'background' => $group[0]->background,
                    'font' => $group[0]->font,
                    'border' => $group[0]->border,
                    'hidden' => $post->hidden,
                    'signature' => $user[0]->signature,
                    'last_date' => functions::format_date($post->last_date),
                    'last_datetime' => functions::format_datetime($post->last_date),
                    'last_author' => $post->last_author,
                    'last_author_id' => $post->last_author_id,
                );
            }

            $thread_array[] = array(
                'id' => $thread->id,
                'section_id' => $thread->section_id,
                'name' => $thread->name,
                'access' => explode(',', $thread->access),
                'date' => functions::format_date($thread->date),
                'datetime' => functions::format_datetime($thread->date),
                'author' => $thread->author,
                'author_id' => $thread->author_id,
                'status' => $thread->status,
                'hidden' => $thread->hidden,
                'sticky' => $thread->sticky,
                'views' => $thread->views,
                'posts' => $post_array,
            );

            unset($post_array);
        }
        
        $get_category = db()->select('category')->apply();
        while ($category = db()->get_row($get_category)) {
            $category_array[] = array(
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'access' => explode(',', $category->access),
            );

            $get_section = db()->select('section')->where('category_id', $category->id)->apply();
            while ($section = db()->get_row($get_section)) {
                $section_array[$category->id][] = array(
                    id => $section->id,
                    name => $section->name,
                );
            }
        }
        
        $thread_array['category'] = $category_array;
        $thread_array['section'] = $section_array;

        $thread_update_views = $thread_array[0]['views'] + 1;
        db()->query("UPDATE `thread` SET `views` = '" . $thread_update_views . "' WHERE `id` = '" . $thread_array[0]['id'] . "'");
        
        
        $parent_for_breadcrumbs = $thread_array[0]['section_id'];
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
        
        $section_for_breadcrumbs = db()->select('section')->where('id', $thread_array[0]['section_id'])->get();
        $category_for_breadcrumbs = db()->select('category')->where('id', $section_for_breadcrumbs[0]->category_id)->get();
        $breadcrums_array[] = array(
            'id' => $category_for_breadcrumbs[0]->id,
            'name' => $category_for_breadcrumbs[0]->name,
        );
        
        $thread_array['breadcrumbs'] = $breadcrums_array;
        

        return $thread_array;
    }

}
