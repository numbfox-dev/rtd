<?
$get_group = db()->select('group')->apply();
while ($group = db()->get_row($get_group)) {
    $group_array[$group->id] = array(
        'id' => $group->id,
        'name' => $group->name,
        'description' => $group->description,
    );
}

$get_chat_settings = db()->select('chat_settings')->apply();
while ($chat = db()->get_row($get_chat_settings)) {
    $chat_array[] = array(
        'access' => explode(',', $chat->access),
    );
}
?>

<form method="POST" id="edit_chat" data-title="Настройки чата" style="width: 500px; margin: 10px;">
    <div>
        <div>
            <div style="width: 50%; margin: 10px 5px 10px 0;" data-title="Доступ к чату">
                <div>
                    <? foreach ($group_array as $key => $value): ?>

                        <? for ($i = 0; $i < sizeof($chat_array[0]['access']); $i++): ?>
                            <? $checked = (functions::one_of($chat_array[0]['access'], $group_array[$key]['id'])) ? 'checked' : ''; ?>
                        <? endfor; ?>

                        <input type="checkbox" <?= $checked; ?> name="<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                    <? endforeach; ?>
                </div>
                <br>
                <input type="button" value="Изменить" onclick="edit_chat()">
            </div>            
        </div>
        <input type="button" value="Очистить чат" onclick="erase_chat()">
    </div>
</form>