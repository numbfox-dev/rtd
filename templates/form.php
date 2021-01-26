<form id="create_thread" method="POST">
    <div id="register_header"><?= $data['form']['name']; ?></div>
    <div id="register_body" class="row">
        <div>
            <div class="register_element">
                <div>Имя</div>
                <div><input type="text" class="text" name="name"></div>
            </div>
            
            <div class="register_element">
                <div>Возраст</div>
                <div><input type="text" class="text" name="age"></div>
            </div>
            
            <div class="register_element">
                <div>Город/часовой пояс</div>
                <div><input type="text" class="text" name="location"></div>
            </div>
            
            <div class="register_element">
                <div>Игровой ник. Так же указать старые, если ник менялся</div>
                <div><input type="text" class="text" name="nickname"></div>
            </div>           

            <div class="register_element">
                <div>Класс</div>
                <div>
                    <select class="text" name="class">
                        <? for ($i = 1; $i <= sizeof($data['class']); $i++): ?>
                            <option name="<?= $i; ?>"><?= $data['class'][$i]; ?></option>
                        <? endfor; ?>
                    </select>
                </div>
            </div>
            
            <div class="register_element">
                <div>Уровень персонажа</div>
                <div><input type="text" class="text" name="level"></div>
            </div>
            
            <div class="register_element">
                <div>Кукла(<a href="https://mypers.pw">mypers.pw</a>)</div>
                <div><input type="text" class="text" name="doll"></div>
            </div>
            
            <div class="register_element">
                <div>Приложите <a href="http://piccy.info">скриншот</a> персонажа (должно быть открыто окно характеристик, наведен курсор на джина)</div>
                <div><input type="text" class="text" name="screenshot"></div>
            </div>
            
            <div class="register_element">
                <div>В каких гильдиях состояли и почему покинули?</div>
                <div><input type="text" class="text" name="guilds"></div>
            </div>
            
            <div class="register_element">
                <div>Кратко опишите задачи класса на тв/мбг/замесе</div>
                <div><input type="text" class="text" name="tasks"></div>
            </div>
            
            <div class="register_element">
                <div>Есть ли у вас личный кос игроков?</div>
                <div><input type="text" class="text" name="blacklist"></div>
            </div>
            
            <div class="register_element">
                <div>Почему вы хотите вступить в гильдию RTD?</div>
                <div><input type="text" class="text" name="reasons"></div>
            </div>
            
            <div class="register_element">
                <div>Кто из гильдии RTD мог бы вас отрекомендовать?</div>
                <div><input type="text" class="text" name="recommended"></div>
            </div>

            <div>
                Все, без исключения, пункты заявки должны быть заполнены
                <br>
                Руководство Гильдии оставляет за собой право потребовать дополнительную информацию и задавать вопросы касаемо персонажа, а так же закрыть заявку без обьяснения причин.
            </div>
            
            <div class="register_element">
                <div id="error"></div>
            </div>
            <div class="button" onclick="create_new_thread_form('<?= $data['section']['id']; ?>')">Создать</div>
        </div>
    </div>
</form>