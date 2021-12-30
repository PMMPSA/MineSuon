<?php

namespace VirakMC\lootbox;

use VirakMC\lootbox\command\glxCommand;
use VirakMC\lootbox\types\LootboxManager;
use VirakMC\lootbox\libs\muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

    /** @var self */
    private static $instance;

    /** @var LootboxManager */
    private $lootboxManager;

    public function onLoad() {
        if(!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        if(!is_dir($this->getDataFolder() . "lootboxes")) {
            mkdir($this->getDataFolder() . "lootboxes");
        }
        $this->saveResource("lootboxes" . DIRECTORY_SEPARATOR . "money.yml");
        $this->saveResource("lootboxes" . DIRECTORY_SEPARATOR . "box.yml");
        $this->saveResource("lootboxes" . DIRECTORY_SEPARATOR . "orebox.yml");
        $this->saveResource("lootboxes" . DIRECTORY_SEPARATOR . "rank.yml");
        self::$instance = $this;
    }

    public function onEnable() {
        $this->lootboxManager = new LootboxManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("hq", new glxCommand());
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
    }

    /**
     * @return self
     */
    public static function getInstance(): self {
        return self::$instance;
    }

    /**
     * @return LootboxManager
     */
    public function getLootboxManager(): LootboxManager {
        return $this->lootboxManager;
    }
}