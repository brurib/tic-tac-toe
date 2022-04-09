<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Move;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Exception\InvalidMoveException;

#[AsController]
class PerformMove extends AbstractController
{
    public function __invoke(ManagerRegistry $doctrine, Move $data): Game
    {
        $entityManager = $doctrine->getManager();
        $game = $entityManager->getRepository(Game::class)->find($data->getGameId());
        if($game == null){
            throw new InvalidMoveException('Invalid move: game not found');
        }
        $game->move($data);
        $entityManager->flush();
        return $game;
    }
}