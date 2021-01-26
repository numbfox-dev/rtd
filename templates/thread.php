<div class="breadcrumbs row">
    <a class="breadcrumbs_button" href="<?= functions::home(); ?>">Главная</a>
    <a class="breadcrumbs_button" href="<?= functions::home() . '/category/' . $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['name'] . '-' . $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['id']; ?>"><?= $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['name']; ?></a>
	<? for($i = sizeof($data['breadcrumbs']) - 2; $i >= 0; $i--): ?>
	<a class="breadcrumbs_button" href="<?= functions::home() . '/section/' . $data['breadcrumbs'][$i]['name'] . '-' . $data['breadcrumbs'][$i]['id']; ?>"><?= $data['breadcrumbs'][$i]['name']; ?></a>
	<? endfor; ?>
</div>

<div id="thread">
    <div id="thread_header">
        <? if (functions::one_of($data[0]['access'], $template_data['user']['group_id']) and $data[0]['hidden'] != 1): ?>
            <div id="title" class="row">
                <div><?= $data[0]['name']; ?></div>
                <? if ($data[0]['author_id'] == $template_data['user']['id'] or functions::one_of($data['moder'], $template_data['user']['group_id'])): ?>
                <div class="fa fa-edit" title="Изменить название темы" style="margin: 9px 0 0 10px; font-size: 22px; cursor: pointer;" onclick="edit_thread_title('<?= $data[0]['id']; ?>')"></div>
                <? endif; ?>
            </div>
            <div id="info" class="row">
                <div id="author" class="fa fa-user"><a href=""><?= $data[0]['author']; ?></a></div>
                <div id="date"  title="<?= $data[0]['datetime']; ?>" class="fa fa-clock-o"><?= $data[0]['date']; ?></div>
            </div>
        </div>
    
        <? if (functions::one_of($data['moder'], $template_data['user']['group_id'])): ?>
        <a href="#moder" id="thread_moder">...</a>
        <div class="modal-wrapper">
            <aside class="modal" id="moder">
                <header>
                    <h2>Настройки темы</h2>
                </header>
                <section>
                    <div id="modal_thread_title" style="margin-bottom: 10px;">
                        <div>Заголовок</div>
                        <div><input type="text" value="<?= $data['0']['name']; ?>"></div>
                    </div>
                    
                    <div id="modal_thread_status" style="margin-bottom: 10px;">
                        <? if($data[0]['status'] == 0): ?>
                        <div><input type="checkbox" value="Закрыть">Закрыть</div>
                        <? else: ?>
                        <div><input type="checkbox" value="Открыть">Открыть</div>
                        <? endif; ?>
                    </div>
                    
                    <div id="modal_thread_sticky" style="margin-bottom: 10px;">
                        <? if($data[0]['sticky'] == 0): ?>
                        <div><input type="checkbox" value="Закрепить">Закрепить</div>
                        <? else: ?>
                        <div><input type="checkbox" value="Открепить">Открепить</div>
                        <? endif; ?>
                    </div>
                    
                    <div id="modal_thread_tranfer" style="margin-bottom: 10px;">
                        <select>
                        <? for($i = 0; $i < sizeof($data['category']); $i++): ?>
                            <option disabled><?= $data['category'][$i]['name']; ?></option>
                            <? for ($j = 0; $j < sizeof($data['section'][$data['category'][$i]['id']]); $j++): ?>
                                <? $select = ($data['section'][$data['category'][$i]['id']][$j]['id'] == $data[0]['section_id']) ? 'selected' : ''; ?>
                                <option <?= $select; ?> id="<?= $data['section'][$data['category'][$i]['id']][$j]['id']; ?>"> - <?= $data['section'][$data['category'][$i]['id']][$j]['name']; ?></option>
                            <? endfor; ?>
                        <? endfor; ?>
                        </select>        
                    </div>
                    <div class="button" onclick="thread_hide('<?= $data[0]['id']; ?>')">Скрыть тему</div>
                    <br>
                    <div class="button" onclick="thread_moderate('<?= $data[0]['id']; ?>')">Сохранить</div>
                </section>
                <footer class="footer">
                    <a href="#" class="button">Закрыть</a>
                </footer>
            </aside>
        </div>
        <? endif; ?>
        
        <? for ($i = 0; $i < sizeof($data[0]['posts']); $i++): ?>
            <div class="thread_content row <?
            if ($data[0]['posts'][$i]['hidden'] == 1) {
                echo 'hidden';
            }
            ?>" post="<?= $data[0]['posts'][$i]['id']; ?>">
                <div class="user">
                    <div class="avatar"><img src="<?= $data[0]['posts'][$i]['avatar']; ?>"></div>
                    <div class="name"><a href="<?= functions::home() . '/users/' . $data[0]['posts'][$i]['author'] . '-' . $data[0]['posts'][$i]['author_id']; ?>" target="_blank"><?= $data[0]['posts'][$i]['author']; ?></a></div>
                    <div class="rank"><?= $data[0]['posts'][$i]['rank']; ?></div>
                    <div class="banner" style="background: <?= $data[0]['posts'][$i]['background']; ?>; border: 1px solid <?= $data[0]['posts'][$i]['border']; ?>; color: <?= $data[0]['posts'][$i]['font']; ?>"<?/*type="<?= $data[0]['posts'][$i]['group_id']; ?>"*/?>><?= $data[0]['posts'][$i]['group']; ?></div>
                    <div class="class"><img src="<?= functions::home() . $data[0]['posts'][$i]['sign']; ?>" title="<?= $data[0]['posts'][$i]['class']; ?>"></div>
                </div>
                <div class="answer column">
                    <div class="info row">
                        <div class="date" title="<?= $data[0]['posts'][$i]['datetime']; ?>"><?= $data[0]['posts'][$i]['date']; ?> 
                        <? if ($data[0]['posts'][$i]['hidden'] == 1) { echo 'Скрыт'; }?>
                        <? if ($data[0]['posts'][$i]['last_date'] != '30.11.-0001'): ?>
                        <div title="<?= $data[0]['posts'][$i]['last_datetime']; ?>">Последнее редактирование: <?= $data[0]['posts'][$i]['last_author']; ?> в <?= $data[0]['posts'][$i]['last_date']; ?></div>
                        <? endif; ?>
                        </div>
                        <div class="order">#<?= $i + 1; ?></div>
                    </div>
                    <div class="text"><?= functions::render_post($data[0]['posts'][$i]['content']); ?></div>
                        <? if (functions::is_logined()): ?>                    
                        <div class="buttons">                                             
                            <? if (($data[0]['status'] == 0 and functions::one_of($data[0]['access'], $template_data['user']['group_id'])) or $template_data['user']['group_id'] < 3): ?>
                                <div class="button" onclick="quote('<?= $data[0]['posts'][$i]['id']; ?>')">Ответить</div>
                            <? endif; ?>
                            
                                
                            <? if ($data[0]['posts'][$i]['author_id'] == $template_data['user']['id'] or functions::one_of($data['moder'], $template_data['user']['group_id'])): ?>                                
                                <div class="button" onclick="edit('<?= $data[0]['posts'][$i]['id']; ?>')">Редактировать</div>                                
                            <? endif; ?>
                                
                            <? if (functions::one_of($data['moder'], $template_data['user']['group_id']) and $i > 0): ?>
                                <? if($data[0]['posts'][$i]['hidden'] == 0): ?>
                                <div class="button" onclick="hide('<?= $data[0]['posts'][$i]['id']; ?>')">Скрыть</div>
                                <? else: ?>
                                <div class="button" onclick="show('<?= $data[0]['posts'][$i]['id']; ?>')">Показать</div>
                                <? endif; ?>
                            <? endif; ?>


                        <? /*
                          <div class="button"><i class="fa fa-thumbs-o-down"></i></div>
                          <div class="button"><i class="fa fa-thumbs-o-up"></i></div>
                         */ ?>
                        </div>
                        <? endif; ?>
                    
                    <? if (sizeof($data[0]['posts'][$i]['signature'] > 0)): ?>                    
                        <?/*<hr style="margin: 10px 0 5px 0;">*/?>
                    <? endif; ?>
                    
                    <?//= functions::render_post($data[0]['posts'][$i]['signature']); ?>
                </div>
            </div>
        <? endfor; ?>

        <? /*
          <div id="pagination">
          <a class="button" href="">1</a>
          <a class="button" href="">2</a>
          <a class="button" href="">3</a>
          <a class="button" href="">4</a>
          </div>
         */ ?>

        <div id="write_answer" class="row">
    <? if ($data[0]['status'] == 0 or functions::one_of($data['moder'], $template_data['user']['group_id'])): ?>
        <? if (functions::one_of($data[0]['access'], $template_data['user']['group_id']) and functions::is_logined()): ?>
                    <div id="user">
                        <div id="avatar"><img src="<?= $template_data['user']['avatar']; ?>"></div>
                    </div>
                    <div id="answer">
                        <div id="border">
                            <div id="tools">							
                                <? /* <div class="button fa fa-eraser"></div> */ ?>
                                <div class="button fa fa-bold bb-code" bb-code="b" title="Жирный текст"></div>
                                <div class="button fa fa-italic bb-code" bb-code="i" title="Курсивный текст"></div>
                                <div class="button fa fa-underline bb-code" bb-code="u" title="Подчеркнутый текст"></div>
                                <div class="button fa fa-strikethrough bb-code" bb-code="s" title="Зачеркнутый текст"></div>
            <? /* <div class="button fa fa-text-height"></div> */ ?>
                                <div class="button fa fa-link bb-code" bb-code="url" title="Ссылка"></div>
                                <div class="button fa fa-align-left bb-code" bb-code="left" title="Текст слева"></div>
                                <div class="button fa fa-align-center bb-code" bb-code="center" title="Текст в центре"></div>								
                                <div class="button fa fa-align-right bb-code" bb-code="right" title="Текст справа"></div>
                                <div class="button fa fa-flag bb-code" bb-code="spoiler" title="Спойлер"></div>
                                <div class="button fa fa-list-ul bb-code" bb-code="ul" title="Маркированный список"></div>
				<div class="button fa fa-list-ol bb-code" bb-code="ol" title="Нумерованный список"></div>
                            </div>
                            <hr>
                            <div id="text" contenteditable="true" role="textbox" aria-multiline="true"></div><?/**/?>
                        </div>
                        <div id="buttons">
                            <div class="button" onclick="answer('<?= $data[0]['id']; ?>')">Отправить</div>
                        </div>
                    </div>
                <? else: ?>
                    Вы не можете отвечать в этой теме.
                <? endif; ?>
            <? else: ?>
                Тема закрыта для обсуждений.
    <? endif; ?>
<? else: ?>
            Нет доступа.
<? endif; ?>
    </div>
</div>


<div class="breadcrumbs row" style="margin-top: auto;">
    <a class="breadcrumbs_button" href="<?= functions::home(); ?>">Главная</a>
    <a class="breadcrumbs_button" href="<?= functions::home() . '/category/' . $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['name'] . '-' . $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['id']; ?>"><?= $data['breadcrumbs'][sizeof($data['breadcrumbs']) - 1]['name']; ?></a>
	<? for($i = sizeof($data['breadcrumbs']) - 2; $i >= 0; $i--): ?>
	<a class="breadcrumbs_button" href="<?= functions::home() . '/section/' . $data['breadcrumbs'][$i]['name'] . '-' . $data['breadcrumbs'][$i]['id']; ?>"><?= $data['breadcrumbs'][$i]['name']; ?></a>
	<? endfor; ?>
</div>