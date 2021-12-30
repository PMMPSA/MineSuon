<?php

declare(strict_types=1);

namespace VirakMC\lootbox\command;

use VirakMC\lootbox\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class glxCommand extends Command {

    /**
     * glxCommand constructor.
     */
    public function __construct() {
        parent::__construct("hq", "-.", "§3/hp [tên người chơi] [loại] [số lượng]");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($sender instanceof ConsoleCommandSender or $sender->isOp()) {
            if(!isset($args[2])) {
                $sender->sendMessage(TextFormat::YELLOW . $this->getUsage());
                return;
            }
            $player = Loader::getInstance()->getServer()->getPlayer($args[0]);
            if(!$player instanceof Player) {
                $sender->sendMessage(TextFormat::DARK_RED . TextFormat::BOLD . "Người chơi không tồn tại");
                return;
            }
            $lootbox = Loader::getInstance()->getLootboxManager()->getLootbox($args[1]);
            if($lootbox === null) {
                $sender->sendMessage(TextFormat::DARK_RED . TextFormat::BOLD . "Hộp quà không tồn tại!");
                return;
            }
            $amount = max(1, is_numeric($args[2]) ? (int)$args[2] : 1);
            $item = $lootbox->getItem();
            $item->setCount($amount);
            $player->getInventory()->addItem($item);
            return;
        }
        $sender->sendMessage(TextFormat::DARK_RED . TextFormat::BOLD . "Không đủ quyền!");
        return;
    }
}