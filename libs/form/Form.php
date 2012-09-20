<?php
/**
 * 
 * @author fsw
 *
 */
class Form
{
	public $fields = array();
	public $defaults = array();
	public $title = '';
	public $description = '';
	public $handler = '';
	public $submitText = 'Submit';
	
	private $id = 0;
	
	static $counter;
	
	public function __construct()
	{
		$this->id = ++static::$counter;
	}
	
	public function __toString()
	{
		try
		{
			$this->name = 'form' . $this->id;
			$this->data = $this->defaults;
			if (!empty($_POST[$this->name]))
			{
				$errors = array();
				foreach ($this->fields as $key => $field)
				{
					$error = $field->validate(isset($data[$key]) ? $data[$key] : null);
					if ($error !== true)
					{
						$errors[$key] = $error;
					}
				}
				$this->data = array_merge($this->data, $_POST[$this->name]);
				if (empty($this->errors))
				{
					$bar = $this->handler;
					$bar($this->data);
				}
			}
			else
			{
				$this->errors = array();
			}
			ob_start();
			require('form.html.php');
			return ob_get_clean();
		}
		catch (Exception $e)
		{
			trigger_error($e->getMessage());
		}
		trigger_error('Widget "' . $this->path . '" not found.');
	}
	
}