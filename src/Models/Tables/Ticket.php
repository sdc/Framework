<?php
/**
 * Tickets Table
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Models\Tables;

use Jay\System\Database\DataValidation;
use Jay\Models\Accessor\Ticket as TicketAccessor;
use Jay\Models\Entities\Ticket as TicketEntity;

class Ticket
{
	private $accessor;

	public function __construct(DataValidation $validator, TicketAccessor $accessor)
	{
		$this->validator = $validator;
		$this->accessor = $accessor;
	}

	public function validation(TicketEntity $entity)
	{
		$this->validator->validate($entity->name, [
				'type' => 'string',
				'null' => true
			]);

		$this->validator->validate($entity->email, [
				'type' => 'email',
				'null' => true
			]);

		$this->validator->validate($entity->date, [
				'type' => 'date',
				'null' => false
			]);

		$this->validator->validate($entity->department, [
				'type' => 'string',
				'null' => true
			]);

		$this->validator->validate($entity->phone, [
				'type' => 'string',
				'null' => true
			]);

		$this->validator->validate($entity->building, [
				'type' => 'string',
				'null' => false
			]);

		$this->validator->validate($entity->room, [
				'type' => 'string',
				'null' => false
			]);

		$this->validator->validate($entity->description, [
				'type' => 'string',
				'null' => false
			]);

		$this->validator->validate($entity->priority, [
				'type' => 'string',
				'null' => true
			]);

		$this->validator->validate($entity->additional, [
				'type' => 'string',
				'null' => false
			]);

		$this->validator->validate($entity->priority, [
				'type' => 'boolean',
				'null' => false
			]);

		if (isset($this->validator->errors)) {
			$entity->errors = $this->validator->errors;
			return false;
		}

		return true;
	}

	public function create(TicketEntity $entity)
	{
		if ($this->validation($entity)) {
			$this->accessor->insert($entity);
			return true;
		}

		return false;
	}
}