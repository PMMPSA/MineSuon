<?php
declare(strict_types=1);

namespace Wool\TrashGUI;

use pocketmine\{Server, Player};
use pocketmine\command\{Command, CommandSender};
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use muqsit\invmenu\{
  InvMenu,
  InvMenuHandler
};

use Wool\TrashGUI\GUI;

class Trash extends PluginBase implements Listener{

  public function onEnable(): void{
    $this->getServer()->getPluginManager()->registerEvents($this, $this);

    if(!InvMenuHandler::isRegistered()){
      InvMenuHandler::register($this);
    }
  }

  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
    if($sender instanceof Player){
      if(strtolower($cmd->getName()) == "trash"){
        $GUI = new GUI($sender);
        $GUI->send($sender);
      }
    }else{
      $this->getLogger()->warning("You can't use command on console");
      return true;
    }
    return true;
  }
}
