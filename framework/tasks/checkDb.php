<?php 


$models = $this->site->getModels();
$tables = array();
foreach ($models as $model)
{
	$tables = array_merge($tables, $this->site->model($model)->getStructure());
}
$current = $this->site->readDbStructure();
//var_dump($current);
//die();
$sqls = array();
foreach ($tables as $name => $fields)
{
	if (array_key_exists($name, $current))
	{
		$changes = array();
		foreach ($fields as $key => $field)
		{
			if (array_key_exists($key, $current[$name]))
			{
				if ($current[$name][$key] != $field)
				{
					if (strpos($key, 'index_') === 0)
					{
						$changes[] = 'DROP KEY `' . $key . '`';
						$changes[] = 'ADD ' .  $field . ' /*' . $current[$name][$key] . '*/';
					}
					else
					{
						$changes[] = 'MODIFY COLUMN `' . $key . '` ' .  $field . ' /*' . $current[$name][$key] . '*/';
					}
				}
				unset($current[$name][$key]);
			}
			else
			{
				$changes[] = 'ADD ' . (strpos($key, 'index_') === 0 ? '' : 'COLUMN `' . $key . '` ') .  $field;
			}
		}
		foreach ($current[$name] as $key => $field)
		{
			$changes[] = 'DROP ' . (strpos($key, 'index_') === 0 ? 'KEY' : 'COLUMN') . ' `' . $key . '`';
		}
		if (!empty($changes))
		{
			$sqls[$name] = 'ALTER TABLE ' . $name . ' ' . PHP_EOL . implode(',' . PHP_EOL, $changes);
		}
		else
		{
			$sqls[$name] = '-- OK';
		}
		unset($current[$name]);
	}
	else
	{
		$rows = array();
		foreach ($fields as $key => $field)
		{
			if (strpos($key, 'index_') === 0)
			{
				$rows[] = $field;
			}
			else
			{
				$rows[] = '`' . $key . '` ' .  $field;
			}
		}
		$sqls[$name] = 'CREATE TABLE ' . $name . ' (' . PHP_EOL . implode(',' . PHP_EOL, $rows) . PHP_EOL . ') ENGINE=InnoDB DEFAULT CHARSET=utf8';
	}
}
foreach ($current as $name => $fields)
{
	$sqls[$name] = 'DROP TABLE ' . $name;
}

/*
if (!empty($run) && !empty($sqls[$run]))
{
	$this->site->getDb()->query($sqls[$run]);
	$sqls[$run] = '-- DONE';
}
*/
foreach ($sqls as $key => $sql)
{
	echo '-- ' . $key . NL;
	echo $sql . ';' . NL;
}
