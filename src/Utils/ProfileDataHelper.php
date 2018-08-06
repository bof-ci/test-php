<?php

namespace BOF\Utils;


class ProfileDataHelper
{
    /**
     * @param $profiles
     * @param $views
     * @return array
     */
    public function tableViewDataAssemble($profiles, $views)
    {
        $result = [];

        $profilesDefaultData = $this->getDefaultProfilesData($profiles);

        foreach ($views as $row) {

            $year = $row['year'];
            $month = $row['month'];
            $profileName = $row['profile_name'];
            $views = $row['views'];

            if (!isset($result[$year])) {

                $result[$year] = $profilesDefaultData;
            }

            $idx = array_search(
                $profileName,
                array_column($result[$year], 0)
            );

            $result[$year][$idx][$month] = $views;
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getMonthsRange(): array
    {
        $months = [];

        $start = new \DateTime('2020-01-01');
        $interval = new \DateInterval('P1M');
        $end = new \DateTime('2021-01-01');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $months[] = $dt->format('M');
        }

        return $months;
    }

    /**
     * @param null $year
     * @return string
     */
    public function getTableHeader($year = null): string
    {
        return $year ? sprintf('Profile %d', $year) : 'Profile';
    }

    /**
     * @param array $profiles
     * @return array
     */
    private function getDefaultProfilesData(array $profiles): array
    {
        $result = [];

        foreach ($profiles as $profile) {

            $defaultData = array_fill(1, 12, 'n/a');
            array_unshift($defaultData, $profile['name']);
            $result[$profile['id']] = $defaultData;
        }

        usort($result, function ($dataSet1, $dataSet2) {

            return $dataSet1[0] <=> $dataSet2[0];
        });

        return $result;
    }
}