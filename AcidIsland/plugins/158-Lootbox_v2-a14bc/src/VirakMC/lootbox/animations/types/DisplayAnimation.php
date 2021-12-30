<?php

declare(strict_types=1);

namespace VirakMC\lootbox\animations\types;

use VirakMC\lootbox\animations\Animation;
use VirakMC\lootbox\Loader;
use VirakMC\lootbox\reward\ItemReward;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class DisplayAnimation extends Animation {

    /** @var ItemReward */
    private $itemEntity;

    /**
     * DisplayAnimation constructor.
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
        parent::tick($task);
        if($this->ticks === 1) {
            $reward = $this->getReward();
            $callable = $reward->getCallback();
            $callable($this->owner);
        }
        if($this->ticks === 3) {
            $reward = $this->getReward();
            $item = $reward->getItem();
            $directionVector = $this->owner->getDirectionVector();
            $this->itemEntity = $this->owner->getLevel()->dropItem($this->owner->add(0, 2, 0), $item, $directionVector->multiply(0.25), 1000);
            $this->itemEntity->setNameTag($reward->getName());
            $this->itemEntity->setNameTagVisible(true);
            $this->itemEntity->setNameTagAlwaysVisible(true);
            $this->owner->addXp(1000000);
            $this->owner->subtractXp(1000000);
        }
        if($this->ticks >= 40) {
            Loader::getInstance()->getScheduler()->cancelTask($task->getTaskId());
            if($this->itemEntity->isClosed() or $this->itemEntity->isFlaggedForDespawn()) {
                return;
            }
            $this->itemEntity->flagForDespawn();
        }
    }
}