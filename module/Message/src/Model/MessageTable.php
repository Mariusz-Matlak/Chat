<?php
/**
 * Created by PhpStorm.
 * User: kamil
 * Date: 06.10.16
 * Time: 18:09
 */

namespace Message\Model;

use RuntimeException;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;

class MessageTable extends AbstractTableGateway
{
    private $tableGateway;

    public function __construct(AbstractTableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function  fetchFirstTen(){
        return $this->tableGateway->select(function(Select $select){
           return $select->limit(10)->order(['id' => 'DESC']);
        });
    }

    public function getMessage($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function deleteAllInsteadOfFirstTen(){
        $sql = "DELETE FROM message WHERE id NOT IN (SELECT id FROM message limit 10 ORDER BY id DESC )";
        $this->tableGateway
            ->getAdapter()
            ->driver
            ->getConnection()
            ->execute($sql);
    }

    public function saveMessage(Message $message)
    {
        $data = [
            'content' => $message->getContent(),
            'createdAt'  => $message->getCreatedAt(),
        ];

        $id = (int) $message->getId();

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getMessage($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
        deleteAllInsteadOfFirstTen();
    }

    public function deleteMessage($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}