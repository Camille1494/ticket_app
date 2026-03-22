<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Etat;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\CategorieType;
use App\Form\EtatType;
use App\Form\TicketAdminType;
use App\Form\UserType;
use App\Repository\CategorieRepository;
use App\Repository\EtatRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    // Dashboard
    #[Route('/', name: 'app_admin')]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    // --- CATEGORIES ---
    #[Route('/categories', name: 'app_admin_categories')]
    public function categories(CategorieRepository $repo): Response
    {
        return $this->render('admin/categories/index.html.twig', ['categories' => $repo->findAll()]);
    }

    #[Route('/categories/new', name: 'app_admin_categorie_new')]
    public function newCategorie(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'Catégorie créée !');
            return $this->redirectToRoute('app_admin_categories');
        }
        return $this->render('admin/categories/form.html.twig', ['form' => $form->createView(), 'titre' => 'Nouvelle catégorie']);
    }

    #[Route('/categories/{id}/edit', name: 'app_admin_categorie_edit')]
    public function editCategorie(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée !');
            return $this->redirectToRoute('app_admin_categories');
        }
        return $this->render('admin/categories/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier la catégorie']);
    }

    #[Route('/categories/{id}/delete', name: 'app_admin_categorie_delete', methods: ['POST'])]
    public function deleteCategorie(Categorie $categorie, EntityManagerInterface $em): Response
    {
        $em->remove($categorie);
        $em->flush();
        $this->addFlash('success', 'Catégorie supprimée !');
        return $this->redirectToRoute('app_admin_categories');
    }

    // --- ETATS ---
    #[Route('/etats', name: 'app_admin_etats')]
    public function etats(EtatRepository $repo): Response
    {
        return $this->render('admin/etats/index.html.twig', ['etats' => $repo->findAll()]);
    }

    #[Route('/etats/new', name: 'app_admin_etat_new')]
    public function newEtat(Request $request, EntityManagerInterface $em): Response
    {
        $etat = new Etat();
        $form = $this->createForm(EtatType::class, $etat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etat);
            $em->flush();
            $this->addFlash('success', 'État créé !');
            return $this->redirectToRoute('app_admin_etats');
        }
        return $this->render('admin/etats/form.html.twig', ['form' => $form->createView(), 'titre' => 'Nouvel état']);
    }

    #[Route('/etats/{id}/edit', name: 'app_admin_etat_edit')]
    public function editEtat(Etat $etat, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EtatType::class, $etat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'État modifié !');
            return $this->redirectToRoute('app_admin_etats');
        }
        return $this->render('admin/etats/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier l\'état']);
    }

    #[Route('/etats/{id}/delete', name: 'app_admin_etat_delete', methods: ['POST'])]
    public function deleteEtat(Etat $etat, EntityManagerInterface $em): Response
    {
        $em->remove($etat);
        $em->flush();
        $this->addFlash('success', 'État supprimé !');
        return $this->redirectToRoute('app_admin_etats');
    }

    // --- TICKETS ---
    #[Route('/tickets/{id}/edit', name: 'app_admin_ticket_edit')]
    public function editTicket(Ticket $ticket, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TicketAdminType::class, $ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Ticket modifié !');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/tickets/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier le ticket']);
    }

    #[Route('/tickets/{id}/delete', name: 'app_admin_ticket_delete', methods: ['POST'])]
    public function deleteTicket(Ticket $ticket, EntityManagerInterface $em): Response
    {
        $em->remove($ticket);
        $em->flush();
        $this->addFlash('success', 'Ticket supprimé !');
        return $this->redirectToRoute('app_admin');
    }

    // --- USERS ---
    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $repo): Response
    {
        return $this->render('admin/users/index.html.twig', ['users' => $repo->findAll()]);
    }

    #[Route('/users/new', name: 'app_admin_user_new')]
    public function newUser(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur créé !');
            return $this->redirectToRoute('app_admin_users');
        }
        return $this->render('admin/users/form.html.twig', ['form' => $form->createView(), 'titre' => 'Nouvel utilisateur']);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_user_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié !');
            return $this->redirectToRoute('app_admin_users');
        }
        return $this->render('admin/users/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier l\'utilisateur']);
    }
}
