<?php

namespace pvp;

use pocketmine\block\Block;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pvp\PopupTask;

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        @mkdir($this->getDataFolder(), 0744, true);
        $this->hp = new Config($this->getDataFolder()."hp.yml",Config::YAML);
        $this->giap = new Config($this->getDataFolder()."giap.yml",Config::YAML);
        $this->maxhp = new Config($this->getDataFolder()."maxhp.yml",Config::YAML);  
        $this->kn = new Config($this->getDataFolder()."kn.yml",Config::YAML);
        $this->dame = new Config($this->getDataFolder()."dame.yml",Config::YAML); 
        $this->wa = new Config($this->getDataFolder()."wanted.yml",Config::YAML); 
        $this->mode = new Config($this->getDataFolder()."mode.yml",Config::YAML);            
}

    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->wa->exists($ev->getPlayer()->getName())) {
            $this->wa->set($ev->getPlayer()->getName(), 0);
            $this->wa->save();
        }        
        if(!$this->hp->exists($ev->getPlayer()->getName())) {
            $this->hp->set($ev->getPlayer()->getName(), 20);
            $this->hp->save();
        }
        if(!$this->maxhp->exists($ev->getPlayer()->getName())) {
            $this->maxhp->set($ev->getPlayer()->getName(), 20);
            $this->maxhp->save();
        }
        if(!$this->kn->exists($ev->getPlayer()->getName())) {
            $this->kn->set($ev->getPlayer()->getName(), 0);
            $this->kn->save();
        } 
        if(!$this->giap->exists($ev->getPlayer()->getName())) {
            $this->giap->set($ev->getPlayer()->getName(), 0);
            $this->giap->save();
        }
        if(!$this->dame->exists($ev->getPlayer()->getName())) {
            $this->dame->set($ev->getPlayer()->getName(), 1);
            $this->dame->save();
        }
        if(!$this->mode->exists($ev->getPlayer()->getName())) {
            $this->mode->set($ev->getPlayer()->getName(), "on");
            $this->mode->save();
        }            
    }
    

    public function tip(PlayerMoveEvent $ev){
		$player = $ev->getPlayer();
		$giap = $this->giap->get($player->getName());  
        $dame = $this->dame->get($player->getName());         
        $maxhp = $this->maxhp->get($player->getName()); 
		$kn = $this->kn->get($player->getName());
       
		if($player->getLevel()->getName() == "Arena1"){
		    $player->getLevel()->setTime(15000); 
		}
	    if($player->getLevel()->getName() == "Arena2"){
			if($dame > $maxhp *2 || $dame > $giap * 2|| $giap > $maxhp *2 || $giap > $dame *2 || $maxhp > $giap * 2 || $maxhp > $dame * 2){
				$player->sendMessage("§l§lchỉ số của bạn không cân bằng không thể vào");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($player->getName())));			    
			}
		    $player->getLevel()->setTime(15000); 
			if($maxhp < 51 && !$player->isOp()){
				$player->sendMessage("§l§cbạn đã chết do khí huyết có boss quá cao yêu cầu max hp lớn hơn 50");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($entity->getName())));			    
			}
		}
		
		if($player->getLevel()->getName() == "Arena3"){
			if($dame > $maxhp *2 || $dame > $giap * 2|| $giap > $maxhp *2 || $giap > $dame *2 || $maxhp > $giap * 2 || $maxhp > $dame * 2){
								$player->sendMessage("§l§cchỉ số của bạn không cân bằng không thể vào");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($player->getName())));			    
			}
		    $player->getLevel()->setTime(15000); 
			if($maxhp < 201 && !$player->isOp()){
				$player->sendMessage("§l§cbạn đã chết do khí huyết có boss quá cao yêu cầu max hp lớn hơn 200");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($player->getName())));			    
			}
		}
		
		if($player->getLevel()->getName() == "Arena5"){
		    $player->getLevel()->setTime(15000); 
			if($dame > $maxhp *2 || $dame > $giap * 2|| $giap > $maxhp *2 || $giap > $dame *2 || $maxhp > $giap * 2 || $maxhp > $dame * 2){
				$player->sendMessage("§l§cchỉ số của bạn không cân bằng không thể vào");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($player->getName())));			    
			}
			if($maxhp < 1001 && !$player->isOp()){
				$player->sendMessage("§l§cbạn đã chết do khí huyết của boss quá cao yêu cầu max hp lớn hơn 1000");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($player->getName())));			    
			}
		}
		
		if($player->getLevel()->getName() == "Arena4"){
			if($dame > $maxhp *2 || $dame > $giap * 2|| $giap > $maxhp *2 || $giap > $dame *2 || $maxhp > $giap * 2 || $maxhp > $dame * 2){
				$player->sendMessage("§l§cchỉ số của bạn không cân bằng không thể vào");
			    $this->getServer()->getCommandMap()->dispatch($player,"ai home");
                $player->extinguish();
                $player->removeAllEffects();
                $this->hp->set($player->getName(), ($this->maxhp->get($player->getName())));			    
			}
		    $player->getLevel()->setTime(15000); 
			if($maxhp < 501 && !$player->isOp()){
				$player->sendMessage("§l§cbạn đã chết do khí huyết của boss quá cao yêu cầu max hp lớn hơn 500");
			    $this->getServer()->getCommandMap()->dispatch($entity,"ai home");
                $entity->extinguish();
                $entity->removeAllEffects();
                $this->hp->set($entity->getName(), ($this->maxhp->get($entity->getName())));			    
			}
		}
		
        $hp = $this->hp->get($player->getName());
        if($hp > 0){
            $player->setHealth($player->getMaxHealth());
        }  
        $player->sendTip("§l§c♥:§r§f $hp §a/§6 $maxhp §a§l☘: §r§e$kn\n§l§b▼:§e §r$giap §l§c⚔:§r §e$dame ");
    }
     
    public function onDeath(EntityDamageByEntityEvent $ev){
        $entity = $ev->getEntity();
        $damager = $ev->getDamager();
       // pvp
        if($entity instanceof Player && $damager instanceof Player){
           $maxhp1 = $this->maxhp->get($damager->getName());
		   $maxhp2 = $this->maxhp->get($entity->getName());
		   if($maxhp1 * 2 < $maxhp2 && !$damager->isOp()){
			    $this->getServer()->broadcastMessage("§l§c".$damager->getName()."§e đã chết do khí huyết của §f".$entity->getName());
			        $this->getServer()->getCommandMap()->dispatch($entity,"ai home");
                    $damager->extinguish();
                    $damager->removeAllEffects();
                    $this->hp->set($damager->getName(), $this->maxhp->get($damager->getName()));
		   }					
		   if($this->mode->get($damager->getName()) == "on"){
              $damager->sendMessage("§c• §l§aBạn không thể pvp khi đang ở chế độ hoà bình");
               $ev->setCancelled(true);
                return;
           }
           if($this->mode->get($entity->getName()) == "on"){
              $damager->sendMessage("§c• §l§aBạn không thể pvp với người ở chế độ hoà bình");
               $ev->setCancelled(true);
               return;
           }           
        }
                  
        //remove health
        if($entity instanceof Player){
            $hp = $this->hp->get($entity->getName());
            $giap = $this->giap->get($entity->getName());
            $damage = $ev->getFinalDamage();
			$id = mt_rand(1,10);
			if($id == 8){
				if($damager instanceof Player) $damager->sendMessage("§l§eĐịch đã đỡ được chiêu của bạn");
				$ev->setCancelled(true);
			}
            if($damager instanceof Player){
				$item = $entity->getInventory()->getItemInHand()->getCustomName();
				$xg = 0;
				if($item == "§l§c•§e Kiếm Xuyên Phá§c •"){
					$xg = 50;
				}
				$g = $giap - $xg;
				if($g<0){
					$g = 0;
				}
				$tl = 1;
				$id = mt_rand(1,20);
				if($id == 7) $tl = 2;
				$damager->sendMessage("§l§eĐánh trúng điểm chí mạng x2 dame");
				if($entity instanceof Player) $entity->sendMessage("§l§ebạn bị đánh trúng điểm chí mạng");
                $dame = $this->dame->get($damager->getName());
                $hps = (($damage + $dame ) * $tl) - $g;
                if(0 >= $hps){
                   $hps = 1;
                }    
             
            }else{
                $hps = $damage - $giap;
                if(0 >= $hps){
                   $hps = 1;
                }
            }
            if($hp >= 0){
				$ev->setBaseDamage(0);
                $this->hp->set($entity->getName(), ($this->hp->get($entity->getName()) - $hps));
                $this->hp->save();
                $entity->setHealth($entity->getMaxHealth());
                if($hp = $this->hp->get($entity->getName()) <= 0){
                    $entity->setHealth(20);
					if($damager instanceof Player){
			        $item = $damager->getInventory()->getItemInHand()->getCustomName();
				    if($item == "§l§c•§e Kiếm Xuyên Phá§c •"){
                    $this->hp->set($damager->getName(), ($this->maxhp->get($damager->getName())));
                    $this->hp->save();
					}
                    $entity->setHealth($entity->getMaxHealth());
					}					
			        $this->getServer()->broadcastMessage("§l§c".$damager->getName()."§e đã giết §f".$entity->getName());
			        if($this->maxhp->get($entity->getName()) > 0){
			            if($damager instanceof Player){
			                if($this->wa->get($entity->getName()) > 1){
			                    $this->eco->addMoney($damager->getName(), $this->wa->get($entity->getName()));
			                    $this->wa->set($entity->getName(), 1);
			                    $damager->sendMessage("§c• §l§aBạn đã nhận được§f ".$this->wa->get($entity->getName())." §bxu§a từ truy nã");
			                }
			            }
			        } 
 		            $this->getServer()->getCommandMap()->dispatch($entity,"ai home");
                    $entity->extinguish();
                    $entity->removeAllEffects();
                    $this->hp->set($entity->getName(), ($this->maxhp->get($entity->getName())));			    
                }               
            }
        }
    }
    public function mob(EntityDamageByEntityEvent $ev){
        $entity = $ev->getEntity();
        $damager = $ev->getDamager();
		if($entity instanceof Player && !$damager instanceof Player){
	          if($damager->getMaxHealth() > 100){
			     $ev->setKnockBack(0.4);
			}
		}
        if(!$entity instanceof Player && $damager instanceof Player){
            if($entity->getMaxHealth() > 100){
			     $ev->setKnockBack(0);
			}
			$hp = $entity->getHealth();
            $dame =$ev->getFinalDamage(); 
            $index_dame = $this->dame->get($damager->getName());
            $damage = $hp-$dame-$index_dame;
            $ev->setBaseDamage($index_dame + $dame);
            if($damage <= 0){
                if($entity->getMaxHealth() >= 100 && $entity->getMaxHealth() < 1000){
                    $this->kn->set($damager->getName(), ($this->kn->get($damager ->getName()) + 1));                 
                    $this->kn->save();
                    $damager->sendMessage("§l§a+ §e1§a☘");                  
                }
                if($entity->getMaxHealth() >= 1000 && $entity->getMaxHealth() < 10000){
                    $this->kn->set($damager->getName(), ($this->kn->get($damager ->getName()) + 3));    
                    $this->kn->save();  
                    $damager->sendMessage("§l§a+ §e3§a☘");                            
                }
                if($entity->getMaxHealth() >= 10000){
                   $this->kn->set($damager->getName(), ($this->kn->get($damager ->getName()) + 7));                  
                   $this->kn->save();
                   $damager->sendMessage("§l§a+ §e7§a☘");
                }
            }
        }          
    }
    public function onItemHeld(PlayerItemHeldEvent $ev){
        $task = new PopupTask($this, $ev->getPlayer());
        $this->getScheduler()->scheduleRepeatingTask($task, 20);
    }
    
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "player":
			$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
            $form = $formapi->createSimpleForm(function(Player $sender, $data){
            $result = $data;
            if($result === null){
              return;
            }
            $kn = $this->kn->get($sender->getName());              
            if($result == 0){
                if($kn >= 50){
                   $this->kn->set($sender->getName(), ($this->kn->get($sender->getName()) - 50)); 
                   $this->maxhp->set($sender->getName(), ($this->maxhp->get($sender->getName()) + 1));
                   $this->maxhp->save;
                   $sender->sendTitle("§l§a+ §e1§c⚔");
                }else{
					$sender->sendTitle("§l§fKhông đủ §a☘");               
                }
            }
            if($result == 1){
                if($kn >= 100){
                    $this->kn->set($sender->getName(), ($this->kn->get($sender->getName()) - 100)); 
                    $this->giap->set($sender->getName(), ($this->giap->get($sender->getName()) + 1));
                    $this->giap->save;
                    $sender->sendTitle("§l§a+ §e1§b▼");
                }else{
					$sender->sendTitle("§l§fKhông đủ §a☘");               
            }
        }
        if($result == 2){
            if($kn >= 75){
                $this->kn->set($sender->getName(), ($this->kn->get($sender->getName()) - 75)); 
                $this->dame->set($sender->getName(), ($this->dame->get($sender->getName()) + 1));
                $this->dame->save;
                $sender->sendTitle("§l§a+ §e1§c♥");
            }else{
                $sender->sendTitle("§l§fKhông đủ §a☘");               
            }            
        }
        $this->kn->save;
        });
            $hptext = $this->getConfig()->get("hptext");
            $hpimage = $this->getConfig()->get("hpimage");
            $giaptext = $this->getConfig()->get("giaptext");
            $giapimage = $this->getConfig()->get("giapimage");
            $dametext = $this->getConfig()->get("dametext");
            $dameimage = $this->getConfig()->get("dameimage");
            $exittext = $this->getConfig()->get("exittext");
            $exitimage = $this->getConfig()->get("exitimage");
        $name = $sender->getName();
        $hp = $this->hp->get($sender->getName());
        $maxhp = $this->maxhp->get($sender->getName());
        $giap = $this->giap->get($sender->getName());
        $dame = $this->dame->get($sender->getName());        
        $kn = $this->kn->get($sender->getName());                        
        $form->setTitle("§l§3$name");
        $form->setContent("§l§c♥:§r§f $hp §a/§6 $maxhp \n§a§l☘: §r§e$kn \n§l§b▼:§e §r$giap\n§l§c⚔:§r §e$dame"); 
    	$form->addButton($hptext,1,"$hpimage");
    	$form->addButton($giaptext,1,"$giapimage");
    	$form->addButton($dametext,1,"$dameimage");     	
    	$form->addButton($exittext,1,"$exitimage");   	
        $form->sendToPlayer($sender);
        break;
        case "truyna":
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data == null){
                return;
            }
            if($data[0] == null||$data[1] == null){
				$sender->sendMessage("§a§lThiếu dữ liệu");                
                return;
            }
			if(!(is_numeric($data[1]))){
				$sender->sendMessage("§a§lYêu Cầu Nhập Số tiền");
				return true;
			}
            if(!$this->wa->exists($data[0])){
                $sender->sendMessage("§c§lNgười Chơi Không tồn tại");
                return;
            }
            if(!$data[0] == $sender->getName()){
                $sender->sendMessage("§c§lbạn không thể truy nã chính mình");                
                return;
            }
            if($data[1] < 10000){
                $sender->sendMessage("§c§lSố tiền truy nã phải lớn hơn 99999"); 
                return;
            }
            if($data[1] > $this->eco->myMoney($sender)){
                $sender->sendMessage("§c§lMức tiền thưởng lớn hơn số tiền của bạn");                 
                return;
            }
            $this->wa->set($sender->getName(), ($this->wa->get($sender->getName()) + $data[1]));            
            $this->eco->reduceMoney($sender, $data[1]);
			 $this->getServer()->broadcastMessage("§l§f•§c".$data[0]."§e đã được truy nã với giá §f".$data[1]);             
        });
        $form->setTitle("§c§lTruy Nã");
		$form->addInput("§rnhập Người Chơi Muống truy nã");
		$form->addInput("§rnhập Số tiền muống truy nã");
        $form->sendToPlayer($sender); 
        break;
        case "tnlist":
		$levelplot = $this->wa->getAll();
		$message = "";
		$message1 = "";
		if(count($levelplot) > 0){
			arsort($levelplot);
			$i = 1;
			foreach($levelplot as $name => $level){
			    if($level >=100000){
				$message .= "§l§3" . $name . " §d→ §3giá §f" . $level . " §2xu\n\n";
				$message1 .= "§l§3" . $name . " §d→ §3giá §f" . $level . " §2xu\n\n";
			    }
			    if($i <= 100){
			        break;
			    }
				++$i;
			}
		}
		
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
		});
		$form->setTitle("§l§cDanh sách truy nã");
		$form->setContent($message);
		$form->addButton("§l§cThoát");
		$form->sendToPlayer($sender);          break;
		case "mode":
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
		  if($data == 0){
		      $this->mode->set($sender->getName(), "on");
				$sender->sendMessage("§a§lBạn đã bật chế độ §aHòa Bình");
		  } 
		  if($data == 1){
		      $this->mode->set($sender->getName(), "off");
				$sender->sendMessage("§a§lBạn đã bật chế độ §cPVP");		      
		  }
		});
        $hoabinhtext = $this->getConfig()->get("hoabinhtext");
            $hoabinhimage = $this->getConfig()->get("hoabinhimage");
            $pvptext = $this->getConfig()->get("pvptext");
            $pvpimage = $this->getConfig()->get("pvpimage");
		if($this->mode->get($sender->getName()) == "on"){
		    $mode = "§l§a⊕\n§3§l•§e Chế độ hiện tại của bạn:§a Hòa Bình";
		}
		if($this->mode->get($sender->getName()) == "off"){
		    $mode = "§l§c✘\n§3§l•§e Chế độ hiện tại của bạn:§c PVP";
		}		
		$form->setTitle("§l§cMode");
		$form->setContent($mode);
		$form->addButton($hoabinhtext,1,"$hoabinhimage");
		$form->addButton($pvptext,1,"$pvpimage");		
		$form->sendToPlayer($sender); 		    
            return true;
        }
        return true;
	}
}
