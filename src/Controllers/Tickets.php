<?php
/**
 * Application Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

class Tickets extends Application
{
    public function index()
    {
        $html = $this->template->render('homepage');
    }
}
