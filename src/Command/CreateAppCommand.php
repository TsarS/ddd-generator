<?php
declare(strict_types=1);

// src/Command/CreateAppCommand.php

namespace YourVendor\DDDGenerator\Command;

use YourVendor\DDDGenerator\DDDGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


final class CreateAppCommand extends Command
{
    protected static $defaultName = 'create';

    protected function configure(): void
    {
        $this
            ->setDescription('Создает структуру DDD для приложения из ddd.json и events.json')
            ->addArgument('app', InputArgument::REQUIRED, 'Название приложения')
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Путь к ddd.json конфигурации')
            ->addOption('events', 'e', InputOption::VALUE_REQUIRED, 'Путь к events.json для межагрегатных связей');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $appName = $input->getArgument('app');
        $configPath = $input->getOption('config');
        $eventsPath = $input->getOption('events');

        if (empty($appName) || empty($configPath)) {
            $output->writeln('<error>Укажите название приложения и путь к ddd.json</error>');
            return Command::FAILURE;
        }

        if (!file_exists($configPath)) {
            $output->writeln("<error>Config file not found: $configPath</error>");
            return Command::FAILURE;
        }

        $generator = new DDDGenerator();
        $generator->generate($appName, $configPath, $eventsPath);

        $output->writeln('<info>Структура успешно создана!</info>');
        return Command::SUCCESS;
    }
}