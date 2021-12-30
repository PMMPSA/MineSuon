<?php

namespace CoinAPI;

use pocketmine\{
	Player,
	Server,
	utils\Config,
	event\Listener,
	command\Command,
	plugin\PluginBase,
	command\CommandSender,
	event\player\PlayerJoinEvent
};

class Main extends PluginBase implements Listener {

	public function onLoad() {
		if(!is_dir($this->getDataFolder())) {
		 	@mkdir($this->getDataFolder());
			}
		}

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->coin = new Config($this->getDataFolder()."Coin.yml",Config::YAML);
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer()->getName();
		if(!$this->coin->exists($player)) {
			$this->coin->set($player, 0);
			$this->coin->save();
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		switch($command->getName()) {

			case "mycoin":
				if(!($sender instanceof Player)) {
					$sender->sendMessage("⨝§l§c Vui Lòng Thực Hiện Lệnh Này Trong Trò Chơi!");
					return true;
				}else{
					$sender->sendMessage("⨝§l§c Số Coin Của Bạn Là:§e {$this->coin->get($sender->getName())} Coin!");
					return true;
				}
			return true;
			break;
			
			case "seecoin":
				if(!isset($args[0])) {
					$sender->sendMessage("⨝§l§c Sử Dụng:§e /seecoin <Tên Người Chơi>");
					return true;
				}
				$player = $this->getServer()->getPlayer($args[0]);
				if(!$player) {
					$sender->sendMessage("⨝§l§c Không Tìm Thấy Người Chơi:§e {$args[0]}!");
					return true;
				}
				$sender->sendMessage("⨝§l§c Số Coin Của Người Chơi:§e {$player->getName()} §clà §e{$this->coin->get($player->getName())} Coin!");
				$player->sendMessage("⨝§l§c Người Chơi §e{$sender->getName()}§c Vừa Xem Số Coin Của Bạn!");
				return true;
				break;

			case "paycoin":
				if(!isset($args[0])) {
				$sender->sendMessage("⨝§l§c Sử Dụng:§e /paycoin <Tên Người Chơi> <Số Lượng Coin>");
				return true;
				}
				$player = $this->getServer()->getPlayer($args[0]);
				if(!$player) {
					$sender->sendMessage("⨝§l§c Không Tìm Thấy Người Chơi:§e {$args[0]}!");
					return true;
				}
				if(!is_numeric($args[1])) {
				$sender->sendMessage("⨝§l§c Số Coin Phải Là Số!");
				return true;
				}
				if(strrchr($args[1], ".") == true) {
  				$sender->sendMessage("⨝§l§c Số Coin Không Hợp Lệ!");
				return true;
				}
				if($args[1] < 1) {
				$sender->sendMessage("⨝§l§c Số Coin Phải Lớn Hơn §e1!");
				return true;
				}
				if($this->coin->get($sender->getName()) >= $args[1]) {
					$this->coin->set($sender->getName(), $this->coin->get($sender->getName()) - (int)$args[1]);
					$this->coin->set($player->getName(), $this->coin->get($player->getName()) + (int)$args[1]);
					$this->coin->save();
					$sender->sendMessage("⨝§l§c Đã Chuyển §e{$args[1]} Coin§c Cho Người Chơi §e{$player->getName()}!");
					$player->sendMessage("⨝§l§c Bạn Đã Nhận Được §e{$args[1]} Coin§c Từ Người Chơi §e{$sender->getName()}!");
				}else{
					$sender->sendMessage("⨝§l§c Bạn Không Đủ §e{$args[1]} Coin§c Để Chuyển Cho Người Chơi §e{$player->getName()}!");
				}
			return true;
			break;

			case "setcoin":
				if($sender->isOp()) {
					if(!isset($args[0])) {
						$sender->sendMessage("⨝§l§c Sử Dụng:§e /setcoin <Tên Người Chơi> <Số Lượng Coin>");
						return true;
					}
/*					if($args[1] < 1) {
						$sender->sendMessage("⨝§l§c Số Coin Phải Lớn Hơn §e1!");
						return true;
					}*/
					if(!is_numeric($args[1])) {
						$sender->sendMessage("⨝§l§c Số Coin Phải Là Số!");
						return true;
					}
					if(strrchr($args[1], ".") == true) {
  						$sender->sendMessage("⨝§l§c Số Coin Không Hợp Lệ!");
						return true;
					}
					$player = $this->getServer()->getPlayer($args[0]);
					if(!$player) {
						$sender->sendMessage("⨝§l§c Không Tìm Thấy Người Chơi:§e {$args[0]}!");
						return true;
					}
					$this->coin->set($player->getName(), $args[1]);
					$this->coin->save();
					$sender->sendMessage("⨝§l§c Số Coin Của Người Chơi§e {$player->getName()}§c Đã Được Chỉnh Thành§e {$args[1]} Coin!");
					$player->sendMessage("⨝§l§c Quản Trị Viên§e {$sender->getName()}§c Đã Chỉnh Số Coin Của Bạn Thành§e {$args[1]} Coin!");				
				}else{
					$sender->sendMessage("⨝§l§c Bạn Không Có Quyền Sử Dụng Câu Lệnh Này!");
				}
			return true;
			break;

			case "givecoin":
				if($sender->isOp()) {
					if(!isset($args[0])) {
						$sender->sendMessage("⨝§l§c Sử Dụng:§e /givecoin <Tên Người Chơi> <Số Lượng Coin>");
						return true;
					}
					if($args[1] < 1) {
						$sender->sendMessage("⨝§l§c Số Coin Phải Lớn Hơn §e1!");
						return true;
					}
					if(!is_numeric($args[1])) {
						$sender->sendMessage("⨝§l§c Số Coin Phải Là Số!");
						return true;
					}
					if(strrchr($args[1], ".") == true) {
  						$sender->sendMessage("⨝§l§c Số Coin Không Hợp Lệ!");
						return true;
					}
					$player = $this->getServer()->getPlayer($args[0]);
					if(!$player) {
						$sender->sendMessage("⨝§l§c Không Tìm Thấy Người Chơi:§e {$args[0]}!");
						return true;
					}
					$this->coin->set($player->getName(), $this->coin->get($player->getName()) + (int)$args[1]);
					$this->coin->save();
					$sender->sendMessage("⨝§l§c Số Coin Của Người Chơi§e {$player->getName()}§c Đã Được Tăng Thêm§e {$args[1]} Coin!");
					$player->sendMessage("⨝§l§c Số Coin Của Bạn Đã Được Tăng Thêm§e {$args[1]} Coin §cBởi Quản Trị Viên§e {$sender->getName()}");
					}else{
						$sender->sendMessage("⨝§l§c Bạn Không Có Quyền Sử Dụng Câu Lệnh Này!");
					}
					return true;
					break;

				case "takecoin":
				if($sender->isOp()) {
					if(!isset($args[0])) {
						$sender->sendMessage("⨝§l§c Sử Dụng:§e /takecoin <Tên Người Chơi> <Số Lượng Coin>");
						return true;
					}
					if($args[1] < 1) {
						$sender->sendMessage("⨝§l§c Số Coin Phải Lớn Hơn §e1!");
						return true;
					}
					if(!is_numeric($args[1])) {
						$sender->sendMessage("⨝§l§c Số Coin Phải Là Số!");
						return true;
					}
					if(strrchr($args[1], ".") == true) {
  						$sender->sendMessage("⨝§l§c Số Coin Không Hợp Lệ!");
						return true;
					}
					$player = $this->getServer()->getPlayer($args[0]);
					if(!$player) {
						$sender->sendMessage("⨝§l§c Không Tìm Thấy Người Chơi:§e {$args[0]}!");
						return true;
					}
						$this->coin->set($player->getName(), $this->coin->get($player->getName()) - (int)$args[1]);
						$this->coin->save();
					$sender->sendMessage("⨝§l§c Số Coin Của Người Chơi§e {$player->getName()}§c Đã Được Giảm Xuống§e {$args[1]} Coin!");
					$player->sendMessage("⨝§l§c Số Coin Của Bạn Đã Bị Giảm Xuống§e {$args[1]} Coin §cBởi Quản Trị Viên§e {$sender->getName()}");
						}else{
						$sender->sendMessage("⨝§l§c Bạn Không Có Quyền Sử Dụng Câu Lệnh Này!");
					}
					return true;
					break;

				case "doicoin":
					if(!($sender instanceof Player)) {
					$sender->sendMessage("⨝§l§c Vui Lòng Thực Hiện Lệnh Này Trong Trò Chơi!");
					return true;
					}
					if(!is_numeric($args[0])) {
					$sender->sendMessage("⨝§l§c Sử Dụng:§e /doicoin <Số Lượng Coin>");
					return true;
					}
					if(strrchr($args[1], ".") == true) {
  					$sender->sendMessage("⨝§l§c Số Coin Không Hợp Lệ!");
					return true;
					}
					if($args[0] < 1) {
					$sender->sendMessage("⨝§l§c Số Coin Phải Lớn Hơn §e1!");
					return true;
					}
					if($this->money->myMoney($sender->getName()) >= $args[0]*100000) {
					$this->money->reduceMoney($sender->getName(), $arg[0]*100000);
					$this->coin->set($sender->getName(), $this->coin->get($sender->getName()) + $args[0]);
					$this->coin->save();
					$sender->sendMessage("⨝§l§c Đổi Thành Công§Ư {$args[0]} Coin §cVới Giá§e {($args[0]*50000)} Xu!");
					}else{
					$sender->sendMessage("⨝§l§c Bạn Cần §e{($args[0]*50000)} Xu §cĐể Đổi §e{$args[0]} Coin!");
					}
			return true;
			break;
			
			case "topcoin":
			$max = 0;
			foreach($this->coin->getAll() as $c) {
				$max += count($this->coin->getAll());
			}
			$page = ceil($max/5);
			$page = array_shift($args);
			$page = max(1, $page);
			$page = min($max, $page);
			$page = (int)$page;
			$sender->sendMessage("⨝ §l§cBảng Xếp Hạng Coin §f<§cTrang §e".$page."§f/§e".$max."§f> §r⨝");
			$aa = $this->coin->getAll();
			arsort($aa);
			$i = 0;
				foreach($aa as $b => $a){
					if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4) {
						$i1 = $i + 1;
						$c = $this->coin->get($b);
						$sender->sendMessage("⨞ §l§f[§bHạng {$i1}§f] §c{$b} §f:§e {$c} Coin §r⨟");							
					}
					$i++;
				}
			return true;
			break;

			case "helpcoin":
				if($sender->isOp()) {
					$sender->sendMessage("⨝§l§b Hướng Dẫn Sử Dụng Coin §r⨝");
					$sender->sendMessage("⨞§l§e /mycoin §f-§c Hiển Thị Số Coin Của Bản Thân");
					$sender->sendMessage("⨞§l§e /doicoin <Số Lượng Coin>§f-§c Đổi Tiền Thành Coin");
					$sender->sendMessage("⨞§l§e /topcoin <Trang> §f-§c Hiển Thị Bảng Xếp Hạng Coin");
					$sender->sendMessage("⨞§l§e /seecoin <Tên Người Chơi> §f-§c Hiển Thị Số Coin Của Người Chơi");
					$sender->sendMessage("⨞§l§e /setcoin <Tên Người Chơi> <Số Lượng Coin> §f-§c Chỉnh Số Coin Người Chơi");
					$sender->sendMessage("⨞§l§e /givecoin <Tên Người Chơi> <Số Lượng Coin> §f-§c Tăng Số Coin Người Chơi");
					$sender->sendMessage("⨞§l§e /takecoin <Tên Người Chơi> <Số Lượng Coin> §f-§c Giảm Số Coin Người Chơi");
					$sender->sendMessage("⨞§l§e /helpcoin §f-§c Hiển Thị Hướng Dẫn Sử Dụng Các Lệnh Liên Quan Tới Coin!");
					$sender->sendMessage("⨞§l§e /paycoin <Tên Người Chơi> <Số Lượng Coin> §f-§c Chuyển Số Coin Cho Người Chơi");
				}else{
					$sender->sendMessage("⨝§l§b Hướng Dẫn Sử Dụng Coin §r⨝");
					$sender->sendMessage("⨞§l§e /mycoin §f-§c Hiển Thị Số Coin Của Bản Thân");
					$sender->sendMessage("⨞§l§e /doicoin <Số Lượng Coin>§f-§c Đổi Tiền Thành Coin");
					$sender->sendMessage("⨞§l§e /topcoin <Trang> §f-§c Hiển Thị Bảng Xếp Hạng Coin");
					$sender->sendMessage("⨞§l§e /seecoin <Tên Người Chơi> §f-§c Hiển Thị Số Coin Của Người Chơi");
					$sender->sendMessage("⨞§l§e /helpcoin §f-§c Hiển Thị Hướng Dẫn Sử Dụng Các Lệnh Liên Quan Tới Coin!");
					$sender->sendMessage("⨞§l§e /paycoin <Tên Người Chơi> <Số Lượng Coin> §f-§c Chuyển Số Coin Cho Người Chơi");
				}
			return true;
			break;

			}
		}

	public function myCoin($player) {
		if($player instanceof Player){
			$player = $player->getName();
		}
		$this->coin = new Config($this->getDataFolder()."Coin.yml",Config::YAML);
		return $this->coin->get($player);
	}

	public function addCoin($player, $coin) {
		if($player instanceof Player){
			$player = $player->getName();
		}
		$this->coin = new Config($this->getDataFolder()."Coin.yml",Config::YAML);
		$this->coin->set($player, $this->coin->get($player) + (int)$coin);
		$this->coin->save();
	}

	public function reduceCoin($player, $coin) {
		if($player instanceof Player){
			$player = $player->getName();
		}
		$this->coin = new Config($this->getDataFolder()."Coin.yml",Config::YAML);
		if($this->coin->get($player) - $coin < 0) {
			return true;
		}
		$this->coin->set($player, $this->coin->get($player) - (int)$coin);
		$this->coin->save();
	}
}