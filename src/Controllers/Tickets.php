<?php
/**
 * Tickets Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

use Jay\Tables\Tickets as Table;

class Tickets extends Application
{
	public function __construct(Table $tickets)
	{
		call_user_func_array(array('parent', '__construct'), func_get_args());
	}

    public function index()
    {
        $html = $this->template->render('dev-homepage');
    }

    public function add()
    {
    	$data = $this->request->request->all();
    	var_dump($data);
    }
}
