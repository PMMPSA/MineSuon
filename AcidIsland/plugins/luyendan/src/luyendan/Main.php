<?php

namespace luyendan;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
       @mkdir($this->getDataFolder(), 0744, true);
       $this->linhthao = new Config($this->getDataFolder()."linhthao.yml",Config::YAML);			
    }

    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->linhthao->exists($ev->getPlayer()->getName())) {
        $this->linhthao->set($ev->getPlayer()->getName(), 0);
        $this->linhthao->save();
        }
    }

    public function onBreak(BlockBreakEvent $ev){
        if(!$ev->isCancelled()){
            if($ev->getBlock()->getId() == 235){
                $slot = mt_rand(1,3);
                   $this->linhthao->set($ev->getPlayer()->getName(), $this->linhthao->get($ev->getPlayer()->getName()) + $slot);
		    }
        }
    }

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "luyendan":
            $id = $sender->getInventory()->getItemInHand()->getId();
            if(!$id == 0){
     		  $sender->sendMessage("§l§c•§b yêu cầu tay không cầm gì để luyện đan");       
            }else{              
            $this->menu($sender);
            }
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
            $linhthao = $this->linhthao->get($sender->getName());
            if($linhthao >= 150){
               $linhthao = $this->linhthao->set($sender->getName(), $this->linhthao->get($sender->getName()) - 150);                
              $this->linhthao->save();
              $id = mt_rand(1,10);
              if(!$id == 4){
                $sender->sendMessage("§l§f•§c luyện đan thất bại");
                return;
              }
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cLinh động đan");
              $item->setLore(array("§e§lTác dụng: §f+3 hp gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã luyện thành công §l§clinh động đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ linh thao để luyện §l§cLinh động đan");                
            }
              break;
              case 1:
            $linhthao = $this->linhthao->get($sender->getName());
            if($linhthao >= 150){
                
               $linhthao = $this->linhthao->set($sender->getName(), $this->linhthao->get($sender->getName()) - 150);
              $this->linhthao->save();
                 $id = mt_rand(1,10);
              if(!$id == 4){
                $sender->sendMessage("§l§f•§c luyện đan thất bại");
                return;
              }
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cGân cốt đan");
              $item->setLore(array("§e§lTác dụng: §f+3 giáp gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã luyện thành công §l§cGân cốt đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ linh thảo để luyện §l§cGân cốt đan");                
            }
              break;
              case 2:
            $linhthao = $this->linhthao->get($sender->getName());
            if($linhthao >= 150){
               $linhthao = $this->linhthao->set($sender->getName(), $this->linhthao->get($sender->getName()) - 150);
              $this->linhthao->save();
              $id = mt_rand(1,10);
              if(!$id == 4){
                $sender->sendMessage("§l§f•§c luyện đan thất bại");
                return;
              }
			  $item = Item::get(341, 0, 1);
		      $item->setCustomName("§l§cBạo nguyên đan");
              $item->setLore(array("§e§lTác dụng: §f+3 dame gốc"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c•§b Bạn đã luyện thành công l§cBạo nguyên đan");
            }else{
			  $sender->sendMessage("§l§c•§b Bạn không đủ linh thảo để luyện l§cBạo nguyên đan");                
            }
              break;  
         }
        });
        $linhthao = $this->linhthao->get($sender->getName());
        $form->setTitle("§l§f•§b Luyện Đan §f•");
        $form->setContent("§l§f•§a Linh thảo§f: §e".$linhthao."\n\n§c•§e Kiếm linh thảo từ đào luckyblock\n ");
		$form->addButton("§l§f•§e Luyện §cLinh động đan §f•\nTốn 500 linh thảo");
		$form->addButton("§l§f•§e Luyện §cGân cốt đan §f•\nTốn 500 linh thảo");
 		$form->addButton("§l§f•§e Luyện §cbạo nguyên đan §f•\nTốn 500 linh thảo");      
        $form->sendToPlayer($sender);
	}
}
