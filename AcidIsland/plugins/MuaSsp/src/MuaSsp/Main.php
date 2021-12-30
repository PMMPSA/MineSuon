<?php

namespace MuaSsp;

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{TextFormat};
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use jojoe77777\FormAPI;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		$player = $sender->getPlayer();
		switch ($cmd->getName()){
			case "muassp":
			if(!($sender instanceof Player)){
				$this->getLogger()->notice("Please Dont Use that command in here.");
				return true;
			}
			$this->mainForm($player);
			break;
		}
		return true;
    }
    
	public function mainForm($player){

		$form = $this->formapi->createSimpleForm(function (Player $player, $result){

			if($result === null){
				return;
			}
			switch($result){
				case 0:
				    if($player->hasPermission("royalPass.Tungsten.Perm")){
				       $player->sendMessage("§l§cBạn đã mua Royal Pass rồi"); 
				       return;
				    }
			        if($this->point->myMoney($player) >= 50){
				        $player->sendMessage("§l§eBạn đã mua thành công §aRoyal Pass.");
				        $this->point->reduceMoney($player, 50);
				        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm " .  $player->getName() . " royalPass.Tungsten.Perm");
			        }else{
			          $player->sendMessage("§l§eBạn cần §a50 §epoint để mua Royal Pass này!");
			        }
				break;
				
		}
		});
		$form->setTitle("§laSeaonPass");
		$form->addButton("§l§3● §2Mua SeasonPass §3●");
		$form->sendToPlayer($player);
		
	}
 
}