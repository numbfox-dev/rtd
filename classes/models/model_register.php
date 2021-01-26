<?
class model_register {
	public function get_data($action) {
		$get_class = db()->select('class')->apply();
		while ($class = db()->get_row($get_class)) {
			$array[$class->id] = $class->name;
		}
		
		return $array;
	}
}