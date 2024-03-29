<?php
/** 
 * Simple Pager
 * 
 * @package Core
 * @author fsw
 */

class Pager extends Template
{
	
	public function __construct($limit, $page, $total , $href='?page=_PAGE_', $template = 'pager/default.html')
	{
		parent::__construct($template);
		$this->page = $page; 
  		$this->href = $href;
  		$this->total = $total;
  		$this->last = $total ? ceil($total / $limit) : 0;
	}
	
	public function hrefNextPage()
	{
		if ($this->page < $this->last)
		{
			return str_replace('_PAGE_', $this->page + 1, $this->href);
		}
		else
		{
			return null;
		}
	}
	
	public function hrefPrevPage()
	{
		if ($this->page > 1)
		{
			return str_replace('_PAGE_', $this->page - 1, $this->href);
		}
		else
		{
			return null;
		}
	}
	
	public function hrefFirstPage()
	{
		return str_replace('_PAGE_', 1, $this->href);
	}
	
	public function hrefLastPage()
	{
		return str_replace('_PAGE_', $this->last, $this->href);
	}
	
}
