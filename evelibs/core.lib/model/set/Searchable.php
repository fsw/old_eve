<?php
/**
 * Database table searchable via solr.
 * 
 * @package Core
 * @author fsw
 * 
 */

trait model_set_Searchable
{
	protected static $searchable = true;
	
	private static $solrClient = null;
	/*
	
	protected static function getSolrClient()
	{
		if (static::solrClient === null)
		{
			if (class_exists('SolrClient'))
			{
				$config = Config::get('model', 'solr');
				static::$solrClient = new SolrClient([
					'hostname' => $config['host'],
					'port' => $config['port'],
				]);
			}
			else
			{
				static::$solrClient = false;
			}
		}
		return static::$solrClient;
	}
	
	public static function save(array $row)
	{
		$id = parent::save($row);
		$row = static::getById($id);
		$client = static::getSolrClient();
		$doc = new SolrInputDocument();
		$doc->addField('id', $id);
		$doc->addField('body', $row);
		$updateResponse = $client->addDocument($doc);
		var_dump($updateResponse->getResponse());
		
		return $id;
	}
	
	public static function query($query, $limit = null, $page = 1)
	{
		$client = static::getSolrClient();
		$query = new SolrQuery();
		$query->setQuery($query);
		if ($limit != null)
		{
			$query->setStart(($page - 1) * $limit);
			$query->setRows($limit);
		}
		//$query->addField('cat')->addField('features')->addField('id')->addField('timestamp');
		
		$query_response = $client->query($query);
		$response = $query_response->getResponse();
		var_dump($response);
	}
		
	*/
}
