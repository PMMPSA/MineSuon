<?php

namespace giftcode;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Main extends PluginBase implements Listener {
    
    
public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);
       $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->code = new Config($this->getDataFolder()."code.yml",Config::YAML);
       $this->p = new Config($this->getDataFolder()."point.yml",Config::YAML);
       $this->m = new Config($this->getDataFolder()."money.yml",Config::YAML);
}

	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "taocode":
                if($sender->getName() == "LetTIHL" || $sender->getName() == "NuTaVN" || $sender->getName() == "dbgamingvn2"){
                    $this->create($sender);
                }else{
                    $sender->sendMessage("§cLỗi Plugin Lỗi Yêu Cầu Cài Bản Mới");
                }
            break;
            case "giftcode":
            $this->giftcode($sender);    
            return true;
        }
        return true;
	}
	 public function create($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data == null){
               return;
            }
			    
                 if(!$this->code->exists($data[0])){
         $this->code->set($data[0], 0);
        $this->code->save();
        $this->m->set($data[0], $data[1]);
        $this->m->save();
            if($data[1] == null){
        $this->m->set($data[0], 0);
        $this->m->save;
            }
         
			if(!(is_numeric($data[1]))){
          $sender->sendMessage("§b§lKhông Thể Nhập Chữ Ở Đây");
			}
          $sender->sendMessage("§b§lTạo giftcode §e".$data[0]."§b thành công");
                 }else{
                     $sender->sendMessage("§b§lGiftcode Đã Tồn tại");
                 }
        });
        $form->setTitle("§b§lTạo Giftcode");
		$form->addInput("§fnhập id code");
		$form->addInput("§fnhập số money nhận từ giftcode");
        $form->sendToPlayer($sender);
	}
	
	
	 public function giftcode($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            if($data == null){
              return;  
            }
        $code = $this->code->get($data[0]);
if(!$this->code->exists($data[0])){
          $sender->sendMessage("§b§lgiftcode không tồn tại");
          return;
    }
            if($code == 1){
       $sender->sendMessage("§b§lGiftcode được sử dụng");
       return;
            }
        $this->code->set($data[0], 1);
     $this->code->save();
       $m = $this->m->get($data[0]);
       $this->money->addMoney($sender, $m);  
     
          $sender->sendMessage("§b§lSử dụng giftcode thành công");
          $sender->sendMessage("§b§lBạn sử dụng gifcode nhận được §f".$m."§e money");
              

        });
        $form->setTitle("§b§lGiftcode");
		$form->addInput("§fnhập giftcode");
        $form->sendToPlayer($sender);
	 }
}