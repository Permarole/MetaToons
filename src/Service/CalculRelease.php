<?php

namespace App\Service;

use DateTime;
use DateTimeInterface;

class CalculRelease {

    public function isNew(DateTime $date): bool
    {
        $now = new DateTime();

        return $date->modify('+2 days') > $now ? true : false;
    }
}