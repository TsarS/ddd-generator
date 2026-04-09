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

        // If no config provided, check for ddd.json in current directory
        $configPath = $input->getOption('config');
        if (empty($configPath)) {
            $cwd = getcwd();
            if (file_exists($cwd . '/ddd.json')) {
                $configPath = $cwd . '/ddd.json';
            }
        }

        // If no events provided, check for events.json in current directory
        $eventsPath = $input->getOption('events');
        if (empty($eventsPath)) {
            $cwd = getcwd();
            if (file_exists($cwd . '/events.json')) {
                $eventsPath = $cwd . '/events.json';
            }
        }

        if (empty($appName) || empty($configPath)) {
            $output->writeln('<error>Укажите название приложения и путь к ddd.json</error>');
            return Command::FAILURE;
        }

        // Resolve relative paths from script directory or current working directory
        $configPath = $this->resolvePath($configPath);
        $eventsPath = $eventsPath ? $this->resolvePath($eventsPath) : null;

        if (!file_exists($configPath)) {
            $output->writeln("<error>Config file not found: $configPath</error>");
            return Command::FAILURE;
        }

        $generator = new DDDGenerator();
        try {
            $projectPath = getcwd() . "/$appName";
            $generator->generate($appName, $configPath, $eventsPath);
            $output->writeln("<info>Структура успешно создана в: $projectPath</info>");
            return Command::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

    private function resolvePath(string $path): string
    {
        if (str_starts_with($path, './') || str_starts_with($path, '../')) {
            // Try relative to current working directory first
            $cwd = getcwd();
            $resolved = realpath($cwd . '/' . $path);
            if ($resolved !== false && file_exists($resolved)) {
                return $resolved;
            }
            // Then try relative to script location
            $scriptDir = dirname(__DIR__, 2);
            $resolved = realpath($scriptDir . '/' . $path);
            if ($resolved !== false && file_exists($resolved)) {
                return $resolved;
            }
        }
        return $path;
    }
}