<?php
/**
 * Tickets Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

use Jay\Tables\Tickets as Table;
use Symfony\Component\HttpFoundation\Request;
use Jay\System\Template;

class Tickets extends Application
{
    private $tickets;

    public function __construct(Request $request, Template $template, Table $table)
    {
        parent::__construct($request, $template);
        $this->tickets = $table;
    }

    public function index()
    {
        $html = $this->template->render('dev-homepage');
    }

    public function create()
    {
        $ticket = $this->tickets->createEntity($this->request->request->all());
        if ($this->tickets->save($ticket)) {
            echo 'Ticket Created! <hr />';
        }
    }
}
