<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;

class KittyTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;

        $this->hydrator     = new ReflectionHydrator();
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function save(Kitty $kitty) {
        $data = $this->hydrator->extract($kitty);

        $id = (int) $kitty->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, array('id' => $id));
        }
    }

    public function delete($id) {
        $this->tableGateway->delete(array('id' => $id));
    }
}
