<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;


class BookController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/books', name: 'app_books')]
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $booksQuery = $bookRepository->findAllBooks(); // Assuming this method returns a Query object

        // Paginate the results of the query
        $pagination = $paginator->paginate(
            $booksQuery, // pass Query object
            $request->query->getInt('page', 1), // page number
            9 // limit per page
        );

        // Create an array of loan statuses for the books
        $loanStatuses = [];
        foreach ($pagination as $book) {
            $loanStatuses[$book->getId()] = $this->isBookLoaned($book->getId());
        }

        return $this->render('book/index.html.twig', [
            'pagination' => $pagination,
            'loanStatuses' => $loanStatuses,
        ]);
    }

    #[Route('/books/search', name: 'book_search')]
    public function search(BookRepository $bookRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('q', '');
        $booksQuery = $bookRepository->findByNameStartingWith($query); // Assuming this method returns a Query object

        // Paginate the results of the query
        $pagination = $paginator->paginate(
            $booksQuery, // pass Query object
            $request->query->getInt('page', 1), // page number
            10 // limit per page
        );

        // Create an array of loan statuses for the books
        $loanStatuses = [];
        foreach ($pagination as $book) {
            $loanStatuses[$book->getId()] = $this->isBookLoaned($book->getId());
        }

        return $this->render('book/index.html.twig', [
            'pagination' => $pagination,
            'loanStatuses' => $loanStatuses,
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
