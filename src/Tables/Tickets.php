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

	public $columns = [
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

	public function __construct(Adapter $adapter, DataValidation $validator) 
	{
		$this->db = $adapter->PDO;
		$this->validator = $validator;
	}

	public function save($entity)
	{
		if ($this->validation($entity)) {
			$colNames = array_keys($this->columns);
			$cols = implode(', ', $colNames);
			$vals = implode(', ', preg_filter('/^/', ':', $colNames));

			$query = "INSERT INTO tickets ($cols) VALUES ($vals)";
			$st = $this->db->prepare($query);
			$st->execute((array) $entity);
			return true;
		}

		return false;
	}

	public function validation($entity)
	{
		$clean = true;
		foreach ($entity as $name => $value) {
			if (is_array($value)) { continue; }
			try {
				$entity->$name = $this->validator->sanitize(
					$value, $this->columns[$name]['type'], $this->columns[$name]['null']
				);
			} catch (\Exception $e) {
				$entity->errors[$name] = $e->getMessage();
				$clean = false;
			}
		}

		return $clean ? true : false;
	}

	public function createEntity($data)
	{
		$entity = new \StdClass;
		foreach (array_keys($this->columns) as $name) {
			$entity->$name = isset($data[$name]) ? $data[$name] : null;
		}

		return $entity;
	}
}
