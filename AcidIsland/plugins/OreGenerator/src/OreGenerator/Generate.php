<?php

namespace OreGenerator;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\block\Iron;
use pocketmine\block\Cobblestone;
use pocketmine\block\Glowingobsidian;
use pocketmine\block\Diamond;
use pocketmine\block\Emerald;
use pocketmine\block\Gold;
use pocketmine\block\Coal;
use pocketmine\block\Lapis;
use pocketmine\block\Redstone;
use pocketmine\block\IronOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\EmeraldOre;
use pocketmine\block\GoldOre;
use pocketmine\block\CoalOre;
use pocketmine\block\LapisOre;
use pocketmine\block\RedstoneOre;
use pocketmine\block\Stone;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\Config;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Generate extends PluginBase implements Listener{

    public function onEnable(){
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");		
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
@mkdir($this->getDataFolder(), 0744, true);
       $this->ore = new Config($this->getDataFolder()."ore.yml",Config::YAML);
    if(!$this->ore->exists("ore")){
        $this->ore->set("ore", 245); 
        $this->ore->save();
    }
    }
  
    public function oreRandom(BlockUpdateEvent $event){
        $block = $event->getBlock();
        $end = false;
        for ($i = 0; $i <= 0; $i++) {
           $idblock = $block->getSide($i)->getId();
            $ore = $this->ore->get("ore");          
            if ($idblock !== $ore) {
                return;
            }
                $id = mt_rand(1, 20);
                switch ($id) {
                    case 2;
                        $newBlock = new IronOre();
                        break;
                    case 4;
                        $newBlock = new GoldOre();
                        break;
                    case 6;
                        $newBlock = new EmeraldOre();
                        break;
                    case 8;
                        $newBlock = new CoalOre();
                        break;
                    case 10;
                        $newBlock = new RedstoneOre();
                        break;
                    case 12;
                        $newBlock = new DiamondOre();
                        break;
					case 14;
                        $newBlock = new LapisOre();
                        break;
                    case 7;
                        $newBlock = new Iron();
                        break;
                    case 9;
                        $newBlock = new Gold();
                        break;
                    case 6;
                        $newBlock = new Emerald();
                        break;
                    case 11;
                        $newBlock = new Coal();
                        break;
                    case 15;
                        $newBlock = new Redstone();
                        break;
                    case 17;
                        $newBlock = new Diamond();
                        break;
					case 19;
                        $newBlock = new Lapis();
                        break;	
                    default:
                        $newBlock = new Stone();
                }
                $block->getLevel()->setBlock($block, $newBlock, true, false);
                return;
        }
    }
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "ore":
            $this->menu($sender);
            return true;
        }
        return true;
	}
	
	 public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
            if (!$sender->getInventory()->canAddItem(Item::get(0, 0, 1))){
				$sender->sendMessage("§l§3• §l§fTúi đồ của bạn không còn trống ");
				return;
			}               
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
            $money = $this->eco->MyMoney($sender);
            if($money >= 20000){
               $this->eco->reduceMoney($sender, 20000);	
               $id = $this->ore->get("ore");			
			   $item = Item::get($id, 0, 1);
			   $item->setCustomName("§l§fOreGenerator Simple");
               $item->setLore(array(""));
			   $sender->getInventory()->addItem($item);
		       $sender->sendMessage("§l§3●§e Bạn đã nhận được block §l§fOreGenerator Simple");
            }else{
			  $sender->sendMessage("§l§3●§c Bạn không đủ tiền để mua block §l§fOreGenerator Simple");                
            } 
              break; 
         }
        });
		$content = $this->getConfig()->get("content");
        $title = $this->getConfig()->get("title");
        $button1 = $this->getConfig()->get("button1");
        $image1 = $this->getConfig()->get("Game-button1-image");
        $image = $this->getConfig()->get("Game-exit-image");
		$exit = $this->getConfig()->get("Game-exit");
        $form->setTitle($title);
        $form->setContent($content);
		$form->addButton($exit,1,"$image");
        $form->addButton($button1,1,"$image1");
        $form->sendToPlayer($sender);
	}
    
}
