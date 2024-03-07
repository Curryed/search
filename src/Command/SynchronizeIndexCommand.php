<?php

declare(strict_types=1);

namespace NereaEnrique\Search\Command;

use JoliCode\Elastically\Client;
use JoliCode\Elastically\Index;
use JoliCode\Elastically\Mapping\YamlProvider;
use NereaEnrique\Search\ElasticSearch\DataFixtures\Books;
use NereaEnrique\Search\ElasticSearch\Index\BooksCaseSensitiveIndex;
use NereaEnrique\Search\ElasticSearch\Index\BooksEdgeNgramIndex;
use NereaEnrique\Search\ElasticSearch\Index\BooksIndex;
use NereaEnrique\Search\ElasticSearch\Index\BooksNgramIndex;
use NereaEnrique\Search\ElasticSearch\Index\BooksStemmerIndex;
use NereaEnrique\Search\ElasticSearch\Index\BooksStopIndex;
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
    public const INDEXES = [
        BooksIndex::NAME,
        BooksCaseSensitiveIndex::NAME,
        BooksEdgeNgramIndex::NAME,
        BooksNgramIndex::NAME,
        BooksStemmerIndex::NAME,
        BooksStopIndex::NAME,
    ];

    public function __construct(
        private readonly Client $client,
        private readonly YamlProvider $yamlMappingProvider,
        private readonly Books $booksFixtures,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (self::INDEXES as $indexName) {
            $this->prepareAndCreateIndex($indexName);
            $this->loadFixtures($indexName);
        }

        return Command::SUCCESS;
    }

    private function prepareAndCreateIndex(string $indexName): void
    {
        $indexConfiguration = $this->getIndexConfiguration($indexName);
        $index = $this->client->getIndex($indexName);
        $this->createIndex($index, $indexConfiguration);
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

    private function loadFixtures(string $indexName): void
    {
        $this->booksFixtures->load($indexName);
    }
}
