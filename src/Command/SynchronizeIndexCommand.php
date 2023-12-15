<?php

declare(strict_types=1);

namespace NereaEnrique\Search\Command;

use JoliCode\Elastically\Client;
use JoliCode\Elastically\Index;
use JoliCode\Elastically\Mapping\YamlProvider;
use NereaEnrique\Search\ElasticSearch\DataFixtures\Books;
use NereaEnrique\Search\ElasticSearch\Index\BooksIndex;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:synchronize-index',
    description: 'Synchronize index and generate content.',
    aliases: [
        'index',
    ],
)]
final class SynchronizeIndexCommand extends Command
{
    public function __construct(
        private readonly Client $client,
        private readonly YamlProvider $yamlMappingProvider,
        private readonly Books $booksFixtures,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $indexConfiguration = $this->getIndexConfiguration();
        $index = $this->client->getIndex(BooksIndex::NAME);
        $this->createIndex($index, $indexConfiguration);

        $this->loadFixtures();

        return Command::SUCCESS;
    }

    private function getIndexConfiguration(): array
    {
        return $this->yamlMappingProvider->provideMapping(BooksIndex::NAME, ['filename' => BooksIndex::NAME.'.yaml']);
    }

    private function createIndex(Index $index, array $indexConfiguration): void
    {
        if ($index->exists()) {
            $index->delete();
        }

        $index->create($indexConfiguration);
    }

    private function loadFixtures(): void
    {
        $this->booksFixtures->load();
    }
}
