<?php

class Actions extends actions_Frontend
{
	public function before($method, $args)
	{
		parent::before($method, $args);
		$this->layout->method = $method;
	}
	public function actionIndex()
	{
		$this->layout->setHtmlTitle('Eve Framework');
		return Markdown::fromFile('doc/ABOUT.md');
	}
	
	public function actionIntro()
	{
		$this->layout->setHtmlTitle('Eve Framework - Quick Introduction');
		return Markdown::fromFile('doc/INTRO.md');
	}	
	
	public function actionDocs()
	{
		if ($this->request->extension()==='html')
		{
			$this->layout->addCss('/static/apigen/resources/style.css');
			$this->layout->addJs('/static/apigen/resources/combined.js');
			$this->layout->addJs('/static/apigen/elementlist.js');
				
			$file = implode('/' , $this->request->getPath()) . '.' . $this->request->extension();
			$file = Fs::read('doc/html/' . $file);
			$this->layout->setHtmlTitle(substr($file,
					strpos($file, '<title>') + 7,
					strpos($file, '</title>') - strpos($file, '<title>') - 7
			));
			$file = substr($file,
					strpos($file, '<body>') + 6,
					strpos($file, '</body>') - strpos($file, '<body>') - 6
					);
			return $file;
		}
		return null;
	}	
}
