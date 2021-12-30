<?php

namespace restart;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
        @mkdir($this->getDataFolder(), 0744, true);
        $this->xyz = new Config($this->getDataFolder()."xyz.yml",Config::YAML);
        $this->check = new Config($this->getDataFolder()."check.yml",Config::YAML);
    }

    public function onJoin(PlayerJoinEvent $ev) {
        $p = $ev->getPlayer();
        if(!$this->check->exists($p->getName())) {
            $this->check->set($p->getName(), 0);
            $this->check->save();
        }
        if($this->check->get($p->getName()) == 1){
            $x = $this->xyz->get($p->getName())["x"];
            $y = $this->xyz->get($p->getName())["y"];
            $z = $this->xyz->get($p->getName())["z"];
            $world = $this->xyz->get($p->getName())["world"];
            $level = Server::getInstance()->getLevelByName($world);
            $p->teleport(new Position($x, $y, $z, $level));
            $p->sendMessage("§l§aĐã Dịch chuyển về vị trí củ");
            $this->check->set($p->getName(), 0);
            $this->check->save();
        }
    }

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "restart":
                if($sender->isOp()){
                   $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->saveAll();
                   $this->getServer()->getPluginManager()->getPlugin("PointAPI")->saveAll(); 
				   foreach($this->getServer()->getOnlinePlayers() as $player){
                     $this->check->set($player->getName(), 1);
                     $this->check->save();
			          $this->xyz->set($player->getName(), ["x" => $player->getX(), "y" => $player->getY(), "z" => $player->getZ(), "world" => $player->getLevel()->getName()]);
			          $this->xyz->save();
			          $player->transfer("103.243.173.162", "28184");                      
				   }
				   $this->getServer()->shutdown();				   
                }
           return true;
        }
        return true;
	}
}
