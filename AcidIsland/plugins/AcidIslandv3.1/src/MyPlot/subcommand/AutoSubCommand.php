<?php
namespace MyPlot\subcommand;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Server;

class AutoSubCommand extends SubCommand
{


    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.auto");
    }
    
    public function pl(Player $player)
    {
        return $player;
    }

    public function execute(CommandSender $sender, array $args) {
        if (!empty($args)) {
            return false;
        }
              $level = Server::getInstance()->getLevelByName("ai");
        $sender->getPlayer()->teleport(new Position(0, 1, 0, $level));
        $player = $sender->getServer()->getPlayer($sender->getName());
        $levelName = $player->getLevel()->getName();
        if (!$this->getPlugin()->isLevelLoaded($levelName)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("autoo.notplotworld"));
            return true;
        }
        if (($plot = $this->getPlugin()->getProvider()->getNextFreePlot($levelName)) !== null) {
            $this->getPlugin()->teleportPlayerToPlot($player, $plot);
            $sender->sendMessage($this->translateString("autoo.success", [$plot->X, $plot->Z]));
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("autoo.noplots"));
        }
              $command = "ai claim";
              $this->getPlugin()->getServer()->getCommandMap()->dispatch($sender, $command);
        return true;
    }
}