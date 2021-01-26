<?
$get_category = db()->select('category')->apply();
while ($category = db()->get_row($get_category)) {
    $category_array[] = array(
        'id' => $category->id,
        'name' => $category->name,
        'description' => $category->description,
        'access' => explode(',', $category->access),
    );

    $get_section = db()->select('section')->where('category_id', $category->id)->apply();
    while ($section = db()->get_row($get_section)) {
        $section_array[$category->id][] = array(
            id => $section->id,
            name => $section->name,
        );
    }
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

<style>
    .category {
        border: 1px solid black;
        width: 400px;
        padding: 5px;
    }

    .category:hover {
        background: #1ee25b;
        color: white;
    }

    .section {
        margin-left: 15px;
        border: 1px solid black;
        width: 400px;
        padding: 5px;
    }

    .section:hover {
        background: #1e8fe2;
        color: white;
    }

    .name {
        width: 100%;
    }

    .edit {
        cursor: pointer;
        margin-left: 5px;
    }

    #forums {
        width: 35%;
        overflow-y: scroll;
        height: calc(100vh - 35px);
    }
</style>

<div class="row" style="padding: 10px;">
    <div id="forums">
        <? for ($i = 0; $i < sizeof($category_array); $i++): ?>

            <div class="category row" id="<?= $category_array[$i]['id']; ?>">
                <div class="name"><?= $category_array[$i]['name']; ?></div>
                <div class="edit"><img src="../img/edit.png" onclick="location.href = '<?= functions::home(); ?>/panel/edit_category/?id=<?= $category_array[$i]['id']; ?>'"></div>
                <div class="edit"><img src="../img/cross.png" onclick="delete_category('<?= $category_array[$i]['id']; ?>')"></div>
            </div>

            <? for ($j = 0; $j < sizeof($section_array[$category_array[$i]['id']]); $j++): ?>
                <div class="section row" id="<?= $section_array[$category_array[$i]['id']][$j]['id']; ?>">
                    <div class="name"><?= $section_array[$category_array[$i]['id']][$j]['name']; ?></div>
                    <div class="edit"><img src="../img/edit.png" onclick="location.href = '<?= functions::home(); ?>/panel/edit_section/?id=<?= $section_array[$category_array[$i]['id']][$j]['id']; ?>'"></div>
                    <div class="edit"><img src="../img/cross.png" onclick="delete_section('<?= $section_array[$category_array[$i]['id']][$j]['id']; ?>')"></div>
                </div>
            <? endfor; ?>

        <? endfor; ?>
    </div>

    <div id="forums_create">
        <form method="POST" id="create_new_category" data-title="Создать новую категорию" style="width: 500px; margin: 10px;">
            <div>
                <input type="text" id="name" placeholder="Название">
                <input type="text" id="description" placeholder="Описание">
                <input type="text" id="notice" placeholder="Объявление">

                <div style="width: 50%; margin: 10px 5px 10px 0;" data-title="Доступ к разделу">
                    <div>
                        <? foreach ($group_array as $key => $value): ?>
                            <input type="checkbox" name="<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                        <? endforeach; ?>
                    </div>
                </div>

                <input type="button" value="Создать" onclick="create_new_category()">
            </div>
        </form>

        <form method="POST" id="create_new_section" data-title="Создать новую секцию" style="width: 740px; margin: 10px;">
            <div>
                <input type="text" id="name" placeholder="Название">
                <select id="category">				
                    <? for ($i = 0; $i < sizeof($category_array); $i++): ?>
                        <option id="<?= $category_array[$i]['id']; ?>"><?= $category_array[$i]['name']; ?></option>
                    <? endfor; ?>
                </select>
                <select id="section">
                        <option id="0">Без родительской категории</option>
                    <? for($i = 0; $i < sizeof($category_array); $i++): ?>
                        <option disabled><?= $category_array[$i]['name']; ?></option>
                        <? for ($j = 0; $j < sizeof($section_array[$category_array[$i]['id']]); $j++): ?>
                            <option id="<?= $section_array[$category_array[$i]['id']][$j]['id']; ?>"> - <?= $section_array[$category_array[$i]['id']][$j]['name']; ?></option>
                        <? endfor; ?>
                    <? endfor; ?>
                </select>

                <div class="row">
                    <div style="width: 50%; margin: 10px 5px 10px 0;" data-title="Доступ к секции">
                        <div>
                            <? foreach ($group_array as $key => $value): ?>
                                <input type="checkbox" name="a_<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div style="width: 50%; margin: 10px 0 10px 5px;" data-title="Возможность создания тем">
                        <div>
                            <? foreach ($group_array as $key => $value): ?>
                                <input type="checkbox" name="c_<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div style="width: 50%; margin: 10px 0 10px 5px;" data-title="Модерирование">
                        <div>
                            <? foreach ($group_array as $key => $value): ?>
                                <input type="checkbox" name="m_<?= $group_array[$key]['id']; ?>"><?= $group_array[$key]['name']; ?><br>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>

                <input type="button" value="Создать" onclick="create_new_section()">
            </div>
        </form>
    </div>
</div>



























