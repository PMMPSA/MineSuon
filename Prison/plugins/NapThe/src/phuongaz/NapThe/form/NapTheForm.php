<?php

namespace phuongaz\NapThe\form;


use pocketmine\Player;
use pocketmine\Server;

use jojoe77777\FormAPI\CustomForm;
use phuongaz\NapThe\Card;

Class NapTheForm extends CustomForm{

	private $player;
	private $id_card;
	private $name_card;

	private $amount = [10000 => "§l§c༺§e 10.000 VND §c༻", 20000 => "§l§c༺§e 20.000 VND §c༻", 50000 => "§l§c༺§e 50.000 VND §c༻", 100000 => "§l§c༺§e 100.000 VND §c༻", 200000 => "§l§c༺§e 200.000 VND §c༻", 500000 => "§l§c༺§e 500.000 VND §c༻"];
	public function __construct(Player $player, int $id_card, string $name_card){
		$this->id_card = $id_card;
		$this->name_card = $name_card;
		$this->player = $player;
		$Callable = $this->getCallable();
		parent::__construct($Callable);
	}

	public function __init(){
		//$this->addLabel("§f§lLcoin:§e ". Coin::getInstance()->getCoin($this->player)."\n§a§lKhuyến Khích Nạp Thẻ§e Zing");
		$this->addDropDown("§r§l§e⨞§a Mệnh Giá: §c(Sai Mệnh Giá Mất Thẻ)", array_values($this->amount));
		$this->addInput("§r§l§e⨞§a Số Seri:", "§l§e(Nhập Số Seri Vào Đây)");
		$this->addInput("§r§l§e⨞§a Mã Số:", "§l§e(Nhập Mã Số Vào Đây)");
	}

	public function getCallable() :Callable{
		return function(Player $player, ?array $data){
			if(is_null($data)) return;
			$card_value = array_keys($this->amount)[$data[0]];
			if(isset($data[1]) and isset($data[2])){
				$pin = $data[2];
				$seri = $data[1];
			}
			$data_card = ["PIN" => $pin, "SERI" => $seri, "CARD_VALUE" => $card_value, "ID" => $this->id_card, "NAME" => $this->name_card];
			$card = new Card($data_card);
			$card->sendCard($player);
		};
	}
}
