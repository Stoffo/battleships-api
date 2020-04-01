<?php


namespace App\Http\Controllers;

use Faker\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class BattleshipController extends BaseController
{
    public function fireShot(Request $request)
    {
        $this->validate($request, [
            'x' => 'required|integer|min:1|max:10',
            'y' => 'required|integer|min:1|max:10',
        ]);

        $f = Factory::create(app()->getLocale());


        return new JsonResponse([
            'game_over' => false,
            'hit' => $f->boolean,
            'sunk' => $f->boolean,
            'enemy_shot' => [
                'x' => $f->numberBetween(1, 10),
                'y' => $f->numberBetween(1, 10),
            ],
        ]);
    }
}
