<?php
/**
 * ██╗░░██╗██╗██████╗░░█████╗░████████╗███████╗░█████╗░███╗░░░███╗
 * ██║░░██║██║██╔══██╗██╔══██╗╚══██╔══╝██╔════╝██╔══██╗████╗░████║
 * ███████║██║██████╔╝██║░░██║░░░██║░░░█████╗░░███████║██╔████╔██║
 * ██╔══██║██║██╔══██╗██║░░██║░░░██║░░░██╔══╝░░██╔══██║██║╚██╔╝██║
 * ██║░░██║██║██║░░██║╚█████╔╝░░░██║░░░███████╗██║░░██║██║░╚═╝░██║
 * ╚═╝░░╚═╝╚═╝╚═╝░░╚═╝░╚════╝░░░░╚═╝░░░╚══════╝╚═╝░░╚═╝╚═╝░░░░░╚═╝
 * SafeServer-HiroTeam By WillyDuGang
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/
 *
 *
 * GitHub: https://github.com/HiroshimaTeam/SafeServer-HiroTeam
 */
namespace HiroTeam\SafeServer\commands;

use HiroTeam\SafeServer\SafeServerMain;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class SafeDelete extends PluginCommand
{
    /**
     * SafeDelete constructor.
     * @param SafeServerMain $plugin
     */
    public function __construct(SafeServerMain $plugin)
    {
        parent::__construct("safedelete", $plugin);
        $this->setDescription("Delete the xuid associated with a player");
        $this->setUsage("/safedelete <player>");
        $this->setPermission("safedelete.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if(empty($args[0])) return false;
        /** @var SafeServerMain $main */
        $main = $this->getPlugin();
        if(!$main->getManager()->deleteOne($args[0])){
            $sender->sendMessage("§c$args[0] has never connected to this server");
            return true;
        }
        $sender->sendMessage("§aYou have successfully delete the xuid associated with $args[0]");
        return true;
    }
}
