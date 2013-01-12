<?php 
class TemplatesTest extends PHPUnit_Framework_TestCase
{
	public function testRender()
	{
		$data = ['title' => 'Title', 'body' => 'This is a body'];

		foreach (['html', 'phtml', 'phaml'] as $type)
		{
			$template = new Template('_tests/template_' . $type . '.html', $data);
			$template = preg_replace('/\s+/', '', (string)$template);
			$this->assertTrue(strpos($template, '<h1id="test">Title</h1>') !== false);
			$this->assertTrue(strpos($template, '>Thisisabody<') !== false);
			$this->assertTrue(strpos($template, '<ulclass="list"><li>1</li><li>2</li><li>3</li></ul>') !== false);
		}
	}
}