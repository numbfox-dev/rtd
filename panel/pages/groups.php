<?
$get_group = db()->select('group')->apply();
while ($group = db()->get_row($get_group)) {	
	$group_array[] = array(
		'id' => $group->id,
		'name' => $group->name,
		'description' => $group->description,
	);
}

?>

<style>
.group_div {
	border: 1px solid;
	padding: 5px;
}
</style>

<? for($i = 0; $i < sizeof($group_array); $i++): ?>
<div class="row">
	<div class="group_div"><?= $group_array[$i]['name']; ?></div>
	<div class="group_div"><?= $group_array[$i]['description']; ?></div>
	<div class="group_div"><input type="button" onclick="delete_group('<?= $group_array[$i]['id']; ?>')" value="Удалить"></div>
</div>
<? endfor; ?>
<input type="text" id="new_group_name" placeholder="Название группы">
<input type="text" id="new_group_description" placeholder="Описание группы">
<input type="button" onclick="new_group()">