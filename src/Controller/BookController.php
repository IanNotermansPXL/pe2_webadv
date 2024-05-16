<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;


class BookController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/books', name: 'app_books')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAllBooks();

        // Create an array of loan statuses for the books
        $loanStatuses = [];
        foreach ($books as $book) {
            $loanStatuses[$book->getId()] = $this->isBookLoaned($book->getId());
        }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'loanStatuses' => $loanStatuses,
        ]);
    }

    #[Route('/books/search', name: 'book_search')]
    public function search(BookRepository $bookRepository, Request $request): Response
    {
        $query = $request->query->get('q', '');
        $books = $bookRepository->findByNameStartingWith($query);

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/books/loan/{id}', name: 'book_loan')]
    public function loan(int $id, BookRepository $bookRepository): Response
    {
        // Find the book by its ID
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }

        // Create a new loan
        $loan = new Loan();
        $loan->setBook($book);
        $loan->setUser($this->getUser()); // Assuming the user is logged in
        $loan->setLoanDate(new \DateTime());

        // Save the loan to the database
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($loan);
        $entityManager->flush();

        // Redirect the user to the book list
        return $this->redirectToRoute('app_books');
    }

    public function isBookLoaned(int $bookId): bool
    {
        $loanRepository = $this->managerRegistry->getRepository(Loan::class);
        $loan = $loanRepository->findOneBy(['book' => $bookId, 'returnDate' => null]);

        return $loan !== null;
    }
}
