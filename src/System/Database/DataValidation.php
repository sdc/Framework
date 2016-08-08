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
		'string'	=> FILTER_SANITIZE_STRING,
		'url'		=> FILTER_SANITIZE_URL
	];

	private $dataType = INPUT_POST;

	public function sanitize($data, $type, $null = true)
	{
		$data = isset($this->filters[$type]) ? filter_var($data, $this->filters[$type]) : $data;

		if (empty($data) && $null && $data !== 0) {
			return null;
		}

		if (empty($data) && $data !== 0) {
			throw new \Exception("is required");
		}

		return $data;
	}
}
