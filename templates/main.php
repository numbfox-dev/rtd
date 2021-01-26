<div class="row">
    <div id="forum">

        <? if (functions::one_of($data['chat_settings']['access'], $template_data['user']['group_id'])): ?>
        <div id="chat">
            <div id="chat_header">Чатек</div>
            <div id="chat_box">
                <? for ($i = 0; $i < sizeof($data['chat']); $i++): ?>
                <div class="chat_message row">
                    <div class="chat_message_author"><a href="<?= functions::home() . '/users/' . $data['chat'][$i]['author'] . '-' . $data['chat'][$i]['author_id']; ?>" style="color: <?= $data['chat'][$i]['background']; ?>"><?= $data['chat'][$i]['author'] . ':'; ?></a></div>
                    <div class="chat_message_text"><?= $data['chat'][$i]['message']; ?></div>
                    <div class="chat_message_date" title="<?= functions::format_datetime($data['chat'][$i]['date']); ?>"><?= functions::format_time($data['chat'][$i]['date']); ?></div>
                </div>
                <? endfor; ?>
            </div>
            <div id="smile_box">
            <? $stickers = scandir($_SERVER['DOCUMENT_ROOT'] . '/img/stickers'); ?>
            <? for ($i = 2; $i < sizeof($stickers); $i++): ?>
                <img class="sticker" src="<?= functions::home() . '/img/stickers/' . $stickers[$i]; ?>" onclick="insert_sticker('<?= substr($stickers[$i], 0, -4); ?>')">
            <? endfor; ?>
            </div>
            <div class="row">                
                <input type="text" id="chat_text">
                <div class="fa fa-smile-o" onclick="show_stickers()"></div>
                <div class="button" onclick="send_message()">Отправить</div>
            </div>
        </div>
        <? endif; ?>

        <? for ($i = 0; $i < sizeof($data['category']); $i++): ?>
            <? //if (functions::one_of($data[$i]['access'], $template_data['user']['group_id'])): ?>
            <div class="category">
                <div class="category_title">
                    <a href="<?= functions::home() . '/category/' . $data['category'][$i]['name'] . '-' . $data['category'][$i]['id']; ?>"><?= $data['category'][$i]['name']; ?></a>
                    <div class="category_description"><?= $data['category'][$i]['description'] ?></div>
                </div>
                <? for ($j = 0; $j < sizeof($data['category'][$i]['sections']); $j++): ?>
                    <? //if (functions::one_of($data[$i]['sections'][$j]['access'], $template_data['user']['group_id'])): ?>
                    <div class="category_body row">
                        <div class="category_body_img"><i class="fa fa-comments-o" aria-hidden="true" onclick=""></i></div>
                        <div class="category_body_name">
                            <div><a href="<?= functions::home() . '/section/' . $data['category'][$i]['sections'][$j]['name'] . '-' . $data['category'][$i]['sections'][$j]['id']; ?>"><?= $data['category'][$i]['sections'][$j]['name']; ?></a></div>
                            <? for($k = 0; $k < sizeof($data['category'][$i]['sections'][$j]['child_sections']); $k++): ?>
                            <div style="font-size: 12px;"><a href="<?= functions::home() . '/section/' . $data['category'][$i]['sections'][$j]['child_sections'][$k]->name . '-' . $data['category'][$i]['sections'][$j]['child_sections'][$k]->id ; ?>"><div style="padding-right: 3px;" class="fa fa-comments-o"></div><?= $data['category'][$i]['sections'][$j]['child_sections'][$k]->name; ?></a></div>
                            <? endfor; ?>
                        </div>
                        <div class="category_body_info row">
                            <div class="threads">
                                <div class="threads_count"><?= $data['category'][$i]['sections'][$j]['threads']; ?></div>
                                <div class="threads_text"><?= functions::many($data['category'][$i]['sections'][$j]['threads'], 'Тема', 'Темы', 'Тем'); ?></div>
                            </div>
                            <div class="answers">
                                <div class="answers_count"><?= $data['category'][$i]['sections'][$j]['answers']; ?></div>
                                <div class="answers_text"><?= functions::many($data['category'][$i]['sections'][$j]['answers'], 'Ответ', 'Ответа', 'Ответов'); ?></div>
                            </div>
                            <div class="last">
                                <? if ($data['category'][$i]['sections'][$j]['last_id'] > 0): ?>
                                    <div class="last_name"><a href="<?= functions::home() . '/thread/' . $data['category'][$i]['sections'][$j]['last_url'] . '-' . $data['category'][$i]['sections'][$j]['last_id']; ?>"><?= $data['category'][$i]['sections'][$j]['last_name']; ?></a></div>
                                    <div class="last_info"><?= $data['category'][$i]['sections'][$j]['last_date'] . ' - '; ?><a href="<?= functions::home() . '/user/' . $data['category'][$i]['sections'][$j]['last_author'] . '-' . $data['category'][$i]['sections'][$j]['last_author_id']; ?>"><?= $data['category'][$i]['sections'][$j]['last_author']; ?></a></div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>

                    <? if ((sizeof($data['category'][$i]['sections']) > 1) and ( sizeof($data['category'][$i]['sections']) != $j + 1)): ?>
                        <hr>
                    <? endif; ?>

                    <? //endif; ?>
                <? endfor; ?>
            </div>
            <? //endif; ?>
        <? endfor; ?>
    </div>

    <div id="right_sidebar">
        <? if (sizeof($data['last_thread']) > 0): ?>
            <div class="block">
                <div class="block_title">Последние темы</div>
                <div class="block_body">
                    <? for ($i = 0; $i < sizeof($data['last_thread']); $i++): ?>
                        <div class="last_threads row">
                            <div class="avatar_mini"><img src="<?= $data['last_thread'][$i]['avatar']; ?>"></div>
                            <div class="last_threads_info">
                                <div><a href="<?= functions::home() . '/thread/' . $data['last_thread'][$i]['url'] . '-' . $data['last_thread'][$i]['id']; ?>"><?= $data['last_thread'][$i]['name']; ?></a></div>
                                <div>Автор <a href="<?= functions::home() . '/users/' . $data['last_thread'][$i]['author'] . '-' . $data['last_thread'][$i]['author_id']; ?>"><?= $data['last_thread'][$i]['author']; ?></a> - <?= $data['last_thread'][$i]['date']; ?></div>
                                <div>Ответы: <?= $data['last_thread'][$i]['answers']; ?></div>
                                <div><?= $data['last_thread'][$i]['section']; ?></div>
                            </div>
                        </div>

                        <? if ((sizeof($data['last_thread']) > 1) and ( sizeof($data['last_thread']) != $i + 1)): ?>
                            <hr class="block_divider">
                        <? endif; ?>

                    <? endfor; ?>
                </div>
            </div>
        <? endif; ?>

        <? /* ?>
          <div class="block">
          <div class="block_title">События этой недели</div>
          <div class="block_body">
          <div class="column">
          <div>Арена Героев (Ремесло)</div>
          <div class="button">Приду</div>
          <div class="button">Не приду</div>
          </div>
          <hr class="block_divider">
          <div class="column">
          <div>Территориальные Войны (четверг)</div>
          <div class="button">Приду</div>
          <div class="button">Не приду</div>
          </div>
          <hr class="block_divider">
          <div class="column">
          <div>Межсерверная Битва Гильдий</div>
          <div class="button">Приду</div>
          <div class="button">Не приду</div>
          </div>
          <hr class="block_divider">
          <div class="column">
          <div>Дракон Садэман</div>
          <div class="button">Приду</div>
          <div class="button">Не приду</div>
          </div>
          <hr class="block_divider">
          <div class="column">
          <div>Территориальные Войны (Воскресенье)</div>
          <div class="button">Приду</div>
          <div class="button">Не приду</div>
          </div>
          </div>
          </div>
          <? */ ?>

        <div class="block">
            <div class="block_title">Статистика форума</div>
            <div class="block_body">
                <div class="forum_statistic row">
                    <div class="parameter">Темы: </div>
                    <div class="value"><?= $data['statistic'][0]['threads']; ?></div>
                </div>
                <div class="forum_statistic row">
                    <div class="parameter">Сообщения: </div>
                    <div class="value"><?= $data['statistic'][0]['answers']; ?></div>
                </div>
                <div class="forum_statistic row">
                    <div class="parameter">Пользователи: </div>
                    <div class="value"><?= $data['statistic'][0]['users']; ?></div>
                </div>
                <div class="forum_statistic row">
                    <div class="parameter">Новый пользователь: </div>
                    <div class="value"><a href="<?= functions::home() . '/users/' . $data['statistic'][0]['new_user'] . '-' . $data['statistic'][0]['new_user_id']; ?>"><?= $data['statistic'][0]['new_user']; ?></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

 <? if (functions::one_of($data['chat_settings']['access'], $template_data['user']['group_id'])): ?>
<script src="<?= functions::dynamic_file_name('/js/chat.js'); ?>"></script>
<? endif; ?>