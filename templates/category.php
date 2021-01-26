<div class="breadcrumbs row">
    <a class="breadcrumbs_button" href="<?= functions::home(); ?>">Главная</a>
</div>
<div id="category">
    <div id="category_header">
        <? if (functions::one_of($data[0]['access'], $template_data['user']['group_id'])): ?>
            <div id="title"><?= $data[0]['name'] ?></div>
            <div class="category_description"><?= $data[0]['description'] ?></div>
        </div>

        <? if ($data[0]['notice'] != ''): ?>
            <div id="category_notice">
                <?= functions::render_post($data[0]['notice']); ?>
            </div>
        <? endif; ?>

        <? if (functions::is_logined()): ?>
            <div style="height: 30px; margin: 20px 0 10px 0px;">
                <div class="button" style="float: right;">Отметить прочитанным</div>
            </div>	
        <? endif; ?>

        <div class="category">
            <? for ($i = 0; $i < sizeof($data); $i++): ?>
                <? for ($j = 0; $j < sizeof($data[$i]['sections']); $j++): ?>
                    <? if (functions::one_of($data[$i]['sections'][$j]['access'], $template_data['user']['group_id'])): ?>
                        <div class="category_body row">
                            <div class="category_body_img"><i class="fa fa-comments-o" aria-hidden="true" onclick=""></i></div>
                            <div class="category_body_name">
                                <div><a href="<?= functions::home() . '/section/' . $data[$i]['sections'][$j]['name'] . '-' . $data[$i]['sections'][$j]['id']; ?>"><?= $data[$i]['sections'][$j]['name']; ?></a></div>
                                <? for($k = 0; $k < sizeof($data[$i]['sections'][$j]['child_sections']); $k++): ?>
                                <div style="font-size: 12px;"><a href="<?= functions::home() . '/section/' . $data[$i]['sections'][$j]['child_sections'][$k]->name . '-' . $data[$i]['sections'][$j]['child_sections'][$k]->id ; ?>"><div style="padding-right: 3px;" class="fa fa-comments-o"></div><?= $data[$i]['sections'][$j]['child_sections'][$k]->name; ?></a></div>
                                <? endfor; ?>
                            </div>
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

                        <? if ((sizeof($data[$i]['sections']) > 1) and ( sizeof($data[$i]['sections']) != $j + 1)): ?>
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

<?/*Закрывает <div id="main">*/?>
</div>

<div class="breadcrumbs row" style="margin-top: auto;">
    <a class="breadcrumbs_button" href="<?= functions::home(); ?>">Главная</a>
</div>