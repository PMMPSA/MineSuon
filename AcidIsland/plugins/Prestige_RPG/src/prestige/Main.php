<?php

namespace prestige;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;

Class Main extends PluginBase implements Listener{
    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->cap = new Config($this->getDataFolder()."cap.yml",Config::YAML);
       $this->kn = new Config($this->getDataFolder()."kinhnghiem.yml",Config::YAML);
        $this->t = new Config($this->getDataFolder()."t.yml",Config::YAML);
        $this->nung = new Config($this->getDataFolder()."Nung.yml",Config::YAML);
     $this->hp = new Config($this->getDataFolder()."hp.yml",Config::YAML);
}

    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->t->exists($ev->getPlayer()->getName())) {
            $this->t->set($ev->getPlayer()->getName(), 10);
            $this->t->save();
        }
        if(!$this->nung->exists($ev->getPlayer()->getName())) {
            $this->nung->set($ev->getPlayer()->getName(), 1);
            $this->nung->save();
        }
        if(!$this->hp->exists($ev->getPlayer()->getName())) {
            $this->hp->set($ev->getPlayer()->getName(), 20);
            $this->hp->save();
        }else{
            $hp =  $this->hp->get($ev->getPlayer()->getName());
            $ev->getPlayer()->setMaxHealth($hp);
            $ev->getPlayer()->setHealth($hp);
        }
        if(!$this->cap->exists($ev->getPlayer()->getName())) {
            $this->cap->set($ev->getPlayer()->getName(), 1);
            $this->cap->save();
        }
        if(!$this->kn->exists($ev->getPlayer()->getName())) {
            $this->kn->set($ev->getPlayer ()->getName(), 0);
            $this->kn->save();
    }

    }
	/**
     *@param BlockBreakEvent $ev
     *@priority HIGHEST
     */
    public function onbreak(BlockBreakEvent $ev) {
        if(!$ev->isCancelled()){
        if($ev->getBlock()->getId() == 15){
            $nung = $this->nung->get($ev->getPlayer()->getName());
            $ev->setDrops(array());
            $item = Item::get(265, 0, $nung);
            $ev->getPlayer()->getInventory()->addItem($item);
        }
        if($ev->getBlock()->getId() == 14){
            $nung = $this->nung->get($ev->getPlayer()->getName());
            $ev->setDrops(array());
            $item = Item::get(266, 0, $nung);
            $ev->getPlayer()->getInventory()->addItem($item);
        }            
            $level = $this->cap->get($ev->getPlayer()->getName());
            if($level >= 65){
               $id= mt_rand(1,10);
            if ($id !== 1){
                return;   
            }
            switch($ev->getBlock()->getId()){
                case 16:
                    $fortune = $ev->getPlayer()->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::FORTUNE); 
                    $ev->getPlayer()->getInventory()->addItem(Item::get(263, 0, $fortune+ 1));
                break;
                case 73:
                    $fortune = $ev->getPlayer()->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::FORTUNE); 
                    $ev->getPlayer()->getInventory()->addItem(Item::get(331, 0, $fortune+ 1));                  
                break;
                case 129:
                    $fortune = $ev->getPlayer()->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::FORTUNE); 
                    $ev->getPlayer()->getInventory()->addItem(Item::get(388, 0, $fortune+ 1));    
                break;
                case 56:                                   $fortune = $ev->getPlayer()->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::FORTUNE); 
                    $ev->getPlayer()->getInventory()->addItem(Item::get(264, 0, $fortune+ 1));    
                break;
            }
            }

        if($level >= 25){
            $item = $ev->getPlayer()->getInventory()->getItemInHand();
            $item->setDamage(0);
            $ev->getPlayer()->getInventory()->setItemInHand($item);
        }
        if($level >= 15){
           $ev->getPlayer()->setFood(20);    
        }              
        $cap  = $this->cap->get($ev->getPlayer()->getName());
        if($cap >= 5){
            $id = mt_rand(1,20);
            switch($id){
                case 5:
                    $money = $cap*50 /5;
                    $this->eco->addMoney($ev->getPlayer()->getName(), $money);
                break;
                case 10:
                    $money = $cap*50 /3;
                    $this->eco->addMoney($ev->getPlayer()->getName(), $money);
                break;
                case 15:
                    $money = $cap*50 /2;
                    $this->eco->addMoney($ev->getPlayer()->getName(), $money);
                break;
                case 20:
                    $money = $cap*80;
                    $this->eco->addMoney($ev->getPlayer()->getName(), $money);
                break;
            }
       }
       $exp1 = $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       switch($ev->getBlock()->getId()){
          case 56: 
             $exp1;
             $this->kn->save();
          break;
          case 14:
             $exp1;
             $this->kn->save(); 
          break;
          case 15:
             $exp1;
             $this->kn->save(); 
          break;
          case 16:
             $exp1;
             $this->kn->save(); 
          break;                     
          case 41:
             $exp1;
             $this->kn->save(); 
          break;           
          case 42:
             $exp1;
             $this->kn->save(); 
          break;
          case 57:
             $exp1;
             $this->kn->save(); 
          break;
          case 73:
             $exp1;
             $this->kn->save(); 
          break;
          case 133:
             $exp1;
             $this->kn->save(); 
          break;
          case 129:
             $exp1;
             $this->kn->save(); 
          break;
          case 173:
             $exp1;
             $this->kn->save(); 
          break;
          case 21:
             $exp1;
             $this->kn->save(); 
          break;
          case 22:
             $exp1;
             $this->kn->save(); 
          break;
          case 152:
             $exp1;
             $this->kn->save(); 
          break;    
       }
       $cap = $this->cap->get($ev->getPlayer()->getName());
       $lenhcap = $cap * 200;
       if($this->kn->get($ev->getPlayer()->getName()) >= $lenhcap) {
          $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) - $lenhcap));
          $this->kn->save();
          $money = $cap*1000;
          $this->eco->addmoney($ev->getPlayer(), $money);
          $ev->getPlayer()->sendMessage("??l??3?????f Prestige??3 ?????r B???n ???? l??n level prestige v?? nh???n ???????c ".$money." ??emoney");
          $this->cap->set($ev->getPlayer()->getName(), ($this->cap->get($ev->getPlayer()->getName()) + 1));
          $this->cap->save();
          if($cap > 35){
             $api = $this->getServer()->getPluginManager()->getPlugin("RPG");
             $api->maxhp->set($ev->getPlayer()->getName(), ($api->maxhp->get($ev->getPlayer()->getName()) + 1));        
          }
        }
        }
    }
    
    public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "prestige":
                $this->Menu($sender);
           return true;
        }
        return true;
    }
    
     public function menuttn($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
              return;
          }
          $level = $this->cap->get($sender->getName());      
          switch($result){
              case 0:
              break;
              case 1:
                 if($level >= 5){
                    $sender->sendMessage("??l??3?????f Prestige??3 ?????r T??nh n??ng t??? ?????ng");
                 }else{
                     $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                 }
              break;
              case 2:
                 if($level >= 15){
                    $sender->sendMessage("??l??3?????f Prestige??3 ?????r T??nh n??ng t??? ?????ng");
                 }else{
                     $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                 }
              break;
              case 3:
                 if($level >= 25){
                    $sender->sendMessage("??l??3?????f Prestige??3 ?????r T??nh n??ng t??? ?????ng");
                 }else{
                     $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                 }
              break;
              case 4:
                 if($level >= 35){
                    $sender->sendMessage("??l??3?????f Prestige??3 ?????r T??nh n??ng t??? ?????ng");
                 }else{
                     $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                }
              break;
              case 5:
                 if($level >= 45){
                    $this->nung($sender);
                 }else{
                     $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                 }
              break;
              case 6:
                 if($level >= 65){
                    $sender->sendMessage("??3?????f Prestige??3 ?????r T??nh n??ng t??? ?????ng");
                 }else{
                     $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                 }
              break;
              case 7:
                 if($level >= 85){
                    $sender->sendMessage("??l??3?????f Prestige??3 ?????r T??nh n??ng b???o tr??");
                 }else{
                    $sender->sendMessage("??l??3?????f Prestige??3 ?????r B???n ch??a ????? level c???a prestige");
                 }
              break;   
         }
        });
          $level = $this->cap->get($sender->getName());   
       if ($level>= 5){
            $mine = "???? M???";
        }else{
            $mine = "Level 5 m???";
        }
        if ($level>= 15){
            $feed = "???? M???";
        }else{
            $feed = "Level 15 m???";
        }
        if ($level>= 25){
            $fix = "???? M???";
        }else{
            $fix = "Level 30 m???";
        }
                if ($level>= 35){
            $hp = "???? M???";
        }else{
            $hp = "Level 35 m???";
        }
            if ($level>= 45){
            $n = "???? M???";
        }else{
            $n = "Level 45 m???";
        }
           if ($level>= 65){
            $t = "???? M???";
        }else{
            $t = "Level 65 m???";
        }
        $form->setTitle("??l??3?????f Prestige??3 ???");
        $form->addButton("??l??3?????c Tho??t??3 ???");
        $form->addButton("??l??3?????f Mine Ra Ti???n??3 ???\n??c".$mine);
        $form->addButton("??l??3??? ??fKh??ng B??? ????i??3 ???\n??c".$feed);
        $form->addButton("??l??3?????f T??? ?????ng S???a Ch???a C??p??3 ???\n??c".$fix);
        $form->addButton("??l??3?????f T??ng M??u??3 ???\n??c".$hp);
        $form->addButton("??l??3?????f T??? ?????ng Nung ??3???\n??c".$n);
        $form->addButton("??l??3?????f T??? L??? X2 Fortun??3 ???\n??c".$t);
        $form->sendToPlayer($sender);
     }
    
        public function nung($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
              return;
          }
          switch($result){
              case 0:
              break;
              case 1:
                 $phi = $this->nung->get($sender->getName()) * 20;
                 $point = $this->point->MyPoint($sender);
                 if($point >= $phi){
                    $this->point->reducePoint($sender, $phi);
                    $this->nung->set($sender->getName(), ($this->nung->get($sender->getName()) + 1));
                     $this->getServer()->broadcastMessage("??l??3?????f ???? M?????3 ?????r Ch??c M???ng ??f".$sender->getName()."??f ???? N??ng T??? Nung L??n C???p ".$this->nung->get($sender->getName()));
                  }else{
                      $sender->sendMessage("??l??3?????f ???? M?????3 ?????rB???n C???n ??f".$phi." ??aPoint??f ????? N??ng C???p");
                  }    
              break;
         }
        });
        $nung = $this->nung->get($sender->getName());
        $form->setTitle("??l??3?????f T??? ?????ng Nung??3 ?????r");
        $form->setContent("??l??3??? ??fLevel Hi???n T???i C???a B???n ??c".$nung."");
        $form->addButton("??c Tho??t");
        $form->addButton("??3??l??? ??fN??ng C???p??3 ???");
        $form->sendToPlayer($sender);
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
                 $this->menuttn($sender);
              break;
              case 1:
                 $this->Toplevel($sender);
              break;
         }
        });
        $cap = $this->cap->get($sender->getName());
        $kn = $this->kn->get($sender->getName());
        $name = $sender->getName();
        $maxkn = $cap* 200;
        $form->setTitle("??l??3?????f Prestige??3 ???");
        $form->setContent("??l??3??? ??cName: ??a".$name."\n??l??3?????f Level: ??a".$cap."\n??l??3?????f Exp: ??a".$kn."");
        $form->addButton("??l??3?????f Ch???c N??ng??3 ???");
        $form->addButton("??l??3?????f Top Level??3 ???");
        $form->sendToPlayer($sender);
    }
                
    public function Toplevel(Player $sender){
        $levelplot = $this->cap->getAll();
        $message = "";
        $message1 = "";
        if(count($levelplot) > 0){
            arsort($levelplot);
            $i = 1;
            foreach($levelplot as $name => $level){
                $message .= "??l??3TOP " . $i . ": ??6" . $name . " ??d??? ??f" . $level . " ??2C???p\n\n";
                $message1 .= "??l??3TOP " . $i . ": ??6" . $name . " ??d??? ??f" . $level . " ??2C???p\n";
                if($i >= 100){
                    break;
                }
                ++$i;
            }
        }
        
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
        });
        $form->setTitle("??l??3?????f Prestige??3 ???");
        $form->setContent($message);
        $form->addButton("??l??3?????c Tho??t ??3???");
        $form->sendToPlayer($sender);
        return true;
    }
}