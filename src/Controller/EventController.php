<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\EventParticipant;
use App\Entity\ScoreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    #[Route('/events', name: 'events_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $events = $em->getRepository(Event::class)->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/events/streaming/{id}', name: 'events_streaming', methods: ['GET'])]
    public function streaming(int $id, EntityManagerInterface $em): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        if($event->getSport()->getScoreType()->getName() == ScoreType::$score_type_point) {
            $videoId = "wzkzCdaYEss";
        } else {
            $videoId = "cQWcVHdIHgA";
        }

        return $this->render("event/streaming.html.twig", [
            'videoId' => $videoId,
        ]);
    }

    #[Route('/events/{id}', name: 'events_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        if($event->getSport()->getScoreType()->getName() == ScoreType::$score_type_point) {
            return $this->render("event/score.html.twig", [
                'teamOne' => $event->getEventParticipants()[0],
                'teamTwo' => $event->getEventParticipants()[1],
            ]);
        } else {
            $eventParticipants = $em->getRepository(EventParticipant::class)->findAllByEventOrdered($event->getId());

            return $this->render("event/rank.html.twig", [
                'eventParticipants' => $eventParticipants,
            ]);
        }

        // return $this->render("event/$tpl.html.twig", [
        //     'event' => $event,
        // ]);
    }

    #[Route('/events/{id}/update', name: 'events_update', methods: ['POST'])]
    public function update(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        // Ici, tu peux ajouter la logique de mise à jour de l'événement
        // Par exemple, mise à jour de la date de début pour la simulation
        $event->setDateStart(new \DateTime()); // Juste un exemple de mise à jour

        $em->flush();

        return $this->redirectToRoute('events_show', ['id' => $id]);
    }
}
