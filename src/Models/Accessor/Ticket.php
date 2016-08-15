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
        $query = 'INSERT INTO tickets 
                  (id, name, email, date, department, phone, building, room, description, 
                  priority, additional, sent) 
                  VALUES (:id, :name, :email, :date, :department, :phone, :building, :room, 
                  :description, :priority, :additional, :sent)';

        $st = $this->db->prepare($query);
        $st->execute((array) $entity);
        $entity->id = $this->db->lastInsertId();  
    }

    public function select($id)
    {
        $query = 'SELECT * FROM tickets WHERE id = :id';
        $st = $this->db->prepare($query);
        $st->execute(['id' => $id]);

        return $st->fetch();
    }

    public function update($id, $entity)
    {     
        $query = 'UPDATE tickets 
                  SET name = :name, email = :email, date = :date, department = :department, 
                  phone = :phone, building = :building, room = :room, description = :description, 
                  priority = :priority, additional = :additional, sent = :sent 
                  WHERE id = :id';
        $st = $this->db->prepare($query);

        $st->execute((array) $entity);
    }
}