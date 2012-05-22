<?php
/**
 *
 * @author fsw
 *
 */

class Request
{
	private $path = array();
	private $usedPath = array();

	function __construct($mixed = null)
	{
		if (is_null($mixed))
		{
			if (php_sapi_name() == 'cli')
			{
				$this->fromCli();
			}
			else
			{
				$this->fromServer();
			}
		}
		elseif(is_string($mixed))
		{
			$this->fromUrl($mixed);
		}
	}

	public function fromServer()
	{
		$this->path = explode('/', $_SERVER['REQUEST_URI']);
		array_shift($this->path);
		$this->domain = explode('.', $_SERVER["SERVER_NAME"]);
		$this->params = $_GET;
		$this->post = $_POST;
		$this->files = $_FILES;
	}

	public function fromCli()
	{
		global $argv;
		array_shift($argv);
		$this->path = $argv;
	}

	function shiftPath()
	{
		$this->usedPath[] = array_shift($this->path);
		return end($this->usedPath);
	}

	function unshiftPath()
	{
		array_unshift($this->path, array_shift($this->usedPath));
	}

	function glancePath()
	{
		return reset($this->path);
	}

	function getPath()
	{
		return '';
	}

	function getDocument()
	{
		return '';
	}

	function getParams()
	{
		return '';
	}

	function getPostParams()
	{
		return '';
	}
}

