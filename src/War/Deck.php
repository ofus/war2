<?php
/**
 * Author: Andrew Joseph
 */

namespace War;

class Deck
{
    /** @var Array  */
    protected $cards;

    /**
     * Assign cards to the deck and shuffle them
     */
    public function __construct()
    {
        $this->cards = array_map(
            function ($card_index) {
                return $card_index % 13;
            },
            range(0, 51)
        );
        shuffle($this->cards);
    }

    /**
     * Shuffle the deck
     * @return Deck
     */
    public function shuffle()
    {
        shuffle($this->cards);
        return $this;
    }

    /**
     * @return array
     */
    public function getCards()
    {
        return $this->cards;
    }

}

