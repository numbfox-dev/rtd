<div id="new_thread">
    <? if (functions::is_logined()): ?>
        <? if (functions::one_of($data[0]['create'], $template_data['user']['group_id'])): ?>
            <div id="title" contenteditable="true" data-placeholder="Заголовок темы"></div>
            <div id="body" class="row">
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
                        <div id="text" contenteditable="true"></div>
                    </div>
                    <div id="buttons">
                        <div class="button" onclick="create_new_thread('<?= $data[0]['id']; ?>')">Создать тему</div>
                    </div>
                </div>
            </div>
        <? else: ?>
            У вас недостаточно прав для создания тем в этой секции.
        <? endif; ?>
    <? else: ?>
        Данная опция недоступна. Пожалуйста, войдите или зарегистрируйтесь.
    <? endif; ?>
</div>