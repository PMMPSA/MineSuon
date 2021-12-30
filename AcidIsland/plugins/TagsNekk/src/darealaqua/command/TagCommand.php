<?php

namespace darealaqua\command;

use darealaqua\Main;
use darealaqua\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class TagCommand extends Command {

    /*** @var Main */
    private $main;

    /**
     * TagCommand constructor.
     * @param Main $main
     */
    public function __construct(Main $main)
    {
        parent::__construct("bietdanh", "Tag được chưa", "", ["danhhieu"]);
        $this->main = $main;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(C::RED . "In game!");
        } else {
            Utils::openTagSelectorForm($sender);
        }
    }
}
