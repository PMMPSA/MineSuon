<?php

declare(strict_types=1);

namespace phuongaz\EnchantForm;

use jojoe77777\FormAPI\{
	SimpleForm,
	CustomForm
};

use pocketmine\utils\TextFormat as TF;
use pocketmine\Player;


use pocketmine\item\{
	Item,
	enchantment\Enchantment
};
Class Forms {

	public static function MainForm( $player) {
		$form = new SimpleForm(function(Player $player, ?int $data){
			if(is_null($data)) return;
			if(is_null(Enchant::$enchantments[$data])) return;
			self::ConfirmForm(Enchant::$enchantments[$data], $player);
		});

		$form->setTitle(" §l§bGiao Diện Cường Hóa ");
		$form->setContent("⩜§l§a Hãy Chọn Phù Phép Để Cường Hoá Vật Phẩm Của Bạn!");
		foreach(Enchant::$enchantments as $enchant){
			$form->addButton("§l§e❖§c {$enchant->getName()} §e❖");
		}
		$form->sendToPlayer($player);
	}

	public static function ConfirmForm(Enchantment $enchantment, Player $player) {
		$form = new CustomForm(function(Player $player, ?array $data) use ($enchantment){
			if(is_null($data)) return;
			if(Enchant::getInstance()->checkMoney($player, round($data[3]))){
				Enchant::getInstance()->EnchantItem($player, $enchantment, (int)$data[3]);
			}else self::CustomForm($player, "⩐§l§c Rất Tiếc Bạn Không Đủ Tiền Để Cường Hoá Vật Phẩm §e⩐");
		});

		$form->setTitle(" §l§aXác Nhận Cường Hóa ");
		$form->addLabel("§l§e❖§a Phù Phép: §c". $enchantment->getName());
		$form->addLabel("§l§e❖§a Tiền Của Bạn: §e". Enchant::getInstance()->getEco()->myMoney($player)." §aXu§e ⩐\n");
		$form->addLabel("§l§e❖§a Giá Phù Phép Mỗi Cấp: §e".Enchant::$money ." §aXu §e⩐");
		$form->addSlider("§l§e❖§a Cấp Độ Bạn Cường Hóa: §e", 1, 10 , 1);
		$form->addLabel("§l§e❖§c Lưu Ý: Hệ Thống Không Hỗ Trợ Cường Hóa Cộng Dồn!");
		//$form->addLabel("Max level enchant: ".$enchantment->getMaxLevel());
		$form->sendToPlayer($player);
		
	}

	public static function CustomForm(Player $player, string $string, ?Item $item = null) {
		$form = new CustomForm(function(Player $player, $data){ if(is_null($data)) return; });
		$form->setTitle("§l§c❖§e Thông Báo §l§c❖");
		$form->addLabel($string);
		if($item !== null){
			$form->addLabel("§l§e❖§a Danh Sách Phù Phép Của Vật Phẩm:");
			//$form->addLabel("Name Item: ". $item->getCustomName());
			foreach($item->getEnchantments() as $enchant){
				$enchantm = $enchant->getType();
				$form->addLabel("§l§c-§a Phù Phép:§e ". $enchantm->getName(). " §f|§a Cấp:§e ". $enchant->getLevel()); 
			}
		}
		$form->sendToPlayer($player);
		return;
	}
}