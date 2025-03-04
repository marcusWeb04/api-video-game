<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'newsletter-command',
    description: 'Envoie un e-mail à un utilisateur',
)]
class NewsletterCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Envoie un email.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
