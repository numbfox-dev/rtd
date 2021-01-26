<?
function include_classes(): void {
    $folder = scandir($_SERVER['DOCUMENT_ROOT'] . '/panel/classes');

    for ($i = 2; $i < sizeof($folder); $i++) {
		if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/panel/classes/' . $folder[$i])) {
			include_once($_SERVER['DOCUMENT_ROOT'] . '/panel/classes/' . $folder[$i]);
		}
    }
}

include_classes();
functions::to_https();
functions::new_session();