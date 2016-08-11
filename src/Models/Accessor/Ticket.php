<?php
/**
 * Ticket table accessor
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Models\Accessor;
use Jay\System\Adapter;

class Ticket
{
    private $db;

    public function __construct(Adapter $adapter) 
    {
        $this->db = $adapter->PDO;
    }

    public function insert($entity)
    {
        $query = 'INSERT INTO tickets';
        $query.= '(name, email, date, department, phone, building, room, description, ';
        $query.= 'priority, additional, sent)';
        $query.= 'VALUES (:name, :email, :date, :department, :phone, :building, :room, ';
        $query.= ':description, :priority, :additional, :sent)';

        $st = $this->db->prepare($query);
        $st->execute((array) $entity);   
    }
}