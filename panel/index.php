<? include_once($_SERVER['DOCUMENT_ROOT'] . '/panel/loader.php'); ?>


<? //$protocol = ($_SERVER['REQUEST_SCHEME'] == 'http') ? redirect('https://lazorell.ru/panel/') : ''; ?>
<? //functions::to_https(); ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="<?= functions::home(); ?>/js/jquery-3.3.1.min.js"></script>
<script src="<?= functions::dynamic_file_name('/panel/js/functions.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= functions::dynamic_file_name('/panel/css/style.css'); ?>">

<?
$user = db()->select('users')->where('id', $_SESSION['id'])->get();
//if ($user[0]->group_id == 1 or $user[0]->group_id == 2):
$access_array = array(
    0 => '1',
    1 => '2',
    2 => '8',
);

if (functions::one_of($access_array, $user[0]->group_id)):
    ?>

    <div id="main" class="row">
        <div id="menu" class="column">
            <a class="menu_item" href="<?= functions::home(); ?>">На сайт</a>
            <a class="menu_item" href="<?= functions::home(); ?>/panel/">Главная</a>
            <a class="menu_item" href="<?= functions::home(); ?>/panel/forums/">Разделы форума</a>
            <a class="menu_item" href="<?= functions::home(); ?>/panel/groups/">Группы</a>
            <a class="menu_item" href="<?= functions::home(); ?>/panel/users/">Пользователи</a>
            <a class="menu_item" href="<?= functions::home(); ?>/panel/chat/">Настройки чата</a>
            <a class="menu_item" href="<?= functions::home(); ?>/panel/menu/">Главное меню</a>
            <a class="menu_item" id="exit" onclick="logout()">Выйти из админки</a>
        </div>
        <div id="content"><? router::route(); ?></div>
    </div>

<? else: include_once('login.php'); ?>
<? endif; ?>