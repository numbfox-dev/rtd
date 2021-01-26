<div class="breadcrumbs row">
    <a class="breadcrumbs_button" href="<?= functions::home(); ?>">Главная</a>
    <a class="breadcrumbs_button" href="<?= functions::home() . '/category/' . $data['category'][0]->name . '-' . $data['category'][0]->id; ?>"><?= $data['category'][0]->name; ?></a>
    <? for ($i = sizeof($data['breadcrumbs']) - 1; $i >= 0; $i--): ?>
        <a class="breadcrumbs_button" href="<?= functions::home() . '/section/' . $data['breadcrumbs'][$i]['name'] . '-' . $data['breadcrumbs'][$i]['id']; ?>"><?= $data['breadcrumbs'][$i]['name']; ?></a>
    <? endfor; ?>
</div>
<div id="section">
    <div id="section_header">
        <? if (functions::one_of($data[0]['access'], $template_data['user']['group_id'])): ?>
            <div id="title"><?= $data[0]['name']; ?></div>
        </div>	

        <? if ($data['notice'] != ''): ?>
            <div id="category_notice">
                <?= functions::render_post($data['notice']); ?>
            </div>
        <? endif; ?>

        <? if (sizeof($data['sections']) > 0): ?>
            <div class="section_child">
                <? for ($i = 0; $i < sizeof($data['sections']); $i++): ?>
                    <div class="section_child_title row">
                        <a href="<?= functions::home() . '/section/' . $data['sections'][$i]['name'] . '-' . $data['sections'][$i]['id']; ?>"><?= $data['sections'][$i]['name']; ?></a>
                        <div class="section_child_description"><?= $data['sections'][$i]['description'] ?></div>
                    </div>	
                <? endfor; ?>
            </div>
        <? endif; ?>

        <? /* 	
          <div id="category">
          <div id="category_header">
          <? if (functions::one_of($data[0]['access'], $template_data['user']['group_id'])): ?>
          <div id="title"><?= $data[0]['name']?></div>
          <div class="category_description"><?= $data[0]['description'] ?></div>
          </div>
          <div style="height: 30px; margin: 20px 0 10px 0px;">
          <div class="button" style="float: right;">Отметить прочитанным</div>
          </div>
          <div class="category">
          <? for ($i = 0; $i < sizeof($data); $i++): ?>
          <? for ($j = 0; $j < sizeof($data[$i]['sections']); $j++): ?>
          <? if (functions::one_of($data[$i]['sections'][$j]['access'], $template_data['user']['group_id'])): ?>
          <div class="category_body row">
          <div class="category_body_img"><i class="fa fa-comments-o" aria-hidden="true" onclick=""></i></div>
          <div class="category_body_name"><a href="<?= functions::home() . '/section/' . $data[$i]['sections'][$j]['name'] . '-' . $data[$i]['sections'][$j]['id']; ?>"><?= $data[$i]['sections'][$j]['name']; ?></a></div>
          <div class="category_body_info row">
          <div class="threads">
          <div class="threads_count"><?= $data[$i]['sections'][$j]['threads']; ?></div>
          <div class="threads_text"><?= functions::many($data[$i]['sections'][$j]['threads'], 'Тема', 'Темы', 'Тем'); ?></div>
          </div>
          <div class="answers">
          <div class="answers_count"><?= $data[$i]['sections'][$j]['answers']; ?></div>
          <div class="answers_text"><?= functions::many($data[$i]['sections'][$j]['answers'], 'Ответ', 'Ответа', 'Ответов'); ?></div>
          </div>
          <div class="last">
          <? if ($data[$i]['sections'][$j]['last_id'] > 0): ?>
          <div class="last_name"><a href="<?= functions::home() . '/thread/' . $data[$i]['sections'][$j]['last_url'] . '-' . $data[$i]['sections'][$j]['last_id']; ?>"><?= $data[$i]['sections'][$j]['last_name']; ?></a></div>
          <div class="last_info"><?= $data[$i]['sections'][$j]['last_date'] . ' - '; ?><a href="<?= functions::home() . '/user/' . $data[$i]['sections'][$j]['last_author'] . '-' . $data[$i]['sections'][$j]['last_author_id']; ?>"><?= $data[$i]['sections'][$j]['last_author']; ?></a></div>
          <? endif; ?>
          </div>
          </div>
          </div>

          <? if ((sizeof($data[$i]['sections']) > 1) and (sizeof($data[$i]['sections']) != $j + 1)): ?>
          <hr>
          <? endif; ?>

          <? endif; ?>
          <? endfor; ?>
          <? endfor; ?>

          <? else: ?>
          Нет доступа
          <? endif; ?>

          </div>
          </div>
         */ ?>




        <? if (functions::is_logined()): ?>	
            <div style="height: 30px; margin: 20px 0 10px 0px;">
                <div class="button" style="float: right;" onclick="">Отметить прочитанным</div>	

                <? if (functions::one_of($data[0]['create'], $template_data['user']['group_id'])): ?>
                    <? if ($data[0]['form_id'] == 0): ?>
                        <div class="button" style="float: right; margin-right: 10px;" onclick="create_new_thread_link('<?= $data[0]['name'] . '-' . $data[0]['id']; ?>')">Создать тему</div>
                    <? else: ?>
                        <div class="button" style="float: right; margin-right: 10px;" onclick="create_new_thread_link_form('<?= $data[0]['form_name'] . '-' . $data[0]['form_id']; ?>')">Создать тему</div>
                    <? endif; ?>
                <? endif; ?>

            </div>

        <? endif; ?>

        <div class="section">
            <? if (sizeof($data[0]['threads']) == 0 /* and $data[0]['create'][0] != '' */): ?>
                Темы еще не созданы.
            <? else: ?>

                <? for ($i = 0; $i < sizeof($data[0]['sticky_threads']); $i++): ?>
                    <div class="section_body row">
                        <div class="avatar"><img src="<?= $data[0]['sticky_threads'][$i]['avatar']; ?>"></div>
                        <div class="thread_info">
                            <div class="thread_title"><a href="<?= functions::home() . '/thread/' . $data[0]['sticky_threads'][$i]['url'] . '-' . $data[0]['sticky_threads'][$i]['id']; ?>"><?= $data[0]['sticky_threads'][$i]['name']; ?></a></div>
                            <div class="thread_date" title="<?= $data[0]['sticky_threads'][$i]['datetime']; ?>"><a href="<?= functions::home() . '/user/' . $data[0]['sticky_threads'][$i]['author'] . '-' . $data[0]['sticky_threads'][$i]['author_id']; ?>"><?= $data[0]['sticky_threads'][$i]['author']; ?></a> <?= $data[0]['sticky_threads'][$i]['date']; ?></div>
                        </div>
                        <div class="thread_status">
                            <? if ($data[0]['sticky_threads'][$i]['status'] == 0): ?>
                                <div class="fa fa-unlock"></div>
                            <? else: ?>
                                <div class="fa fa-lock"></div>
                            <? endif; ?>
                        </div>
                        <div class="thread_viewed">
                            <div class="row">
                                <div class="parameter">Ответы:</div>
                                <div class="value"><?= $data[0]['sticky_threads'][$i]['answers']; ?></div>
                            </div>
                            <div class="row">
                                <div class="parameter">Просмотры:</div>
                                <div class="value"><?= $data[0]['sticky_threads'][$i]['views']; ?></div>
                            </div>
                        </div>
                        <div class="thread_last_message">
                            <div class="date" title="<?= $data[0]['sticky_threads'][$i]['last_datetime']; ?>"><?= $data[0]['sticky_threads'][$i]['last_date']; ?></div>
                            <div class="user"><a href="<?= functions::home() . '/user/' . $data[0]['sticky_threads'][$i]['author'] . '-' . $data[0]['sticky_threads'][$i]['author_id']; ?>"><?= $data[0]['sticky_threads'][$i]['last_author']; ?></a></div>
                        </div>
                    </div>
                <? endfor; ?>

                <? if (sizeof($data[0]['sticky_threads']) > 0): ?>
                    <hr style="margin: 5px 0">
                <? endif; ?>

                <? for ($i = 0; $i < sizeof($data[0]['threads']); $i++): ?>
                    <div class="section_body row">
                        <div class="avatar"><img src="<?= $data[0]['threads'][$i]['avatar']; ?>"></div>
                        <div class="thread_info">
                            <div class="thread_title"><a href="<?= functions::home() . '/thread/' . $data[0]['threads'][$i]['url'] . '-' . $data[0]['threads'][$i]['id']; ?>"><?= $data[0]['threads'][$i]['name']; ?></a></div>
                            <div class="thread_date" title="<?= $data[0]['threads'][$i]['datetime']; ?>"><a href="<?= functions::home() . '/user/' . $data[0]['threads'][$i]['author'] . '-' . $data[0]['threads'][$i]['author_id']; ?>"><?= $data[0]['threads'][$i]['author']; ?></a> <?= $data[0]['threads'][$i]['date']; ?></div>
                        </div>
                        <div class="thread_status">
                            <? if ($data[0]['threads'][$i]['status'] == 0): ?>
                                <div class="fa fa-unlock"></div>
                            <? else: ?>
                                <div class="fa fa-lock"></div>
                            <? endif; ?>
                        </div>
                        <div class="thread_viewed">
                            <div class="row">
                                <div class="parameter">Ответы:</div>
                                <div class="value"><?= $data[0]['threads'][$i]['answers']; ?></div>
                            </div>
                            <div class="row">
                                <div class="parameter">Просмотры:</div>
                                <div class="value"><?= $data[0]['threads'][$i]['views']; ?></div>
                            </div>
                        </div>
                        <div class="thread_last_message">
                            <div class="date" title="<?= $data[0]['threads'][$i]['last_datetime']; ?>"><?= $data[0]['threads'][$i]['last_date']; ?></div>
                            <div class="user"><a href="<?= functions::home() . '/user/' . $data[0]['threads'][$i]['author'] . '-' . $data[0]['threads'][$i]['author_id']; ?>"><?= $data[0]['threads'][$i]['last_author']; ?></a></div>
                        </div>
                    </div>
                <? endfor; ?>

            <? endif; ?>

        <? else: ?>
            Нет доступа.
        <? endif; ?>
    </div>
</div>

<? /* Закрывает <div id="main"> */ ?>
</div>

<div class="breadcrumbs row" style="margin-top: auto;">
    <a class="breadcrumbs_button" href="<?= functions::home(); ?>">Главная</a>
    <a class="breadcrumbs_button" href="<?= functions::home() . '/category/' . $data['category'][0]->name . '-' . $data['category'][0]->id; ?>"><?= $data['category'][0]->name; ?></a>
    <? for ($i = sizeof($data['breadcrumbs']) - 1; $i >= 0; $i--): ?>
        <a class="breadcrumbs_button" href="<?= functions::home() . '/section/' . $data['breadcrumbs'][$i]['name'] . '-' . $data['breadcrumbs'][$i]['id']; ?>"><?= $data['breadcrumbs'][$i]['name']; ?></a>
    <? endfor; ?>
</div>