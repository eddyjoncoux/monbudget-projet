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

    // Get active withdrawals for the user
    $withdrawals = $withdrawalRepository->findActiveByUser($this->getUser());

    // Regrouper les transactions ET prélèvements par date
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

    // Ajouter les prélèvements (en utilisant la date du prochain prélèvement)
    foreach ($withdrawals as $withdrawal) {
        $dateKey = $withdrawal->getNextWithdrawalDate()->format('Y-m-d');
        if (!isset($itemsByDate[$dateKey])) {
            $itemsByDate[$dateKey] = [
                'date' => $withdrawal->getNextWithdrawalDate(),
                'items' => []
            ];
        }
        $itemsByDate[$dateKey]['items'][] = [
            'type' => 'withdrawal',
            'data' => $withdrawal
        ];
    }

    // Trier par date décroissante
    krsort($itemsByDate);

    return $this->render('user/dashboard.html.twig', [
        'transactions' => $transactions,
        'itemsByDate' => $itemsByDate,
        // ... tes autres variables existantes
    ]);
}
}
