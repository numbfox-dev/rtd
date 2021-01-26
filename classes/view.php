<?
class view {
	private static $template_view = 'template'; //Общий вид
	private static $content_view; //Контент
	
	public function create($content_view, $template_data, $data) {
		include_once($_SERVER['DOCUMENT_ROOT'] . '/templates/' . self::$template_view  . '.php');
	}
}