<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');
        
        // Get Year
        $year = $input->getArgument('year');
        if(is_null($year)){
          $year = date("Y");
        } else if($year < 2000 || $year > date("Y")){
          echo "Argument must be number between 2000 and " . date("Y");
          die();
        }
        
        // Display data
        $sql = "
          SELECT 
          p.profile_name 
          , FORMAT( SUM( IF( v.date >= '" . $year . "-01-01' AND v.date<= '" . $year . "-01-31', v.views, 0) ) , 0) as mon1
          , FORMAT( SUM( IF( v.date >= '" . $year . "-02-01' AND v.date<= '" . $year . "-02-29', v.views, 0) ) , 0) as mon2
          , FORMAT( SUM( IF( v.date >= '" . $year . "-03-01' AND v.date<= '" . $year . "-03-31', v.views, 0) ) , 0) as mon3
          , FORMAT( SUM( IF( v.date >= '" . $year . "-04-01' AND v.date<= '" . $year . "-04-30', v.views, 0) ) , 0) as mon4
          , FORMAT( SUM( IF( v.date >= '" . $year . "-05-01' AND v.date<= '" . $year . "-05-31', v.views, 0) ) , 0) as mon5
          , FORMAT( SUM( IF( v.date >= '" . $year . "-06-01' AND v.date<= '" . $year . "-06-30', v.views, 0) ) , 0) as mon6
          , FORMAT( SUM( IF( v.date >= '" . $year . "-07-01' AND v.date<= '" . $year . "-07-31', v.views, 0) ) , 0) as mon7
          , FORMAT( SUM( IF( v.date >= '" . $year . "-08-01' AND v.date<= '" . $year . "-08-31', v.views, 0) ) , 0) as mon8
          , FORMAT( SUM( IF( v.date >= '" . $year . "-09-01' AND v.date<= '" . $year . "-09-30', v.views, 0) ) , 0) as mon9
          , FORMAT( SUM( IF( v.date >= '" . $year . "-10-01' AND v.date<= '" . $year . "-10-31', v.views, 0) ) , 0) as mon10
          , FORMAT( SUM( IF( v.date >= '" . $year . "-11-01' AND v.date<= '" . $year . "-11-30', v.views, 0) ) , 0) as mon11
          , FORMAT( SUM( IF( v.date >= '" . $year . "-12-01' AND v.date<= '" . $year . "-12-31', v.views, 0) ) , 0) as mon12            
          FROM `profiles` p
          LEFT JOIN `views` v 
             ON p.profile_id = v.profile_id
            AND v.date >= '" . $year . "-01-01' AND v.date<= '" . $year . "-12-31'
          GROUP BY p.profile_id , p.profile_name
          ORDER BY p.profile_name ASC
        ";       
        $data = $db->query($sql)->fetchAll();
        
        // Set n/a if empty
        array_walk($data, function(&$row, $key){
          array_walk($row, function(&$item){
            if($item == "0"){
              $item = "n/a"; 
            }
          });
        });       

        // Show data in a table - headers, data
        $io->table(['Profile ' . $year, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Avg', 'Sep', 'Okt', 'Nov', 'Dec'], $data);

    }
}
