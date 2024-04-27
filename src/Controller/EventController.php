<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\EventParticipant;
use App\Entity\ScoreType;
use App\Service\LivescoreService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/events/today', name: 'events_today', methods: ['GET'])]
    public function todayEvents(EntityManagerInterface $em): JsonResponse
    {
        $events = $em->getRepository(Event::class)->findAllToday();

        $eventsArray = array_map(function ($event) {
            return [
                'event_id' => $event['event_id'],
                'event' => $event['sport_name'],
                'area_name' => $event['area_name'],
                'roomUrl' => "{$event['sport_name']}#sport={$event['sport_name']}",
            ];
        }, $events);

        return new JsonResponse($eventsArray);
    }

    #[Route('/events/streaming/{id}', name: 'events_streaming', methods: ['GET'])]
    public function streaming(int $id, EntityManagerInterface $em): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        if($event->getSport()->getScoreType()->getName() == ScoreType::$score_type_point) {
            $videoId = "pVIAx16odCs";
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
                'eventId' => $event->getId(),
                'teamOne' => $event->getEventParticipants()[0],
                'teamTwo' => $event->getEventParticipants()[1],
            ]);
        } else {
            $eventParticipants = $em->getRepository(EventParticipant::class)->findAllByEventOrdered($event->getId());

            return $this->render("event/rank.html.twig", [
                'eventId' => $event->getId(),
                'eventParticipants' => $eventParticipants,
            ]);
        }
    }

    #[Route('/events/{id}/update', name: 'events_update', methods: ['GET'])]
    public function update(int $id, EntityManagerInterface $em, LivescoreService $LS): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        $now = new DateTime();
        if ($event->getDateStart() && $event->getDateEnd()) {
            if ($now >= $event->getDateStart() && $now <= $event->getDateEnd()) {
                $LS->randomizeScore($event);
            }
        }

        if($event->getSport()->getScoreType()->getName() == ScoreType::$score_type_point) {
            return $this->render("_components/score-skeleton.html.twig", [
                'teamOne' => $event->getEventParticipants()[0],
                'teamTwo' => $event->getEventParticipants()[1],
            ]);
        } else {
            $eventParticipants = $em->getRepository(EventParticipant::class)->findAllByEventOrdered($event->getId());

            $eventParticipants = $em->getRepository(EventParticipant::class)->findAllByEventOrdered($event->getId());
            return $this->render('_components/rank-skeleton.html.twig', [
                'eventParticipants' => $eventParticipants,
            ]);
        }
    }
}
