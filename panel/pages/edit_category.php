<?
$get_category = db()->select('category')->where('id', $_GET['id'])->apply();
while ($category = db()->get_row($get_category)) {
    $category_array[] = array(
        'id' => $category->id,
        'name' => $category->name,
        'description' => $category->description,
        'access' => explode(',', $category->access),
        'notice' => $category->notice,
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
?>

<form method="POST" id="edit_category" data-title="Изменить категорию" style="width: 500px; margin: 10px;">
    <div>
        <input type="text" id="name" placeholder="Название" value="<?= $category_array[0]['name']; ?>" style="width: 100%;">
        <p>
        <input type="text" id="description" placeholder="Описание" value="<?= $category_array[0]['description']; ?>" style="width: 100%;">
        <p>
        <input type="text" id="notice" placeholder="Объявление внутри категории" value="<?= $category_array[0]['notice']; ?>" style="width: 100%;">

        <div style="width: 50%; margin: 10px 5px 10px 0;" data-title="Доступ к разделу">
            <div>
                <? foreach ($group_array as $key => $value): ?>

                    <? for ($i = 0; $i < sizeof($category_array[0]['access']); $i++): ?>
                        <? $checked = (functions::one_of($category_array[0]['access'], $group_array[$key]['id'])) ? 'checked' : ''; ?>
                    <? endfor; ?>

                    <input type="checkbox" <?= $checked; ?> name="<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                <? endforeach; ?>
            </div>
        </div>

        <input type="button" value="Изменить" onclick="edit_category('<?= $_GET['id']; ?>')">
    </div>
</form>

