<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 30/07/18
 * Time: 17:08
 */

namespace BOF\AppBundle;

class BusinessAnalyst
{
    private $results;
    private $year;

    const JANUARY = 1;
    const DECEMBER = 12;

    const NA = 'n/a';

    private $peoples = [];

    /**
     * setResults
     *
     * @param $results
     * @return $this
     */
    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

    /**
     * formatResult
     *
     * @return array
     */
    public function formatResultPerProfiles()
    {
        $output = [];
        foreach ($this->results as $result)
        {
            if (!$this->year) {
                $this->setYear($result['year']);
            }

            if (!isset($this->peoples[$result['profile_id']])) {
                $this->setPeople($result['profile_id'], $result['profile_name']);
            }

            $output[$result['profile_id']][(int)$result['month']] = $result['views'];
        }

        return $this->fillMissingData($output);
    }

    /**
     * setYear
     *
     * @param $year
     */
    private function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * getYear
     *
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * setPeople
     *
     * @param $profileId
     * @param $profileName
     */
    private function setPeople($profileId, $profileName)
    {
        $this->peoples[$profileId] = $profileName;
    }

    /**
     * @return array
     */
    public function getPeoples()
    {
        return $this->peoples;
    }

    public function getPerson($profileId)
    {
        return $this->peoples[$profileId];
    }

    /**
     * fillMissingData
     *
     * @param array $results
     * @return array
     */
    public function fillMissingData(array $results)
    {
        foreach ($results as $personId => &$result)
        {
            $result = array_replace($this->fillAllYear(), $result);
            ksort($result);
        }

        return $results;
    }

    /**
     * fillAllYear
     *
     * @return array
     */
    private function fillAllYear()
    {
        return array_fill(self::JANUARY, self::DECEMBER, self::NA);
    }

    /**
     * getMonthOfAction
     *
     * @return array
     */
    private function getMonthOfAction()
    {
        $months = [];
        for ($startMonth = self::JANUARY; $startMonth <= self::DECEMBER; $startMonth++) {
            $date = date("F", mktime(0, 0, 0, $startMonth, 10));
            $months[] = substr($date,0,3);
        }

        return $months;
    }

    /**
     * getHeader
     *
     * @return array
     */
    public function getHeader()
    {
        $months = $this->getMonthOfAction();
        array_unshift($months, 'Profile ' . $this->getYear());

        return $months;
    }
}