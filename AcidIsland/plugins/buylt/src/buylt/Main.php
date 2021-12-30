<?php

namespace buylt;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};

class Main extends PluginBase implements Listener {
    
    
    public function onEnable() 
       $this->getServer()->getPluginManager()->registerEvents($this ,$this);
       $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    }
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "buylt":
            $this->menu($sender);
            return true;
        }
        return true;
	}
	
	 public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data == null)return;
			if(!(is_numeric($data[0]))){
				$sender->sendMessage("§a§lYêu Cầu Nhập Số");
				return true;
			}
            $money = $this->money->myMoney($sender);
            if($money >= $buy = $data[0] * 10000){
                $this->money->reduceMoney($sender, $buy);
                $linhthao = $this->getServer()->getPluginManager()->getPlugin("luyendan")->linhthao;
                $linhthao->set($sender->getName(), $linhthao->get($player->getName()) +$data[0]);
                $linhthao->save();
            }
        });
        $form->setTitle("§3§lbuy linh thảo");
		$form->addInput("nhập số linh thảo muống mua");
        $form->sendToPlayer($sender);
	}

}















