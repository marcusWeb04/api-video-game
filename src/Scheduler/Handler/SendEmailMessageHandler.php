<?php

namespace App\Scheduler\Handler;

use App\Entity\User;
use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
use App\Service\MailerService;
use App\Scheduler\Message\SendEmailMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class SendEmailMessageHandler
{
    private User $user;
    private $array = [];

    public function __construct(private MailerService $mailerService)
    {}

    public function SetUser(User $user):void
    {
        $this->user = $user;
    }

    public function videoGames(VideoGameRepository $repository)
    {
        $this->array = $repository->findGamesReleasingInNext7Days();
    }

    public function __invoke(SendEmailMessage $message): void
    {
        $this->mailerService->sendEmail(
            $this->user->getEmail(),
            '',
            $this->array,
        );
    }
}