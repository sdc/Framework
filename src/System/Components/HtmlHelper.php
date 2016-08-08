<?php
/**
 * HTML Helper class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Components;

use Jay\System\HtmlHelper as HtmlHelpderInterface;

class HtmlHelper implements HtmlHelpderInterface
{
    public function asset($path)
    {
        if (!BASE) {
            echo "/assets/$path";
            return;
        }

        echo '/'.BASE."/assets/$path";
        return;
    }
}