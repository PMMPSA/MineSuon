<?php

namespace phoban;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\entity\EntityDamageByEntityEvent;


class Main extends PluginBase implements Listener {
    
    
    public function onEnable() {
		$this->getLogger()->info("\n\n§4Plugin phó bản Code By LetTIHL\n\n");
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    }
    
    
    
 

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "phoban":
            $this->stone($sender);
            return true;
        }
        return true;
	}
	
    public function stone($sender){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
				    case 0:
                        $level = Server::getInstance()->getLevelByName("Arena1");
                        $sender->teleport(new Position(103, 82, 20, $level));				                               $sender->sendMessage("§l§abạn đã tiến phó bản");
				    break;
					case 1:
                        $level = Server::getInstance()->getLevelByName("Arena2");
                        $sender->teleport(new Position(103, 82, 20, $level));				                               $sender->sendMessage("§l§abạn đã tiến phó bản");
					break;
					case 2:
                        $level = Server::getInstance()->getLevelByName("Arena3");
                        $sender->teleport(new Position(103, 82, 20, $level));				                               $sender->sendMessage("§l§abạn đã tiến phó bản");					    
					break;
					case 3:
                        $level = Server::getInstance()->getLevelByName("Arena4");
                        $sender->teleport(new Position(103, 82, 20, $level));				                               $sender->sendMessage("§l§abạn đã tiến phó bản");					    
					break;
					case 4:
                        $level = Server::getInstance()->getLevelByName("Arena5");
                        $sender->teleport(new Position(103, 82, 20, $level));				                               $sender->sendMessage("§l§abạn đã tiến phó bản");					    
					break;    
				}
			});
			$form->setTitle("§l§f•§aPhó Bản§f•");
			$form->addButton("§l§f• §aPhó Bản 1§f •");
			$form->addButton("§l§f• §aPhó Bản 2§f •");
			$form->addButton("§l§f• §aPhó Bản 3§f •");
		     $form->addButton("§l§f• §aPhó Bản 4§f •");
			$form->addButton("§l§f• §aPhó Bản 5§f •");		     
			$form->sendToPlayer($sender);
    }
}