<?php

class Tickets extends Collection
{

	//$fn = array( 'self', 'foo2' );
	//print call_user_func( $fn, 6, 2 );

	private static function getEntity($data)
	{
	  return new Ticket($data);
	}

	private static function getIndexes()
	{

	}
}
