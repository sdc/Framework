<?php
/**
 * Tickets table class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Tables;
use Jay\System\Adapter;

class Tickets
{
	protected $db;

	public function __construct(Adapter $adapter) 
	{
		$this->db = $adapter->PDO;
	}
}
