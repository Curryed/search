<?php

declare(strict_types=1);

namespace NereaEnrique\Search\ElasticSearch\DataFixtures;

use Elastica\Document;
use JoliCode\Elastically\Indexer;
use NereaEnrique\Search\Domain\Book;

final class Books
{
    public function __construct(
        private readonly Indexer $indexer,
    ) {}

    public function load(string $indexName): void
    {
        $this->createBook($indexName, new Book(
            'The Lord of the Rings',
            'J. R. R. Tolkien',
            'The Lord of the Rings is an epic high fantasy novel by the English author and scholar J. R. R. Tolkien.',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'The Hobbit',
            'J. R. R. Tolkien',
            'The Hobbit, or There and Back Again is a children\'s fantasy novel by English author J. R. R. Tolkien.',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'The Silmarillion',
            'J. R. R. Tolkien',
            'The Silmarillion is a collection of mythopoeic works by English writer J. R. R. Tolkien, edited and published posthumously by his son, Christopher Tolkien, in 1977, with assistance from Guy Gavriel Kay.',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'The Great Gatsby',
            'F. Scott Fitzgerald',
            'The Great Gatsby is a 1925 novel by American writer F. Scott Fitzgerald. Set in the Jazz Age on Long Island, the novel depicts narrator Nick Carraway\'s interactions with mysterious millionaire Jay Gatsby and Gatsby\'s obsession to reunite with his former lover, Daisy Buchanan.',
            'fiction, tragedy',
        ));

        $this->createBook($indexName, new Book(
            'The Catcher in the Rye',
            'J. D. Salinger',
            'The Catcher in the Rye is a novel by J. D. Salinger, partially published in serial form in 1945–1946 and as a novel in 1951. It was originally intended for adults but is read by adolescents for its themes of angst, alienation, and as a critique on superficiality in society.',
            'realistic fiction',
        ));

        $this->createBook($indexName, new Book(
            'The Grapes of Wrath',
            'John Steinbeck',
            'The Grapes of Wrath is an American realist novel written by John Steinbeck and published in 1939. The book won the National Book Award and Pulitzer Prize for fiction, and it was cited prominently when Steinbeck was awarded the Nobel Prize in 1962.',
            'historical fiction',
        ));

        $this->createBook($indexName, new Book(
            'The Adventures of Huckleberry Finn',
            'Mark Twain',
            'Adventures of Huckleberry Finn is a novel by Mark Twain, first published in the United Kingdom in December 1884 and in the United States in February 1885. Commonly named among the Great American Novels, the work is among the first in major American literature to be written throughout in vernacular English, characterized by local color regionalism.',
            'adventure fiction',
        ));

        $this->createBook($indexName, new Book(
            'Harry Potter and the Philosopher\'s Stone',
            'J. K. Rowling',
            '',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'Harry Potter and the Chamber of Secrets',
            'J. K. Rowling',
            '',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'Harry Potter and the Prisoner of Azkaban',
            'J. K. Rowling',
            '',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'Harry Potter and the Goblet of Fire',
            'J. K. Rowling',
            '',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'Harry Potter and the Order of the Phoenix',
            'J. K. Rowling',
            '',
            'fantasy',
        ));

        $this->createBook($indexName, new Book(
            'Harry Potter and the Half-Blood Prince',
            'J. K. Rowling',
            '',
            'fantasy',
        ));

        $this->createBook(
            $indexName,
            new Book(
                'Harry Potter and the Deathly Hallows',
                'J. K. Rowling',
                '',
                'fantasy',
            ),
        );

        $this->createBook(
            $indexName,
            new Book(
                'The Hunger Games',
                'Suzanne Collins',
                '',
                'science fiction',
            ),
        );
    }

    private function createBook(string $indexName, Book $book): void
    {
        $this->indexer->scheduleIndex(
            $indexName,
            new Document(
                null,
                $book->toDocument(),
            ),
        );
        $this->indexer->flush();
    }
}
