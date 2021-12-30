<?php

namespace cpt;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;

class Main extends PluginBase implements Listener {
    
    
    public function onEnable() {
        $this->getLogger()->info("§4Plugin cây phát tài đã được bật bởi LetTIHL");
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        @mkdir($this->getDataFolder(), 0744, true);
        $this->info = new Config($this->getDataFolder() . "info.yml", Config::YAML);
        $this->cap = new Config($this->getDataFolder() . "cap.yml", Config::YAML);        
    }

    public function onJoin(PlayerJoinEvent $ev) {
        $name = $ev->getPlayer()->getName();
        if(!($this->info->exists(strtolower($name)))){        
             $this->info->set(strtolower($name), ["level" => 1, "exp" => 0, "stone" => 0]);
             $this->info->save();
        }
    }
    
    /**
     *@param BlockPlaceEvent $ev
     *@priority HIGHEST
     */

    public function onbreak(BlockBreakEvent $ev) {
        $player = $ev->getPlayer();
        $level = $this->getLevel($player);        
        $exp = $this->getExp($player);
        $stone = $this->getStone($player);
        if(!$ev->isCancelled()){
            if($ev->getBlock()->getId() == 1 ){
                 if(!$stone  > 100000){
                      $this->info->set(strtolower($player->getName()), ["level" => $level , "exp" => $exp, "stone" => $stone + 1]);
                      $this->info->save();
                 }
            }
            $id = mt_rand(1,10);    
            if($id == 1){
               $player->sendTip("§6• §l§c+1 §aExp");            
               $this->info->set(strtolower($player->getName()), ["level" => $level, "exp" => $exp + 1, "stone" => $stone]);
               $this->info->save();
               if($exp >= $level * 1000){
                   if ($level < 10){
                       $this->info->set(strtolower($player->getName()), ["level" => $level + 1 , "exp" => 0 , "stone" => $stone]);
                       $this->info->save();
                       $level = $this->getLevel($player);            
                       $player->sendMessage("§l§c•§e Chúc mừng Cấp cây của bạn đã lên cấp mới §c!\n§l§7-§e Cấp cây hiện tại của bạn:§c ". $level);
                   }
               }
            }
        }
    }
    
        /**
     *@param BlockPlaceEvent $ev
     *@priority HIGHEST
     */

    public function Stone(BlockBreakEvent $ev) {
        $player = $ev->getPlayer();
        $level = $this->getLevel($player);        
        $exp = $this->getExp($player);
        $stone = $this->getStone($player);
        if($ev->getBlock()->getId() == 1 || $ev->getBlock()->getId() == 4){
            if($stone  < 100000){
                $this->info->set(strtolower($player->getName()), ["level" => $level , "exp" => $exp, "stone" => $stone + 1]);
                  $this->info->save();
             }
        }    
    }

    public function getLevel($player){
        return $level = $this->info->get(strtolower($player->getName()))["level"];
    }
    
    public function getExp($player){
        return $this->info->get(strtolower($player->getName()))["exp"];
    }
    public function getStone($player){
        return $this->info->get(strtolower($player->getName()))["stone"];
    }    
    
    public function onDeath(EntityDamageByEntityEvent $ev){
        $entity = $ev->getEntity();
        $damager = $ev->getDamager();
        if(!$entity instanceof Player && $damager instanceof Player){
            if($ev->getFinalDamage() - $entity->getHealth() >= 0) {  
                $id = mt_rand(1,10);
                if($id = 1){
                    $level = $this->getLevel($damager);        
                   $exp = $this->getExp($damager);
                   $stone = $this->getStone($damager);
                   $e = $exp +1;
                   $this->info->set(strtolower($damager->getName()), ["level" => $level, "exp" => $e, "stone" => $stone]);
                   $this->info->save();
               }
               if($exp >=$level * 1000){
                   if($level < 10){
                       $this->info->set(strtolower($damager->getName()), ["level" => $level + 1 , "exp" => 0, "stone" => $stone]);
                       $level = $this->getLevel($damager);            
                       $damager->sendMessage("§l§c•§e Chúc mừng Cấp cây của bạn đã lên cấp mới §c!\n§l§7-§e Cấp cây hiện tại của bạn:§c ". $level);
                   }
               }
           }
              $this->info->save();
        }        
    }    
    
    public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "cpt":
            $this->menu($sender);
            return true;
        }
        return true;
    }
    
     public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
              return;
          }
          switch($result){
              case 0:
              $this->cay($sender);
              break;
              case 1:
              $this->nhiemvu($sender);
              break;
              case 2:
              $this->phucloimenu($sender);
              break;  
         }
        });
        $xemcaytext = $this->getConfig()->get("xemcaytext");
        $xemcayimage = $this->getConfig()->get("xemcayimage");
        $nhiemvutext = $this->getConfig()->get("nhiemvutext");
        $nhiemvuimage = $this->getConfig()->get("nhiemvuimage");
        $phucloitext = $this->getConfig()->get("phucloitext");
        $phucloiimage = $this->getConfig()->get("phucloiimage");
        $form->setTitle("§l§c•§e Cây Phát Tài§c •");
        $form->addButton($xemcaytext,1,"$xemcayimage");
        $form->addButton($nhiemvutext,1,"$nhiemvuimage");
        $form->addButton($phucloitext,1,"$phucloiimage");        
        $form->sendToPlayer($sender);
    }

    public function nhiemvu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
              return;
          }
          switch($result){
              case 0:
              $this->menu($sender);
              break;
          }
        });
        $thoatnhiemvutext = $this->getConfig()->get("exitnvtext");
        $exitnvimage = $this->getConfig()->get("exitnvimage");
        $form->setTitle("§l§c•§e Nhiệm Vụ§c •");
        $form->setContent("§l§c•§6 Khai thác block khoáng sản §c!\n§l§7-§e Nhận về 1 §aexp §echo cây\n§l§c•§6 Giết quái hoặc người chơi §c!/\n§l§7-§e Nhận về 0.5§a exp§e cho cây §c!");
        $form->addButton($thoatnhiemvutext,1,"$exitnvimage");
        $form->sendToPlayer($sender);
}
    public function cay($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
              return;
          }
          switch($result){
              case 0:
              $this->menu($sender);
              break;
          }
        });
        $name = $sender->getName();
        $level = $this->getLevel($sender);
        $stone = $this->getStone($sender);        
        $exp = $this->getExp($sender);
        $maxexp = $level * 1000;
        $thoatnhiemvutext2 = $this->getConfig()->get("exitnvtext2");
        $exitnvimage2 = $this->getConfig()->get("exitnvimage2");
        $form->setTitle("§l§c•§e Cây Phát Tài §c•");
        $form->setContent("§l§c•§f Thông Tin Cây§c •\n§l§7- §eTên người chơi: $name\n§l§7-§e Cấp cây hiện tại: $level\n§l§7- §eKinh nghiệm: §l§a$exp/§c$maxexp");
        $form->addButton($thoatnhiemvutext2,1,"$exitnvimage2");
        $form->sendToPlayer($sender);
    }
    public function phucloimenu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
              return;
          }
          switch($result){
              case 0:
              $stone = $this->getStone($sender);
              $level = $this->getLevel($sender);
              $exp = $this->getExp($sender);
              $xu = $stone * $level;
              $this->eco->addMoney($sender, $xu);
              $sender->sendMessage("§l§f•§a Bạn đã nhận §e$xu §axu từ phúc lợi ");              
                $this->info->set(strtolower($sender->getName()), ["level" => $level , "exp" => $exp, "stone" => 0]);                        $this->info->save();
              break;  
              case 1:
                  $this->menu($sender);
                  break;
          }
        });
        $stone = $this->getStone($sender);
        $level = $this->getLevel($sender);
        $xu = $stone * $level;
        $collect = $this->getConfig()->get("thuthap");
        $collect2 = $this->getConfig()->get("thuthap2");
        $backtohome = $this->getConfig()->get("backtohome");
        $backtohome2 = $this->getConfig()->get("backtohome2");
        $form->setTitle("§l§c•§e Phúc Lợi §c•");
        $form->setContent("§l§7-§e Tổng xu trong cây còn:§f $stone §e⛃");
        $form->addButton($collect,1,"$collect2");
        $form->addButton($backtohome,1,"$backtohome2");
        $form->sendToPlayer($sender);
    }
}