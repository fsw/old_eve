<?php 

class frontend_Catalogue extends Frontend
{
	public function before($method, $args)
	{
		parent::before($method, $args);
		
		$this->layout->addJs('/static/jquery.cssmap.js');
		$this->layout->addCss('/static/cssmap-poland/cssmap-poland.css');
		
	}
	public function actionIndex()
	{
		$this->layout->setHtmlTitle('Baza Lekarzy');
		$this->searchForm = $this->getSearchForm();
	}
	
	public function actionSearch($category = 'lekarz', $region = 'polska', $page = 1, $getSearch = array())
	{
		//in case js failed:
		if (($category == '__CATEGORY__') && ($region == '__REGION__'))
		{
			$category = $this->request->getParam('category');
			$region = $this->request->getParam('region');
			$this->redirectTo(Site::lt('catalogue/search', $category, $region, $page, $getSearch));
		}
		$this->category = model_Categories::getByField('slug', $category);
		$this->region = model_Regions::getByField('slug', $region);
		
		$this->searchForm = $this->getSearchForm($category, $region, $getSearch);
		
		$this->assert(!empty($this->category) && !empty($this->region));
		
		$this->title = $this->category['plural'] . ' ' . $this->region['locative'];
		$this->layout->setHtmlTitle($this->title);
		
		$this->page = $page;
		//var_dump($this->category['plural'], $this->region['locative']);
		
		$this->categories = model_Categories::getForSelect('plural', 'slug');
		$this->regions = model_Regions::getForSelect('nominative', 'slug');
				
		$this->tabs = new Tabber([
				'Lista' => Site::lt('catalogue/search', $category, $region, 1),
				'Mapa' => Site::lt('catalogue/search', $category, $region, 'map')
			],
			($this->page === 'map' ? 2 : 1),
			'/static/tabs.png'
		);
		
		
		$this->promoted = model_Items::search(null, [], 3);
		foreach ($this->promoted as &$promoted)
		{
			$promoted = new Template('frontend/catalogue/itemRow.html', ['item' => $promoted]);
		}
		
		if ($page == 'map')
		{
			$this->items = model_Items::search(null, []);
		}
		else
		{
			$this->items = model_Items::search(null, [], 12, $page, $foundRows);
			foreach ($this->items as &$item)
			{
				$item = new Template('frontend/catalogue/itemRow.html', ['item' => $item]);
			}
			$this->pager = new Pager(12, $page, $foundRows, Site::lt('catalogue/search', $category, $region, '_PAGE_', $getSearch));
		}
		
		
		//var_dump($categories->getAll());
		/*
		$items = model_Items::getInstance();
		$ret = $items->save([
				'title' => 'Tester 7',
				'category' => 2,
				'locations' => [
					model_Locations::add(['title' => 'Gabinet 1', 'lon' => 53.625935290597985, 'lat' => 18.622512817382812]),
					model_Locations::add(['title' => 'Gabinet 2', 'lon' => 52.229675600000000000, 'lat' => 21.012228700000037000]),
				]
			]);
		var_dump($ret);
		*/
		
		//$items->addLocation(2, 53.625935290597985, 18.622512817382812, 'test');
		
		//var_dump($items->getAll());
		
		//$items->
		
		//var_dump(google_Maps::getAddress(52.229675600000000000, 21.012228700000037000));
		
		
		
	}
	
	public function actionItem($slug)
	{
		$this->searchForm = $this->getSearchForm();
		$this->item = model_Items::getByField('slug', $slug);
		$this->assert(!empty($this->item));
		$this->ratings = model_Ratings::search('item = ' . $this->item['id']);
		$this->categories = model_Categories::getByIds($this->item['categories']);
		$this->layout->setHtmlTitle($this->item['title']);
	}
	
	public function actionSave($id = 0)
	{
		$fields = model_Items::getFields();
		$form = new Form();
		$form->title = 'Dodaj nowy wpis';
		$form->addElements($fields);
		return $form;
	}
	
	private function getSearchForm($category = 'lekarz', $region = 'polska', $getSearch = array())
	{
		$data = [
			'category' => model_Categories::getByField('slug', $category),
			'region' => model_Regions::getByField('slug', $region),
			'categories' => model_Categories::getForSelect('plural', 'slug'),
			'regions' => model_Regions::getForSelect('nominative', 'slug'),
		];
		return new Template('frontend/catalogue/searchForm.html', $data);
	}
} 