<?php

namespace luckyblock;

use pocketmine\{Server, Player};
use pocketmine\command\{ConsoleCommandSender, Command, CommandSender};
use pocketmine\event\{Listener, block\BlockBreakEvent};
use pocketmine\item\Item;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;


Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
        $this->b = new Config($this->getDataFolder()."block.yml",Config::YAML);
}
   public function onJoin(PlayerJoinEvent $ev) {
     if(!$this->b->exists($ev->getPlayer()->getName())) {
     $this->b->set($ev->getPlayer()->getName(), 0);
     $this->b->save();
    }
   }

public function onbreak(BlockBreakEvent $ev) {
    
    
        $this->ores=array(235);
          $p = $ev->getPlayer();
          $block = $ev->getBlock();
          $ev->setInstaBreak(true);
        foreach($this->ores as $ore){
          if($block->getId() === $ore && !$ev->isCancelled()){
          $this->b->set($ev->getPlayer()->getName(), ($this->b->get($ev->getPlayer()->getName()) + 1));
          $this->b->save;
            $ev->setDrops(array());
                      $id = $ev->getPlayer()->getInventory()->getItemInHand()->getCustomName();
          if($id == "§l§6•§3 Pickaxe §eLuckyBlock §l§6•"){
          $id = mt_rand(1,1000);
             if($id == 50){
            $this->eco->addMoney($p, 100000);    
                    $this->getServer()->broadcastMessage("§l§6•§f Chúc Mừng ".$p->getName()." Đã Đạt Giải §e50k §fXu từ luckyblock");   
             }else{
                 $h = mt_rand(1,100);
               $xu = 10 * $h;
            $this->eco->addMoney($p, $xu);   
            $p->sendPopup("§l§6•§f Bạn Đã Nhận Được §e".$xu."§f xu");
            }
          }else{
          $this->getServer()->getCommandMap()->dispatch($p,"ai home");
        	$this->getServer()->broadcastMessage("§l§6•§f Yêu cầu bạn sài cúp luckyblock ".$ev->getPlayer()->getName()." không bạn sẽ§c về đảo §f hãy sử dụng /luckyblock để nhận cúp");
          }    
        }
    }
}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "luckyblock":
            $this->menu($sender);
            break;
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
         	$inv = $sender->getInventory();  
			$item = Item::get(278, 0, 1);
			$item->setCustomName("§l§6•§3 Pickaxe §eLuckyBlock §l§6•");
			$item->setLore(array("§l§6•§fSKILL: §aĐào luckyblock"));
		  $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1000));
          $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 1));
			$inv->addItem($item);
			$sender->sendMessage("§l§6•§f Đã nhận cúp đào §eLuckyblock");    
              break;
              case 1:
              $this->top($sender);
              break;
         }
        });
         $form->setTitle("§l§6•§3 LuckyBlock §cMenu §l§6•");
		$form->addButton("§l§c『§fLấy cúp』");
		$form->addButton("§l§c『§fTop cúp』");
        $form->sendToPlayer($sender);
	}
	
		public function top(Player $sender){
		$levelplot = $this->b->getAll();
		$message = "";
		$message1 = "";
		if(count($levelplot) > 0){
			arsort($levelplot);
			$i = 1;
			foreach($levelplot as $name => $level){
				$message .= "§l§3TOP " . $i . ": §6" . $name . " §d→ §f" . $level . " §2Block\n\n";
				$message1 .= "§l§3TOP " . $i . ": §6" . $name . " §d→ §f" . $level . " §2Block\n";
				if($i >= 100){
					break;
				}
				++$i;
			}
		}
		
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			switch($result){
				case 0:
				break;
			}
		});
		$form->setTitle("§l§6•§3 Top §cBreak §l§6•");
		$form->setContent("§l§6•§3 Danh sách top luckyblock");
		$form->setContent($message);
		$form->addButton("§l§e•§c Thoát §e•");
		$form->sendToPlayer($sender);
		return true;
	}
}
