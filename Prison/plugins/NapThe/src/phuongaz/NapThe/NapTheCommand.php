<?php

namespace phuongaz\NapThe;

use pocketmine\command\{Command, CommandSender};
use pocketmine\Player;
use phuongaz\NapThe\form\CardStatusForm;

Class NapTheCommand extends Command{

	public function __construct(){
		parent::__construct("napthe", "Nạp Thẻ");
	}

	public function execute(CommandSender $sender, string $label, array $args) :bool{
		if(!$sender instanceof Player) return false;
		$form = new CardStatusForm();
        $form->__init();
        $form->setTitle("§r§e⨞§l§c Nạp Thẻ §eMine§aSuon §r§e⨟");
        $sender->sendForm($form);
        return true;
	}
}