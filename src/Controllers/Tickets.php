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
        $this->template->render('homepage');
    }

    public function create()
    {
        $ticket = $this->tickets->createEntity($this->request->request->all());
        $ticket->sent = (int) false;
        $ticket->date = date('Y-m-d H:i:s');

        if ($this->tickets->save($ticket)) {
            echo 'Ticket Created! <hr />';
        }
    }

    public function review($params) 
    {
        $id = $params['id'];
        $ticket = $this->tickets->get($id);

        // Create is editable method? + add expiry time. 
        if ($ticket->sent) {
            $this->redirect('mitie');
            // flash error here
        }

        $this->template->render('review', (array) $ticket);
    }

    // This will be a private function when redirection to methods is resolved so editable check not required.
    public function update($params)
    {
        $id = $params['id'];
        
    }
}
