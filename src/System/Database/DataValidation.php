<?php
/**
 * DataValidation Class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Database;

class DataValidation
{
	private $filters = [
		'email' 	=> FILTER_SANITIZE_EMAIL,
		'integer' 	=> FILTER_SANITIZE_NUMBER_INT,
		'string'	=> FILTER_SANITIZE_STRING,
		'url'		=> FILTER_SANITIZE_URL
	];

	private $dataType = INPUT_POST;

	public function sanitize($data, $type, $null = true)
	{
		$filter = isset($this->filters[$type]) ? $this->filters[$type] : FILTER_DEFAULT;
		$data = filter_var($data, $filter);

		if (empty($data) && $null) {
			return null;
		}

		if (empty($data)) {
			throw new \Exception("cannot be null");
		}

		return $data;
	}
}
