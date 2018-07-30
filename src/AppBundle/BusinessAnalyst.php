<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 30/07/18
 * Time: 17:08
 */

namespace BOF\AppBundle;


use function foo\func;

class BusinessAnalyst
{
    private $results;
    const JANUARY = 1;
    const DECEMBER = 12;
    const NA = 'n/a';

    public $minYear;
    public $maxYear;

    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

    public function formatResult()
    {
        $output = [];
        $minYear = $maxYear = (int) current($this->results)['year'];
        foreach ($this->results as $result)
        {
            if ($result['year'] <= $minYear) {
                $minYear = (int) $result['year'];
            }

            if ($result['year'] >= $maxYear) {
                $maxYear = (int) $result['year'];
            }

            $output[$result['profile_name']][$result['year']][(int)$result['month']] = $result['views'];
            $output[$result['year']][(int)$result['month']][$result['profile_name']] = $result['views'];
        }

        $this->minYear = $minYear;
        $this->maxYear = $maxYear;

        return $this->fillMissingData($output);
    }

    public function fillMissingData(array $results)
    {
        var_dump($results); die;
        foreach ($results as $person => &$result)
        {
            for ($year = $this->minYear; $year <= $this->maxYear; $year++) {
                if (!isset($result[$year])) {
                    $result[$year] = [];
                }

                $result[$year] = array_replace($this->fillAllYear(), $result[$year]);
            }

            ksort($result);
        }

        return $results;
    }

    private function fillAllYear()
    {
        return array_fill(self::JANUARY, self::DECEMBER, self::NA);
    }

    public function getHeader()
    {
        return array_merge(['Profile'], self::getMonths());
    }

    public static function getMonths()
    {
        $months = [];
        for ($m = self::JANUARY; $m <= self::DECEMBER; ++$m){
            $months[$m] = \DateTime::createFromFormat('!m', $m)->format('F');
        }

        return $months;
    }

    private function getProfiles(array $results)
    {
        return array_unique(array_column($results, 'profile_name'));
    }
}