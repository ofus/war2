<?php
/**
 * Author: Andrew Joseph
 */

namespace War;

/**
 * Class Game
 * @package War
 */
class Game
{
    /** @var Array */
    protected $hands;

    /** @var String */
    protected $log;

    /** @var int */
    protected $turn;

    /** @var Deck */
    protected $deck;

    /** @var mixed */
    protected $winner;

    /**
     * Create a new game
     */
    public function __construct()
    {
        $this->deck = new Deck();
        $this->winner = -1;
        $this->turn = 0;
        $this->hands = array_chunk($this->deck->getCards(), 26);

    }

    /**
     * Get player in lead.  In a tie for lead, just get first player.
     * @return int
     */
    public function getPlayerAtLead()
    {
        $playerScore[0] = count($this->hands[0]);
        $playerScore[1] = count($this->hands[1]);
        if ($playerScore[0] == $playerScore[1]) {
            $this->winner = -1;
            $leader = 0;
        } else {
            $this->winner = ($playerScore[0] > $playerScore[1]) ? 0 : 1;
            $leader = $this->winner;
        }
        return $leader;
    }

    /**
     * Is the game over?
     * @return bool true when game is over
     */
    public function isGameOver()
    {
        return (empty($this->hands[0]) || empty($this->hands[1]));
    }

    /**
     * Get the current turn #
     * @return int
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Fetch logs and purge
     * @return String
     */
    public function getLog()
    {
        $tmp = $this->log;
        $this->log = '';
        return $tmp;
    }

    /**
     * Queue a log entry to be displayed
     * @param string $msg log message
     */
    public function log($msg)
    {
        $this->log .= "#{$this->turn} SCORE: P0="
            . $this->getScore(0) . ", P1=" . $this->getScore(1) . ")\t$msg\n";
    }

    /**
     * @param int $player
     * @return int player's score
     */
    public function getScore($player)
    {
        return count($this->hands[$player]);
    }

    /**
     * Fetch the winning player if game is over
     * @return string or false if game not over
     */
    public function getWinner()
    {
        if (!$this->isGameOver()) {
            return false;
        }
        $this->getPlayerAtLead();
        if ($this->winner === -1) {
            return "tie";
        }
        return "Player " . $this->winner;
    }

    /**
     * Execute the next round
     * @return bool true if game is not yet over
     */
    public function doRound()
    {
        if ($this->isGameOver()) {
            return false;
        }
        $this->turn++;
        $card0 = $this->draw(0);
        $card1 = $this->draw(1);

        $this->log("Draw:"
            . " (P0)" . self::cardToString($card0)
            . " (P1)" . self::cardToString($card1)
        );

        if ($card0 == $card1) { // if card values are a tie, there is a war
            $this->log("WAR with " . $this->cardToString($card0) . "s, draw again.");
            $pot = Array($card0, $card1);

            for ($i = 0; $this->isGameOver() == false; $i++) {
                $card0 = $this->draw(0);
                $card1 = $this->draw(1);
                array_push($pot, $card0, $card1);

                /* rules specify that one of the cards removed will not be
                used to determine round winner */
                if (($i % 2 == 0)) {
                    continue;
                }

                $msg = "Draw:"
                    . " (P0)" . self::cardToString($card0)
                    . " (P1)" . self::cardToString($card1);

                if (($card0 == $card1)) {
                    $this->log($msg . " Tie. WAR CONTINUES!");
                    continue; // continue if tie or for every other card draw
                }

                // round won, game continues
                $winner = ($card0 > $card1) ? 0 : 1;
                $this->addCards($winner, $pot);
                $this->log($msg . " Player $winner wins round and takes the pot.");
                return true;
            }
            $this->log("a player has run out of cards... game over!");
            return false;

        } else { // no war
            $winner = ($card0 > $card1) ? 0 : 1;
            $this->addCards($winner, Array($card0, $card1));
            $this->log("Player $winner wins round and collects both cards.");
        }
        return true;
    }

    /**
     * Draw a card out of a player's hand
     * @param int $player
     * @return int card
     */
    protected function draw($player)
    {
        return array_pop($this->hands[$player]);
    }

    /**
     * Add cards to the beginning of a player's hand
     * @param int   $player
     * @param array $cards
     */
    protected function addCards($player, Array $cards)
    {
        foreach ($cards as $card) {
            array_unshift($this->hands[$player], $card);
        }
    }

    /**
     * Determine and convert a card's value as a string
     * @param int $value
     * @return string
     */
    public function cardToString($value)
    {
        $values = self::getValues(true);
        if (is_array($value)) {
            $str = '';
            foreach($value as $k => $v) {
                $str .= "[$k]" . $values[$v] . ' ';
            }
            return rtrim($str);
        }
        return $values[$value];
    }

    /** Get the values used for converting internally stored card value to displayed value
     * format
     * note: a deuce or a "2" is stored as a 0 internally, a "3" as a 1, etc
     * @param bool $compact should array be compact or full text
     * @return array card output values
     */
    public static function getValues($compact = false){
        if ($compact == true) {
            return Array(0 => "2", "3", "4", "5", "6", "7", "8", "9", "T", "J", "Q", "K", "A");
        }
        return Array(0 => "deuce", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "jack", "queen", "king", "ace");
    }

}

