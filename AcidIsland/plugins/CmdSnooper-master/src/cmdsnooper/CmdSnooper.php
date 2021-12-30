<?php

namespace cmdsnooper;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class CmdSnooper extends PluginBase {
	public $snoopers = [];
	
	public function onEnable() {
		@mkdir($this->getDataFolder());
		
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
	  	"Console.Logger" => "true",
  		));
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args) : bool {	
		if(strtolower($cmd->getName()) == "theodoi") {
		 	if($sender instanceof Player) {
				if($sender->hasPermission("snoop.command")) {
					if(!isset($this->snoopers[$sender->getName()])) {
						$sender->sendMessage("§l§2[§4Snoop§2]> §fBạn Đã Bật Xem Lệnh Member Sài");
						$this->snoopers[$sender->getName()] = $sender;
						return true;
					} else {
						$sender->sendMessage("§l§2[§4Snoop§2]> §fBạn Đã Tắc Xem Lệnh Member Sài");
						unset($this->snoopers[$sender->getName()]);
						return true;
					}
				}
			}
		}
	}
 }
