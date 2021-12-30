<?php

namespace prisonmine;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\block\BlockPlaceEvent;

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->world = new Config($this->getDataFolder()."world.yml",Config::YAML);
       $this->id = new Config($this->getDataFolder()."id.yml",Config::YAML);
         $this->id->set("235"); 
         $this->id->save();
	    
	}
	public function onBlock(BlockPlaceEvent $ev){
        $p = $ev->getPlayer();
         $level = $this->getServer()->getPlayer($p->getName());
        $levelname = $level->getLevel()->getName();
        if(!$this->world->exists($levelname)){
                $this->world->set($levelname, "off"); 
                $this->world->save();
        }
        if($this->world->get($levelname) == "on"){
            $ev->setCancelled(true);
        }
    }
    
    
    public function onbreak(BlockBreakEvent $ev) {
        $id = $ev->getBlock()->getId();
        $p = $ev->getPlayer();
         $level = $this->getServer()->getPlayer($p->getName());
        $levelname = $level->getLevel()->getName();
        if(!$this->world->exists($levelname)){
                $this->world->set($levelname, "off"); 
                $this->world->save();
        }
        if($this->world->get($levelname) == "off"){
            return;
        }
        if($this->id->get($id) == null){
            $ev->setCancelled(true);
        }
    }
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "prmine":
                $this->menu($sender);
           return true;
        }
        return true;
	}
                
    public function menu($sender){
       if($sender->isOp()){}else{
           return;
       }
       $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
         $level = $this->getServer()->getPlayer($sender->getName());
        $levelname = $level->getLevel()->getName();
          switch($result){
              case 0:
            $this->world->set($levelname, "on"); 
            $this->world->save();
            $sender->sendMessage("§l§f[§bPrison §eMiner§f]§a Đã §fBật §aChế Độ Prison Miner");
              break;
              case 1:
              $this->world->set($levelname, "off"); 
              $this->world->save();
            $sender->sendMessage("§l§f[§bPrison §eMiner§f]§a Đã §fTắt §aChế Độ Prison Miner");
              break;
         }
        });
        $form->setTitle("§b§lPrison Mine");
		$form->addButton("§f•§e§lOn§f•");
		$form->addButton("§f•§e§lOff§f•");
        $form->sendToPlayer($sender);
	}
}
