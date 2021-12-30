<?php

namespace item;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    public function onEnable(){	
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    
    public function onUse(PlayerInteractEvent $ev){
    $api = $this->getServer()->getPluginManager()->getPlugin("RPG");        
    $player = $ev->getPlayer();
    $inv = $player->getInventory();
    $item = $inv->getItemInHand();
    $count = $item->getCount();
    $id = $item->getId();
    $customname = $item->getCustomName();
    if($id = 341)
    {
        if($customname == "§l§cĐá chữa trị")
        {
            $hp = $api->hp->get($player->getName());
            $maxhp= $api->maxhp->get($player->getName());
            if($hp == $maxhp)
            {
                return;
            }
            $hp_new = $hp + 5;
            if($hp_new > $maxhp){
               $hp_new = $maxhp; 

            }
            $api->hp->set($player->getName(), $hp_new);
	 $api->hp->save();
		$item->setCount($count -1);
            
			$inv->setItemInHand($item);
        }
        
        // add máu
        if($customname == "§l§cLinh động đan")
        {
			$maxhp= $api->maxhp->get($player->getName());
			$id = mt_rand(1,2);
			if($id == 1){
				$player->sendMessage("§l§a Sử dụng đan thành công +3 maxhp");
            $api->maxhp->set($player->getName(), $maxhp + 3); 
			}else{
				if($maxhp > 1){
				$player->sendMessage("§l§c Bạn bị bạo đan -2 maxhp");
				$api->maxhp->set($player->getName(), $maxhp - 2); 
				}
			}
             $api->maxhp->save();
			$item->setCount($count -1);
			$inv->setItemInHand($item);            
        }
        ///add giáp
        if($customname == "§l§cGân cốt đan")
        {
            $giap= $api->giap->get($player->getName());
           $id = mt_rand(1,2);
			if($id == 1){
				$player->sendMessage("§l§a Sử dụng đan thành công +3 giap");
            $api->giap->set($player->getName(), $giap + 3); 
			}else{
				if($giap > 1){
				$player->sendMessage("§l§c Bạn bị bạo đan -2 giap");
				$api->giap->set($player->getName(), $giap - 2); 
		        }
			}
            $api->giap->save();
			$item->setCount($count -1);
			$inv->setItemInHand($item);            
        }
        if($customname == "§l§cBạo nguyên đan")
        {
            $dame = $api->dame->get($player->getName());
           $id = mt_rand(1,2);
			if($id == 1){
				$player->sendMessage("§l§a Sử dụng đan thành công +3 dame");
            $api->dame->set($player->getName(), $dame + 3); 
			}else{
				if($dame > 1){
				$player->sendMessage("§l§c Bạn bị bạo đan -2 dame");
				$api->dame->set($player->getName(), $dame - 2); 
			    }
			}
             $api->dame->save();
			$item->setCount($count -1);
			$inv->setItemInHand($item);            
        }          
    }
    }
}
