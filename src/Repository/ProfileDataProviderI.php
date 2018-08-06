<?php

namespace BOF\Repository;


interface ProfileDataProviderI
{
    /**
     * @return array
     */
    public function getYearlyResult(): array;

    /**
     * @return array
     */
    public function getAllProfileNames(): array;
}