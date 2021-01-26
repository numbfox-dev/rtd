<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">

        <meta name="description" content="">
        <meta name="keywords" content="<? //router::get_tags();    ?>">

        <link rel="canonical" href="<? //router::get_canonical();    ?>">        
        <link rel="stylesheet" type="text/css" href="<?= functions::dynamic_file_name('/css/style.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?= functions::dynamic_file_name('/css/adaptive.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?= functions::dynamic_file_name('/css/elements.css'); ?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        
        <script src="<?= functions::dynamic_file_name('/js/jquery-3.3.1.min.js'); ?>"></script>
        <script src="<?= functions::dynamic_file_name('/js/functions.js'); ?>"></script>

        <meta name="yandex-verification" content="783f72fd50396d82" />
        <title><?= $template_data['title']; ?></title>
    </head>
    <body>
        <header class="row">
            <div id="logo"><a href="<?= functions::home(); ?>"><img src="<?= functions::dynamic_file_name('/img/logo.png'); ?>"></a></div>
            <div id="info">
                <? if (functions::is_logined()): ?>
                    <div id="username"><?= $_SESSION['name']; ?></div>
                    <div id="user_information" class="row">
                        <div id="avatar"><img src="<?= $template_data['user']['avatar']; ?>"></div>
                        <div id="statistic">
                            <div id="threads" class="row">
                                <div class="parameter">Создано тем: </div>
                                <div class="value"><?= $template_data['user']['threads']; ?></div>
                            </div>
                            <div id="messages" class="row">
                                <div class="parameter">Сообщения: </div>
                                <div class="value"><?= $template_data['user']['answers']; ?></div>
                            </div>
                            <div id="reputation" class="row">
                                <div class="parameter">Репутация: </div>
                                <div class="value">0</div>
                            </div>
                            <div id="points" class="row">
                                <div class="parameter">Баллы: </div>
                                <div class="value">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <a class="button" style="margin: 0 auto 0 0;" href="<?= functions::home() . '/users/' . $template_data['user']['name'] . '-' . $template_data['user']['id']; ?>">Профиль</a>
                        <? /* <div class="button" style="margin: 0 auto;">Сообщения</div> */ ?>
                        <div class="button" style="margin: 0 0 0 auto;" onclick="logout()">Выйти</div>
                    </div>
                <? else: ?>
                    <input id="username" class="enter" name="login" type="text" placeholder="Логин или Email">
                    <input id="password" class="enter" name="password" type="password" placeholder="Пароль">
                    <div class="row" style="justify-content: space-between;">
                        <div class="button" onclick="login()">Войти</div>					
                        <a href="<?= functions::home() . '/register'; ?>"><div class="button" style="float: right;">Регистрация</div></a>
                    </div>
                <? endif; ?>
            </div>
        </header>

        <nav class="row">
            <? for ($i = 0; $i < sizeof($template_data['menu']); $i++): ?>          
            <a class="menu_button row" href="<?= $template_data['menu'][$i]['url']; ?>">
                <div class="fa fa-file-text-o" style="margin: 3px 7px 0 0;"></div>
                <div><?= $template_data['menu'][$i]['name']; ?></div>
            </a>          
            <? endfor; ?>
        </nav>
        
        <nav class="mobile">
            <input id="toggle" type="checkbox" style="display: none;">
            <label for="toggle"><div id="burger"></div></label>            
            <div id="mobile_menu">
                <div id="mobile_menu_header">Меню</div>
                <hr>
                <? if (!functions::is_logined()): ?>
                    <input id="username11" class="enter" name="login" type="text" placeholder="Логин или Email">
                    <input id="password11" class="enter" name="password" type="password" placeholder="Пароль">
                    <div class="row" style="justify-content: space-between;">
                        <div class="button" onclick="login1()">Войти</div>					
                        <a href="<?= functions::home() . '/register'; ?>"><div class="button" style="float: right;">Регистрация</div></a>
                    </div>
                <? endif; ?>
                    
                <? for ($i = 0; $i < sizeof($template_data['menu']); $i++): ?>          
                <a href="<?= $template_data['menu'][$i]['url']; ?>">                   
                    <div class="mobile_menu_button"><?= $template_data['menu'][$i]['name']; ?></div>
                </a>          
                <? endfor; ?>
            </div>
        </nav>
        

        <div id="main">
            <? include($_SERVER['DOCUMENT_ROOT'] . '/templates/' . $content_view . '.php'); ?>
        </div>

        <footer></footer>
    </body>
</html>