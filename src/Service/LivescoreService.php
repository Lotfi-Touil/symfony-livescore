<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\ScoreType;
use Doctrine\ORM\EntityManagerInterface;

class LivescoreService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function randomizeScore(Event $event)
    {
        if($event->getSport()->getScoreType()->getName() == ScoreType::$score_type_point) {
            $this->processScore($event);
        } else {
            $this->processClassement($event);
        }

        $this->em->flush();
    }

    private function randomTrueByPercent($percent) {
        return rand(1, 100) <= $percent;
    }

    private function processScore(Event $event)
    {
        $shouldUpdateScore = $this->randomTrueByPercent(25); // 25% de chance

        if ($shouldUpdateScore) {
            $EPs = $event->getEventParticipants();

            if ($this->randomTrueByPercent(50)) {
                $scoreToUpdate = $EPs[0]->getScore(); // Première équipe
            } else {
                $scoreToUpdate = $EPs[1]->getScore(); // Seconde équipe
            }

            $scoreToUpdate->setValue($scoreToUpdate->getValue() + 1);
        }
    }

    private function processClassement(Event $event)
    {
        $EPs = $event->getEventParticipants();

        foreach ($EPs as $ind => $eventParticipant) {
            if ($ind == 0) { // On met zéro pour le premier :)
                $eventParticipant->getScore()->setValue(0);
                continue;
            }
            $eventParticipant->getScore()->setValue($this->getRandomValue());
        }
    }

    private function getRandomValue() {
        return rand(1, 2000) / 100;
    }
}
