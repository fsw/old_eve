<?php

class cms_Actions extends BaseActions
{
	static function actionIndex()
	{
		self::setLayout('cms');
		return new \Widget('test');
	}
	
	static function actionCheckDatabase()
	{
		$ret = '';
		$model = \Project::getModel();
		$tables = array();
		foreach ($model as $className)
		{
			$tables = array_merge($tables, $className::getStructure());
		}
		$current = array();
		foreach (\Db::fetchCol('SHOW TABLES') as $t)
		{
			$current[$t] = array();
			$create = \Db::fetchRow('SHOW CREATE TABLE ' . $t);
			$create = $create['Create Table'];
			$create = substr($create, strpos($create, '(') + 1, strrpos($create, ')') - strpos($create, '(') - 1);
			$create = explode(',' . "\n", $create);
			foreach ($create as $row)
			{
				if (strpos($row, 'KEY') !== false)
				{
					if (strpos($row, 'PRIMARY') !== false)
					{
						$current[$t]['index_primary'] = trim($row);
					}
					else
					{
						$start = strpos($row, '`') + 1;
						$current[$t][substr($row, $start, strpos($row, '`', $start) - $start)] = trim($row);
					}
				}
				else
				{
					$current[$t][substr($row, strpos($row, '`') + 1, strrpos($row, '`') - strpos($row, '`') - 1)] =
					trim(substr($row, strrpos($row, '`') + 1));
				}
			}
		}
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
					$sqls[] = 'ALTER TABLE ' . $name . ' ' . PHP_EOL . implode(',' . PHP_EOL, $changes);
				}
				else
				{
					$ret .= $name . ' OK' . PHP_EOL;
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
				$sqls[] = 'CREATE TABLE ' . $name . ' (' . PHP_EOL . implode(',' . PHP_EOL, $rows) . PHP_EOL . ') ENGINE=InnoDB DEFAULT CHARSET=utf8';
			}
		}
		foreach ($current as $name => $fields)
		{
			$sqls[] = 'DROP TABLE ' . $name;
		}
		foreach ($sqls as $sql)
		{
			$ret .= $sql . ';' . PHP_EOL . PHP_EOL;
			\Db::query($sql);
		}
		return $ret;
	}
	
}
