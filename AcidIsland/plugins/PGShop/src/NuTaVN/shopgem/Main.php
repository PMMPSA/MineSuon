<?php

namespace NuTaVN\shopgem;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\{command\ConsoleCommandSender, Player, utils\TextFormat};
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI;

class Main extends PluginBase implements Listener{

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");		
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()) {
			case "pgshop":
			if(!($sender instanceof Player)){
                return true;
			}
		$this->menu($sender);
		}
		return true;
	}

	public function menu($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
				case 0:
				break;
                case 1:
                $this->gemshop($sender);
                break;
                case 2:
                $this->pointshop($sender);
                break;
            }
        });
		$form->setTitle("§l§c•§e Shop PG§c •");
		$form->addButton("§l§c•§e Thoát§c •");
        $form->addButton("§l§c•§e Gem§c •");
        $form->addButton("§l§c•§e Point§c •");
        $form->sendToPlayer($sender);
	}

    public function gemshop($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
				case 0:
                $this->menu($sender);
                break;
                case 1:
                $this->muadetugem($sender);
                break;
                case 2:
                $this->muarankgem($sender);
                break;
				case 3:
                $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "thoikhong");
                break;
				case 4:
                $this->shopitemvipgem($sender);
                break;
				case 5:
				$this->shopdangem($sender);
				break;
            }
        });
		$form->setTitle("§l§c•§e Shop Gem§c •");
		$form->addButton("§l§c•§e Menu Shop§c •");
        $form->addButton("§l§c•§e Mua Đệ Tử§c •");
        $form->addButton("§l§c•§e Mua Rank§c •");
		$form->addButton("§l§c•§e Thời Không§c •");
		$form->addButton("§l§c•§e Shop Item Vip§c •");
	    $form->addButton("§l§c•§e Shop Đan§c •");
        $form->sendToPlayer($sender);
	}
	 public function pointshop($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
				case 0:
                $this->menu($sender);
                break;
                case 1:
                $this->muadetupoint($sender);
                break;
                case 2:
                $this->muarankpoint($sender);
                break;
				case 3:
				$this->shopdanpoint($sender);
				break;
				case 4:
				$this->shopitemvippoint($sender);
				break;
            }
        });
		$form->setTitle("§l§c•§e Shop Point§c •");
		$form->addButton("§l§c•§e Menu Shop§c •");
        $form->addButton("§l§c•§e Mua Đệ Tử§c •");
        $form->addButton("§l§c•§e Mua Rank§c •");
		$form->addButton("§l§c•§e Shop Đan§c •");
		$form->addButton("§l§c•§e Shop Item Vip§c •");
        $form->sendToPlayer($sender);
	}
	 public function muarankpoint($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
 	    
		$form = $formapi->createSimpleForm(function(Player $sender, $data){
		$point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");     	
          $tien = $point->mypoint($sender);   
		  $result = $data;
          if($result === null){
		  return;
		  }
          switch($result){
              case 0:
			  $this->pointshop($sender);
			  break;
              case 1:
            	if($tien >= 12){
				$sender->sendMessage("§e§lBạn đã mua thành công rank 1");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " Plus 7");   
				$point->reducePoint($sender, 12);
			}else{
				$sender->sendMessage("§c Bạn cần 12 Point để mua Rank");
			}
              break;
              case 2:
        if($tien >= 55){
			  	$sender->sendMessage("§e§lBạn đã mua thành công rank 2");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " PLus1 14");   
				$point->reducePoint($sender, 55);
			}else{
				$sender->sendMessage("§cBạn cần 55 Point để mua Rank");
			}
              break;
              case 3:
            	if($tien >= 115){
			 	$sender->sendMessage("§e§lBạn đã mua thành công rank 3");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " mvp 28");   
				$point->reducePoint($sender, 115);
			}else{
				$sender->sendMessage("§cBạn cần 115 Point để mua Rank");

			}
              break;
              case 4:
           if($tien >= 400){
				$sender->sendMessage("§e§lBạn đã mua thành công rank 4");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " premium 48");   
				$point->reducePoint($sender, 400);
			}else{
				$sender->sendMessage("§cBạn cần 400 Point để mua Rank");
			}
              break;
			  case 5:
           	if($tien >= 1000){
				$sender->sendMessage("§e§lBạn đã mua thành công rank 5");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " platinum 70");   
				$point->reducePoint($sender, 1000);
			}else{
				$sender->sendMessage("§cBạn cần 1000 Point để mua Rank");
			}
              break;

         }
        });
        $form->setTitle("§l§c• §6Rank Shop §bPoint §l§c•");
		$form->addButton("§l§f• §cTrở lại shop point §l§f•");
		$form->addButton("§l§c• §6Rank §3I §l§c•\n§l§c• §e12 §fPoint§r/§e7 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3II §l§c•\n§l§c• §e55 §fPoint§r/§e14 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3III §l§c•\n§l§c• §e115 §fPoint§r/§e28 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3IV §l§c•\n§l§c• §e400 §fPoint§r/§e48 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3V §l§c•\n§l§c• §e1000 §fPoint§r/§e70 §fDays §l§c•");
        $form->sendToPlayer($sender);
	}
	
	public function muadetupoint($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
                 case 0:
				 $point = $this->pointapi->myPoint($sender);
				 $cost = 50;
				 if($point >= $cost){
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
					 $this->pointapi->reducePoint($sender, $cost);
					 $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "detu " . $sender->getName());
					 $sender->sendMessage("§aBạn đã mua thành công");
     }else{
         $sender->sendMessage("§l§b•§avui lòng tay của bạn không cần item gì để nhận minion");
     }
					 return true;
            }else{
				$sender->sendMessage("§l§e•§cBạn không đủ tiền");
			}
			break;
                    case 1:
                        break;
						case 2:
						$this->pointshop($sender);
                        break;
			        
            }
        });
		$form->setTitle("§eMua §bĐệ tử");
        $form->setContent("§aBạn có muốn mua §bĐệ Tử§c giá §650 §ePoint");
        $form->addButton("§l§f• §aCó §l§f•");
        $form->addButton("§l§f• §cKhông §l§f•");
		$form->addButton("§l§f• §cTrở lại shop point §l§f•");
        $form->sendToPlayer($sender);
	}
	
	public function muadetugem($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
                 case 0:
	          	$gem = $this->getServer()->getPluginManager()->getPlugin("gem");                     
				 $gem = $gem->gem->get($sender->getName());
				 $cost = 500;
				 if($gem >= $cost){
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
					 $gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 500);
					 $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "detu " . $sender->getName());
					 $sender->sendMessage("§aBạn đã mua thành công");
     }else{
         $sender->sendMessage("§l§b•§avui lòng tay của bạn không cần item gì để nhận minion");
     }
					 return true;
            }else{
				$sender->sendMessage("§l§e•§c§l Bạn không đủ §dgem");
			}
			break;
			case 1:
                        break;
						case 2:
						$this->gemshop($sender);
                        break;
            }
        });
		$form->setTitle("§eMua §bĐệ tử");
        $form->setContent("§aBạn có muốn mua §bĐệ Tử§c giá §6500 §eGem");
        $form->addButton("§l§f• §aCó §l§f•");
        $form->addButton("§l§f• §cKhông §l§f•");
		$form->addButton("§l§f• §cTrở lại shop gem §l§f•");
        $form->sendToPlayer($sender);
	}
	
	public function muarankgem($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function(Player $sender, $data){
		  $gem = $this->getServer()->getPluginManager()->getPlugin("gem");     	
          $tien = $gem->gem->get($sender->getName());   
		  $result = $data;
          if($result === null){
		  return;
		  }
          switch($result){
              case 0:
			  $this->gemshop($sender);
			  break;
              case 1:
            	if($tien >= 50){
				$sender->sendMessage("§e§lBạn đã mua thành công rank 1");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " Plus 7");   
				$gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 50);
			}else{
				$sender->sendMessage("§c Bạn cần 50 Gem để mua Rank");
			}
              break;
              case 2:
        if($tien >= 200){
			  	$sender->sendMessage("§e§lBạn đã mua thành công rank 2");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " PLus1 14");   
			$gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 200);
			}else{
				$sender->sendMessage("§cBạn cần 200 Gem để mua Rank");
			}
              break;
              case 3:
            	if($tien >= 500){
			 	$sender->sendMessage("§e§lBạn đã mua thành công rank 3");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " mvp 28");   
			$gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 500);
			}else{
				$sender->sendMessage("§cBạn cần 500 Gem để mua Rank");

			}
              break;
              case 4:
           if($tien >= 1500){
				$sender->sendMessage("§e§lBạn đã mua thành công rank 4");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " premium 48");   
				$gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 1500);
			}else{
				$sender->sendMessage("§cBạn cần 1500 Gem để mua Rank");
			}
              break;
			  case 5:
           	if($tien >= 3000){
				$sender->sendMessage("§e§lBạn đã mua thành công rank 5");
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " platinum 70");   
								$gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) -3000);
			}else{
				$sender->sendMessage("§cBạn cần 3000 Gem để mua Rank");
			}
              break;
         }
        });
        $form->setTitle("§l§c• §6Rank Shop §eGem §l§c•");
		$form->addButton("§l§f• §cTrở lại shop gem §l§f•");
		$form->addButton("§l§c• §6Rank §3I §l§c•\n§l§c• §e50 §fGem§r/§e7 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3II §l§c•\n§l§c• §e200 §fGem§r/§e14 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3III §l§c•\n§l§c• §e500 §fGem§r/§e28 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3IV §l§c•\n§l§c• §e1500 §fGem§r/§e48 §fDays §l§c•");
		$form->addButton("§l§c• §6Rank §3V §l§c•\n§l§c• §e3000 §fGem§r/§e70 §fDays §l§c•");
        $form->sendToPlayer($sender);
	}

    public function shopdangem($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
            if (!$sender->getInventory()->canAddItem(Item::get(0, 0, 1))){
				$sender->sendMessage("§l§cTúi đồ của bạn không còn trống :))");
				return;
			}               
          $result = $data;
          if($result === null){
          }
		  $gem = $this->getServer()->getPluginManager()->getPlugin("gem");     	
          $point = $gem->gem->get($sender->getName());   
          switch($result){
              case 0:
			  $this->pointshop($sender);
              break;
              case 1:
			  $money = $this->eco->MyMoney($sender);
            if($money >= 20000){
              $this->eco->reduceMoney($sender, 20000);	
			   $item = Item::get(341, 0, 1);
			   $item->setCustomName("§l§cĐá chữa trị");
               $item->setLore(array("§e§lTác dụng: §fhồi 5 hp"));
			   $sender->getInventory()->addItem($item);
		       $sender->sendMessage("§l§c•§b Bạn mua thành công §l§cĐá chữa trị");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua §l§cĐá chữa trị");                
            } 
              break;
              case 2:
			  
            if($point >= 15){
               $gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 15);
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cLinh động đan");
              $item->setLore(array("§e§lTác dụng: §f+3 hp gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã mua thành công §l§clinh động đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua §l§cLinh động đan");                
            }
              break;
              case 3:
            if($point >= 15){
               $gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 15);
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cGân cốt đan");
              $item->setLore(array("§e§lTác dụng: §f+3 giáp gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã mua thành công §l§cGân cốt đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua §l§cGân cốt đan");                
            }
              break;
              case 4:
            if($point >= 15){
               $gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 15);
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cBạo nguyên đan");
              $item->setLore(array("§e§lTác dụng: §f+3 dame gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã mua thành công l§cBạo nguyên đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua l§cBạo nguyên đan");                
            }
              break;   
              case 5:
			 $this->tacdung($sender);
			 break;
         }
		 
        });
        $form->setTitle("§l§c[§eSHOP ĐAN§c]");
	    $form->addButton("§l§f• §cTrở lại shop point §l§f•");
        $form->addButton("§l§6♦ §c Đá chữa trị§6 ♦\n§e20k Money");
        $form->addButton("§l§6♦ §cLinh Động Đan§6 ♦\n§e15 Gem");  
        $form->addButton(" §l§6♦ §fGân Cốt Đan§6 ♦\n§e15 Gem");  
        $form->addButton(" §l§6♦ §3Bạo Nguyên Đan§6 ♦\n§e15 Gem");      
        $form->addButton(" §l§6♦ §eTác dụng đan§6 ♦\n§eFree Cost");         
        $form->sendToPlayer($sender);
}
	
	public function shopdanpoint($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
            if (!$sender->getInventory()->canAddItem(Item::get(0, 0, 1))){
				$sender->sendMessage("§l§cTúi đồ của bạn không còn trống :))");
				return;
			}               
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
			  $this->pointshop($sender);
              break;
              case 1:
			  $money = $this->eco->MyMoney($sender);
            if($money >= 20000){
               $this->eco->reduceMoney($sender, 20000);
			   $item = Item::get(341, 0, 1);
			   $item->setCustomName("§l§cĐá chữa trị");
               $item->setLore(array("§e§lTác dụng: §fhồi 5 hp"));
			   $sender->getInventory()->addItem($item);
		       $sender->sendMessage("§l§c•§b Bạn mua thành công §l§cĐá chữa trị");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua §l§cĐá chữa trị");                
            } 
              break;
              case 2:
			  $point = $this->point->myPoint($sender);
            if($point >= 3){
              $this->point->reducePoint($sender, 3);
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cLinh động đan");
              $item->setLore(array("§e§lTác dụng: §f+3 hp gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã mua thành công §l§clinh động đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua §l§cLinh động đan");                
            }
              break;
              case 3:
			  $point = $this->point->myPoint($sender);
            if($point >= 3){
               $this->point->reducePoint($sender, 3);
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cGân cốt đan");
              $item->setLore(array("§e§lTác dụng: §f+3 giáp gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã mua thành công §l§cGân cốt đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua §l§cGân cốt đan");                
            }
              break;
              case 4:
			  $point = $this->point->myPoint($sender);
            if($point >= 3){
               $this->point->reducePoint($sender, 3);
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cBạo nguyên đan");
              $item->setLore(array("§e§lTác dụng: §f+3 dame gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã mua thành công l§cBạo nguyên đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ tiền để mua l§cBạo nguyên đan");                
            }
              break;   
              case 5:
			 $this->tacdung($sender);
			 break;
         }
        });
        $form->setTitle("§l§c[§eSHOP ĐAN§c]");
	    $form->addButton("§l§f• §cTrở lại shop point §l§f•");
        $form->addButton("§l§6♦ §c Đá chữa trị§6 ♦\n§e20k Money");
        $form->addButton("§l§6♦ §cLinh Động Đan§6 ♦\n§e3 Point");  
        $form->addButton(" §l§6♦ §fGân Cốt Đan§6 ♦\n§e3 Point");  
        $form->addButton(" §l§6♦ §3Bạo Nguyên Đan§6 ♦\n§e3 Point");      
        $form->addButton(" §l§6♦ §eTác dụng đan§6 ♦\n§eFree Cost");         
        $form->sendToPlayer($sender);
}
  public function tacdung($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          switch($result){
                   case 0:
                  $this->menu($sender);
                   break;
          }
         });
        $form->setTitle("§c§lTác Dụng Đan");
        $form->setContent(" §l§6• §c Đá chữa trị:§7 Dùng để hồi 5hp sử dụng 1 lần\n§l§6•§a Linh Động Đan:§7 Tăng 4HP tối đa trong lần sử dụng VD:§cHP: §b1/20 §7-> §cHP: §b1/24\n§l§6•§b Gân Cốt Đan:§7 Tăng giáp của bạn tối đa +4 Giáp VD: §fGiáp: §b1 §7-> §fGiáp: §34\n§l§6•§3 Bạo Nguyên Đan:§7 Tăng sát thương khi đánh của bạn [Dame] VD: §3Dame: §b1 §7-> §3Dame: §d4");
		$form->addButton("§0§lTrở lại ");
        $form->sendToPlayer($sender);
	}
	public function shopitemvippoint($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
            $result = $data;
            if($result === null){
                return;
            }
            switch($result){
                case 0:
                    $this->pointshop($sender);
                break; 
				case 1:
                    $this->makiem($sender);
                break;
         }
        });
        $form->setTitle("§l§c•§e Shop Item Vip§c •");
		$form->addButton("§l§f• §cTrở lại shop point §l§f•");
		$form->addButton("§l§c•§e Kiếm Xuyên Phá§c •");		
        $form->sendToPlayer($sender);
	}
	public function makiem($sender){
	    $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
			  return;
          }
          switch($result){
              case 0:
		$point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
		$tien = $point->mypoint($sender);
			$id = $sender->getInventory()->getItemInHand()->getId();  
			if($tien >= 105){
			    if($id == 0){
			$item = Item::get(276, 0, 1);
			$item->setCustomName("§l§c•§e Kiếm Xuyên Phá§c •");
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 50));                      $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(13), 1)); 
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 50)); 
			$item->setLore(array("\n§l§b•§eKhả Năng Hồi Máu Khi giết Người Khác\n\n§c•§aXuyên 50 giáp\n\n§fĐánh Giá: §e**"));
			$sender->getInventory()->setItemInHand($item);
						$point->reducePoint($sender, 105);
			    }else{
                $sender->sendMessage("§cYêu Cầu Tay trống Để Nhận Item");
			    }
			}else{
              $sender->sendMessage("§cBạn Không Đủ Tiền Để Mua");
			}
			    
              break;
              case 1:
              break;
			  case 2:
			  $this->shopitemvippoint($sender);
              break;
         }
        });
        $form->setTitle("§l§c•§e Shop Item Vip§c •");;
        $form->setContent("§l§c•§e Phí là :§f105 §6Point§a Bạn muốn mua nó chứ !");
		$form->addButton("§f•§b§l Mua §f•");
		$form->addButton("§f•§b§l Không §f•");
		$form->addButton("§f•§b§l Trở lại §f•");
        $form->sendToPlayer($sender);
	}
	public function shopitemvipgem($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
            $result = $data;
            if($result === null){
                return;
            }
            switch($result){
                case 0:
                    $this->gemshop($sender);
                break; 
				case 1:
                    $this->canh($sender);
                break;
				case 2:
                    $this->dau($sender);
                break; 
         }
        });
        $form->setTitle("§l§c•§e Shop Item Vip§c •");
		$form->addButton("§l§c•§b Trở lại shop gem§c •");
		$form->addButton("§l§c•§b Cánh Thiên Thần§c •");
        $form->addButton("§l§c•§6 Đầu Rồng Aguman§c •");		
        $form->sendToPlayer($sender);
	}
	public function canh($sender){
	    $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
		$gem = $this->getServer()->getPluginManager()->getPlugin("gem");
		$tien = $gem->gem->get($sender->getName());
			$id = $sender->getInventory()->getItemInHand()->getId();  
			if($tien >= 200){
			    if($id == 0){
			$item = Item::get(444, 0, 1);
			$item->setCustomName("§l§c•§b Cánh Thiên Thần§c •");
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2000)); 
			$item->setLore(array("\n§l§b•§e Khi mặc sẽ ngầu lòi hơn\n\n§fĐánh Giá: §e3 sao"));
			$sender->getInventory()->setItemInHand($item);
             $gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 200);
             $gem->gem->save();
			    }else{
                $sender->sendMessage("§cYêu Cầu Tay trống Để Nhận Item");
			    }
			}else{
              $sender->sendMessage("§cBạn Không Đủ Tiền Để Mua");
			}
			    
              break;
              case 1:
              break;
			  case 2:
			  $this->shopitemvipgem($sender);
              break;
         }
        });
        $form->setTitle("§l§c•§e Shop Item Vip§c •");
        $form->setContent("§l§c•§e Phí là :§f200 §dGem§a Bạn muốn mua nó chứ !");
		$form->addButton("§f•§b§l Mua §f•");
		$form->addButton("§f•§b§l Không §f•");
		$form->addButton("§f•§b§l Trở lại §f•");
        $form->sendToPlayer($sender);
	}
	
		public function dau($sender){
	    $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
		$gem = $this->getServer()->getPluginManager()->getPlugin("gem");
		$tien = $gem->gem->get($sender->getName());
			$id = $sender->getInventory()->getItemInHand()->getId();  
			if($tien >= 1000){
			    if($id == 0){
			$item = Item::get(397, 5, 1);
			$item->setCustomName("§l§c•§6 Đầu Rồng Aguman§c •");
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2000)); 
			$item->setLore(array("\n§l§b•§e Khi mặc sẽ ngầu lòi hơn\n\n§fĐánh Giá: §e4 sao"));
			$sender->getInventory()->setItemInHand($item);
             $gem->gem->set($sender->getName(), $gem->gem->get($sender->getName()) - 1000);
             $gem->gem->save();
			    }else{
                $sender->sendMessage("§cYêu Cầu Tay trống Để Nhận Item");
			    }
			}else{
              $sender->sendMessage("§cBạn Không Đủ Tiền Để Mua");
			}
			    
              break;
              case 1:
              break;
			  case 2:
			  $this->shopitemvipgem($sender);
              break;
         }
        });
        $form->setTitle("§l§c•§e Shop Item Vip§c •");
        $form->setContent("§l§c•§e Phí là :§f1000 §dGem§a Bạn muốn mua nó chứ !");
		$form->addButton("§f•§b§l Mua §f•");
		$form->addButton("§f•§b§l Không §f•");
		$form->addButton("§f•§b§l Trở lại §f•");
        $form->sendToPlayer($sender);
	}	
}