<?php

namespace BOF\Repository;

use BOF\Repository\Common\AbstractRepository;

/**
 * Class DailyStatisticsViewsRepository
 * @package BOF\Repository
 */
class DailyStatisticsViewsRepository extends AbstractRepository
{

    protected function setUp()
    {
        $this->setTable('daily_statistics_views');
    }

    /**
     * Generating data by day and profiles from views
     *
     * @return \Doctrine\DBAL\Driver\Statement
     * @throws \Doctrine\DBAL\DBALException
     */
    public function generateDailyByViews() {
        return $this->query('
        INSERT INTO `daily_statistics_views`
        
        (`profile`, `date`, `views`, `created`, `updated`) (
            SELECT
            
                -- Index 1
                views.profile,
                
                -- Index 2
                DATE(views.date) as date,
                
                
                -- Data
                COUNT(views.id) as total_views,
                
                NOW() as created,
                
                NOW() as updated
                
            FROM views
            WHERE DATE(views.deleted) = "9999-12-31"
            GROUP BY views.profile, DATE(views.date)
        )
        ');
    }

    /**
     * @param int $year
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function searchByYearAndMonth(int $year) {

        $sql = '
        SELECT
            main.profile as profile,
            IFNULL(SUM(main.month_jan), "N/O") as `jan`,
            IFNULL(SUM(main.month_feb), "N/O") as `feb`,
            IFNULL(SUM(main.month_mar), "N/O") as `mar`,
            IFNULL(SUM(main.month_apr), "N/O") as `apr`,
            IFNULL(SUM(main.month_may), "N/O") as `may`,
            IFNULL(SUM(main.month_jun), "N/O") as `jun`,
            IFNULL(SUM(main.month_jul), "N/O") as `jul`,
            IFNULL(SUM(main.month_aug), "N/O") as `aug`,
            IFNULL(SUM(main.month_sep), "N/O") as `sep`,
            IFNULL(SUM(main.month_oct), "N/O") as `oct`,
            IFNULL(SUM(main.month_nov), "N/O") as `nov`,
            IFNULL(SUM(main.month_dec), "N/O") as `dec`
        FROM (
        SELECT
        
            profiles.name as profile,
        
            CASE month_number
                WHEN 1 THEN views.views
                ELSE NULL
            END as month_jan,
            
            CASE month_number
                WHEN 2 THEN views.views
                ELSE NULL
            END as month_feb,
            
            CASE month_number
                WHEN 3 THEN views.views
                ELSE NULL
            END as month_mar,
            
            CASE month_number
                WHEN 4 THEN views.views
                ELSE NULL
            END as month_apr,
            
            CASE month_number
                WHEN 5 THEN views.views
                ELSE NULL
            END as month_may,
            
            CASE month_number
                WHEN 6 THEN views.views
                ELSE NULL
            END as month_jun,
            
            CASE month_number
                WHEN 7 THEN views.views
                ELSE NULL
            END as month_jul,
            
            CASE month_number
                WHEN 8 THEN views.views
                ELSE NULL
            END as month_aug,
            
            CASE month_number
                WHEN 9 THEN views.views
                ELSE NULL
            END as month_sep,
            
            CASE month_number
                WHEN 10 THEN views.views
                ELSE NULL
            END as month_oct,
            
            CASE month_number
                WHEN 11 THEN views.views
                ELSE NULL
            END as month_nov,
            
            CASE month_number
                WHEN 12 THEN views.views
                ELSE NULL
            END as month_dec
            
        FROM profiles
            
            LEFT JOIN (
                SELECT 
                    profile,
                    SUM(views) as views,
                    MONTH(date) as month_number
                FROM
                daily_statistics_views
                WHERE DATE(deleted) = "9999-12-31"
                AND YEAR(date) = :year
                GROUP BY profile, month_number
            ) as views ON views.profile = profiles.id	
        WHERE DATE(profiles.deleted) = "9999-12-31"
        ORDER BY profile ASC
        ) as main
        GROUP BY main.profile
        ';

        $statement = $this->db->prepare($sql);
        $statement->bindParam("year", $year);
        $statement->execute();

        return $statement->fetchAll();

    }

}