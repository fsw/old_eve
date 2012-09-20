<?php

class Actions extends BaseActions
{
	protected static function preAction($action)
	{
		/*
		if (!users_Users::isLoggedIn())
		{
			self::redirectTo(Routing::linkTo('users', 'login'));
		}*/
	}
		
	public static function actionIndex($view = 'list', Array $search = array())
	{
		$where = '1';
		$bind = array();
		if(!empty($search['status']) && ($search['status'] != 'ALL'))
		{
			if ($search['status'] == 'OPEN')
			{
				$where .= ' AND status IN ("new", "assigned", "inprogress", "blocked")';		
			}
			elseif ($search['status'] == 'RESOLVED')
			{
				$where .= ' AND status IN ("fixed", "pushed", "closed")';
				
			}
			elseif (in_array($search['status'], array('new', 'assigned', 'inprogress', 'blocked', 'fixed', 'pushed', 'closed')))
			{
				$where .= ' AND status="' . $search['status'] . '"';
			}
		}
		
		if (!empty($search['project']))
		{
			//TODO children
			$where .= ' AND project_id="' . (int)$search['project'] . '"';
		}
		if (!empty($search['query']))
		{
			$where .= ' AND (title LIKE ? OR description LIKE ?)';
			$bind[] = '%' . $search['query'] . '%';
			$bind[] = '%' . $search['query'] . '%';
		}
		
		
		if ($view == 'list')
		{
			$where .= ' ORDER BY status ASC';
			return new Widget('tickets', array('tickets' => Tickets::search($where, $bind)));
		}
		elseif ($view == 'calendar')
		{
			$where .= ' AND timestamp IS NOT NULL ORDER BY timestamp ASC';
			$tickets = Tickets::search($where, $bind);
				
			$month = date('n');
			$year = date('Y');
			//TODO routing
			$yearTimestamp = mktime(0, 0, 0, 1, 1, $year);
			$yearOffset = date('w', $yearTimestamp);
			
			$monthsDays = array();
			$startDay = 0;
			for ($i = 1; $i <13; $i++)
			{
				$monthsDays[] = $md = cal_days_in_month(CAL_GREGORIAN, $i, $year);
				if ($i < $month)
				{
					$startDay += $md;
				}
			}
			
			$dayOfYear = date('z');
			$dayOfMonth = date('j');
			
			$inMonth = date('t');
			
			$monthStart = $dayOfYear - $dayOfMonth;
			$monthEnd = $monthStart + $inMonth;
			
			$startDay = floor(($monthStart - 7) / 7) * 7 - $yearOffset;
			$endDay = $startDay + (7*7);
			
			$week = array();
			$weeks = array();
			
			$secsPerDay = 60*60*24;
			
			$timestamp = $yearTimestamp + ($startDay*$secsPerDay);
			
			for ($i = $startDay; $i <= $endDay; $i++)
			{
				
				$timestamp += $secsPerDay;				
				$day = array(
						'doy' => $i,
						'label' => date('Y m d', $timestamp),
						'tickets' => array()
				);
				
				while(!empty($tickets) && (current($tickets)['timestamp'] < $timestamp))
				{
					$day['tickets'][] = array_shift($tickets);
				}
					
				if ($dayOfYear == $i)
				{
					$day['class'] = 'today';
				}
				if ($i < $monthStart)
				{
					$day['class'] = 'gray';
				}
				elseif ($i > $monthEnd)
				{
					$day['label'] = $i - $monthEnd;
					$day['class'] = 'gray';
				}
				$week[] = $day; 
				if (count($week) == 7)
				{
					$weeks[] = $week;
					$week = array();
				}
			}
			
			return new Widget('calendar', array('weeks' => $weeks));
		}
			
	}
	
	public static function actionProjects()
	{
		return new Widget('projects', array('projects' => Projects::getAllAsTree()));
	}
	
	public static function actionTicket($ticketId)
	{
		return new Widget('ticket', array('ticket' => Tickets::getById($ticketId)));
	}
	
	public static function actionTicketStatus($referer, $ticketId, $status)
	{
		Tickets::update($ticketId, array('status' => $status));
		self::redirectTo($referer);
	}
	
	public static function actionEditTicket($ticketId = 0)
	{
		return new form_Ticket($ticketId ? Tickets::getById($ticketId) : array());
	}
	
	public static function actionEditProject($projectId = 0)
	{
		return new form_Project($projectId ? Projects::getById($projectId) : array());
	}
	
}
