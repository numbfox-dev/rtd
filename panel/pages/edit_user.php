<?
$get_group = db()->select('group')->apply();
while ($group = db()->get_row($get_group)) {
    $group_array[] = array(
        'id' => $group->id,
        'name' => $group->name,
        'description' => $group->description,
    );
}

$id = intval(filter_input(INPUT_GET, 'id'));
$user = db()->select('users')->where('id', $id)->get();
?>

<div style="padding: 10px;">
    <div data-title="<?= $user[0]->name; ?>">
        <div>
            <div>Почта</div>
            <div><input type="text" id="email" value="<?= $user[0]->email; ?>"></div>            
        </div>
        <div>
            <div>Группа</div>
            <div>
                <select class="user_group" user="<?= $user[0]->id; ?>" id="group">
                    <? for ($j = 1; $j < sizeof($group_array); $j++): ?>
                        <? $selected = ($group_array[$j]['id'] == $user[0]->group_id) ? 'selected' : ''; ?>
                        <option id="<?= $group_array[$j]['id']; ?>" <?= $selected; ?>><?= $group_array[$j]['name']; ?></option>
                    <? endfor; ?>
                </select>
            </div>            
        </div>
        <div>
            <div>Новый пароль</div>
            <div><input type="text" id="new_password"></div>            
        </div>
        <div>
            <div>Аватар</div>
            <div><input type="text" id="avatar" value="<?= $user[0]->avatar; ?>"></div>            
        </div>
        <div>
            <input type="button" onclick="edit_user('<?= $user[0]->id; ?>')" value="Изменить">
        </div>
    </div>
</div>