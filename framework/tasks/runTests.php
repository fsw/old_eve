<?php 


function test($desc, $bool)
{
	echo $desc . str_repeat('.', 40 - strlen($desc)) . ($bool ? '..OK' : 'FAIL') . NL;
	//'[0;37;40m'
	//'[1;31;40m'
}


$tasks = array();
foreach (array_reverse(Cado::getRoots()) as $root)
{
	if (Fs::isDir($root . '/tests/'))
	{
		foreach(Fs::listFiles($root . '/tests/') as $file)
		{
			if (strpos($file, '.php') !== null)
			{
				$tasks[$file] = true;
			}
		}		
	}
}

foreach (array_keys($tasks) as $task)
{
	$path = Cado::findResource('tests/' . $task);
	echo '============================================' . NL;
	echo 'RUNNING TEST SUITE: ' . $task . NL;
	require $path;
}

echo '============================================' . NL;
