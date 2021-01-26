<div id="register">
    <div id="register_header">Регистрация</div>
    <div id="register_body" class="row">
        <div style="width: 50%;">
            <div class="register_element">
                <div>Игровой ник *</div>
                <div><input type="text" class="text" id="name"></div>
            </div>
            <div class="register_element">
                <div>Почта *</div>
                <div><input type="text" class="text" id="email"></div>
            </div>
            <div class="register_element">
                <div>Пароль *</div>
                <div><input type="password" class="text" id="password1"></div>
            </div>
            <div class="register_element">
                <div>Повтор пароля *</div>
                <div><input type="password" class="text" id="password2"></div>
            </div>
            <div class="register_element">
                <div>Класс в игре</div>
                <div>
                    <select class="text" id="class">
                        <? for ($i = 1; $i <= sizeof($data); $i++): ?>
                        <option id="<?= $i; ?>"><?= $data[$i]; ?></option>
                        <? endfor; ?>
                    </select>
                </div>
            </div>
            <div class="register_element">
                <div>Ссылка на аватар</div>
                <div><input type="text" class="text" id="avatar_link"></div>
            </div>
            <div class="register_element">
                <div id="error"></div>
            </div>
            <div class="button" onclick="register()">Зарегистрироваться</div>
        </div>
        <?/*<div id="rules" style="width: 50%;">Правила:</div>*/?>
    </div>
</div>