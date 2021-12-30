<?php
declare(strict_types=1);

namespace Wool\TrashGUI;

use Wool\TrashGUI\Trash;
use pocketmine\{Server, Player};
use muqsit\invmenu\{
  InvMenu,
  InvMenuHandler
};
use pocketmine\item\Item;
use pocketmine\inventory\transaction\action\SlotChangeAction;

class GUI{

  private $player;

  public function __construct($player){
		$this->player = $player;
	}

  public function send($player){
    $menu = InvMenu::create(InvMenu::TYPE_CHEST);
    $menu->setName("TrashGUI");
    $menu->setListener([$this, "Listener"])->send($player);
  }

  public function Listener(Player $player, Item $itemClicked): bool{
    return true;
  }

}
