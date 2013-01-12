<?php

Eve::requireVendor('autoload.php');

class html_Logger implements PHPUnit_Framework_TestListener
{
	private $indentLevel = 0;
	private $lastResult = '';
	private $lastMessage = '';
	private $results = [];
	
	public function getResults()
	{
		return $this->results;
	}
	
	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->lastMessage = $e->getMessage();
		$this->lastResult = 'ERROR';
	}

	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		$this->lastMessage = $e->getMessage();
		$this->lastResult = 'FAILED';
	}

	public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->lastMessage = $e->getMessage();
		$this->lastResult = 'INCOMPLETE';
	}

	public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->lastMessage = $e->getMessage();
		$this->lastResult = 'SKIPPED';
	}

	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		/*
		echo str_repeat(' ', $this->indentLevel) . '=== ' . $suite->getName() . ' ==='. NL;
		$this->indentLevel++;
		*/
	}

	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		/*
		echo NL;
		$this->indentLevel--;
		*/
	}

	public function startTest(PHPUnit_Framework_Test $test)
	{
		
		$name = PHPUnit_Util_Test::describe($test);
		$name = str_replace('Test::test', '::', $name); 
		echo str_repeat(' ', $this->indentLevel) . $name .
		str_repeat('.', 80 - strlen($name));
		
		$this->lastResult = 'OK';
		$this->lastMessage = '';
	}

	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		echo str_repeat('.', 10 - strlen($this->lastResult)) . $this->lastResult . NL;
		if (empty($this->results[$this->lastResult]))
		{
			$this->results[$this->lastResult] = 0;
		}
		$this->results[$this->lastResult] ++;

		if (!empty($this->lastMessage))
		{
			echo $this->lastMessage . NL;
		}
	}
}


class frontend_Testsuite extends controller_Layout
{
	public static $allowRobots = false;
	
	public function before($method, $args)
	{
		$this->assert(Eve::isDev());
		$this->isRoot = (Request::isLocalHost());
		parent::before($method, $args);
	}
	
	public function actionIndex()
	{
		$dirs = Eve::findAll('_tests');	
		$logger = new html_Logger();
		$this->suites = [];
		foreach (array_reverse($dirs) as $dir)
		{
			$this->suites[$dir]['name'] = Text::slug(basename(dirname($dir)));
			$facade = new File_Iterator_Facade;
			$files  = $facade->getFilesAsArray($dir, ['Test.php']);
			$suite = new PHPUnit_Framework_TestSuite($dir);
			$suite->addTestFiles($files);
			$result = new PHPUnit_Framework_TestResult();
			//$result->addListener($logger);
			$suite->run($result);
			
			$this->suites[$dir]['result'] = [
				'passed' => $result->passed(),
				'notImplemented' => $result->notImplemented(),
				'deprecatedFeatures' => $result->deprecatedFeatures(),
				'failures' => $result->failures(),
				'errors' => $result->errors(),
				'skipped' => $result->skipped(),
			];
		}
		/*
		$results = $logger->getResults();
		echo NL . NL;
		$total = array_sum($results);
		foreach($results as $key => $count)
		{
			$percent = empty($total) ? 0 : (100 * $count / $total);
			echo $key . ' = ' . $count . '/' . $total . ' (' . $percent . '%)' . NL;
		}*/
	}
	
}