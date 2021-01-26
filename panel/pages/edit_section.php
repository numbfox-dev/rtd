<?
$get_section = db()->select('section')->where('id', $_GET['id'])->apply();
while ($section = db()->get_row($get_section)) {
    $section_array[] = array(
        'id' => $section->id,
        'category_id' => $section->category_id,
        'name' => $section->name,
        'access' => explode(',', $section->access),
        'create' => explode(',', $section->create),
        'moder' => explode(',', $section->moder),
    );
}

$get_group = db()->select('group')->apply();
while ($group = db()->get_row($get_group)) {
    $group_array[$group->id] = array(
        'id' => $group->id,
        'name' => $group->name,
        'description' => $group->description,
    );
}

$get_category = db()->select('category')->apply();
while ($category = db()->get_row($get_category)) {
    $category_array[] = array(
        'id' => $category->id,
        'name' => $category->name,
    );
}
?>

<form method="POST" id="edit_section" data-title="Изменить секцию" style="width: 740px; margin: 10px;">
    <div>
        <input type="text" id="name" placeholder="Название" value="<?= $section_array[0]['name']; ?>">
        <select id="category">
            <? for ($i = 0; $i < sizeof($category_array); $i++): ?>

                <? $selected = ($section_array[0]['category_id'] == $category_array[$i]['id']) ? 'selected' : ''; ?>

                <option id="<?= $category_array[$i]['id']; ?>" <?= $selected; ?>><?= $category_array[$i]['name']; ?></option>
            <? endfor; ?>
        </select>

        <? /*
          <? foreach ($group_array as $key => $value): ?>

          <? for ($i = 0; $i < sizeof($section_array[0]['access']); $i++): ?>
          <? $checked = (functions::one_of($section_array[0]['access'], $group_array[$key]['id'])) ? 'checked' : ''; ?>
          <? endfor; ?>

          <input type="checkbox" <?= $checked; ?> name="<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
          <? endforeach; ?>
         */ ?>

        <div class="row">
            <div style="width: 50%; margin: 10px 5px 10px 0;" data-title="Доступ к секции">
                <div>
                    <? foreach ($group_array as $key => $value): ?>

                        <? for ($i = 0; $i < sizeof($section_array[0]['access']); $i++): ?>
                            <? $checked = (functions::one_of($section_array[0]['access'], $group_array[$key]['id'])) ? 'checked' : ''; ?>
                        <? endfor; ?>

                        <input type="checkbox" <?= $checked; ?> name="a_<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                    <? endforeach; ?>
                </div>
            </div>
            <div style="width: 50%; margin: 10px 0 10px 5px;" data-title="Возможность создания тем">
                <div>
                    <? foreach ($group_array as $key => $value): ?>

                        <? for ($i = 0; $i < sizeof($section_array[0]['create']); $i++): ?>
                            <? $checked = (functions::one_of($section_array[0]['create'], $group_array[$key]['id'])) ? 'checked' : ''; ?>
                        <? endfor; ?>

                        <input type="checkbox" <?= $checked; ?> name="c_<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                    <? endforeach; ?>
                </div>
            </div>
            <div style="width: 50%; margin: 10px 0 10px 5px;" data-title="Модерирование">
                <div>
                    <? foreach ($group_array as $key => $value): ?>

                        <? for ($i = 0; $i < sizeof($section_array[0]['create']); $i++): ?>
                            <? $checked = (functions::one_of($section_array[0]['moder'], $group_array[$key]['id'])) ? 'checked' : ''; ?>
                        <? endfor; ?>

                        <input type="checkbox" <?= $checked; ?> name="m_<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                    <? endforeach; ?>
                </div>
            </div>
        </div>

        <input type="button" value="Изменить" onclick="edit_section('<?= $_GET['id']; ?>')">
    </div>
</form>

