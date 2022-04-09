<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GameRepository;
use App\Controller\CreateNewGame;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Exception\InvalidMoveException;
use App\Entity\Move;


#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'game:list']],
        'post' => [
            'normalization_context' => ['groups' => 'game:create'],
            'controller' => CreateNewGame::class,
            'read' => false,
            'deserialize' => false,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'Empty' => [
                            'schema' => ['type' => 'none']]
                    ]
                ]
            ]
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'game:item']],
        'delete' => ['normalization_context' => ['groups' => 'game:item']]
    ],
    order: ['id' => 'DESC'],
    formats: ['json' => ['application/json']],
    paginationEnabled: false
)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['game:list', 'game:item', 'game:create'])]
    private $id;

    #[ORM\Column(type: 'json', options: ['default' => '[[0,0,0],[0,0,0],[0,0,0]]'])]
    #[Groups(['game:list', 'game:item'])]
    private $board;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['game:list', 'game:item'])]
    private $turn;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['game:list', 'game:item'])]
    private $player_moved;

    #[ORM\Column(type: 'boolean', options: ['default' => FALSE])]
    #[Groups(['game:list', 'game:item'])]
    private $is_over;

    #[ORM\Column(type: 'string', length: 255, options: ['default' => 'NO'])]
    #[Groups(['game:list', 'game:item'])]
    private $winner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoard(): ?array
    {
        return $this->board;
    }

    public function setBoard(array $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getTurn(): ?int
    {
        return $this->turn;
    }

    public function setTurn(int $turn): self
    {
        $this->turn = $turn;

        return $this;
    }

    public function getPlayerMoved(): ?int
    {
        return $this->player_moved;
    }

    public function setPlayerMoved(int $player_moved): self
    {
        $this->player_moved = $player_moved;

        return $this;
    }

    public function getIsOver(): ?bool
    {
        return $this->is_over;
    }

    public function setIsOver(bool $is_over): self
    {
        $this->is_over = $is_over;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function move(Move $move): self
    {
        if ($this->validateMove($move->getRow(), $move->getColumn(), $move->getPlayer())){
            $this->board[$move->getRow()][$move->getColumn()] = $move->getPlayer();
            $this->setPlayerMoved($move->getPlayer());
            $this->setTurn($this->getTurn() + 1);
            
            if (!$this->checkIfAnySlotIsEmpty()){
                $this->setIsOver(true);
            }

            $this->setWinner($this->checkWinningPlayer());
            if($this->getWinner() != 'NO' && !$this->getIsOver()){
                $this->setIsOver(true);
            }
        }
        return $this;
    }

    private function validateMove(int $row, int $column, int $player): bool
    {
        // check if game is ended
        if ($this->getIsOver()){
            throw new InvalidMoveException(sprintf('Game %s is over', $this->id));
        }
        // check if player is valid
        if ($player < 1 || $player > 2){
            throw new InvalidMoveException(sprintf('Player %s is not valid! Tic-tac-toe is a 2 player game, use 1 or 2 as player', $player));
        }
        // check if player is allowed to move
        if ($this->getPlayerMoved() == $player){
            throw new InvalidMoveException(sprintf('Player %s already moved last turn', $player));
        }
        // check if move is within boundaries
        if ($row < 0 || $row > 2 || $column < 0 || $column > 2){
            throw new InvalidMoveException(sprintf('Row %s column %s is outside the 3x3 board: use values between 0 and 2 included', $row, $column));
        }
        // check if slot in the board is empty
        if ($this->getBoard()[$row][$column] != 0){
            throw new InvalidMoveException(sprintf('Row %s column %s is already occupied by player %s', $row, $column, $this->getBoard()[$row][$column]));
        }

        return true;
    }

    private function checkIfAnySlotIsEmpty(): bool
    {
        foreach ($this->getBoard() as $row){
            foreach ($row as $column){
                if($column == 0){
                    return true;
                }
            }
        }
        return false;
    }

    private function checkWinningPlayer(): string
    {
        //check rows winning
        foreach (array(0, 1, 2) as $row){
            if ($this->getBoard()[$row][0] != 0 && $this->getBoard()[$row][0] == $this->getBoard()[$row][1] && $this->getBoard()[$row][0] == $this->getBoard()[$row][2]){
                return sprintf('PLAYER %s', $this->getBoard()[$row][0]);
            }
        }
        //check columns winning
        foreach (array(0, 1, 2) as $column){
            if ($this->getBoard()[0][$column] != 0 && $this->getBoard()[0][$column] == $this->getBoard()[1][$column] && $this->getBoard()[0][$column] == $this->getBoard()[2][$column]){
                return sprintf('PLAYER %s', $this->getBoard()[0][$column]);
            }
        }
        //check diagonals winning
        if ($this->getBoard()[0][0] != 0 && $this->getBoard()[0][0] == $this->getBoard()[1][1] && $this->getBoard()[0][0] == $this->getBoard()[2][2]){
            return sprintf('PLAYER %s', $this->getBoard()[0][0]);
        }
        if ($this->getBoard()[0][2] != 0 && $this->getBoard()[0][2] == $this->getBoard()[1][1] && $this->getBoard()[0][2] == $this->getBoard()[2][0]){
            return sprintf('PLAYER %s', $this->getBoard()[0][0]);
        }
        return 'NO';
    }
}
