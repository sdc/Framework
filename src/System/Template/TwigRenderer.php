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
use Jay\System\HtmlHelper;

class TwigRenderer implements Template
{

    public function __construct(
        Twig_Environment $template, 
        Response $response, 
        Flash $flash, 
        HtmlHelper $html)
    {
        $this->template = $template;
        $this->response = $response;
        $this->flash = $flash;
        $this->html = $html;
    }

    public function render($template, $data = [], $layout = 'default')
    {
    	if (isset($data['app_layout_name'])) {
    		exit;
    	}

    	$data['app_layout_name'] = "$layout.html";

        $flashRender = new \Twig_SimpleFunction('flash', [$this->flash, 'render']);
        $this->template->addFunction($flashRender);

        $asset = new \Twig_SimpleFunction('asset', [$this->html, 'asset']);
        $this->template->addFunction($asset);        
        
    	$this->response->setContent(
    		$this->template->render("$template.html", $data)
    	);
    }
}