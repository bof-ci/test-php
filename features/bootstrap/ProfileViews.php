<?php
use BOF\Application;

final class ProfileViews
{
    private $app;
    private $db;
    private $dataExists;

    public function __construct()
    {
        $this->app = new Application();
        $this->db = $this->app->getContainer()->get('database_connection');
    }

    /**
     * @param int $year
     */
    public function setDataExits($year)
    {
        $query = $this->db->prepare("SELECT COUNT(*) AS records_count FROM views WHERE `date` >= :startDate AND `date` <= :endDate");
        $query->bindValue("startDate", "$year-01-01");
        $query->bindValue("endDate", "$year-12-31");
        $query->execute();

        $this->dataExists = false;
        if(1 <= $query->fetchColumn()) {
            $this->dataExists = true;
        }
    }

    /**
     * @return boolean
     */
    public function getDataExists()
    {
        return $this->dataExists;
    }

    /**
     * Set up database for testing
     */
    public static function setupDatabase()
    {
        $app = new Application();
        $db = $app->getContainer()->get('database_connection');
        $db->query('DROP TABLE IF EXISTS `bof_test`.`profiles`');
        $db->query('CREATE TABLE `bof_test`.`profiles` (
        `profile_id` INT NOT NULL ,
        `profile_name` VARCHAR( 100 ) NOT NULL
        ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci');

        $db->query('DROP TABLE IF EXISTS `bof_test`.`views`');
        $db->query('CREATE TABLE `bof_test`.`views` (
        `profile_id` INT NOT NULL ,
        `date` DATE NOT NULL ,
        `views` INT NOT NULL
        ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci');

        $db->query("INSERT INTO `bof_test`.`profiles` VALUES(1, 'Karl Lagerfeld'), (2, 'Anna Wintour')");
        $db->query("INSERT INTO `views` (`profile_id`, `date`, `views`) VALUES
            (1, '2015-01-01', 10),
            (1, '2015-02-01', 20),
            (1, '2015-03-01', 30),
            (1, '2015-04-01', 40),
            (1, '2015-05-01', 50),
            (1, '2015-06-01', 60),
            (1, '2015-07-01', 70),
            (1, '2015-08-01', 80),
            (1, '2015-09-01', 90),
            (1, '2015-10-01', 100),
            (1, '2015-11-01', 110),
            (1, '2015-12-01', 120),
            (2, '2015-01-01', 10),
            (2, '2015-02-01', 20),
            (2, '2015-03-01', 30),
            (2, '2015-04-01', 40),
            (2, '2015-05-01', 50),
            (2, '2015-06-01', 60),
            (2, '2015-07-01', 70),
            (2, '2015-08-01', 80),
            (2, '2015-09-01', 90),
            (2, '2015-10-01', 100),
            (2, '2015-11-01', 110),
            (2, '2015-12-01', 120),
            (1, '2016-01-01', 1000000),
            (1, '2016-02-01', 2000000),
            (2, '2016-01-01', 1000000),
            (2, '2016-02-01', 2000000)
            ");
    }

    /**
     * Clean up testing data
     */
    public static function cleanDatabase()
    {
        $app = new Application();
        $db = $app->getContainer()->get('database_connection');
        $db->query('DROP TABLE IF EXISTS `bof_test`.`profiles`');
        $db->query('DROP TABLE IF EXISTS `bof_test`.`views`');
    }

}
