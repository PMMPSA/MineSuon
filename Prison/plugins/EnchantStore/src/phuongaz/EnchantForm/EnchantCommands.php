<?php
declare(strict_types=1);
namespace phuongaz\EnchantForm;

use pocketmine\command\{
	Command,
	CommandSender
};

use pocketmine\PLayer;

Class EnchantCommands extends Command {

	public function __construct(){
		parent::__construct("enchantshop", "Mở Giao Diện Cửa Hàng Cường Hóa Vật Phẩm!", '/enchantshop', ['ecshop', 'buyec', 'enchantui', 'ecui']);
	}

	public function execute(CommandSender $sender, string $label, array $args) :bool{

		if($sender instanceof Player) {
			Enchant::sendForm($sender);
		}
		return true;
	}

}