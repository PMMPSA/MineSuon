<?php

declare(strict_types=1);

namespace VirakMC\lootbox\animations\types;

use VirakMC\lootbox\animations\Animation;
use VirakMC\lootbox\Loader;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class PlainAnimation extends Animation {

    /**
     * PlainAnimation constructor.
     *
     * @param Player $owner
     * @param array $rewards
     */
    public function __construct(Player $owner, array $rewards) {
        parent::__construct($owner, $rewards);
    }

    /**
     * @param Task $task
     */
    public function tick(Task $task): void {
        $reward = $this->getReward();
        $callable = $reward->getCallback();
        $callable($this->owner);
        $this->owner->addXp(1000000);
        $this->owner->subtractXp(1000000);
        Loader::getInstance()->getScheduler()->cancelTask($task->getTaskId());
    }
}