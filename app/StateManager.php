<?php


namespace App;


/**
 * Class StateManager
 *
 * This class takes care of the states between requests.
 * For simplicity reasons we save the states in files.
 *
 * @package App
 */
class StateManager
{
    /**
     * @var string
     */
    private $playerGridPath;
    /**
     * @var string
     */
    private $enemyGridPath;
    /**
     * @var GridFactory
     */
    private $gridFactory;

    public function __construct(GridFactory $gridFactory)
    {
        $this->playerGridPath = storage_path('app/playergrid');
        $this->enemyGridPath = storage_path('app/enemygrid');
        $this->gridFactory = $gridFactory;
    }

    public function getPlayerGrid(): Grid
    {
        $savedState = $this->getObjectByPath($this->playerGridPath);

        if ($savedState) {
            return $savedState;
        }
        
        $newPlayerGrid = new Grid();
        $this->serializeObjectAndSave($newPlayerGrid, $this->playerGridPath);

        return $newPlayerGrid;
    }

    public function getEnemyGrid(): Grid
    {
        $savedState = $this->getObjectByPath($this->enemyGridPath);

        if ($savedState) {
            return $savedState;
        }

        $newEnemyGrid = $this->gridFactory::create();
        $this->serializeObjectAndSave($newEnemyGrid, $this->enemyGridPath);

        return $newEnemyGrid;
    }

    public function getObjectByPath($path): ?Grid
    {
        if (file_exists($path)) {
            return unserialize(file_get_contents($path));
        }

        return null;
    }

    public function savePlayerGrid(Grid $grid): void
    {
        $this->serializeObjectAndSave($grid, $this->playerGridPath);
    }

    public function saveEnemyGrid(Grid $grid): void
    {
        $this->serializeObjectAndSave($grid, $this->enemyGridPath);
    }

    private function serializeObjectAndSave(Grid $grid, string $path): void
    {
        $serializedPlayerGrid = serialize($grid);
        file_put_contents($path, $serializedPlayerGrid);
    }

    public function reset(): void
    {
        if (file_exists($this->playerGridPath)) {
            unlink($this->playerGridPath);
        }

        if (file_exists($this->enemyGridPath)) {
            unlink($this->enemyGridPath);
        }
    }
}