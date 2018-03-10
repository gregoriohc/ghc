<?php

namespace Gregoriohc\Ghc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class DbCreateCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('db:create')
            ->setDescription('Create a new database')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the schema that will be created')
            ->addOption('driver', null, InputOption::VALUE_OPTIONAL, 'Database driver', 'mysql')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Server host', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Server port', '3306')
            ->addOption('charset', null, InputOption::VALUE_OPTIONAL, 'Connection charset', 'utf8')
            ->addOption('collation', null, InputOption::VALUE_OPTIONAL, 'Connection collation', 'utf8_unicode_ci')
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, 'Connection username', 'root')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Connection password', '');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => $input->getOption('driver'),
            'database' => '',
            'host' => $input->getOption('host'),
            'port' => $input->getOption('port'),
            'username' => $input->getOption('user'),
            'password' => $input->getOption('password'),
            'charset' => $input->getOption('host'),
            'collation' => 'utf8_unicode_ci',
        ]);

        $capsule->bootEloquent();
        $capsule->setAsGlobal();

        try {
            $schema = $input->getArgument('name');
            $capsule->getConnection()->statement("CREATE DATABASE {$schema}");
        } catch (\Exception $e) {
            $output->writeln('<error>Database creation error: ' . $e->getMessage() . '</error>');
        }
    }
}
