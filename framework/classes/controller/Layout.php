<?php 

abstract class controller_Layout extends Controller
{
	protected $layoutName = 'frontend';
	/**
	 * @var Layout
	 */
	protected $layout;
	
	public function before($method, $args)
	{
		$this->layout = new Layout('layouts/' . $this->layoutName);
		$this->layout->addCss(controller_Static::hrefActions(Controller::getActionsCode($this), 'css'));
		$this->layout->addJs('/static/jquery.js');
		if (CADO_DEV)
		{
			$this->layout->addJs('/static/jixedbar/src/jquery.jixedbar.js');
			$this->layout->addJs('/static/jquery/cookie.js');
			$this->layout->addJs('/static/dev.js');
			$this->layout->addCss('/static/jixedbar/themes/default/jx.stylesheet.css');
			$this->layout->attachDevbar = true;
		}
		//$this->layout->addJs('/static/modernizr.js');
		$this->layout->addJs(controller_Static::hrefActions(Controller::getActionsCode($this), 'js'));
	}

	public function after($response)
	{
		if (is_null($response))
		{
			$response = new Widget('widgets/404');// . self::getActionsCode($this) . substr($this->method, 6), $this);
		}
		$this->layout->widget = $response;
		return $this->layout;
	}
}