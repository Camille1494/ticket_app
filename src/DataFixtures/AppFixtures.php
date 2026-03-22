<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Etat;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Catégories
        $categories = [];
        foreach (['Incident', 'Panne', 'Evolution', 'Anomalie', 'Information'] as $nom) {
            $cat = new Categorie();
            $cat->setNom($nom);
            $manager->persist($cat);
            $categories[] = $cat;
        }

        // États
        $etats = [];
        foreach (['Nouveau', 'Ouvert', 'Résolu', 'Fermé'] as $nom) {
            $etat = new Etat();
            $etat->setNom($nom);
            $manager->persist($etat);
            $etats[] = $etat;
        }

        // Admin
        $admin = new User();
        $admin->setEmail('admin@ticket.local');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin1234'));
        $manager->persist($admin);

        // Staff
        $staff = new User();
        $staff->setEmail('staff@ticket.local');
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setPassword($this->passwordHasher->hashPassword($staff, 'staff1234'));
        $manager->persist($staff);

        // Tickets
        $descriptions = [
            'Le site ne charge plus depuis ce matin, impossible d\'accéder à mon espace client.',
            'La fonctionnalité d\'export PDF ne fonctionne plus depuis la mise à jour.',
            'Nous souhaiterions ajouter un module de statistiques sur le tableau de bord.',
            'Une anomalie a été détectée dans le calcul des totaux de factures.',
            'Pouvez-vous nous informer des prochaines maintenances prévues ?',
        ];

        foreach ($descriptions as $i => $desc) {
            $ticket = new Ticket();
            $ticket->setAuteur('client' . ($i + 1) . '@example.com');
            $ticket->setDescription($desc);
            $ticket->setCategorie($categories[$i % count($categories)]);
            $ticket->setEtat($etats[0]);
            $ticket->setResponsable($staff);
            $manager->persist($ticket);
        }

        $manager->flush();
    }
}