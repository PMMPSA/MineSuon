<?php

/*
*   _____      _ _ 
*  / ____|    | | |
* | (___   ___| | |
*  \___ \ / _ \ | |
*  ____) |  __/ | |
* |_____/ \___|_|_|
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*/

namespace SellHand;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TF;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Main extends PluginBase implements Listener{

     public $sell;
     
	public function onLoad(){
		$this->getLogger()->info("§eServer was running fast");
	}

	public function onEnable(){
    	$this->getLogger()->info(TF::GREEN.TF::BOLD."
   _____      _ _ 
  / ____|    | | |
 | (___   ___| | |
  \___ \ / _ \ | |
  ____) |  __/ | |
 |_____/ \___|_|_|
 Enabled Sell by PrinxIsLequit and georgianYT.
 		");
		$files = array("sell.yml", "messages.yml");
		foreach($files as $file){
			if(!file_exists($this->getDataFolder() . $file)) {
				@mkdir($this->getDataFolder());
				file_put_contents($this->getDataFolder() . $file, $this->getResource($file));
			}
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->sell = new Config($this->getDataFolder() . "sell.yml", Config::YAML);
		$this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
        $this->level = new Config($this->getDataFolder()."level.yml",Config::YAML);
        $this->kn = new Config($this->getDataFolder()."Kinhnghiem.yml",Config::YAML);
	}

	public function onDisable(){
    	$this->getLogger()->info("§cPlugin Disabled!");
  	}

    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->level->exists($ev->getPlayer()->getName())) {
$this->level->set($ev->getPlayer()->getName(), 1);
$this->level->save();
        }
        if(!$this->kn->exists($ev->getPlayer()->getName())) {
$this->kn->set($ev->getPlayer()->getName(), 0);
$this->kn->save();
        }
    }
   public function onQuit(PlayerQuitEvent $ev) {
     $this->kn->save();
     $this->level->save();
    }

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch(strtolower($cmd->getName())){
			case "sell":
			// Checks if command is executed by console.
			// It further solves the crash problem.
			if(!($sender instanceof Player)){
				$sender->sendMessage(TF::RED . TF::BOLD ."Error: ". TF::RESET . TF::DARK_RED ."Please use this command in game!");
				return true;
				break;
			}

				/* Check if the player is permitted to use the command */
				if($sender->hasPermission("sell") || $sender->hasPermission("sell.hand") || $sender->hasPermission("sell.all")){
					/* Disallow non-survival mode abuse */
					if(!$sender->isSurvival()){
						$sender->sendMessage(TF::RED . TF::BOLD ."Lỗi: ". TF::RESET . TF::DARK_RED ."§cHãy chuyển sang chế độ sinh tồn.");
						return false;
					}
					
           if(isset($args[0]) && strtolower($args[0]) == "info"){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
$this->uplevel($sender);
                  break;
}
 });
        $name = $sender->getPlayer()->getName();
        $level = $this->level->get($sender->getPlayer()->getName());
        $kn = $this->kn->get($sender->getPlayer()->getName());
        $uplevel = $level * 10000;
        $form->setTitle("§l§f•§binfo§f•");
          $form->setContent("§l§f•§bLevel§f: §a".$level."\n§f•§bExp§f: §a".$kn."§f/§a".$uplevel."\n§ebạn nhận thêm được ".$level." phần trăm khi sell");
        $form->addButton("§f•§c§lthoát§f•");
        $form->sendToPlayer($sender);
           }elseif(isset($args[0]) && strtolower($args[0]) == "all"){
						if(!$sender->hasPermission("sell.all")){
							$error_allPermission = $this->messages->get("error-nopermission-sellAll");
							$sender->sendMessage(TF::RED . TF::BOLD . "Lỗi: " . TF::RESET . TF::RED . $error_allPermission);
							return false;
						}
						$items = $sender->getInventory()->getContents();
                     	$xu = 0;
						foreach($items as $item){
							if($this->sell->get($item->getId()) !== null && $this->sell->get($item->getId()) > 0){
								$price = $this->sell->get($item->getId()) * $item->getCount();
	                             $xu = $xu + $price;						                                   $this->kn->set($sender->getName(), ($this->kn->get($sender->getName()) + $price));
	                             $this->kn->save();
								$sender->getInventory()->remove($item);
							}
						}
      $level = $this->level->get($sender->getName());
	$ss = 100 - $level;
	if($ss == 0 ){
   EconomyAPI::getInstance()->addMoney($sender, $xu);
	}
   $ud = ($xu * $ss)/100;
   $lx = $xu - $ud;
   if($level < 100){
       
						$sender->sendMessage("§f§lBán được §e".$xu. " §amoney §fvà được §b".$lx." §amoney§f từ sell level");
   }  
   if($level == 100) {
						$sender->sendMessage("§f§lBán được §e".$xu. " §amoney §fvà được §b".$lx." §amoney§f từ sell level");
   }
   EconomyAPI::getInstance()->addMoney($sender, $xu + $lx);
   $this->uplevel($sender);
					}elseif(isset($args[0]) && strtolower($args[0]) == "about"){
						$sender->sendMessage(TF::RED . TF::BOLD . "(!) " . TF::RESET . TF::GRAY . "Server sử dụng plugin Sell Hand, bởi Muqsit Rayyan và sửa bởi georgianYT Và Sửa Lại Lần 2 bởi LetTIHL");
					}else{
						$sender->sendMessage(TF::RED . TF::BOLD . "(!) " . TF::RESET . TF::DARK_RED . "§bCửa hàng bán đồ online");
						$sender->sendMessage(TF::RED . "§a- " . TF::DARK_RED . "§b/sell all " . TF::GRAY . "§7-> §l§eBán tất cả đồ có thể bán trong người.");
						$sender->sendMessage(TF::RED . "§a- " . TF::DARK_RED . "§b/sell info " . TF::GRAY . "§7-> §l§eXem thông tin của bạn.");
						return true;
					}
				}else{
					$error_permission = $this->messages->get("error-permission");
					$sender->sendMessage(TF::RED . TF::BOLD . "Lỗi: " . TF::RESET . TF::RED . $error_permission);
				}
				break;
		}
		return true;
	}
	public function uplevel($sender){
   $level = $this->level->get($sender->getName());
        if($level == 100){
           return; 
        }
   $kn = $this->kn->get($sender->getName());
       $this->kn->save();
       $uplevel = $level * 10000;
       if($kn > $uplevel ){
       $this->kn->set($sender->getName(), ($this->kn->get($sender->getName()) - $uplevel));
       $this->kn->save();
           if($level < 100){
       $this->level->set($sender->getName(), ($this->level->get($sender->getName()) + 1));
           }else{
               return;
           }
       $this->level->save();
      $level = $this->level->get($sender->getName());
							$sender->sendMessage("§b§lchúc mừng sell Level của bạn đã lên level ".$level);
							$this->uplevel($sender);
       }
	}
}
