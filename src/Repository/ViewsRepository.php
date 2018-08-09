<?php

namespace BOF\Repository;

use BOF\Repository\Common\AbstractRepository;

/**
 * Class ViewsRepository
 * @package BOF\Repository
 */
class ViewsRepository extends AbstractRepository
{

    protected function setUp()
    {
        $this->setTable('views');
    }

}