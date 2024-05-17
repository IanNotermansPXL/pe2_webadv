<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
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

    #[Route("/user/loans/pdf", name: "loans_pdf")]
    public function loansPdf(LoanRepository $loanRepository): Response
    {
        // Get the authenticated user
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('The user is not logged in');
        }

        // Fetch all loans
        $loans = $loanRepository->findBy(['user' => $user]);

        // Render the loans in a Twig template
        $html = $this->renderView('user/loans_pdf.html.twig', [
            'user' => $user,
            'loans' => $loans,
        ]);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("loans.pdf", [
            "Attachment" => true
        ]);

        // Stop execution and return a response
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
