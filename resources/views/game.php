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

        .log-container {
            color: red;
            border: 1px solid #adadad;
            float: left;
            width: 460px;
            height: 300px;
            overflow: auto;
        }

        .grid {
            float: left;
            padding: 10px;
        }

        .setup {
            padding: 10px;
        }

        .grid-player {
            margin-right: 30px;
        }

        .grid-cell {
            float: left;
            background-color: royalblue;
            border: 1px solid black;
            height: 50px;
            width: 50px;
            box-sizing: border-box;
        }

        .enemy-cell:hover {
            background-color: dodgerblue;
            cursor: crosshair;
        }

        .ship {
            background-color: #696c6e;
        }

        .hit {
            background-color: red;
        }

        .hit:hover {
            background-color: red;
            cursor: not-allowed;
        }

        .miss {
            background-color: white;
        }

        .miss:hover {
            cursor: not-allowed;
            background-color: white;
        }

        .sunk {
            background-color: black;
        }

        .sunk:hover {
            cursor: not-allowed;
            background-color: black;
        }

        input.coordinates {
            width: 40px;
        }

        form > label {
            width: 90px;
            display: inline-block;
        }
    </style>
</head>

<body>

<h1>Battleships</h1>

<div class="grid grid-player">

    <h2>Player</h2>

    <?php

    use App\Contracts\ShipInterface;
    use App\Grid;
    use App\Models\Battleship;
    use App\Models\Carrier;
    use App\Models\Cruiser;
    use App\Models\Destroyer;
    use App\Models\Submarine;

    for ($x = 1; $x <= Grid::GRID_SIZE; $x++) {
        echo '<div style="clear: both">';
        echo PHP_EOL;

        for ($y = 1; $y <= Grid::GRID_SIZE; $y++) {
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

    for ($x = 1; $x <= Grid::GRID_SIZE; $x++) {
        echo '<div style="clear: both">';
        echo PHP_EOL;

        for ($y = 1; $y <= Grid::GRID_SIZE; $y++) {
            echo "\t";
            echo '<div class="grid-cell enemy-cell" data-x="' . $x . '" data-y="' . $y . '" title="x:' . $x . ' y:' . $y . '"></div>';
            echo PHP_EOL;
        }

        echo "</div>\n";
    }

    ?>
</div>

<div class="setup">
    <h2>Setup</h2>
    <?php

    $ships = [
        Destroyer::class,
        Cruiser::class,
        Battleship::class,
        Submarine::class,
        Carrier::class
    ];
    foreach ($ships as $ship) {
        $model = new $ship(1, 1, ShipInterface::DIRECTION_RIGHT);
        $name = $model->getName();
        $length = $model->getLength();

        echo <<<html
    <div>
        <form class="setup-ship" id="$name">
            <label for="$name">$name</label>
            <input type="hidden" name="type" value="$name">
            <input type="hidden" name="length" value="$length">
            <input type="number" required autocomplete="off" min="1" max="10" placeholder="x" name="x"
                   class="coordinates">
            <input type="number" required autocomplete="off" min="1" max="10" placeholder="y" name="y"
                   class="coordinates">
            <label for="direction-right" style="width: 40px">right</label>
            <input type="radio" id="direction-right" checked name="direction" value="right" checked>
            <label for="direction-down" style="width: 40px">down</label>
            <input type="radio" id="direction-down" name="direction" value="down">
            <button>Place Ship</button>
        </form>
    </div>
html;
    }
    ?>
</div>

<div class="log-container">
    <h3>Errors & Infos</h3>
    <div class="log"></div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    (function () {
        const CSS_HIT = "hit";
        const CSS_MISS = "miss";
        const CSS_SUNK = "sunk";

        function Battleships() {
            this.init();
        }

        Battleships.prototype.init = function () {
            this.resetGame();

            self = this;
            $.post({
                url: "/api/grids/player",
                type: "get",
                success: function (rsp) {
                    self.populateGrid(rsp);
                }
            });
        };

        Battleships.prototype.getCellByCoordinate = function (x, y) {
            return $(".player-cell[data-x=" + x + "][data-y=" + y + "]");
        };

        Battleships.prototype.populateGrid = function (data) {
            console.log("populating player grid...");

            self = this;
            Object.values(data).forEach(function (cellValue, x) {
                x++;
                Object.values(cellValue).forEach(function (shipOnCell, y) {
                    y++;
                    if (shipOnCell) {
                        self.getCellByCoordinate().addClass('ship');
                    }
                });
            });
        };

        Battleships.prototype.drawPlayerShip = function (shipData) {
            for (let i = 0; i < shipData.length; i++) {
                let $cell;
                if (shipData.direction === "right") {
                    $cell = this.getCellByCoordinate(shipData.x, shipData.y + i);
                } else {
                    $cell = this.getCellByCoordinate(shipData.x + i, shipData.y);
                }

                $cell.addClass("ship");
            }
        };

        Battleships.prototype.placeShip = function (shipData) {
            self = this;

            return $.post({
                url: '/api/place-ship',
                type: 'post',
                data: JSON.stringify(shipData),
                contentType: 'application/json',
                success: function () {
                    self.drawPlayerShip(shipData);
                }
            });
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

                    if (rsp.player.hit === true) {
                        if (rsp.player.sunk === true) {
                            alert("Enemy Boat sunk!")
                        }
                        element.removeClass(CSS_MISS).addClass(CSS_HIT);
                    } else {
                        element.addClass(CSS_MISS);
                    }

                    if (rsp.enemy.lost_game === true) {
                        alert("Congratulations! You won the game!")
                    }

                    if (rsp.player.lost_game === true) {
                        alert("You lost!")
                    }

                    battleships.markEnemyShot(rsp.enemy.x, rsp.enemy.y, rsp.enemy.hit, rsp.enemy.sunk);
                }
            });
        };

        Battleships.prototype.markEnemyShot = function (x, y, hit, sunk) {
            //Check if hit or sunk
            let $cell = this.getCellByCoordinate(x, y);

            if (hit) {
                $cell.addClass('hit')
            }
            if (sunk) {
                $cell.addClass('sunk');
            } else {
                $cell.addClass('miss');
            }
        };

        Battleships.prototype.resetGame = function () {
            $.post({
                url: '/api/reset',
                type: 'post',
                success: function () {
                    console.log("reset last game state, ready to go!")
                }
            });
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

        $("form.setup-ship button").click(function (e) {
            e.preventDefault();

            let $button = $(e.target);
            let shipData = {};

            //get form as object
            $button.parent().serializeArray().forEach(function (data) {
                if (data.name === "length" || data.name === "x" || data.name === "y") {
                    data.value = parseInt(data.value);
                }
                shipData[data.name] = data.value;
            });

            battleships.placeShip(shipData)
                .done(function () {
                    $button.prop("disabled", true).html("Ship placed!");
                })
        });

        $(document).ajaxError(function (event, rsp) {
            if (rsp.responseJSON.hasOwnProperty("message")) {
                $(".log").prepend(rsp.responseJSON.message + '<br>');
                return;
            }

            let errorMessage = "";
            Object.values(rsp.responseJSON).map(function (message, x) {
                errorMessage += message + '\n';
            });

            $(".log").prepend(errorMessage + '<br>');
        });
    }());
</script>
</body>
</html>
