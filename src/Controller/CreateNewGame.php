<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateNewGame extends AbstractController
{
    public function __invoke(ManagerRegistry $doctrine): Game
    {
        $entityManager = $doctrine->getManager();
        $new_game = new Game();
        $new_game->setBoard([[0,0,0],[0,0,0],[0,0,0]]);
        $new_game->setIsOver(false);
        $new_game->setWinner('NO');
        $new_game->setPlayerMoved(0);
        $new_game->setTurn(0);
        $entityManager->persist($new_game);
        $entityManager->flush();
        
        return $new_game;
    }
}