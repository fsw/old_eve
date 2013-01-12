<?php 

class WebTest extends PHPUnit_Framework_TestCase
{
	public function testRobots()
	{
		$url = Site::lt('robots', 'txt');
		
		/* self::endTest(
				(strpos($robots['body'], 'Sitemap:') !== false) &&
				(strpos($robots['body'], 'Disallow: /api/') !== false) &&
				($robots['headers']['Content-type'] == 'text/plain')
		);*/
	}
	
	public function testSitemap()
	{
		$url = Site::lt('sitemap', 'xml');
		
	}
}