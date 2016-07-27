<?php
/**
 * Tickets table class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Tables;
use Jay\System\Adapter;
use Jay\System\Database\DataValidation;

class Tickets
{
	private $db;
	private $validator;

	public $entity = [
		'name' 			=> ['type' => 'string', 'null' => true],
		'email' 		=> ['type' => 'email', 'null' => true],
		'date' 			=> ['type' => 'date', 'null' => false],
		'department'	=> ['type' => 'string', 'null' => true],
		'phone'			=> ['type' => 'string', 'null' => true],
		'building'		=> ['type' => 'string', 'null' => false],
		'room'			=> ['type' => 'string', 'null' => false],
		'description'	=> ['type' => 'string', 'null' => false],
		'priority'		=> ['type' => 'string', 'null' => true],
		'additional'	=> ['type' => 'string', 'null' => true],
		'sent'			=> ['type' => 'boolean', 'null' => false]
	];

	private $unknown = [];

	public function __construct(Adapter $adapter, DataValidation $validator) 
	{
		$this->db = $adapter->PDO;
		$this->validator = $validator;
	}

	public function create($data)
	{
		$this->validation($data);
	}

	public function validation($data)
	{
		foreach ($this->entity as $name => $value) {
			if (!isset($data[$name])) {
				$value = null;
			}

			$this->entity[$name] = $this->validator->sanitize(
				$value, $this->entity[$name]['type']
			);
		}
	}
}
