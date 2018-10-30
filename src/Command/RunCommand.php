<?php

namespace Extractor\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Connector\Connector;
use Extractor\Loader\ExtractorLoader;
use Extractor\Connection\PdoConnection;
use RuntimeException;
use PDO;

class RunCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Run an extractor')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Filename'
            )
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Database connection URL'
            )
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Input argument(s)',
                null
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        if (!$url) {
            $url = getenv('PDO');
        }
        if (!$url) {
            throw new RuntimeException("Database URL unspecified. Either pass as an argument, or configure your PDO environment variable.");
        }
        $filename  = $input->getArgument('filename');

        if (!file_exists($filename)) {
            throw new FileNotFoundException($filename);
        }

        $loader = new ExtractorLoader();
        $yaml = file_get_contents($filename);
        $config = Yaml::parse($yaml);
        $extractor = $loader->load($config);

        $connector = new Connector();
        $config = $connector->getConfig($url);
        $pdo = $connector->getPdo($config);


        $inputs = [];
        foreach ($input->getOption('input') ?? [] as $k=>$v) {
            $part = explode('=', $v);
            if (count($part)!=2) {
                throw new RuntimeException("Invalid input format, use `-i key=value`");
            }
            $inputs[$part[0]] = $part[1];
        }



        $connections = [
            'default' => new PdoConnection($pdo),
        ];


        $data = $extractor->extract($connections, $inputs);
        echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . PHP_EOL;

    }
}
