<?php

namespace App\Command;

use Certificationy\Certification\Loader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:exam',
    description: 'Add a short description for your command',
)]
class ExamCommand extends Command
{
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $questions = Loader::init(1, ['architecture'], 'vendor/certificationy/symfony-pack/data/architecture.yml');



        $io->success('Congratulations! You passed the test!');

        return Command::SUCCESS;
    }
}
