<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
     #[Route("/user/loans", name: "user_loans")]
    public function loans(UserRepository $userRepository): Response
    {
        // Get the authenticated user
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('The user is not logged in');
        }

        return $this->render('user/loans.html.twig', [
            'user' => $user,
            'loans' => $user->getLoans(),
        ]);
    }

    #[Route("/user/loans/return/{id}", name: "return_loan", methods: ["POST"])]
    public function returnLoan($id, LoanRepository $loanRepository, EntityManagerInterface $entityManager): Response
    {
        $loan = $loanRepository->find($id);

        if (!$loan) {
            throw $this->createNotFoundException('The loan does not exist');
        }

        $entityManager->remove($loan);
        $entityManager->flush();

        return $this->redirectToRoute('user_loans');
    }
}
