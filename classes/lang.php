<?
	class lang {
		
		public static function get($element) {
			$array = array(
				'access denied' => 'Нет доступа.',
			);
			
			return $array[$element];
		}
	}