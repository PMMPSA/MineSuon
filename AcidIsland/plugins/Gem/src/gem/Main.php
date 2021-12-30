<?php

namespace gem;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\SingletonTrait;
Class Main extends PluginBase implements Listener{
	use SingletonTrait;
	
	public $gem;

	public function onLoad() :void{
		self::setInstance($this);
	}
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
       @mkdir($this->getDataFolder(), 0744, true);
       $this->gem = new Config($this->getDataFolder()."gem.yml",Config::YAML);		
    }
    public function onJoin(PlayerJoinEvent $ev) {
       
        if(!$this->gem->exists($ev->getPlayer()->getName())) {
            $this->gem->set($ev->getPlayer()->getName(), 0);
            $this->gem->save();
        }
    }
}