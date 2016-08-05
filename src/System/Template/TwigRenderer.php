<?php
/**
 * TwigRenderer
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Template;

use Jay\System\Template;
use Twig_Environment;
use Symfony\Component\HttpFoundation\Response;
use Jay\System\Flash;

class TwigRenderer implements Template
{
    private $template;
    private $flash;

    public function __construct(Twig_Environment $template, Response $response, Flash $flash)
    {
        $this->template = $template;
        $this->response = $response;
        $this->flash = $flash;
    }

    public function render($template, $data = [], $layout = 'default')
    {
    	if (isset($data['app_layout_name'])) {
    		exit;
    	}

    	$data['app_layout_name'] = "$layout.html";

        $flashRender = new \Twig_SimpleFunction('flash', [$this->flash, 'render']);
        $this->template->addFunction($flashRender);
        
    	$this->response->setContent(
    		$this->template->render("$template.html", $data)
    	);
    }
}