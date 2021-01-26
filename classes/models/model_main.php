<?

class guest {

    public $group_id = 0;

}

class model_main {

    public function get_data($action) {

        if (isset($_SESSION['id'])) {
            $user = db()->select('users')->where('id', $_SESSION['id'])->get();
        } else {
            $user[0] = new guest;
        }
        
        $get_group = db()->select('group')->apply();        
        while ($group = db()->get_row($get_group)) {
            $group_array[$group->id] = array(
                'background' => $group->background,
                'font' => $group->font,
                'border' => $group->border,
            );
        }

        //$get_last_thread = db()->query("SELECT * FROM `thread` WHERE `status` = 0 AND `access` LIKE '%" . $user[0]->group_id . "%' AND `hidden` = 0 ORDER BY `last_date` DESC LIMIT 5");
        $get_last_thread = db()->query("SELECT * FROM `thread` WHERE `access` LIKE '%" . $user[0]->group_id . "%' AND `hidden` = '0' ORDER BY `last_date` DESC LIMIT 5");
        while ($last_thread = db()->get_row($get_last_thread)) {
            $last_user = db()->select('users')->where('id', $last_thread->author_id)->get();

            $last_thread_array[] = array(
                'id' => $last_thread->id,
                'name' => $last_thread->name,
                'url' => $last_thread->url,
                'access' => $last_thread->access,
                'author' => $last_thread->author,
                'avatar' => $last_user[0]->avatar,
                'author_id' => $last_thread->author_id,
                'answers' => $last_thread->answers,
                'date' => functions::format_date($last_thread->date),
            );
            
            //$get_last_post = db()->select('post')->where('hidden', 0)->_and('thread_id', $)->apply();
        }
        
        

        $category_array['last_thread'] = $last_thread_array;

        $statistic = db()->select('statistic')->get();
        $statistic_array[] = array(
            'threads' => $statistic[0]->threads,
            'answers' => $statistic[0]->answers,
            'users' => $statistic[0]->users,
            'new_user' => $statistic[0]->new_user,
            'new_user_id' => $statistic[0]->new_user_id,
        );

        $category_array['statistic'] = $statistic_array;

        $get_category = db()->query("SELECT * FROM `category` WHERE `access` LIKE '%" . $user[0]->group_id . "%'");
        while ($category = db()->get_row($get_category)) {

            $get_section = db()->query("SELECT * FROM `section` WHERE `category_id` = '" . $category->id . "' AND `access` LIKE '%" . $user[0]->group_id . "%' AND `parent_section_id` = '0'");
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

            $category_array['category'][] = array(
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'threads' => $category->threads,
                'answers' => $category->answers,
                'access' => explode(',', $category->access),
                'sections' => $section_array,
            );

            unset($section_array);
        }

        $get_chat = db()->select('chat')->where('date', date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"))), '>=')->apply();
        while ($chat = db()->get_row($get_chat)) {
            $user1 = db()->select('users')->where('id', $chat->author_id)->get();
            $category_array['chat'][] = array(
                'id' => $chat->id,
                'author' => $chat->author,
                'author_id' => $chat->author_id,
                'message' => functions::antimat($chat->message),
                'date' => $chat->date,
                'background' => $group_array[$user1[0]->group_id]['background'],
            );

            $last_message_id = $chat->id;
        }

        db()->query("UPDATE `users` SET `last_message_id` = '" . $last_message_id . "' WHERE `id` = '" . $user[0]->id . "'");
        
        $get_chat_settings = db()->select('chat_settings')->get();        
        $category_array['chat_settings']['access'] = explode(',', $get_chat_settings[0]->access);
        
        return $category_array;
    }

}
