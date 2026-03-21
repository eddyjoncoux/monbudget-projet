<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Repository\WithdrawalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/dashboard', name: 'app_user_dashboard')]
    public function dashboard(
        TransactionRepository $transactionRepository,
        WithdrawalRepository $withdrawalRepository
    ): Response
    {
        $transactions = $transactionRepository->findBy(
        ['user' => $this->getUser()],
        ['date' => 'DESC']
    );

    // Regrouper les transactions par date
    $itemsByDate = [];
    
    // Ajouter les transactions
    foreach ($transactions as $transaction) {
        $dateKey = $transaction->getDate()->format('Y-m-d');
        if (!isset($itemsByDate[$dateKey])) {
            $itemsByDate[$dateKey] = [
                'date' => $transaction->getDate(),
                'items' => []
            ];
        }
        $itemsByDate[$dateKey]['items'][] = [
            'type' => 'transaction',
            'data' => $transaction
        ];
    }

    // Trier par date décroissante
    krsort($itemsByDate);

    return $this->render('user/dashboard.html.twig', [
        'transactions' => $transactions,
        'itemsByDate' => $itemsByDate,
    ]);
}
}
