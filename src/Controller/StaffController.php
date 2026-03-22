<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketStatutType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/staff')]
class StaffController extends AbstractController
{
    #[Route('/', name: 'app_staff_tickets')]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('staff/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_staff_ticket_show')]
    public function show(Ticket $ticket, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TicketStatutType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour !');
            return $this->redirectToRoute('app_staff_tickets');
        }

        return $this->render('staff/show.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
}