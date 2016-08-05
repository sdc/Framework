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
        $ticket = $this->tickets->createEntity($this->request->request->all());
        $ticket->sent = (int) false;
        $ticket->date = date('Y-m-d H:i:s');

        if ($this->tickets->save($ticket)) {
            $this->flash->info('Please review your ticket and submit');
            $this->redirect('mitie/ticket/review', $ticket->id);
        }

        foreach ($this->ticket->errors as $error) {
            $this->flash->error($error);
        }
    }

    public function review($params) 
    {
        $ticket = $this->tickets->get($params['id']);
        $ticket->id = $params['id'];

        $this->isEditable($ticket);

        $this->template->render('review', (array) $ticket);
    }


    public function update($params)
    {
        $ticket = $this->tickets->get($params['id']);
        $this->isEditable($ticket);

        $this->tickets->updateEntity($ticket, $this->request->request->all());

        if ($this->tickets->update($params['id'], $ticket)) {
            $this->flash->success('Your ticket has been updated');
            $this->redirect('mitie/ticket/review', $params['id']);
        }

        var_dump($ticket->errors);
        echo 'failure'; exit; 
    }

    public function complete($params)
    {
        $ticket = $this->tickets->get($params['id']);
        $this->isEditable($ticket);

        if ($this->sendEmail($ticket)) {
            $ticket->sent = (int) true;
            $this->tickets->update($params['id'], $ticket);

            $this->flash->success('Your ticket has been sent to Mitie');
            $this->redirect('mitie');
        }

        $this->flash->error('There was an error submitting the ticket, please contact IT');
        $this->redirect('mitie/ticket/review', $params['id']);
    }

    private function isEditable($ticket)
    {
        $expiry = date('Y-m-d', strtotime($ticket->date. ' + 1 days'));
        if ($ticket->sent || strtotime(date('Y-m-d')) > strtotime($expiry)) {
            $this->redirect('mitie');
            $this->flash->error('Sorry, this URL is no longer valid');
            exit;
        }
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
}
