<?php
/**
 * Tickets Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

use Jay\Models\Tables\Ticket as Table;
use Jay\Models\Entities\Ticket;
use Symfony\Component\HttpFoundation\Request;
use Jay\System\Template;
use Jay\System\Flash;

class Tickets extends Application
{
    private $tickets;

    public function __construct(Request $request, Template $template, Flash $flash, Table $table)
    {
        parent::__construct($request, $template, $flash);
        $this->tickets = $table;
    }

    public function index()
    {
        $this->template->render('homepage');
    }

    public function create()
    {
        $ticket = new Ticket;
        $this->tickets->mergeEntity($ticket, $this->request->request->all());

        if ($this->tickets->create($ticket)) {
            $this->flash->info('Please review your ticket and submit');
            $this->redirect('ticket/review', $ticket->id);
        }

        $this->outputErrors($ticket);
        $this->redirect();
    }

    public function review($params) 
    {
        $ticket = $this->tickets->get($params['id']);

        $this->isEditable($ticket);
        $this->template->render('review', (array) $ticket);
    }


    public function update($params)
    {
        $ticket = $this->tickets->get($params['id']);
        
        $this->isEditable($ticket);
        $this->tickets->mergeEntity($ticket, $this->request->request->all());

        if ($this->tickets->update($params['id'], $ticket)) {
            $this->flash->success('Your ticket has been updated');
            $this->redirect('ticket/review', $params['id']);
        }

        $this->outputErrors($ticket);
        $this->redirect('ticket/review', $params['id']);
    }

    public function complete($params)
    {
        $ticket = $this->tickets->get($params['id']);
        $this->isEditable($ticket);

        if ($this->sendEmail($ticket)) {
            $ticket->sent = (int) true;
            $this->tickets->update($params['id'], $ticket);

            $this->flash->success('Your ticket has been sent to Mitie');
            $this->redirect();
        }

        $this->flash->error('There was an error submitting the ticket, please contact IT');
        $this->redirect('ticket/review', $params['id']);
    }

    private function sendEmail($ticket)
    {
        $email = "#MAXIMO_EMAIL_BEGIN\nLSNRACTION=CREATE\n;\nLSNRAPPLIESTO=SR\n;\n";
        $email .= "TICKETID=&AUTOKEY&\n;\nCLASS=SR\n;\nDESCRIPTION=$ticket->description\n;\n";
        $email .= "SITEID=SSW\n;\nLOCATION=$ticket->building\n;\nREPORTEDPRIORITY=PRIORITYHERE\n;\n";
        $email .= "MTFMSRCLIENTREF=389456\n;\nDESCRIPTION_LONGDESCRIPTION=$ticket->additional\n;\n";
        $email .= '#MAXIMO_EMAIL_END';

        $to = 'jamesbyrne@southdevon.ac.uk';
        $subject = 'Mitie Support Desk';
        $from = 'From: jamesbyrne@southdevon.ac.uk';

        if (mail($to, $subject, $email, $from)) {
            return true;
        }

        return false;
    }

    private function isEditable($ticket)
    {
        $expiry = date('Y-m-d', strtotime($ticket->date. ' + 1 days'));
        if ($ticket->sent || strtotime(date('Y-m-d')) > strtotime($expiry)) {
            $this->flash->error('Sorry, this URL is no longer valid');
            $this->redirect();
        }
    }

    private function outputErrors($ticket)
    {
        foreach ($ticket->errors as $field => $error) {
            $this->flash->error($field.' '.$error);
        }        
    }
}
