<?php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Schedule;
use Symfony\Contracts\Cache\CacheInterface;
use App\Scheduler\Message\SendEmailMessage;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('dailyMail')]
final class MainSchedule implements ScheduleProviderInterface{

    public function __construct(private CacheInterface $cache){}

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                RecurringMessage::cron('30 8 1 * *', new SendEmailMessage()),
                RecurringMessage::every('10 seconds', new SendEmailMessage())
            )        
            ->stateful($this->cache)
            ;
    }
}