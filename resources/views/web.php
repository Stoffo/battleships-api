<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Code Challenge - Battle Ships</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        html {
            background-color: #3c3f41;
            margin: 20px;
            color: #adadad;
        }

        .grid {
            float: left;
        }

        .grid-player {
            margin-right: 30px;
        }

        .grid-cell {
            float: left;
            background-color: royalblue;
            border: 1px solid black;
            height: 60px;
            width: 60px;
            box-sizing: border-box;
        }

        .enemy-cell:hover {
            background-color: dodgerblue;
            cursor: crosshair;
        }

        .player-cell .boat {
            background-color: ;
        }

        .hit {
            background-color: darkgrey;
        }

        .miss {
            background-color: white;
        }

        .sunk {
            background-color: red;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<body>

<h1>Battleships</h1>

<div class="grid grid-player">

    <h2>Player</h2>

    <?php

    for ($x = 1; $x <= 10; $x++) {
        echo '<div style="clear: both">';
        echo PHP_EOL;

        for ($y = 1; $y <= 10; $y++) {
            echo "\t";
            echo '<div class="grid-cell player-cell" data-x="' . $x . '" data-y="' . $y . '"></div>';
            echo PHP_EOL;
        }

        echo "</div>\n";
        echo PHP_EOL;
    }

    ?>
</div>

<div class="grid grid-enemy">
    <h2>Enemy</h2>

    <?php

    for ($x = 1; $x <= 10; $x++) {
        echo '<div style="clear: both">';
        echo PHP_EOL;

        for ($y = 1; $y <= 10; $y++) {
            echo "\t";
            echo '<div class="grid-cell enemy-cell" data-x="' . $x . '" data-y="' . $y . '"></div>';
            echo PHP_EOL;
        }

        echo "</div>\n";
    }

    ?>
</div>

<div>
    <button>Place Ships randomly</button>
    <button>Restart</button>
</div>

<script>
    (function () {
        const CSS_HIT = 'hit';
        const CSS_MISS = 'miss';
        const CSS_SUNK = 'sunk';

        function Battleships() {
            this.ships = {
                "destroyer": {"length": 2, "x": null, "y": null, "direction": null},
                "submarine": {"length": 3, "x": null, "y": null, "direction": null},
                "cruiser": {"length": 3, "x": null, "y": null, "direction": null},
                "battleship": {"length": 4, "x": null, "y": null, "direction": null},
                "carrier": {"length": 5, "x": null, "y": null, "direction": null},
            };
        }

        Battleships.prototype.placeShip = function (x, y, type) {

        };

        Battleships.prototype.fire = function (element) {
            let x = element.attr('data-x');
            let y = element.attr('data-y');
            let requestData = {"x": x, "y": y};

            $.post({
                url: '/api/fire',
                type: "post",
                data: JSON.stringify(requestData),
                dataType: 'json',
                contentType: "application/json",
                success: function (rsp) {
                    console.info('Shot fired!');
                    console.debug(rsp);

                    if (rsp.hit === true) {
                        if (rsp.sunk === true) {

                            element.addClass(CSS_SUNK);

                            console.info("Enemy Boat sunk!")
                        }

                        element.addClass(CSS_HIT);
                        console.info("Enemy Boat hit!");
                    } else {
                        element.addClass(CSS_MISS);
                    }

                    battleships.markEnemyShot(rsp.enemy_shot.x, rsp.enemy_shot.y);
                },
                error: function (rsp) {
                    console.error(rsp);
                    alert(rsp.statusText + "\nThere is something wrong with the server!\n Check the console.")
                }
            });
        };

        Battleships.prototype.markEnemyShot = function (x, y) {
            //Check if hit or sunk
            $(".player-cell[data-x=" + x + "][data-y=" + y + "]").addClass("sunk").val("X")
        };

        let battleships = new Battleships();

        $(".enemy-cell").click(function (e) {
            let $clickedCell = $(e.target);

            if ($clickedCell.hasClass(CSS_SUNK) || $clickedCell.hasClass(CSS_HIT) || $clickedCell.hasClass(CSS_MISS)) {
                console.info('Field already played!');
                return;
            }

            battleships.fire($clickedCell);
        });
    }());
</script>
</body>
</html>
