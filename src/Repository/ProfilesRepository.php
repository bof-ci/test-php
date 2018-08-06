<?php

namespace BOF\Repository;

use BOF\Repository\Common\AbstractRepository;

/**
 * Class ProfilesRepository
 * @package BOF\Repository
 */
class ProfilesRepository extends AbstractRepository
{

    protected function setUp()
    {
        $this->setTable('profiles');
    }
    
}