<?php

namespace JackPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
        switch($cmd->getName()){
        case "healfeed":
            if($sender->isOp()){
                $this->oprank($sender);
        }elseif($sender->getPlayer()->hasPermission("hf.command")){
            $this->vip1($sender);
        }else{
            $sender->sendMessage("§e• §cHãy Mua Nâng hay Cấp Độ Để Sử Dụng Nhé !");
        }  
        }
        return true;
    }
     
/////////////////vip1//////////////////
     public function vip1($sender){
         $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
         $form = $api->createSimpleForm(function (Player $sender, $data){
             $result = $data;
             if ($result == null) {
             }
         switch($result){
             case 0:
             break;
             case 1:
             $health = $sender->getMaxHealth();
             $sender->setHealth($health);
		     $sender->sendMessage("§l§c• §fBạn đã được hồi máu");
             break;
			 case 2:
			 $sender->setFood(20);
		     $sender->sendMessage("§l§c• §fBạn đã được hồi thức nă");
			 break;
         }
     });
     $form->setTitle("§l§cHealFeed");
        $form->addButton("§l§c• §fThoát§c •");
        $form->addButton("§l§6• §3Hồi máu §6•");
		$form->addButton("§l§6• §3Hồi thức ăn §6•");
     $form->sendToPlayer($sender);
}
    public function novip1($sender){
         $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
         $form = $api->createSimpleForm(function (Player $sender, $data){
             $result = $data;
             if ($result == null) {
             }
            switch($result){
              case 0:
              $command = "muarank";
              $this->getServer()->getCommandMap()->dispatch($sender, $command);
              break;
              case 1:
              break;
            }
        });
        $form->setTitle("Không có đặc quyền");
        $form->addButton("§l§c• §eNâng cấp độ§c •");
        $form->addButton("§l§c• §fThoát§c •");
        $form->sendToPlayer($sender);
    }
//////////////vip6////////////////////
    public function oprank($sender){
         $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
         $form = $api->createSimpleForm(function (Player $sender, $data){
             $result = $data;
             if ($result == null) {
             }
             switch($result){
                case 0:
                break;
                case 1:
				break;
             }
         });
        $form->setTitle("§cHealFeed");      
        $form->setContent("HealFeedUI không hoạt động trên OP");
		 $form->addButton("§l§c• §fThoát§c •");
        $form->sendToPlayer($sender);
     }
    public function nooprank($sender){
         $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
         $form = $api->createSimpleForm(function (Player $sender, $data){
             $result = $data;
             if ($result == null) {
             }
            switch($result){
              case 0:
              $command = "muarank";
              $this->getServer()->getCommandMap()->dispatch($sender, $command);
              break;
              case 1:
              break;
            }
        });
        $form->setTitle("§l§c• §fKhông Có OP§c •");
        $form->setContent("Bạn Không Phải Là OP");
     }
}