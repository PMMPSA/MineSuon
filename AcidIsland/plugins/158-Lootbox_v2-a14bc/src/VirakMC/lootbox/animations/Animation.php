<?php

declare(strict_types=1);

namespace VirakMC\lootbox\animations;

use VirakMC\lootbox\animations\types\ChooseAnimation;
use VirakMC\lootbox\animations\types\DisplayAnimation;
use VirakMC\lootbox\animations\types\PlainAnimation;
use VirakMC\lootbox\animations\types\RollAnimation;
use VirakMC\lootbox\animations\types\SlideAnimation;
use VirakMC\lootbox\Loader;
use VirakMC\lootbox\reward\Reward;
use VirakMC\lootbox\task\AnimationTask;
use VirakMC\lootbox\types\Lootbox;
use pocketmine\Player;
use pocketmine\plugin\PluginException;
use pocketmine\scheduler\Task;

abstract class Animation {

    /** @var Player */
    protected $owner;

    /** @var Reward[] */
    protected $rewards = [];

    /** @var int */
    protected $ticks = 0;

    /**
     * Animation constructor.
     *
     * @param Player $owner
     * @param Reward[] $rewards
     */
    public function __construct(Player $owner, array $rewards) {
        $this->owner = $owner;
        $this->rewards = $rewards;
    }

    /**
     * @param Task $task
     */
    public function tick(Task $task): void {
        if($this->owner === null or $this->owner->isOnline() === false) {
            Loader::getInstance()->getScheduler()->cancelTask($task->getTaskId());
            return;
        }
        $this->ticks++;
    }

    /**
     * @param int $loop
     *
     * @return Reward
     */
    public function getReward(int $loop = 0): Reward {
        $chance = mt_rand(0, 100);
        $reward = $this->rewards[array_rand($this->rewards)];
        if($loop >= 10) {
            return $reward;
        }
        if($reward->getChance() <= $chance) {
            return $this->getReward($loop + 1);
        }
        return $reward;
    }

    /**
     * @param Player $player
     * @param Lootbox $lootbox
     */
    public static function startAnimation(Player $player, Lootbox $lootbox): void {
        switch($lootbox->getAnimationType()) {
            case "roll":
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new AnimationTask(new RollAnimation($player, $lootbox->getRewards())), 1);
                break;
            case "plain":
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new AnimationTask(new PlainAnimation($player, $lootbox->getRewards())), 1);
                break;
            case "slide":
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new AnimationTask(new SlideAnimation($player, $lootbox->getRewards())), 1);
                break;
            case "display":
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new AnimationTask(new DisplayAnimation($player, $lootbox->getRewards())), 1);
                break;
            default:
                throw new PluginException("Invalid animation type: \"{$lootbox->getAnimationType()}\" in lootbox \"{$lootbox->getIdentifier()}\"");
                break;
        }
    }
}