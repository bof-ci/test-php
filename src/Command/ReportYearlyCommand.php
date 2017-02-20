<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use BOF\Command\ReportDataNotFoundException;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getOption('year');
        
        if($this->isYearInFuture($year)) {
            $this->renderEmptyTable($output, $year);
            return;
        }
        
        /** @var $db Connection */
        $db = $this->getContainer()->get('database_connection');
        
        try {
            $profileDisplayOrder = $this->profileDisplayOrder($db);
            $profileViewsPerMonth = $this->profileViewsPerMonth($db, $year, $profileDisplayOrder);
        } catch (ReportDataNotFoundException $e) {
            $this->renderEmptyTable($output, $year);  
            return;
        }
        
        $monthlyViewsGroupedByProfileName = $this->monthlyViewsGroupedByProfile($profileViewsPerMonth, $profileDisplayOrder);
        $tableRows = $this->tableRows($monthlyViewsGroupedByProfileName);
        
        $this->renderTable($output, $year, $tableRows);   
    }
    
    /**
     *  We cannot have historical data for the future
     * 
     * @param int $year
     * @return boolean
     */
    private function isYearInFuture($year)
    {
        return date('Y') < $year;
    }
    
    /**
     * Order of profiles to show for the report
     * 
     * @param object $db
     * @return array
     */
    private function profileDisplayOrder($db)
    {
        $profiles = $db->query('SELECT profile_id, profile_name FROM profiles ORDER BY profile_name ASC')->fetchAll();

        if(!$profiles) {
            throw new ReportDataNotFoundException();
        }
        
        $profileOrder = array();
        foreach($profiles as $profile) {
            $profileOrder[$profile['profile_id']] = $profile['profile_name'];    
        }
        
        return $profileOrder;
    }
    
    
    /**
     * Total views per month for each profile
     * 
     * @param object $db
     * @param int $year
     * @param array $profileDisplayOrder
     * @return array
     */
    private function profileViewsPerMonth($db, $year, $profileDisplayOrder)
    {        
        $query = "SELECT profile_id, MONTH(`date`) AS month, SUM(`views`) AS total_views";
        $query .= " FROM `views`";
        $query .= " WHERE YEAR(`date`) = ?";
        $query .= " GROUP BY profile_id, month";
        $query .= " ORDER BY FIELD(profile_id, ?), month ASC";
          
        $stmt = $db->executeQuery(
                    $query, 
                    array($year, array_keys($profileDisplayOrder)),
                    array(\PDO::PARAM_INT, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                );
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if(!$result) {
            throw new ReportDataNotFoundException();
        }
        return $result;
    }
    
        
    /**
     * Monthly views of profiles grouped by profile name
     * 
     * @param array $profileViewsPerMonth
     * @param array $profileDisplayOrder
     * @return array
     */
    private function monthlyViewsGroupedByProfile($profileViewsPerMonth, $profileDisplayOrder)
    {        
        $monthlyViewsGroupedByProfile = array();
        foreach($profileViewsPerMonth as $data) {
            $monthlyViewsGroupedByProfile[$profileDisplayOrder[$data['profile_id']]][$data['month']] = $data['total_views'];
        }
        return $monthlyViewsGroupedByProfile;
    }
    
    /**
     * Arrange data to show on the console table
     * 
     * @param array $monthlyViewsGroupedByProfileName
     * @return array
     */
    private function tableRows($monthlyViewsGroupedByProfileName)
    {      
        $tableRowData = array();
        foreach($monthlyViewsGroupedByProfileName as $profileName => $profileViews) {
            
            array_walk($profileViews, function(&$value, $key) {
                $value = number_format($value);
            });
            
            // Fill months where no profile view data exists with 'n/a'.
            $monthlyViews = array_replace(array_fill(1, 12, 'n/a'), $profileViews);
            
            // profile name appear first in the table column.
            array_unshift($monthlyViews, $profileName);
            $tableRowData[] = $monthlyViews;
        }
        return $tableRowData;
    }
    
    /**
     * 
     * @param object $output
     * @param int $year
     * @param array $tableRows
     */
    private function renderTable($output, $year, $tableRows)
    {    
        // Show data in a table - headers, data
        $table = new Table($output);
        $table->setHeaders(array(
                    "Profile   $year","Jan","Feb","Mar","Apr","May",
                    "Jun","Jul","Aug","Sep","Oct","Nov","Dec"
                ))
                ->setRows($tableRows);
        $table->render();   
    }
    
    /**
     * 
     * @param object $output
     * @param int $year
     */
    private function renderEmptyTable($output, $year)
    {
        $this->renderTable($output, $year, array(array_fill(1, 13, 'n/a')));
    }
}
