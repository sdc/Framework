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
            $this->redirect('mitie/ticket/review', $ticket->id);
        }

        // Handle errors and flash here.
    }

    public function review($params) 
    {
        $ticket = $this->tickets->get($params['id']);
        $ticket->id = $params['id'];

        $this->isEditable($ticket);

        $this->template->render('review', (array) $ticket);
    }

    // This will be a private function when redirection to methods is resolved so editable check not required.
    public function update($params)
    {
        $ticket = $this->tickets->get($params['id']);
        $this->tickets->updateEntity($ticket, $this->request->request->all());

        if ($this->tickets->update($params['id'], $ticket)) {
            // flash success here.
            $this->redirect('mitie/ticket/review', $params['id']);
        }

        var_dump($ticket->errors);
        echo 'failure'; exit; 
    }

    public function complete($params)
    {
        $ticket = $this->tickets->get($params['id']);
        $ticket->sent = (int) true;

        $this->tickets->update($params['id'], $ticket);
        
        echo 'All done';
    }

    private function isEditable($ticket)
    {
        // add expiry
        if ($ticket->sent) {
            $this->redirect('mitie');
            // flash error here
        }
    }
}
