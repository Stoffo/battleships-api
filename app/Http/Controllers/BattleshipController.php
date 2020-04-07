<?php


namespace App\Http\Controllers;

use App\Contracts\ShipInterface;
use App\Grid;
use App\Services\BattleshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Laravel\Lumen\Routing\Controller as BaseController;

class BattleshipController extends BaseController
{
    /**
     * @var BattleshipService
     */
    private $service;

    public function __construct(BattleshipService $service)
    {
        $this->service = $service;
    }

    public function getPlayerGrid()
    {
        return new JsonResponse($this->service->getPlayerGrid()->getGridAsArray());
    }

    public function getEnemyGrid()
    {
        return new JsonResponse($this->service->getEnemyGrid()->getGridAsArray());
    }

    public function resetGame()
    {
        $this->service->resetGame();

        return new Response;
    }

    public function placeShip(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string',
            'x' => 'required|integer|min:1|max:' . Grid::GRID_SIZE,
            'y' => 'required|integer|min:1|max:' . Grid::GRID_SIZE,
            'direction' => ['required', 'string', Rule::in(
                ShipInterface::DIRECTION_RIGHT,
                ShipInterface::DIRECTION_DOWN)
            ],
        ]);

        $type = $request->get('type');
        $x = $request->get('x');
        $y = $request->get('y');
        $direction = $request->get('direction');

        $shipModel = $this->service->getShipModelByName($type);
        $ship = new $shipModel($x, $y, $direction);

        $this->service->getPlayerGrid()->placeShip($ship);

        return new Response('', 201);
    }

    public function fireShot(Request $request)
    {
        $this->validate($request, [
            'x' => 'required|integer|min:1|max:' . Grid::GRID_SIZE,
            'y' => 'required|integer|min:1|max:' . Grid::GRID_SIZE,
        ]);

        $result = $this->service->shoot($request->get('x'), $request->get('y'));

        return new JsonResponse($result);
    }
}
