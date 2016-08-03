<?php
/**
 * Application Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Jay\System\Template;

class Application
{
    protected $request;
    protected $template;

    public function __construct(
    	Request $request,
    	Template $template
    ) {
        $this->request = $request;
        $this->template = $template;
    }

    public function index()
    {
        $html = $this->template->render('homepage');
    }

    public function redirect($route = false, $var = false)
    {
        $base = $this->request->getScheme().'://'.$this->request->getHttpHost();
        $base .= (!$route) ? '/' : '/'.urlencode($route).'/';
        $base .= (!$var) ? '' : urlencode($var);

        header("Location: $base");    
        exit;
    }
}
