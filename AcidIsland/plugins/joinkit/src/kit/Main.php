<?php

namespace kit;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->ldlc = new Config($this->getDataFolder()."ldlc.yml",Config::YAML);
}

public function onJoin(PlayerJoinEvent $ev) {
	
if(!$this->ldlc->exists($ev->getPlayer()->getName())) {

			$player = $ev->getPlayer();
			$item = Item::get(245, 0, 1);
                $item->setCustomName("§c§lBlock Ore");
                $item->setLore(array("§e§lTác dụng: §fđặt block này ra\n rồi đào sẽ nhận được tài nguyên"));
            $player->getInventory()->addItem(Item::get(79, 0, 2));
			$player->getInventory()->addItem(Item::get(325, 10, 1));
			$player->getInventory()->addItem(Item::get(279, 0, 1));
			$player->getInventory()->addItem(Item::get(278, 0, 1));
			$player->getInventory()->addItem(Item::get(277, 0, 1));
			$player->getInventory()->addItem(Item::get(290, 0, 1));
			$player->getInventory()->addItem(Item::get(287, 0, 12));
			$player->getInventory()->addItem(Item::get(338, 0, 1));
			$player->getInventory()->addItem(Item::get(351, 15, 2));
			$player->getInventory()->addItem(Item::get(361, 0, 1));
			$player->getInventory()->addItem(Item::get(81, 0, 1));
			$player->getInventory()->addItem(Item::get(360, 0, 1));
			$player->getInventory()->addItem(Item::get(39, 0, 1));
			$player->getInventory()->addItem(Item::get(40, 0, 1));
			  $player->getInventory()->addItem($item);
$this->ldlc->set($ev->getPlayer()->getName());
$this->ldlc->save();
       }
    }
}