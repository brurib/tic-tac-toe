<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Controller\PerformMove;
use App\Entity\Game;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'controller' => PerformMove::class,
            'input' => Move::class,
            'output' => Game::class,
            'normalization_context' => ['groups' => 'game:list'],
            'openapi_context' => [
                'description' => "Performs a move in a specified game for a specific player.",
                'summary' => "Performs a move in a specified game."
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'controller' => SomeRandomController::class
        ]
    ],
    order: ['id' => 'DESC'],
    formats: ['json' => ['application/json']],
    paginationEnabled: false
)]
class Move
{
    #[ApiProperty(
        identifier: true,
        )]
    private int $game_id = 0;

    private int $row = 0;

    private int $column = 0;

    private int $player = 0;
    

    
    public function getGameId(): ?int
    {
        return $this->game_id;
    }

    public function setGameId(int $id): self
    {
        $this->game_id = $id;
        return $this;
    }

    public function getRow(): ?int
    {
        return $this->row;
    }

    public function setRow(int $row): self
    {
        $this->row = $row;
        return $this;
    }

    public function getColumn(): ?int
    {
        return $this->column;
    }

    public function setColumn(int $column): self
    {
        $this->column = $column;
        return $this;
    }

    public function getPlayer(): ?int
    {
        return $this->player;
    }

    public function setPlayer(int $player): self
    {
        $this->player = $player;
        return $this;
    }
}