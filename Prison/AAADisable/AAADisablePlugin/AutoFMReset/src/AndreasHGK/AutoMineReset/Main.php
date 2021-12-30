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
			case "mrs";
			if($sender->hasPermission("mineresets.command.resetall")){
				$this->resetAll();
				return true;
			}
			else{
            $sender->sendMessage("⨲§l§c Bạn Không Có Quyền Sử Dụng Câu Lệnh Này!");
			return true;
            }
			break;
		case "autoresets":
			if($sender->hasPermission("amr.autoresets")){
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
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"mine reset fr");
		
		Server::getInstance()->broadcastMessage("⨲§l§a Khu Khai Thác Tự Do Đã Được Lấp Đầy!");
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
