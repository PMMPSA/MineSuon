<?php
namespace AndreasHGK\AutoMineReset;
use pocketmine\command\CommandExecutor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
class Main extends PluginBase{
	
	public $paused = false;
	private $sec = 0;
	public $autopaused = false;
	private $interval = 600;
	
	public function onLoad(){
		//$this->getLogger()->notice(C::GREEN." Đang tải...");
		$this->saveDefaultConfig();
        	$this->reloadConfig();
		if(is_int($this->getConfig()->get('reset-time')) == false){
			if(is_bool($timerseconds > $this->getConfig()->get('sleep-when-empty')) == false){
				$this->getLogger()->notice("⨲§l§c Cấu Hình config.yml Được Thiết Lập Không Chính Xác!");
			}
		}

	}
	
	public function prepLoop(){
		$this->sec = 1;
		$this->interval = $this->getConfig()->get('reset-time');
		$this->milliseconds = 1000;
		$task = new Updater($this);
		$h = $this->getScheduler()->scheduleRepeatingTask($task, 20);
	}
	
	public function onEnable(){
		//$this->getLogger()->notice(C::GREEN." Enabled!");
		$this->prepLoop();
	}
	
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		switch(strtolower($cmd->getName())){
			case "mr";
			if($sender->hasPermission("minereset.command.resetall")){
				$this->resetAll();
				return true;
			}
			else{
            $sender->sendMessage("⨲§l§c Bạn Không Có Quyền Sử Dụng Câu Lệnh Này!");
			return true;
            }
			break;
		case "autoreset":
			if($sender->hasPermission("amr.autoreset")){
				if($this->paused == true){
				$sender->sendMessage("⨲§l§c Tính Năng Này Đã Bị Vô Hiệu Hóa!");
				// $this->paused = false;
				// $sender->sendMessage(C::BOLD.C::AQUA."»".C::RESET.C::DARK_AQUA." Automatic reset has been ".C::GREEN."enabled".C::DARK_AQUA."!");
				// $this->getLogger()->notice(C::GREEN."  ".$sender->getName()."!");
				}else{
				// $this->paused = true;
				// $sender->sendMessage(C::BOLD.C::AQUA."»".C::RESET.C::DARK_AQUA." Automatic reset has been ".C::RED."disabled".C::DARK_AQUA."!");
				// $this->getLogger()->notice(C::GREEN." The timer has been disabled by ".$sender->getName()."!");
				// $this->autopaused = false;
				$sender->sendMessage("⨲§l§c Tính Năng Này Đã Bị Vô Hiệu Hóa!");
				}
			} else {
            $sender->sendMessage("⨲§l§c Bạn Không Có Quyền Sử Dụng Câu Lệnh Này!");
		}
		return true;
		break;
	}
	}
	
	public function autostop(){
		static $y = false;
			if($this->getConfig()->get('sleep-when-empty') == true){
				if(count($this->getServer()->getOnlinePlayers()) != 0){
					if($this->paused == true){
						if($y == true){
						$this->paused = false;
						$this->autopaused = false;
						$y = false;
						//$this->getLogger()->notice(C::GREEN." The timer has been auto-enabled!");
						}
					}
					} else {
						if($y == false) {
						$this->paused = true;
						$y = true;
						$this->autopaused = true;
						//$this->getLogger()->notice(C::GREEN." The timer has been auto-disabled!");
						}
					}
			}
		}
	public function resetAll(){
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset-all");
/*		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset a");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset b");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset c");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset d");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset e");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset f");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset g");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset h");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset i");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset j");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset k");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset l");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset m");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset n");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset o");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset p");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset q");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset r");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset s");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset t");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset u");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset v");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset w");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset x");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset y");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset z");*/

		
		Server::getInstance()->broadcastMessage("⨲§l§a Toàn Bộ Các Khu Mỏ Khai Thác Đã Được Lắp Đầy!");
		}
	
	
	public function onDisable(){
		//$this->getLogger()->notice(C::GREEN." Disabled!");
	}
	
	public function update() {
		$this->autostop();
		$this->bettertimer();
		if($this->sec >= $this->interval){
			$this->resetAll();
		}
		}
	
	public function betterTimer() {
		if($this->paused == false){
			$this->sec++;
		}
		if($this->sec > $this->interval){
			$this->sec = 1;
		}
		//$this->getLogger()->notice(C::GREEN."Debug(sec): ".$this->sec);
		//$this->getLogger()->notice(C::GREEN."Debug(interval): ".$this->interval);
	}
	
}
