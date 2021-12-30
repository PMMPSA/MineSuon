<?php

namespace Prison\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Prison\Main;

class SetrankCommand extends PluginCommand{

    /** @var Main */
    private $plugin;

    public function __construct(string $name, Main $plugin){
        $this->plugin = $plugin;
        parent::__construct($name, $plugin);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if($sender instanceof Player){
            if(!$sender->hasPermission("prison.setrank")){
                $sender->sendMessage(str_replace("{command}", $this->getName(), $this->plugin->getConfig()->get("no-permission-message")));
                return true;
            }
            if(!isset($args[0]) or !isset($args[1])){
                $sender->sendMessage(TextFormat::RED . "§r⨞§l§c Sử Dụng:§e /setrank <Tên người chơi> <Cấp Bậc>");
                return true;
            }
            $matches = $this->plugin->getServer()->matchPlayer($args[0]);
            $player = array_shift($matches);
            if($player === null){
                $sender->sendMessage(TextFormat::RED . "§r⨞§l§c Không Tìm Thấy Người Chơi!");
                return true;
            }
            if(!$this->plugin->rankExists($args[1])){
                $sender->sendMessage(TextFormat::RED . "§r⨞§l§c Cấp Bậc Không Tồn Tại!");
                return true;
            }
            $this->plugin->setRank($player, $args[1]);
            $sender->sendMessage(TextFormat::RED . "§r⨞§l§c Đã Chỉnh Cấp Bậc Của Tù Nhân§e {$player->getName()}§c Thành§e {$args[1]}!");
        }
        return true;
    }
}