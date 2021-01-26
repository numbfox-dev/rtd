<?
class controller {
	
	private $model;
	private $model_template;
	private $view;
	
	public function __construct() {
		$this->view = new view();
	}
	
	public function run($template, $model, $action = null)	{
		$this->model_template = new model_template();
		$template_data = $this->model_template->get_data($template, $action);
		
		if ($model != null) {
			$this->model = new $model();
			$data = $this->model->get_data($action);
		}

		$this->view->create($template, $template_data, $data);
	}
}