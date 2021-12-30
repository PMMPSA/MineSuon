<?php

namespace up;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;

Class Main extends PluginBase implements Listener{
    
    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        @mkdir($this->getDataFolder(), 0744, true);
       $this->m = new Config($this->getDataFolder()."max.yml",Config::YAML);
}
    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->m->exists($ev->getPlayer()->getName())) {
            $this->m->set($ev->getPlayer()->getName(), 5);
            $this->m->save();
         }
    }
    
    public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "up":
                $this->menu($sender);
           return true;
        }
        return true;
    }
                
    public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
              $inv = $sender->getInventory()->getItemInHand();
              $id = $inv->getId();                   $cus = $sender->getInventory()->getItemInHand()->getCustomName();
     $pas = explode(" ", $cus);
              if($pas[0] == "§l§c|§a"){
                  return;
              }
              if($id ==0 ){
                $sender->sendMessage("§l§3[§cOhayo§3]§r Bạn chỉ có thể nâng cấp các vũ khí áo giáp bằng §bkim cương§f như : §ccúp§f, §ckiếm§f, §cGiáp§f,§c Vật Phẩm Phải Là Kim Cương");
                  return;
              }   
              if($this->eco->myMoney($sender) < 500000){
                $sender->sendMessage("§l§3[§cOhayo§3]§r Tiền của bạn là không đủ vui lòng kiếm thêm");
                return;
              }     
             
              //Witch 2
              switch($id){
              case 278:
                 $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                 $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage("§l§3[§cOhayo§3]§r Giới hạn nâng cấp");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
                $item->setCustomName("§l§3•§f Cúp Bakuga §e| §c+ §a".$f);
                $item->setLore(array("§l§3•§f Vật phẩm của bạn hiện tại đã thần thục: §a".$f));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(18), $f));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("§l§3[§cOhayo§3]§r Chúc mừng ".$sender->getName()."§f đã nâng cúp lên §c+§a".$f);
                $this->eco->reduceMoney($sender, 500000);
              break;
              case 276:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::SHARPNESS) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::FIRE_ASPECT) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                $item = Item::get($id, 0, 1);
                if($ec1 >= 6){
                   $sender->sendMessage("§l§3[§cOhayo§3]§rnâng cấp giới hạn 6 không thể tăng thêm");   
                   return;  
                }
                  $item->setCustomName("§l§3•§f Kiếm Bakuga §e| §c+ §a ".$ec1);
                $item->setLore(array("§l§3•§f Vật phẩm của bạn hiện tại đã thần thục: §a".$ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(13), 1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("§l§3[§cOhayo§3]§rChúc mừng §f".$sender->getName()."§f đã nâng thành công kiếm lên §c+§a".$ec1);                 $this->eco->reduceMoney($sender, 100000);
              break;    
              case 310:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                   $sender->sendMessage("§l§3[§cOhayo§3]§r Hiện tại bạn bị giới hạn cấp hãy nâng cấp giới hạn cấp của mình");   
                   return;  
                } 
                $item = Item::get($id, 0, 1);
                                                        $item->setCustomName("§l§3•§f Mũ Bakuga §e| §c+ §a".$ec1);
                $item->setLore(array("§l§3•§f Vật phẩm của bạn hiện tại đã thần thục: §a".$ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("§l§3[§cOhayo§3]§r Chúc mừng §f".$sender->getName()."§f đã nâng mũ thành công lên §c+§a".$ec1);                 $this->eco->reduceMoney($sender, 100000);           
              break;
              case 311:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                   $sender->sendMessage("§l§3[§cOhayo§3]§r Hiện tại bạn bị giới hạn cấp hãy nâng cấp giới hạn cấp của mình");   
                   return;  
                }
                $item = Item::get($id, 0, 1);
                $item->setCustomName("§l§3•§f Áo Bakuga §e| §c+ §a".$ec1);
                $item->setLore(array("§l§3•§f Vật phẩm của bạn hiện tại đã thần thục: §a".$ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("§l§3[§cOhayo§3]§r Chúc mừng §f".$sender->getName()."§f Đã nâng áo thành công lên §c+§a".$ec1);                  $this->eco->reduceMoney($sender, 100000);
              break;  
              case 312:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                  $sender->sendMessage("§l§3[§cOhayo§3]§r Hiện tại bạn bị giới hạn cấp hãy nâng cấp giới hạn cấp của mình");   
                  return;  
                }
                $item = Item::get($id, 0, 1);
                $item->setCustomName("§l§3•§f Quần Bakuga §e| §c+ §a ".$ec1);
                $item->setLore(array("§l§3•§f Vật phẩm của bạn hiện tại đã thần thục: §a".$ec1));       
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
                 $sender->sendMessage("§l§3[§cOhayo§3]§r Chúc mừng §f".$sender->getName()."§f đã nâng quần thành công lên §c+§a".$ec1);                 $this->eco->reduceMoney($sender, 100000);
              break;
              case 313:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                  $sender->sendMessage("§l§3[§cOhayo§3]§r Hiện tại bạn bị giới hạn cấp hãy nâng cấp giới hạn cấp của mình");
                  return;  
                }
                $item = Item::get($id, 0, 1);
                $item->setCustomName("§l§3•§f Giày Bakuga §e| §c+ §a".$ec1);
                $item->setLore(array("§l§3•§f Vật phẩm của bạn hiện tại đã thần thục: §a".$ec1));                  $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("§l§3[§cOhayo§3]§r Chúc mừng §f".$sender->getName()."§f đã nâng cấp giày thành công lên §c+§a".$ec1);                 $this->eco->reduceMoney($sender, 100000);    
              break;
              default:
                $sender->sendMessage("§l§3[§cOhayo§3]§r Sức mạnh vật phẩm của bạn đã bị §aFalco§f từ chối nâng cấp§b\n§c Nâng cấp thất bại");
              }
              break;
              case 2:
                $m = $this->m->get($sender->getName());
                if($m >= 20){
                    $sender->sendMessage("§l§3[§cOhayo§3]§r Nâng cấp đã tối đa"); 
                    return;
                }
               if($this->eco->myMoney($sender) < 100000){
                $sender->sendMessage("§l§3[§cOhayo§3]§r Tiền của bạn không đủ vui lòng kiếm thêm");
                return; 
               }     
               $id = mt_rand(1,10);
               if($id == 5){
                  $this->m->set($sender->getName(), ($this->m->get($sender->getName()) + 1));
                  $this->m->save;
                  $sender->sendMessage("§l§3[§cOhayo§3]§r Chúc mừng §f".$sender->getName()." §f đã phá bỏ giới hạn thành công và lên §c+".$this->m->get($sender->getName()));
                  $this->eco->reduceMoney($sender, 100000);         
               }else{
                   $sender->sendMessage("§l§3[§cOhayo§3]§r§c Phá bỏ giới hạn thất bại");
                    $this->eco->reduceMoney($sender, 100000);         
				   $this->menu($sender);
               }
              break;
         }
        });
		$uptext = $this->getConfig()->get("uptext");
		$upimage = $this->getConfig()->get("upimage");
		$exittext = $this->getConfig()->get("exittext");
		$exitimage = $this->getConfig()->get("exitimage");
		$up2text = $this->getConfig()->get("up2text");
		$up2image = $this->getConfig()->get("up2image");
        $m = $this->m->get($sender->getName());
        $form->setTitle("§l§3•§d Nâng cấp Item§3 •");
        $form->setContent("§l§3•§f Giới hạn nâng cấp của bạn: §f+$m");
        $form->addButton("§l§c Thoát",0,"textures/ui/cancel");
        $form->addButton("§l§6Nâng Cấp",0,"textures/ui/hammer_l");
        $form->addButton("§l§6Phá Bỏ Giới Hạn",0,"textures/ui/hammer_l");
        $form->sendToPlayer($sender);
    }
}
