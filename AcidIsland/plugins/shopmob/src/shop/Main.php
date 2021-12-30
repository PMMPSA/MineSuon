<?php

namespace shop;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;

Class Main extends PluginBase implements Listener{
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->ldlc = new Config($this->getDataFolder()."ldlc.yml",Config::YAML);
}
    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->ldlc->exists($ev->getPlayer()->getName())) {
            $player = $ev->getPlayer();
             $this->getServer()->getCommandMap()->dispatch($player,"ai auto");
            $this->getServer()->getCommandMap()->dispatch($player,"ai claim");
            $this->ldlc->set($ev->getPlayer()->getName());
            $this->ldlc->save();
			return;
       }
		   $player = $ev->getPlayer();
		    $this->getServer()->getCommandMap()->dispatch($player,"ai home 1");
    }
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "mob":
                $this->menu($sender);
           return true;
        }
        return true;
	}
   public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $nbt = Entity::createBaseNBT($sender->asPosition(), null, $sender->getYaw());  
          $result = $data;
         $level = $this->getServer()->getPlayer($sender->getName());
        $levelname = $level->getLevel()->getName();
        if ($levelname == "sb"){
        }else{
        $sender->sendMessage("§l§ebạn Chỉ Có Thể Mua Mob Ở Đảo");
        }    
          $count = 500000;
  $money = $this->eco->myMoney($sender);
		if($money >= 500000){
		}else{
		    $sender->sendMessage("§l§ebạn Cần §f$count §aPoint§e để Mua Mob");    
		    return;
		}
          if($result === null){
              return;
          }
          switch($result){
              case 0:
              break;
              case 1:
        $this->eco->reduceMoney($sender, $count);
        //H mẹ
        $entity = Entity::createEntity("Pig", $sender->getLevel(), $nbt);
        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
         //H Bố
        $entity = Entity::createEntity("Pig", $sender->getLevel(), $nbt);
 

        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
      $sender->sendMessage("§l§bMua Thành Công");    
              break;
             case 2:
        $this->eco->reduceMoney($sender, $count);
        //H mẹ
        $entity = Entity::createEntity("Cow", $sender->getLevel(), $nbt);
 
       
        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
         //H Bố
        $entity = Entity::createEntity("Cow", $sender->getLevel(), $nbt);
 
       
        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
      $sender->sendMessage("§l§bMua Thành Công");          
              break;
                case 3:
        $this->eco->reduceMoney($sender, $count);
        //H mẹ
        $entity = Entity::createEntity("Chicken", $sender->getLevel(), $nbt);
 
        $entity->setNameTag("§l§eGà Bố");
        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
         //H Bố
        $entity = Entity::createEntity("Chicken", $sender->getLevel(), $nbt);
 

        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
           $sender->sendMessage("§l§bMua Thành Công");   
              break;
              case 4:
        $this->eco->reduceMoney($sender, $count);
        //H mẹ
        $entity = Entity::createEntity("Ocelot", $sender->getLevel(), $nbt);
 

        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
         //H Bố
        $entity = Entity::createEntity("Ocelot", $sender->getLevel(), $nbt);
 

        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
      $sender->sendMessage("§l§bMua Thành Công");          
              break;
             case 5:
        $this->eco->reduceMoney($sender, $count);
        //H mẹ
        $entity = Entity::createEntity("Sheep", $sender->getLevel(), $nbt);
 

        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
         //H Bố
        $entity = Entity::createEntity("Sheep", $sender->getLevel(), $nbt);
 

        $entity->setScale(1);
        $entity->setMaxHealth(20);
        $entity->setHealth(20);
        $sender->getLevel()->addEntity($entity);
         $entity->spawnToAll();
      $sender->sendMessage("§l§bMua Thành Công");          
              break;
         }
        });
         $form->setTitle("§b§lShop Mob");
        $form->setContent("§l§b•§fĐồng Giá 500k");
		$form->addButton("§f•§c§lOut§f•");
		$form->addButton("§f•§a§lMua 1 cặp Heo§f•\n§fẤn Vào Để Mua");
		$form->addButton("§f•§a§lMua 1 cặp Bò§f•\n§fẤn Vào Để Mua");
	   $form->addButton("§f•§a§lMua 1 cặp Gà§f•\n§fẤn Vào Để Mua");
	   $form->addButton("§f•§a§lMua 1 cặp Mèo§f•\n§fẤn Vào Để Mua");
	   $form->addButton("§f•§a§lMua 1 cặp Cừu§f•\n§fẤn Vào Để Mua");
        $form->sendToPlayer($sender);
	}
}
