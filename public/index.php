<?php

error_reporting(E_ALL | E_STRICT);

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    // dependencies were installed via composer - this is the main project
    $classLoader = require __DIR__ . '/../vendor/autoload.php';
} else {
    throw new \Exception('Can\'t find autoload.php.');
}

use War\Game;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
    <title>War</title>
</head>
<body>
<div>
    <comment>
        Personal note: This is only written in PHP because the original
        requirements for the project stipulated use of PHP, for proof of
        concept.  In the real world this should be written in something
        like C++ obviously :)
    </comment>
<pre>
/**
    Code Challenges:

    WAR:
    Design a class definition for a deck of playing cards, then write a
    simple program to simulate two players playing the game of War. The
    game and rules are described here:
    <a href="http://www.pagat.com/war/war.html">http://www.pagat.com/war/war.html</a>

    The program should:
    * Shuffle the virtual deck before every game
    * Deal 26 cards to each player
    * Display the cards that were played for each turn, who was the
      winner, and the running score.

 */
</pre>

<div>
    <h1>Codexorz</h1>
    <p>
        <a href="https://github.com/ofus/war2">https://github.com/ofus/war2</a>
    </p>
</div>

    <h1>Output</h1>
    <pre>
    <?php

    $game = new Game();
    echo "Begin!\n";
    for(;;) {
        $game->doRound();
        echo $game->getLog();
    }
    echo "Winner: " . $game->getWinner() . PHP_EOL;
    echo "Game over on turn #" . $game->getTurn() . PHP_EOL;

    ?>
    </pre>
</div>
</body>
</html>