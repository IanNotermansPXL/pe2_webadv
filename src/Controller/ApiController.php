<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/books', name: 'api_books', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAllBooks();

        $booksArray = [];
        foreach ($books as $book) {
            $booksArray[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'publication_year' => $book->getPublicationYear(),
                'publisher' => $book->getPublisher(),
                'genre' => $book->getGenre(),
                'isbn' => $book->getIsbn(),
            ];
        }

        return new JsonResponse($booksArray);
    }

    #[Route('/api/books/{id}', name: 'api_book', methods: ['GET'])]
    public function getBook(int $id, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }

        $bookArray = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'publication_year' => $book->getPublicationYear(),
            'publisher' => $book->getPublisher(),
            'genre' => $book->getGenre(),
            'isbn' => $book->getIsbn(),
        ];

        return new JsonResponse($bookArray);
    }
}
