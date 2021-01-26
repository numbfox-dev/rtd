<?
class model_new_thread {
	
	public function get_data($action)	{
		$action_array = explode('-', $action);
		$id = intval(end($action_array));
		
		$section = db()->select('section')->where('id', $id)->get();
		
		$array[] = array(
			'id' => $id,
			'create' => explode(',', $section[0]->create)
		);
		
		return $array;
	}
}