<?php

namespace pickaxe;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\level\sound\BatSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\DoorSound;
use pocketmine\level\sound\FizzSound;
use pocketmine\level\sound\EndermanTeleportSound;

class Main extends PluginBase implements Listener {
    
    
public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->ldlc = new Config($this->getDataFolder()."ldlc.yml",Config::YAML);
       $this->cap = new Config($this->getDataFolder()."cap.yml",Config::YAML);
       $this->kn = new Config($this->getDataFolder()."kinhnghiem.yml",Config::YAML);
       $this->hs1 = new Config($this->getDataFolder()."haste1.yml",Config::YAML);
       $this->hs2 = new Config($this->getDataFolder()."haste2.yml",Config::YAML);
       $this->hs3 = new Config($this->getDataFolder()."haste3.yml",Config::YAML);
       $this->hs4 = new Config($this->getDataFolder()."haste4.yml",Config::YAML);
       $this->hs5 = new Config($this->getDataFolder()."haste5.yml",Config::YAML);
}

public function onJoin(PlayerJoinEvent $ev) {
    if(!$this->cap->exists($ev->getPlayer()->getName())) {
        $this->cap->set($ev->getPlayer()->getName(), 1);
        $this->cap->save();
    }    
if(!$this->ldlc->exists($ev->getPlayer()->getName())) {
			$player = $ev->getPlayer();
			$inv = $player->getInventory();  
			$item = Item::get(278, 0, 1);
			$cap = $this->cap->get($ev->getPlayer()->getName());
						$item->setCustomName( "??r??l??e?????6 ??aMine??bSuon ??f [??cLevel: ??b".$cap."??f]??a ".$player->getName());
			$inv->addItem($item);
			$player->sendMessage("??l??e?????c Server Minesuon ???? trao cho b???n C??p c???a server h??y c??ng ?????ng h??nh v???i n?? nh?? ????? nh???n nhi???u ch???c n??ng!");
$this->ldlc->set($ev->getPlayer()->getName());
$this->ldlc->save();
    }
   //kinh Nghi???m 
     if(!$this->kn->exists($ev->getPlayer()->getName())) {
        $this->kn->set($ev->getPlayer ()->getName(), 0);
        $this->kn->save();
    }
    if(!$this->hs1->exists($ev->getPlayer()->getName())) {
        $this->hs1->set($ev->getPlayer()->getName(), 0);
        $this->hs2->save();
    }
    if(!$this->hs2->exists($ev->getPlayer()->getName())) {
        $this->hs2->set($ev->getPlayer()->getName(), 0);
        $this->hs2->save();
    }
    if(!$this->hs3->exists($ev->getPlayer()->getName())) {
        $this->hs3->set($ev->getPlayer()->getName(), 0);
        $this->hs3->save();
    }
    if(!$this->hs4->exists($ev->getPlayer()->getName())) {
        $this->hs4->set($ev->getPlayer()->getName(), 0);
        $this->hs4->save();
    }
    if(!$this->hs5->exists($ev->getPlayer()->getName())) {
        $this->hs5->set($ev->getPlayer()->getName(), 0);
        $this->hs5->save();
    }
}

public function onQuit(PlayerQuitEvent $ev) {
$this->ldlc->save();
$this->kn->save();
$this->cap->save();
$this->hs1->save();
$this->hs2->save();
$this->hs3->save();
$this->hs4->save();
$this->hs5->save();
}

/**
     *@param BlockBreakEvent $ev
     *@priority HIGHEST
     */
	 
public function onbreak(BlockBreakEvent $ev) {
    ///Add 
        $player = $ev->getPlayer();
        $item = $player->getInventory()->getItemInHand()->getCustomName();
        $id = $player->getInventory()->getItemInHand()->getId();
		$name = $player->getName();
		$pas = explode(" ", $item);
		if($pas[0] == "??r??l??e?????6"){
			if(strpos($item, $name)  == false){
				$ev->setCancelled(true);
				$player->sendMessage("??l??cC??p n??y kh??ng ph???i c???a b???n");
			}
		}
		if(!$ev->isCancelled()){
			if($pas[0] == "??r??l??e?????6"){
        ////?????p Block ??c Money
    //Haste

        $hs1 = $this->hs1->get($ev->getPlayer()->getName());
        if($hs1 == 1){
                    $ev->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(3), 3000, 0, false));
        }
        $hs2 = $this->hs2->get($ev->getPlayer()->getName());
        if($hs2 == 1){
                    $ev->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(3), 3000, 1, false));
    }
        $hs3 = $this->hs3->get($ev->getPlayer()->getName());
        if($hs3 == 1){
                    $ev->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(3), 3000, 2, false));
    }
        $hs4 = $this->hs4->get($ev->getPlayer()->getName());
        if($hs4 == 1){
                    $ev->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(3), 3000, 3, false));
    }
        $hs5 = $this->hs5->get($ev->getPlayer()->getName());
        if($hs5 == 1){
                    $ev->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(3), 3000, 4, false));
    }
          //Kim C????ng
      if($ev->getBlock()->getId() == 56){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //S???t
      if($ev->getBlock()->getId() == 14){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //V??ng
      if($ev->getBlock()->getId() == 15){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //Than
      if($ev->getBlock()->getId() == 16){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      ///Block V??ng
      if($ev->getBlock()->getId() == 41){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //Block S???t
      if($ev->getBlock()->getId() == 42){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //????
      if($ev->getBlock()->getId() == 4){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //Block Kim C????ng
      if($ev->getBlock()->getId() == 57){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //???? ?????
      if($ev->getBlock()->getId() == 73){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      //block emrald
      if($ev->getBlock()->getId() == 133){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      if($ev->getBlock()->getId() == 129){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      if($ev->getBlock()->getId() == 173){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      if($ev->getBlock()->getId() == 21){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      if($ev->getBlock()->getId() == 22){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
      }
      if($ev->getBlock()->getId() == 152){
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 2));
       $this->kn->save();
      }
	       }
		//}else{
     //$player->sendMessage("??l??c?????e Vui L??ng V??? ?????o M?? ?????p Block Nha!^^");
		}
		 $level = $this->cap->get($ev->getPlayer()->getName());
         $exp = $this->kn->get($ev->getPlayer()->getName());
         $maxexp = $level * 250;
         $ev->getPlayer()->sendTip(" ??l??a??? ??eExp: ??f".$exp."??f/??c".$maxexp.".");
           // l??n C???p
    $cap = $this->cap->get($ev->getPlayer()->getName());
    $lenhcap = $cap * 250;
     if($this->kn->get($ev->getPlayer()->getName()) >= $lenhcap) {
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) - $lenhcap));
       $this->kn->save();
       $money = $cap*1000;
       $player = $ev->getPlayer();
       $this->eco->addmoney($player, $money);
       $this->cap->set($ev->getPlayer()->getName(), ($this->cap->get($ev->getPlayer()->getName()) + 1));
       $this->cap->save();
       //Enchant
       $cap = $this->cap->get($ev->getPlayer()->getName());
        $item = Item::get(278,0,1);
        $player = $ev->getPlayer();
        $lvhx = $cap /2;
				$item->setCustomName( "??r??l??e?????6 ??aMine??bSuon ??f [??cLevel: ??b".$cap."??f]??a ".$player->getName());
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $lvhx));
     $player->sendMessage("??l??c?????e Enchant ??cHi???u xu???t C???p ????? ??e".$lvhx);
       $lvgt = $cap/3;
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(18), $lvgt));
     $player->sendMessage("??l??c?????e Enchant ??cGia T??i C???p ????? ??e".$lvgt); 
			$player->getInventory()->setItemInHand($item);
		$cap = $this->cap->get($ev->getPlayer()->getName());
       $player = $ev->getPlayer();
   			$player->getInventory()->setItemInHand($item);
           $player->setMaxHealth($player->getHealth());
			 $this->getServer()->broadcastMessage("??l??c?????e Ng?????i Ch??i ??f".$player->getName()."??e V???a L??n C??p C???p:??f ".$cap);
			 $player->sendMessage("??l??c?????a C??p c???a B???n ???? l??n Level:??e ".$cap);
        $this->cap->save();
     }
}

	public function onCommand(CommandSender $player, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "pickaxe":
            $this->menu($player);
            return true;
        }
        return true;
	}
	
	 public function menu($player){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $player, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
              $this->effect($player);
              break;
              case 2:
            $inv = $player->getInventory();  
            $cap = $this->cap->get($player->getName());
        $item = Item::get(278,0,1);
        $lvhx = $cap /2;
					$item->setCustomName( "??r??l??e?????6 ??aMine??bSuon ??f [??cLevel: ??b".$cap."??f]??a ".$player->getName());
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $lvhx));
       $lvgt = $cap/3;
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(18), $lvgt));
			$player->getInventory()->addItem($item);

			$player->sendMessage("??l??c?????a Nh???n C??p Level Th??nh C??ng");
              break;
              case 3:
                  $this->Toplevel($player);
              break;
         }
        });
        $form->setTitle("??l??3?????e Menu Pickaxe ??3???");
		$form->addButton("??l??d?????c Tho??t ??d???");
		$form->addButton("??l??d?????3 Effect ??d???");
		$form->addButton("??l??d?????3 Nh???n C??p ??d???");
		$form->addButton("??l??ed?????3 Top c??p ??d???");
        $form->sendToPlayer($player);
	 }

	 public function effect($player){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $player, $data){
          $result = $data;
          if($result === null){
			  $this->menu($player);
          }
          switch($result){
              case 0:
        $level = $this->cap->get($player->getName());
        if($level >= 50){
        $hs1 = $this->hs1->get($player->getName());
            if($hs1 == 1){
        $this->hs1->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? B???t Haste Level 1");
        }elseif($hs1 == 0){
        $this->hs1->set($player->getName(), 1);
        $this->hs2->set($player->getName(), 0);
        $this->hs3->set($player->getName(), 0);
        $this->hs4->set($player->getName(), 0);
        $this->hs5->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? T???t Haste Level 1");
        }
        }else{
              $player->sendMessage("??l??c?????e B???n Kh??ng ????? C???p ????? S??? D???ng T??nh N??ng N???y");
        }

              break;
              case 1:
        $level = $this->cap->get($player->getName());
        if($level >= 200){
        $hs2 = $this->hs2->get($player->getName());
            if($hs2 == 1){
        $this->hs2->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? B???t Haste Level 2");
        }elseif($hs2 == 0){
        $this->hs2->set($player->getName(), 1);
        $this->hs1->set($player->getName(), 0);
        $this->hs3->set($player->getName(), 0);
        $this->hs4->set($player->getName(), 0);
        $this->hs5->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? T???t Haste Level 2");
        }
        }else{
              $player->sendMessage("??l??c?????e B???n Kh??ng ????? C???p ????? S??? D???ng T??nh N??ng N???y");
        }
              break;
              case 2:
        $level = $this->cap->get($player->getName());
        if($level >= 300){
        $hs3 = $this->hs3->get($player->getName());
            if($hs3 == 1){
        $this->hs3->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? B???t Haste Level 3");
        }elseif($hs3 == 0){
        $this->hs3->set($player->getName(), 1);
        $this->hs2->set($player->getName(), 0);
        $this->hs1->set($player->getName(), 0);
        $this->hs4->set($player->getName(), 0);
        $this->hs5->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? T???t Haste Level 3");
        }
        }else{
              $player->sendMessage("??l??c?????e B???n Kh??ng ????? C???p ????? S??? D???ng T??nh N??ng N???y");
        }
        break;
        case 3:
        $level = $this->cap->get($player->getName());
        if($level >= 400){
        $hs4 = $this->hs4->get($player->getName());
            if($hs4 == 1){
        $this->hs4->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? B???t Haste Level 4");
        }elseif($hs4 == 0){
        $this->hs4->set($player->getName(), 1);
        $this->hs2->set($player->getName(), 0);
        $this->hs3->set($player->getName(), 0);
        $this->hs1->set($player->getName(), 0);
        $this->hs5->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? T???t Haste Level 4");
        }
        }else{
              $player->sendMessage("??l??c?????e B???n Kh??ng ????? C???p ????? S??? D???ng T??nh N??ng N???y");
        }
        break;
        case 4:
                $level = $this->cap->get($player->getName());
        if($level >= 400){
        $hs5 = $this->hs5->get($player->getName());
            if($hs5 == 1){
        $this->hs5->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? B???t Haste Level 5");
        }elseif($hs5 == 0){
        $this->hs5->set($player->getName(), 1);
        $this->hs2->set($player->getName(), 0);
        $this->hs3->set($player->getName(), 0);
        $this->hs4->set($player->getName(), 0);
        $this->hs1->set($player->getName(), 0);
              $player->sendMessage("??c?????a B???n ???? T???t Haste Level 5");
        }
        }else{
              $player->sendMessage("??l??c?????e B???n Kh??ng ????? C???p ????? S??? D???ng T??nh N??ng N???y");
        }
        break;
        case 5:
		$this->menu($player);
            break;
        
         }
        });
        $level = $this->cap->get($player->getName());
        if($level >= 100){
            $lv5 = "???? M???";
        }else{
            $lv5 = "Level 100 M???";
        }
        
        if($level >= 200){
            $lv10 = "???? M???";
        }else{
            $lv10 = "Level 200 M???";
        }
        if($level >= 300){
            $lv20 = "???? M???";
        }else{
            $lv20 = "Level 300 M???";
        }
        if($level >= 400){
            $lv30 = "???? M???";
        }else{
            $lv30 = "Level 400 M???";
        }
        if($level >= 500){
            $lv40 = "???? M???";
        }else{
            $lv40= "Level 500 M???";
        }
        $form->setTitle("??l??e?????b Th??ng Tin ??e???");
	    $form->addButton("??l??e?????b Haste Lv1 ??e???\n ??f".$lv5);
	    $form->addButton("??l??e?????b Haste Lv2 ??e???\n ??f".$lv10);
	    $form->addButton("??l??e?????b Haste Lv3 ??e???\n ??f".$lv20);
	    $form->addButton("??l??e?????b Haste Lv4 ??e???\n ??f".$lv30);
	    $form->addButton("??l??e?????b Haste Lv5 ??e???\n ??f".$lv40);
	    $form->addButton("??l??e?????b Tho??t ??e???");
        $form->sendToPlayer($player);
       return true;
	 }
	 
	 


	public function Toplevel(Player $player){
		$levelplot = $this->cap->getAll();
		$message = "";
		$message1 = "";
		if(count($levelplot) > 0){
			arsort($levelplot);
			$i = 1;
			foreach($levelplot as $name => $level){
				$message .= "??l??3TOP " . $i . ": ??6" . $name . " ??d??? ??f" . $level . " ??2C???p\n\n";
				$message1 .= "??l??3TOP " . $i . ": ??6" . $name . " ??d??? ??f" . $level . " ??2C???p\n";
				if($i >= 10){
					break;
				}
				++$i;
			}
		}
		
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $player, ?int $data = null){
			$result = $data;
			switch($result){
				case 0:
				$this->menu($player);
				break;
			}
		});
		$form->setTitle("??l??e?????c TopLevel ??e???");
		$form->setContent("??l??cDanh S??ch TOP 10 Pickaxe:");
		$form->setContent($message);
		$form->addButton("??l??e?????c Tr??? L???i ??e???");
		$form->sendToPlayer($player);
		return true;
	}
}
