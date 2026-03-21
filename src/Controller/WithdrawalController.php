<?php

namespace App\Controller;

use App\Entity\Withdrawal;
use App\Form\WithdrawalFormType;
use App\Repository\WithdrawalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/withdrawal')]
final class WithdrawalController extends AbstractController
{
    private WithdrawalRepository $withdrawalRepository;

    public function __construct(WithdrawalRepository $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }

    #[Route('/', name: 'app_withdrawal_index', methods: ['GET'])]
    public function index(): Response
    {
        $withdrawals = $this->withdrawalRepository->findByUser($this->getUser());

        return $this->render('withdrawal/index.html.twig', [
            'withdrawals' => $withdrawals,
        ]);
    }

    #[Route('/new', name: 'app_withdrawal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $withdrawal = new Withdrawal();

        $form = $this->createForm(WithdrawalFormType::class, $withdrawal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $withdrawal->setUser($this->getUser());
            $withdrawal->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($withdrawal);
            $entityManager->flush();

            $this->addFlash('success', 'Prélèvement ajouté avec succès');

            return $this->redirectToRoute('app_withdrawal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('withdrawal/new.html.twig', [
            'withdrawal' => $withdrawal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_withdrawal_show', methods: ['GET'])]
    public function show(Withdrawal $withdrawal): Response
    {
        if ($withdrawal->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('withdrawal/show.html.twig', [
            'withdrawal' => $withdrawal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_withdrawal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Withdrawal $withdrawal, EntityManagerInterface $entityManager): Response
    {
        if ($withdrawal->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(WithdrawalFormType::class, $withdrawal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $withdrawal->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Prélèvement modifié avec succès');

            return $this->redirectToRoute('app_withdrawal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('withdrawal/edit.html.twig', [
            'withdrawal' => $withdrawal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_withdrawal_delete', methods: ['POST'])]
    public function delete(Request $request, Withdrawal $withdrawal, EntityManagerInterface $entityManager): Response
    {
        if ($withdrawal->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $withdrawal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($withdrawal);
            $entityManager->flush();

            $this->addFlash('success', 'Prélèvement supprimé avec succès');
        }

        return $this->redirectToRoute('app_withdrawal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle', name: 'app_withdrawal_toggle', methods: ['POST'])]
    public function toggle(Request $request, Withdrawal $withdrawal, EntityManagerInterface $entityManager): Response
    {
        if ($withdrawal->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('toggle' . $withdrawal->getId(), $request->getPayload()->getString('_token'))) {
            $withdrawal->setIsActive(!$withdrawal->isActive());
            $withdrawal->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $status = $withdrawal->isActive() ? 'activé' : 'désactivé';
            $this->addFlash('success', "Prélèvement $status avec succès");
        }

        return $this->redirectToRoute('app_withdrawal_index', [], Response::HTTP_SEE_OTHER);
    }
}
