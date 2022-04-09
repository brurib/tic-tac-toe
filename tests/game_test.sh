#!/bin/bash
create_game () {
    curl -s -X 'POST' 'http://127.0.0.1:8000/games' -H 'accept: application/json' -H 'Content-Type: Empty' -d 'Unknown Type: none' | jq -r '.id'
}

get_game () {
    curl -s -X 'GET' "http://127.0.0.1:8000/games/$1" -H 'accept: application/json'
}

delete_game () {
    curl -s -X 'DELETE' "http://127.0.0.1:8000/games/$1" -H 'accept: */*'
}

move () {
    curl -s -X 'POST' 'http://127.0.0.1:8000/moves' -H 'accept: application/json' -H 'Content-Type: application/json' \
    -d "{
    \"row\": $3,
    \"column\": $4,
    \"player\": $2,
    \"gameId\": $1
    }"
}

print_game () {
    local GAME=$1
    GAME_ID=$(echo $GAME | jq '.id')
    BOARD=$(echo $GAME | jq '.board')
    TURN=$(echo $GAME | jq '.turn')
    PLAYER_MOVED=$(echo $GAME | jq '.player_moved')
    IS_OVER=$(echo $GAME | jq '.is_over')
    WINNER=$(echo $GAME | jq '.winner')
    [ -z ${GAME_ID} ] || echo "GAME ID ${GAME_ID}"
    echo "___Turn ${TURN}___"
    echo $(echo $BOARD | jq '.[0]')
    echo $(echo $BOARD | jq '.[1]')
    echo $(echo $BOARD | jq '.[2]')
    echo "‾‾‾‾‾‾‾‾‾‾‾"
    
    [ ${PLAYER_MOVED} = 0 ] || echo "Last move from player ${PLAYER_MOVED}"
    echo "Game is over? ${IS_OVER}"
    echo "WINNER: ${WINNER}"

}

echo "===WINNING TEST==="
echo "\nCreating first game. Player 1 wins with tris in column."
GAME_ID=$(create_game)
[ ${GAME_ID} = 0 ] || echo "Game created"
GAME=$(get_game ${GAME_ID})
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 0 0)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 2 0)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 1 1)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 2 2)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 2 1)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 0 2)
print_game "$GAME"
#player 1 wins
GAME=$(move $GAME_ID 1 0 1)
print_game "$GAME"

delete_game $GAME_ID

echo "\nCreating second game. Player 2 wins with tris in row"
GAME_ID=$(create_game)
[ ${GAME_ID} = 0 ] || echo "Game created"
GAME=$(get_game ${GAME_ID})
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 0 0)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 2 1)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 0 1)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 2 2)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 0 2)
print_game "$GAME"

delete_game $GAME_ID

echo "\nCreating third game. Player 1 wins with diagonal tris"
GAME_ID=$(create_game)
[ ${GAME_ID} = 0 ] || echo "Game created"
GAME=$(get_game ${GAME_ID})
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 0 0)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 2 1)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 1 1)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 0 2)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 2 2)
print_game "$GAME"

delete_game $GAME_ID

echo "\nCreating fourth game. Tie game"
GAME_ID=$(create_game)
[ ${GAME_ID} = 0 ] || echo "Game created"
GAME=$(get_game ${GAME_ID})
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 1 1)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 2 0)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 0 0)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 2 2)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 2 1)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 0 1)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 0 2)
print_game "$GAME"
#player 2 moves
GAME=$(move $GAME_ID 2 1 2)
print_game "$GAME"
#player 1 moves
GAME=$(move $GAME_ID 1 1 0)
print_game "$GAME"

delete_game $GAME_ID