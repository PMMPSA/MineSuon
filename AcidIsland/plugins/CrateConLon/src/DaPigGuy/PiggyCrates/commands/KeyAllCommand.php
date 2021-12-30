<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyCrates\commands;

use DaPigGuy\PiggyCrates\libs\CortexPE\Commando\args\IntegerArgument;
use DaPigGuy\PiggyCrates\libs\CortexPE\Commando\args\RawStringArgument;
use DaPigGuy\PiggyCrates\libs\CortexPE\Commando\BaseCommand;
use DaPigGuy\PiggyCrates\libs\CortexPE\Commando\exception\ArgumentOrderException;
use DaPigGuy\PiggyCrates\PiggyCrates;
use pocketmine\command\CommandSender;

class KeyAllCommand extends BaseCommand
{
    /** @var PiggyCrates */
    protected $plugin;

    /**
     * @param array $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!isset($args["type"])) {
            $sender->sendMessage("Usage: /laykeyall <type>");
            return;
        }
        /** @var int $amount */
        $amount = $args["amount"] ?? 1;
        if (!is_numeric($amount)) {
            $sender->sendMessage($this->plugin->getMessage("commands.keyall.error.not-numeric"));
            return;
        }
        $crate = $this->plugin->getCrate($args["type"]);
        if ($crate === null) {
            $sender->sendMessage($this->plugin->getMessage("commands.keyall.error.invalid-crate"));
            return;
        }
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $crate->giveKey($player, $amount);
            $player->sendMessage($this->plugin->getMessage("commands.keyall.success.sender", ["{CRATE}" => $crate->getName()]));
        }
        $sender->sendMessage($this->plugin->getMessage("commands.keyall.success.target", ["{CRATE}" => $crate->getName()]));

    }

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->setPermission("piggycrates.command.keyall");
        $this->registerArgument(0, new RawStringArgument("type"));
        $this->registerArgument(1, new IntegerArgument("amount", true));
    }
}