<?php

namespace tps;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "tps":
                if($sender->hasPermission("tps.server")){
                     $lit = [];
        foreach($this->getServer()->getOnlinePlayers() as $player){
            $list[] = $player->getName();
            $this->playerList[$player->getName()] = $list;
        }
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if(!$data==null){
			$index = $data[0];
            $name = $this->playerList[$sender->getName()][$index];
            $p = $this->getServer()->getPlayer($name);
            if ($p == null)
			{
				$player->sendMessage("§c§lvui lòng chọn người chơi");
			}else{
            $levelname = $p->getLevel()->getName();
            $x = $p->getX();
            $y = $p->getY();
            $z = $p->getZ();
            $level = Server::getInstance()->getLevelByName($levelname);
            $sender->teleport(new Position($x, $y, $z, $level));
			$sender->sendMessage("§l§aDịch chuyển thành công");  
			}			
			}
        });
        $form->setTitle("§ftps");
        $form->addDropdown("§fdanh sách online\n", $this->playerList[$player->getName()]);
        $form->sendToPlayer($sender);
                }else{
		          $sender->sendMessage("§l§c Bạn chưa có quyền tp");
				}				  
           return true;
        }
        return true;
	}
    
    public function menu($sender){
        $lit = [];
        foreach($this->getServer()->getOnlinePlayers() as $player){
            $list[] = $player->getName();
            $this->playerList[$player->getName()] = $list;
        }
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data==null)return;
			$index = $data[0];
            $name = $this->playerList[$player->getName()][$index];
            $p = $this->getServer()->getPlayer($name);
            if ($p == null)
			{
				$player->sendMessage("§c§lvui lòng chọn người chơi");
				     return;
			}
            $levelname = $p->getLevel()->getName();
            $x = $p->getX();
            $y = $p->getY();
            $z = $p->getZ();
            $level = Server::getInstance()->getLevelByName($levelname);
            $sender->teleport(new Position($x, $y, $z, $level));
			$sender->sendMessage("§l§aDịch chuyển thành công");            
        });
        $form->setTitle("§ftps");
        $form->addDropdown("§fdanh sách online\n", $this->playerList[$player->getName()]);
        $form->sendToPlayer($sender);

    }
}