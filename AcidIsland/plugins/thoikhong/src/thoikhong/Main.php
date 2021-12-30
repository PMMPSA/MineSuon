<?php

namespace thoikhong;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener {
    
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);
         @mkdir($this->getDataFolder(), 0744, true);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	    $this->cap = new Config($this->getDataFolder()."Level.yml",Config::YAML);
        $this->kn = new Config($this->getDataFolder()."kinhnghiem.yml",Config::YAML);
        $this->check = new Config($this->getDataFolder()."check.yml",Config::YAML);       
    }

    public function PlayerMoveEvent(PlayerMoveEvent $ev){ 
        $player = $ev->getPlayer();
        if($player->getLevel()->getName() == "thoikhong"){
			$player->getLevel()->setTime(0);            
            if($this->check->get($player->getName()) == 0){
                $player->sendMessage("§l§f•§cbạn chưa mua thẻ thời không");
                $this->getServer()->getCommandMap()->dispatch($player,"ai home");                
            }
        }
    }
    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->kn->exists($ev->getPlayer()->getName())) {
            $this->kn->set($ev->getPlayer ()->getName(), 0);
            $this->kn->save();
        }
        if(!$this->cap->exists($ev->getPlayer()->getName())) {
            $this->cap->set($ev->getPlayer()->getName(), 1);
            $this->cap->save();
        }
        if(!$this->check->exists($ev->getPlayer()->getName())) {
            $this->check->set($ev->getPlayer()->getName(), 0);
            $this->check->save();
        }        
    }
    
    public function onbreak(BlockBreakEvent $ev){
        $player = $ev->getPlayer();
        $cap = $this->cap->get($player->getName());
	    if(!$ev->isCancelled()){
	        $levelname = $player->getLevel()->getName();
	        if($levelname == "thoikhong"){
                if($ev->getBlock()->getId() == 56){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                    
                }
      //Sắt
                if($ev->getBlock()->getId() == 14){
                   $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                   $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
      //Vàng
                if($ev->getBlock()->getId() == 15){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
      //Than
                if($ev->getBlock()->getId() == 16){
                   $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                   $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
      }
      ///Block Vàng
                   if($ev->getBlock()->getId() == 41){
                       $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                       $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);

                    }
      //Block Sắt
                    if($ev->getBlock()->getId() == 42){
                        $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                        $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                    }
      //Đá
                    if($ev->getBlock()->getId() == 1){
                        $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                   $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
      //Block Kim Cương
                if($ev->getBlock()->getId() == 57){
                   $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
      //Đá Đỏ
                if($ev->getBlock()->getId() == 73){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
      //block emrald
                if($ev->getBlock()->getId() == 133){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
                if($ev->getBlock()->getId() == 129){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu); 
                }
                if($ev->getBlock()->getId() == 173){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
                if($ev->getBlock()->getId() == 21){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 1));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
                if($ev->getBlock()->getId() == 22){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                    $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
                if($ev->getBlock()->getId() == 152){
                    $this->kn->set($player->getName(), ($this->kn->get($player->getName()) + 2));
                     $this->kn->save();
                    $xu = $cap * 500;
                    $this->eco->addMoney($player, $xu);
                }
	        }
		}
        $lenhcap = $cap * 100000;
        if($this->kn->get($player->getName()) >= $lenhcap) {
            if($cap <18){
                $this->kn->set($player->getName(), 0);
                $this->kn->save();
                $this->cap->set($player->getName(), ($this->cap->get($player->getName()) + 1));
                $this->cap->save();
            }
        }
    }

	public function onCommand(CommandSender $player, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "thoikhong":
            $this->pickaxe($player);
            return true;
        }
        return true;
	}
	 public function pickaxe($player){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $player, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
                $api = $this->getServer()->getPluginManager()->getPlugin("gem");   
                $gem = $api->gem->get($player->getName());
                if($this->check->get($player->getName()) == 1){
                    $player->sendMessage("§l§f•§cbạn đã mua thẻ rồi"); 
                    return;
                }
                if($gem >= 500){
                    $api->gem->set($player->getName(), $api->gem->get($player->getName()) - 500); 
                    $this->check->set($player->getName(), 1);
                    $this->check->save();
                    $player->sendMessage("§l§f•§cbạn đã mua thành công thẻ thời không");                    
                    $api->gem->save();
                }else{
                    $player->sendMessage("§l§f•§cbạn cần 500 gem để mua thẻ");                      
                }
              break;
              case 1:
                $level = Server::getInstance()->getLevelByName("thoikhong");
                $player->getPlayer()->teleport(new Position(0, 64, 0, $level));
                $player->sendMessage("§l§f•§cbạn đã dịch chuyển tới thời không");                   
              break;
              case 2:
                $this->info($player);
              break;
                  
         }
        });
        $form->setTitle("§l§e•§f thời không §e•");
		$form->addButton("§l§e•§f Mua thẻ thời không§e•\n§egiá 500 gem");
		$form->addButton("§l§e•§f về thời không §e•");
		$form->addButton("§l§e•§f info §e•");
        $form->sendToPlayer($player);
	 }
	public function info(Player $player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $player, ?int $data = null){});
		$name = $player->getName();
		$level = $this->cap->get($player->getName()); 
		$exp = $this->kn->get($player->getName()); 
	    $maxexp= $level * 100000; 
		$form->setTitle("§l§e•§c Info §e•");
        $form->setContent("§l§c•§f Thông Tin thẻ thời không §c •\n§l§7- §eTên người chơi: $name\n§l§7-§e Cấp thẻ hiện tại: $level\n§l§7- §eKinh nghiệm: §l§a$exp/§c$maxexp"
);
		$form->addButton("§l§e•§c Trở Lại §e•");
		$form->sendToPlayer($player);
		return true;
	}
}
