<?php

namespace stone;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Main extends PluginBase implements Listener {
    
    
public function onEnable() {
		$this->getLogger()->info("\n\n§4Plugin stone Code By LetTIHL\n\n");
$this->getServer()->getPluginManager()->registerEvents($this ,$this);
@mkdir($this->getDataFolder(), 0744, true);
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->kn = new Config($this->getDataFolder()."kn.yml",Config::YAML);
        $this->m = new Config($this->getDataFolder()."manh.yml",Config::YAML);
}

   public function onJoin(PlayerJoinEvent $ev) {
     if(!$this->m->exists($ev->getPlayer()->getName())) {
     $this->m->set($ev->getPlayer()->getName(), 0);
     $this->m->save();
    }
     if(!$this->kn->exists($ev->getPlayer()->getName())) {
     $this->kn->set($ev->getPlayer()->getName(), 0);
     $this->kn->save();
    }
}

    public function onQuit(PlayerQuitEvent $ev) {
   $this->m->save();
   $this->kn->save();
    }
    
    //Kiếm Bcoin
    public function onBreak(BlockBreakEvent $ev){
		$player = $ev->getPlayer();
		if(!$ev->isCancelled()){
			$id = mt_rand(1,30);
			if($id == 11){
			$this->addM($player);
			}
		}
	}

    public function addM($sender){
       $this->m->set($sender->getName(), ($this->m->get($sender->getName()) + 1));
     $sender->sendMessage("§l§e•§bbạn đã nhặt được mảnh mine /stone để kiểm tra");
         $this->m->save();
    }
    
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "stone":
            $this->stone($sender);
            return true;
        }
        return true;
	}
	
    public function stone($sender){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
				    case 0:
				        break;
					case 1:
					$this->create($sender);
					break;
					case 2:
        $item = $sender->getInventory()->getItemInHand()->getCustomName();
        $id = $sender->getInventory()->getItemInHand()->getId();
        if(!$id == 388){
			$sender->sendMessage("§l§e•§eVui Lòng Cầm Đúng Item Đá");
        }
    if($item == "§l§cđá tốc độ"){
			$inv = $sender->getInventory();  
			$item = Item::get(0, 0, 1);
        $inv->setItemInHand($item);
                    $sender->addEffect(new EffectInstance(Effect::getEffect(1), 99990, 1, false));
			$sender->sendMessage("§l§e•§esử dụng Thành Công Đá Tốc Độ");
    }
    
    
    if($item == "§l§cđá giáp"){
			$inv = $sender->getInventory();  
			$item = Item::get(0, 0, 1);
        $inv->setItemInHand($item);
                    $sender->addEffect(new EffectInstance(Effect::getEffect(11), 99990, 1, false));
			$sender->sendMessage("§l§e•§esử dụng Thành Công Đá giáp");
    }
    
    
    if($item == "§l§cđá nhảy cao"){
			$inv = $sender->getInventory();  
			$item = Item::get(0, 0, 1);
        $inv->setItemInHand($item);
                    $sender->addEffect(new EffectInstance(Effect::getEffect(8), 99990, 1, false));
			$sender->sendMessage("§l§e•§esử dụng Thành Công Đá nhảy cao");
    }
    
    
    if($item == "§l§cđá bảo vệ khỏi lửa"){
			$inv = $sender->getInventory();  
			$item = Item::get(0, 0, 1);
        $inv->setItemInHand($item);
                    $sender->addEffect(new EffectInstance(Effect::getEffect(12), 99990, 1, false));
			$sender->sendMessage("§l§e•§esử dụng Thành Công Đá bảo vệ khỏi lửa");
    }
					break;
				}
			});
	$m = $this->m->get($sender->getPlayer()->getName());
			$form->setTitle("§l§f•§aStone§f•");
			$form->setContent("§c• §b§lmảnh Miner: §2".$m);
			$form->addButton("§l§f•§cThoát§f•");
			$form->addButton("§l§f•§aghép mảnh§f•");
			$form->addButton("§l§f•§atrang bị đá§f•");
			$form->sendToPlayer($sender);
    }
    public function create($sender){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
				    case 0:
				    break;
						case 1:
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
$m = $this->m->get($sender->getPlayer()->getName());
    if($m >= 1000){
			$inv = $sender->getInventory();  
			$item = Item::get(388, 0, 1);
			$item->setCustomName("§l§cđá tốc độ");
	     	$item->setLore(array("§a§lCông Dụng: §bsử dụng đá sẽ được tăng tốc độ chạy"));
        $id = 17;
        $lv = 0;
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($id), $lv));
		$inv->setItemInHand($item);
       $this->m->set($sender->getName(), ($this->m->get($sender->getName()) - 1000));
       $this->m->save();
			$sender->sendMessage("§l§e•§eTạo Thành Công Đá Speed");
    }else{
		$sender->sendMessage("§l§e•§eKhông đủ mảnh để ghép đá");
    }
     }else{
		$sender->sendMessage("§l§e•§eYêu cầu tay bạn Không cầm gì để nhận đá");
    }
                    break;
                    case 2:
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
$m = $this->m->get($sender->getPlayer()->getName());
    if($m >= 1000){
			$inv = $sender->getInventory();  
			$item = Item::get(388, 0, 1);
			$item->setCustomName("§l§cđá giáp");
	     	$item->setLore(array("§a§lCông Dụng: §bsử dụng đá sẽ được tăng giáp"));
        $id = 17;
        $lv = 0;
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($id), $lv));
		$inv->setItemInHand($item);
       $this->m->set($sender->getName(), ($this->m->get($sender->getName()) - 1000));
       $this->m->save();
			$sender->sendMessage("§l§e•§etạo thành công đá giáp");
    }else{
		$sender->sendMessage("§l§e•§eKhông đủ mảnh để ghép đá");
    }
     }else{
		$sender->sendMessage("§l§e•§eYêu cầu tay bạn Không cầm gì để nhận đá");
    }
                    break;
                    case 3:
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
$m = $this->m->get($sender->getPlayer()->getName());
    if($m >= 1000){
			$inv = $sender->getInventory();  
			$item = Item::get(388, 0, 1);
			$item->setCustomName("§l§cđá nhảy cao");
	     	$item->setLore(array("§a§lCông Dụng: §bsử dụng đá sẽ được tăng tốc độ nhảy cao"));
        $id = 17;
        $lv = 0;
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($id), $lv));
		$inv->setItemInHand($item);
       $this->m->set($sender->getName(), ($this->m->get($sender->getName()) - 1000));
       $this->m->save();
			$sender->sendMessage("§l§e•§eTạo Thành Công Đá nhảy cao");
    }else{
		$sender->sendMessage("§l§e•§eKhông đủ mảnh để ghép đá");
    }
     }else{
		$sender->sendMessage("§l§e•§eYêu cầu tay bạn Không cầm gì để nhận đá");
    }
                    break;
                    case 4:
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
$m = $this->m->get($sender->getPlayer()->getName());
    if($m >= 1000){
			$inv = $sender->getInventory();  
			$item = Item::get(388, 0, 1);
			$item->setCustomName("§l§cđá bảo vệ khỏi lửa");
	     	$item->setLore(array("§a§lCông Dụng: §bsử dụng đá sẽ được bảo vệ khỏi lửa"));
        $id = 17;
        $lv = 0;
       $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($id), $lv));
		$inv->setItemInHand($item);
       $this->m->set($sender->getName(), ($this->m->get($sender->getName()) - 1000));
       $this->m->save();
			$sender->sendMessage("§l§e•§eTạo Thành Công Đá bảo vệ khỏi lửa");
    }else{
		$sender->sendMessage("§l§e•§eKhông đủ mảnh để ghép đá");
    }
     }else{
		$sender->sendMessage("§l§e•§eYêu cầu tay bạn Không cầm gì để nhận đá");
    }
                    break;
				}
			});
	$m = $this->m->get($sender->getPlayer()->getName());
			$form->setTitle("§l§f•§aStone§f•");
			$form->setContent("§c• §b§lmảnh Miner: §f".$m);
            $form->addButton("§l§f•§cThoát");
			$form->addButton("§l§f•§bghép đá speed§f•\n§e1000 mảnh / 1 đá");
			$form->addButton("§l§f•§bghép đá bảo vệ§f•\n§e1000 mảnh / 1 đá");
			$form->addButton("§l§f•§bghép đá nhảy §f•\n§e1000 mảnh / 1 đá");
			$form->addButton("§l§f•§bghép đá bảo vệ khỏi lửa§f•\n§e1000 mảnh / 1 đá");
			$form->sendToPlayer($sender);
    }
}