<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/dashboard', name: 'app_user_dashboard')]
    public function dashboard(TransactionRepository $transactionRepository): Response
    {
        $transactions = $transactionRepository->findBy(
        ['user' => $this->getUser()],
        ['date' => 'DESC']
    );

    // Regrouper les transactions par date
    $transactionsByDate = [];
    foreach ($transactions as $transaction) {
        $dateKey = $transaction->getDate()->format('Y-m-d');
        if (!isset($transactionsByDate[$dateKey])) {
            $transactionsByDate[$dateKey] = [
                'date' => $transaction->getDate(),
                'transactions' => []
            ];
        }
        $transactionsByDate[$dateKey]['transactions'][] = $transaction;
    }

    return $this->render('user/dashboard.html.twig', [
        'transactions' => $transactions,
        'transactionsByDate' => $transactionsByDate,
        // ... tes autres variables existantes
    ]);
}
}
