<?php

namespace EzMinh\EXPShop;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        @mkdir($this->getDataFolder(), 0744, true);
       $this->auto = new Config($this->getDataFolder()."auto.yml",Config::YAML);
}
    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->auto->exists($ev->getPlayer()->getName())) {
        $this->auto->set($ev->getPlayer()->getName(), "no");
        $this->auto->save();
    }
}
    
	public function onBreak(BlockBreakEvent $ev){
	    $player= $ev->getPlayer();
        $auto_aell_exp = $this->auto->get($player->getName());
		if($auto_aell_exp == "yes"){
           $player_exp = $player->getXpLevel();
            if($player_exp >= 1){
                $player->setXpLevel(0);
                $money = $player_exp * 1000;
                $this->eco->addMoney($player, $money);
            }
	    }
    }
	
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() == "sellexp") 
        {
            if (!$sender instanceof Player) 
            {
                $sender->sendMessage(C::RED . "You can't not use this command here!");
                return false;
            }
                $player_exp = $sender->getXpLevel();
                    $sender->setXpLevel(0);
        $money = $player_exp * 1000;
                    $this->eco->addMoney($sender, $money);
					$sender->sendMessage("§l§f[§cSell Exp§f]§e Bán exp thành công, nhận được§a " . $money . " Money");
		}elseif ($cmd->getName() == "autosellexp"){
            $auto_aell_exp = $this->auto->get($sender->getName());
		    if($auto_aell_exp == "no"){
                $this->auto->set($sender->getName(), "yes");
			    $sender->sendMessage("§l§f[§cSell Exp§f]§e Đã Bật Auto Sell Exp");
		    }elseif($auto_aell_exp == "yes"){
                $this->auto->set($sender->getName(), "no");
				$sender->sendMessage("§l§f[§cSell Exp§f]§e Đã tắc Auto Sell Exp");
		  }
		return true;
       }
     return true;
    }
}