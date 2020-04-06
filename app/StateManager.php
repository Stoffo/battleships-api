<?php


namespace App;


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

    public function getPlayerGrid()
    {
        $savedState = $this->getObjectByPath($this->playerGridPath);

        return $savedState instanceof Grid ? $savedState : new Grid();
    }

    public function getEnemyGrid()
    {
        $savedState = $this->getObjectByPath($this->enemyGridPath);

        return $savedState instanceof Grid ? $savedState : $this->gridFactory::create();
    }

    public function getObjectByPath($path): ?Grid
    {
        if (file_exists($path)) {
            return unserialize(file_get_contents($path));
        }

        return null;
    }

    public function savePlayerGrid(Grid $grid)
    {
        $this->serializeObjectAndSave($grid, $this->playerGridPath);
    }

    public function saveEnemyGrid(Grid $grid)
    {
        $this->serializeObjectAndSave($grid, $this->enemyGridPath);
    }

    private function serializeObjectAndSave(Grid $grid, string $path)
    {
        $serializedPlayerGrid = serialize($grid);
        file_put_contents($path, $serializedPlayerGrid);
    }

    public function reset()
    {
        unlink($this->playerGridPath);
        unlink($this->enemyGridPath);
    }
}