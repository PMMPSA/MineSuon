<?php

declare(strict_types=1);

namespace phuongaz\EnchantForm;

use pocketmine\{
    Server,
    Player
};
use pocketmine\item\{
    Item,
    Tool,
    Armor,
    Sword,
    enchantment\Enchantment,
    enchantment\EnchantmentInstance
};
use pocketmine\utils\Config;

use pocketmine\plugin\PluginBase;
use onebone\economyapi\EconomyAPI;
use onebone\pointapi\PointAPI;


Class Enchant extends PluginBase {

	public static $enchantments = [];
	public static $money = 10000;
	public static $instance;

	public function onEnable(){
		//$this->getLogger()->info("Plugin by phuongaz");
		$this->getServer()->getInstance()->getCommandMap()->register("enchantshop", new EnchantCommands());
		$this->setEnchants();
		self::$instance = $this;
	}

	public static function getInstance(){
		return self::$instance;
	}

	public function setEnchants(){
		for($i = 0; $i <= 30; $i++){
			if(($enchant = Enchantment::getEnchantment((int)$i)) instanceof Enchantment){
				self::$enchantments[] = $enchant;
			}
		}
	}

	public static function sendForm(?PLayer $player) :void{
		Forms::MainForm($player);
	}

	public function checkMoney(Player $player, ?float $data):bool{
		if($this->getEco()->myMoney($player) >= $data * self::$money){
			return true;
		}
		return false;
	}

	public function EnchantItem(Player $player, Enchantment $enchant, int $level) :void{
		$enchantment = new EnchantmentInstance($enchant, $level);
		$item = $player->getInventory()->getItemInHand();
		//var_dump($enchantment->getType());
		if($item->getId() != Item::AIR || $item instanceof Tool || $item instanceof Armor){
			$item->addEnchantment($enchantment);
			$this->getEco()->reduceMoney($player, $level * self::$money);
			$player->getInventory()->setItemInHand($item);
			Forms::CustomForm($player, "§l§e❖§a Cường Hóa Vật Phẩm Thành Công!" , $item);
		}else Forms::CustomForm($player, " §l§cLưu Ý: Vật Phẩm Trên Tay Bạn Phải Là Giáp Hoặc Công Cụ!");
	}

	public function getEco() :EconomyAPI {
		return EconomyAPI::getInstance();
	}

}
