<?php

namespace LetTIHL;

use pocketmine\{Server, Player};
use pocketmine\command\{ConsoleCommandSender, Command, CommandSender};
use pocketmine\event\{Listener, block\BlockBreakEvent};
use pocketmine\utils\Config;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\plugin\PluginBase;

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getLogger()->info("\n\n\n\n§l§a> §bPlugin By LetTIHL\n\n\n\n");
@mkdir($this->getDataFolder(), 0744, true);
$this->atv1 = new Config($this->getDataFolder()."autov1.yml",Config::YAML);
$this->atv2 = new Config($this->getDataFolder()."autov2.yml",Config::YAML);
}

public function onJoin(PlayerJoinEvent $ev) {
if(!$this->atv1->exists($ev->getPlayer()->getName())) {
$this->atv1->set($ev->getPlayer()->getName(), 0);
$this->atv1->save();
    }
if(!$this->atv2->exists($ev->getPlayer()->getName())) {
$this->atv2->set($ev->getPlayer()->getName(), 0);
$this->atv2->save();
    }
}

public function onQuit(PlayerQuitEvent $ev) {
$this->atv1->save();
$this->atv2->save();
}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "autosell":
            $this->auto($sender);
            return true;
        }
        return true;
	}
		
	 public function auto($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
     if($this->atv1->get($sender->getPlayer()->getName()) == 1) {
     $this->atv1->set($sender->getPlayer()->getName(), ($this->atv1->get($sender->getPlayer()->getName()) - 1));
			$sender->sendMessage("§2§lAuto Sell Full §fOff ");
}elseif($this->atv1->get($sender->getPlayer()->getName()) == 0) {
     $this->atv1->set($sender->getPlayer()->getName(), ($this->atv1->get($sender->getPlayer()->getName()) + 1));
			$sender->sendMessage("§2§lAuto Sell Full §fBật ");
     }
     if($this->atv2->get($sender->getPlayer()->getName()) == 1) {
      $this->atv2->set($sender->getPlayer()->getName(), ($this->atv2->get($sender->getPlayer()->getName()) - 1));
     }
              break;
              case 2:
     if($this->atv2->get($sender->getPlayer()->getName()) == 1) {
      $this->atv2->set($sender->getPlayer()->getName(), ($this->atv2->get($sender->getPlayer()->getName()) - 1));
			$sender->sendMessage("§2§lAuto Sell Speed §fTắc");
}elseif($this->atv2->get($sender->getPlayer()->getName()) == 0) {
        $this->atv2->set($sender->getPlayer()->getName(), ($this->atv2->get($sender->getPlayer()->getName()) + 1));
			$sender->sendMessage("§2§lAuto Sell Speed §fBẬT");
     }
     if($this->atv1->get($sender->getPlayer()->getName()) == 1) {
      $this->atv1->set($sender->getPlayer()->getName(), ($this->atv1->get($sender->getPlayer()->getName()) - 1));
     }
              break;

         }
        });
     if($this->atv2->get($sender->getPlayer()->getName()) == 0) {
     $atv2 = "tắt";
    }elseif($this->atv2->get($sender->getPlayer()->getName()) == 1) {
     $atv2 = "bật";
     }
     if($this->atv1->get($sender->getPlayer()->getName()) == 0) {
     $atv1 = "tắt";
    }elseif($this->atv1->get($sender->getPlayer()->getName()) == 1) {
     $atv1 = "bật";
     }
        $form->setTitle("§3§l§oAuTO SeLl");
		$form->addButton("§7》§c§lThoát§r§7《");
		$form->addButton("§2§l§oAuto Sell Full §6[§f".$atv1."§6]");
		$form->addButton("§2§l§oAuto Sell Speed §6[§f".$atv2."§6]");
        $form->sendToPlayer($sender);
	}
		
	public function onBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
     if($this->atv2->get($event->getPlayer()->getName()) == 1) {
        $this->getServer()->getCommandMap()->dispatch($player,"sell all");
	    }elseif($this->atv1->get($event->getPlayer()->getName()) == 1) {
		foreach($event->getDrops() as $drop) {
			if(!$player->getInventory()->canAddItem($drop)) {
				$event->getPlayer()->addTitle("§l§a FULL Đồ Rùi Hihi", "§l§cTự động bán!");
                $this->getServer()->getCommandMap()->dispatch($player,"sell all");

            }

				break; 
			}
		}
	}
}
