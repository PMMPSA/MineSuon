<?php

namespace setitem;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};

class Main extends PluginBase implements Listener {
    
    
public function onEnable() {
		$this->getLogger()->info("§4Plugin setitem By LetTIHL");
$this->getServer()->getPluginManager()->registerEvents($this ,$this);
}
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "setitem":
                if($sender->isOp()){
                   $this->menu($sender);
                }
            return true;
        }
        return true;
	}
	
	    public function meny($sender){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
				    case 0:
				        $this->name($sender);
				    break;
					case 1:
					    $this->lore($sender);
					break;
				}
			});
			$form->setTitle("§l§f•§aPhó Bản§f•");
			$form->addButton("§l§f• §asetName 1§f •");
			$form->addButton("§l§f• §asetLore§f •");
	    }
	
	 public function name($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data == null)return;
            $item = $sender->getInventory()->getItemInhand()->setCustomName($data[0]);
            $sender->getInventory()->setItemInHand($item);
        });
        $form->setTitle("§3§lsetname");
		$form->addInput("§fname");
        $form->sendToPlayer($sender);
	}
	
	 public function lore($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data == null)return;
            $item = $sender->getInventory()->getItemInhand()->setLore(array($data[0]));
            $sender->getInventory()->setItemInHand($item);
        });
        $form->setTitle("§3§lsetlore");
		$form->addInput("§fname");
        $form->sendToPlayer($sender);
	}	
}















