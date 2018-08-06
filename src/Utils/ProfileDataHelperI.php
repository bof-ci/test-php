<?php
/**
 * @author iuli dercaci
 * 06/08/18 10:14
 */

namespace BOF\Utils;


interface ProfileDataHelperI
{
    /**
     * @param $profiles
     * @param $views
     */
    public function tableViewDataAssemble($profiles, $views);

    /**
     * @return array
     */
    public function getMonthsRange(): array;
}