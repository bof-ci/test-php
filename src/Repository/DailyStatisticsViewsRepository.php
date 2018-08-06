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

}