<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
final class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Vérifier si il n'existe pas déjà une catégorie avec le même nom pour l'utilisateur
        $existingCategory = $entityManager->getRepository(Category::class)->findOneBy([
            'name' => $category->getName(),
            'user' => $this->getUser(),
        ]);
        if ($existingCategory) {
            $this->addFlash('error', 'Une catégorie avec ce nom existe déjà.');
            return $this->redirectToRoute('app_category_new');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUser($this->getUser());
            $entityManager->persist($category);
            $entityManager->flush();

             $this->addFlash('success', 'Catégorie ajoutée avec succès');


            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        if ($category->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($category->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/api/create', name: 'app_category_api_create', methods: ['POST'])]
    public function apiCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = trim($data['name'] ?? '');

        if (empty($name)) {
            return $this->json(['success' => false, 'message' => 'Le nom de la catégorie est requis'], 400);
        }

        // Vérifier si la catégorie existe déjà pour l'utilisateur
        $existingCategory = $entityManager->getRepository(Category::class)->findOneBy([
            'name' => $name,
            'user' => $this->getUser(),
        ]);
        if ($existingCategory) {
            return $this->json(['success' => false, 'message' => 'Une catégorie avec ce nom existe déjà'], 400);
        }

        $category = new Category();
        $category->setName($name);
        $category->setUser($this->getUser());

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'category' => [
                'id' => $category->getId(),
                'name' => $category->getName()
            ]
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($category->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
