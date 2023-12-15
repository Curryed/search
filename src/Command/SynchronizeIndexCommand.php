<?php

declare(strict_types=1);

namespace NereaEnrique\Search\Command;

use JoliCode\Elastically\Client;
use JoliCode\Elastically\Index;
use JoliCode\Elastically\Mapping\YamlProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:synchronize-index',
    description: 'Synchronize index.',
    aliases: [
        'index',
    ],
)]
final class SynchronizeIndexCommand extends Command
{
    private const INDEX_NAME = 'books';

    public function __construct(
        private Client $client,
        private YamlProvider $yamlMappingProvider,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $indexConfiguration = $this->getIndexConfiguration(self::INDEX_NAME);
        $index = $this->client->getIndex(self::INDEX_NAME);
        $this->createIndex($index, $indexConfiguration);

        return Command::SUCCESS;
    }

    private function getIndexConfiguration(string $indexName): array
    {
        return $this->yamlMappingProvider->provideMapping($indexName, ['filename' => $indexName.'.yaml']);
    }

    private function createIndex(Index $index, array $indexConfiguration): void
    {
        if ($index->exists()) {
            $index->delete();
        }

        $index->create($indexConfiguration);
    }
}
