<?php

namespace BOF\Repository\Common;

use BOF\Application;
use Doctrine\DBAL\Connection;

abstract class AbstractRepository extends Application
{

    /**
     * @var Connection $db
     */
    protected $db;

    /**
     * @var string $table
     */
    protected $table;

    /**
     * AbstractRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->getContainer()->get('database_connection');

        // Calling setUp
        $this->setUp();
    }

    /**
     * setUp function
     */
    abstract protected function setUp();

    /**
     * @return mixed
     */
    protected function getTable() {
        return $this->table;
    }

    /**
     * @param string $table
     * @return $this
     */
    protected function setTable(string $table) {
        $this->table = $table;
        return $this;
    }

    /**
     * Inserts new row
     *
     * @param array $data
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function insert(array $data = []) {

        if (!isset($data['created'])) {
            $data['created'] = (new \DateTime())->format('Y-m-d H:i:s');
        }

        if (!isset($data['updated'])) {
            $data['updated'] = (new \DateTime())->format('Y-m-d H:i:s');
        }

        return $this->db->insert($this->getTable(), $data);

    }

    /**
     * Updates an existing row by id
     *
     * @param int $id
     * @param array $data
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(int $id, array $data = []) {

        return $this->db->update($this->getTable(), $data, ['id' => $id]);

    }

    /**
     * Soft-deletes a row by id
     *
     * @param int $id
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function delete(int $id) {

        return $this->db->update($this->getTable(), ['deleted' => (new \DateTime())->format('Y-m-d')], ['id' => $id]);

    }

    /**
     * Fetching all, not deleted rows
     *
     * @param string $columns
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetchAll(string $columns = "*") {

        return $this->query(
            sprintf('SELECT %s FROM `%s` WHERE DATE(deleted) = "9999-12-31"', $columns, $this->getTable())
        )->fetchAll();

    }

    /**
     * Running simple query
     *
     * @param $sql
     * @return \Doctrine\DBAL\Driver\Statement
     * @throws \Doctrine\DBAL\DBALException
     */
    public function query(string $sql) {
        return $this->db->query($sql);
    }

}