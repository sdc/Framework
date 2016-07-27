<?php
/**
 * DataValidation Class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Database;

class DataValidation
{
	protected $filters = [
		'email' 	=> FILTER_SANITIZE_EMAIL,
		'integer' 	=> FILTER_SANITIZE_NUMBER_INT,
		'string'	=> FILTER_SANITIZE_STRING,
		'url'		=> FILTER_SANITIZE_URL
	];

	protected $dataType = INPUT_POST;

	public function sanitize($data, $type, $null = true)
	{
		$filter = isset($this->filters[$type]) ? $this->filters[$type] : null;
		$data = filter_var($data, $filter);

		if (is_null($data) && $null) {
			return null;
		}

		if (is_null($data)) {
			echo 'EMPTY SON'; exit;
		}

		return $data;
	}
}
