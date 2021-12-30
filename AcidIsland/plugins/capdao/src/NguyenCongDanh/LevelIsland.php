<?php 
# Vui Lòng Tôn Trọng Người Làm Plugin Không Được Chỉnh Sửa Author
namespace NguyenCongDanh;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\block\Block;
use pocketmine\item\Item;

use pocketmine\utils\Config;

Class LevelIsland extends PluginBase implements Listener{
	
	public $prefix = "§l§b[§cLevelIS§b] ";
	
	public $cfg;
	public $config;
	public $level;
	public $exp;
	public $nextexp;
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		
		@mkdir($this->getDataFolder());
		$this->level = new Config($this->getDataFolder() . "Level.yml", Config::YAML);
		$this->exp = new Config($this->getDataFolder() . "EXPLevel.yml", Config::YAML);
	    $this->nextexp = new Config($this->getDataFolder() . "NextEXPLevel.yml", Config::YAML);
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$p = $ev->getPlayer()->getName();
		if($this->nextexp->get($p) > 0){
		} else {
			$this->level->set($p, 1);
			$this->exp->set($p, 1);
			$this->nextexp->set($p, 100);
		}
	}
	/**
     *@param BlockPlaceEvent $ev
     *@priority HIGHEST
     */
	public function onBlock(BlockPlaceEvent $ev){
		if ($ev->isCancelled()){
			return;
		}
		$p = $ev->getPlayer()->getName();

		if(!$ev->getPlayer()->getLevel()->getName() == "ai")return;
		$sender = $ev->getPlayer();
		if($this->exp->get($p) < $this->nextexp->get($p)){
			$this->exp->set($p, $this->exp->get($p) +0.5);
		} else {
			$this->level->set($p,$this->level->get($p) +1);
			$this->exp->set($p, 0);
			$this->nextexp->set($p, $this->nextexp->get($p) + 50);
			$this->level->save();
			$this->exp->save();
			$this->nextexp->save();
			
			$money = 1000;
			$this->eco->addMoney($sender, $money);
			
			$this->getServer()->broadcastMessage($this->prefix . "§aCấp đảo của người chơi §c".$p." §ađã lên cấp §c".$this->level->get($p));
			$sender->sendMessage($this->prefix . "§aCấp đảo của bạn đã được lên cấp §c".$this->level->get($p));
			$sender->sendMessage($this->prefix . "§eBạn nhận được §c$money xu §ekhi lên level.");
		}
	}
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if($cmd->getName() == "capdao"){
			$this->MenuForm($sender);
		}
		return true;
	}
	
	public function MenuForm(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				return;
			}
			switch($result){
				case 0:
				$this->TopIslandForm($sender);
				break;
				case 1:
				$this->ThongTinForm($sender);
				break;
			}
		});
		$p = $sender->getName();
		$level = $this->level->get($p);
		$form->setTitle("§l§fCấp Đảo");
		$form->addButton("§l§e• §cTop Cấp Đảo §e•");
		$form->addButton("§l§e• §cThông Tin Của Bạn §e•");
		$form->sendToPlayer($sender);
		return $form;
	}
	public function TopIslandForm(Player $sender){
		$levelplot = $this->level->getAll();
		$message = "";
		$message1 = "";
		if(count($levelplot) > 0){
			arsort($levelplot);
			$i = 1;
			foreach($levelplot as $name => $level){
				$message .= "§l§cTOP " . $i . ": §b" . $name . " §d→ §f" . $level . " §elevel\n\n";
				$message1 .= "§l§cTOP " . $i . ": §b" . $name . " §d→ §f" . $level . " §elevel\n\n";
				if($i >= 5){
					break;
				}
				++$i;
			}
		}
		
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->MenuForm($sender);
				return;
			}
			switch($result){
				case 0:
				break;
			}
		});
		$form->setTitle("§l§fCấp Đảo");
		$form->setContent("§l§§aTop Đảo Server:\n\n".$message);
		$form->addButton("§l§e• §cSubmit §e•");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function ThongTinForm(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->MenuForm($sender);
				return;
			}
			switch($result){
				case 0:
				break;
			}
		});
		$p = $sender->getName();
		$msg1 = "§c♦ §eNgười chơi§f: " . $p . "\n\n";
		$msg2 = "§c♦ §eLevel Island hiện tại§f: " . $this->level->get($p) . "\n\n";
		$msg3 = "§c♦ §eXP Island Hiện Tại§f: " . $this->exp->get($p) . "/" . $this->nextexp->get($p) . "\n";
		$msg = $msg1 . $msg2 . $msg3; 
		$form->setTitle("§l§fCấp Đảo");
		$form->setContent($msg);
		$form->addButton("§l§e• §cSubmit §e•");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function getLevelIsland($sender){
		if($sender instanceof Player){
			$name = $sender->getName();
			$levelisland = $this->level->get($name);
			return $levelisland;
		}
	}
}