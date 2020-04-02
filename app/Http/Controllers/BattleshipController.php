<?php


namespace App\Http\Controllers;

use App\Grid;
use App\Services\BattleshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function getGrids()
    {
        return new JsonResponse([
            'player' => $this->service->getPlayerGrid()->getGrid(),
            'enemy' => $this->service->getPlayerGrid()->getGrid()
        ]);
    }

    public function resetGame()
    {
        $this->service->resetGame();

        return new JsonResponse(['success' => true]);
    }

    public function setUp(Request $request)
    {
        $this->validate($request, [
            'ships' => 'array|size:' . BattleshipService::MAX_SUM_SHIPS,
            'ships.*' => 'array'
        ]);
    }

    public function fireShot(Request $request)
    {
        $this->validate($request, [
            'x' => 'required|integer|min:1|max:' . Grid::GRID_SIZE,
            'y' => 'required|integer|min:1|max:' . Grid::GRID_SIZE,
        ]);

        $x = $request->get('x');
        $y = $request->get('y');

        $result = $this->service->shoot($x, $y);

        return new JsonResponse($result);
    }

    public function reset()
    {
        $this->service->reset();
    }
}
