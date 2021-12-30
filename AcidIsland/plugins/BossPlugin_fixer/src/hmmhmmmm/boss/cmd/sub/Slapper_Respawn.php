<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\utils\BossUtils;
use hmmhmmmm\boss\cmd\SubCommand;
use hmmhmmmm\boss\cmd\args\StringArgs;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class Slapper_Respawn extends SubCommand{

   public function __construct(Boss $plugin){
      parent::__construct($plugin, "slapper_respawn");
   }
   
   public function getPrefix(): string{
      return $this->getPlugin()->getPrefix();
   }
   
   public function prepare(): void{
      $this->registerArgument(0, new StringArgs("name", true));
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = $this->getPlugin();
      $lang = $plugin->getLanguage();
      if(!$sender instanceof Player){
         $this->sendConsoleError($sender);
         return;
      }
      if(count($args) < 1){
         $sender->sendMessage($lang->getTranslate(
            "command.slapper_respawn.error1",
            [
               $lang->getTranslate(
                  "command.slapper_respawn.usage"
               )
            ]
         ));
         return;
      }
      $name = $args["name"];
      if(!BossData::isBoss($name)){
         $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
            "command.slapper_respawn.error2",
            [$name]
         ));
         return;
      }
      $bossUtils = new BossUtils();
      $plugin->array["slapper"][$sender->getName()]["respawn"] = $name;
      $bossUtils->makeSlapper($sender);
   }
   
}