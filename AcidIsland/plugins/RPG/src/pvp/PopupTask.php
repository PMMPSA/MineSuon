<?php

namespace pvp;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\Player;
use pvp\Main;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};

Class PopupTask extends Task{


    public function __construct(Main $plugin, Player $player){

        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function onRun($currentTick){
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
         if($player->isSurvival()){
         $s = $player->getMaxHealth() - $player->getHealth();
            if($s >= 20){
				return;
			  }	
				 if($s >0){
             $this->plugin->hp->set($player->getName(), ($this->plugin->hp->get($player->getName()) - $s));
             $player->setHealth($player->getMaxHealth());
               if($this->plugin->hp->get($player->getName()) <= 0){
                  $this->plugin->hp->set($player->getName(), ($this->plugin->maxhp->get($player->getName())));
		              $this->plugin->getServer()->getCommandMap()->dispatch($player,"ai home");
                  $player->setHealth(20);
                  $player->setFood(20);
                 $player->extinguish();
             $player->removeAllEffects();  	                  
			      $this->plugin->getServer()->broadcastMessage("§l§c".$player->getName()."§e đã chết do ma thuật"); 
               }
               }
            }
        }
	}
}