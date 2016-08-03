<?php
/**
 * Routing
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

return [
    ['GET', '/', ['Jay\Controllers\Tickets', 'index']],
    ['POST', '/', ['Jay\Controllers\Tickets', 'create']],
    ['GET', '/ticket/edit/{id:\d+}', ['Jay\Controllers\Tickets', 'edit']]
];