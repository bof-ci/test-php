<?php

namespace BOF\Repository;


use Doctrine\DBAL\Driver\Connection;

class Profile implements ProfileDataProviderI
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * Profile constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     */
    public function getYearlyResult(): array
    {
        $sql = "
            SELECT 
                p.profile_name,
                DATE_FORMAT(v.date, '%Y') AS 'year',
                MONTH (v.date) AS 'month',
                SUM(v.views) AS 'views'
            FROM
                profiles p
                    INNER JOIN
                views v USING (profile_id)
            GROUP BY p.profile_name , YEAR(v.date) , MONTH(v.date)
            ORDER BY year , p.profile_name, DATE_FORMAT(v.date, '%m')
        ";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * @return array
     */
    public function getAllProfileNames(): array
    {
        $sql = 'SELECT profile_id as id, profile_name as name FROM profiles';
        return $this->db->query($sql)->fetchAll();
    }
}