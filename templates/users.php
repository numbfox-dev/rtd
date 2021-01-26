<div id="users_header">Профиль пользователя <?= $data['user']['name']; ?></div>
<div id="users">	
    <div id="users_body" class="row">
        <div id="avatar"><img src="<?= $data['user']['avatar']; ?>"></div>
        <? if ($_SESSION['id'] == $data['user']['id']): ?>
            <div>
                <? /*
                  <div class="register_element">
                  <div>Почта</div>
                  <div><input type="text" class="text" id="email"></div>
                  </div>
                 */ ?>
                <div class="register_element">
                    <div>Класс в игре</div>
                    <div>
                        <select class="text" id="class">
                            <? for ($i = 1; $i <= sizeof($data['class']); $i++): ?>
                                <? $selected = ($i == $data['user']['class_id']) ? 'selected' : ''; ?>
                                <option id="<?= $i; ?>" <?= $selected; ?>><?= $data['class'][$i]['name']; ?></option>
                            <? endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="register_element">
                    <div>Ссылка на аватар</div>
                    <div><input type="text" class="text" id="avatar_link"></div>
                </div>
                <div class="button" onclick="change_user_info('<?= $data['user']['id']; ?>')">Изменить</div>

                <?/*
                <div class="tabs">
                    <input id="tab1" type="radio" name="tabs" checked>
                    <label for="tab1" title="Вкладка 1">Вкладка 1</label>

                    <input id="tab2" type="radio" name="tabs">
                    <label for="tab2" title="Вкладка 2">Вкладка 2</label>

                    <input id="tab3" type="radio" name="tabs">
                    <label for="tab3" title="Вкладка 3">Вкладка 3</label>

                    <input id="tab4" type="radio" name="tabs">
                    <label for="tab4" title="Вкладка 4">Вкладка 4</label>

                    <section id="content-tab1">
                        <p>
                            Здесь размещаете любое содержание....
                        </p>
                    </section>  
                    <section id="content-tab2">
                        <p>
                            Здесь размещаете любое содержание....
                        </p>
                    </section> 
                    <section id="content-tab3">
                        <p>
                            Здесь размещаете любое содержание....
                        </p>
                    </section> 
                    <section id="content-tab4">
                        <p>
                            Здесь размещаете любое содержание....
                        </p>
                    </section>    
                </div>
                */?>
            </div>
        <? endif; ?>
    </div>	
</div>