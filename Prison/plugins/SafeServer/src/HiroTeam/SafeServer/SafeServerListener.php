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
namespace HiroTeam\SafeServer;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class SafeServerListener implements Listener
{
    /**
     * @var SafeServerMain
     */
    private $main;

    /**
     * SafeServerListener constructor.
     * @param SafeServerMain $main
     */
    public function __construct(SafeServerMain $main)
    {
        $this->main = $main;
    }

    /**
     * @param PlayerJoinEvent $event
     * @priority Lowest
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $playerName = $player->getName();
        $xuid = $player->getXuid();
        if (!$this->main->getManager()->isPlayerSafe($xuid, $playerName)) {
            $kickMessage = $this->main->getConfig()->get('kickedMessage');
            $player->kick($kickMessage, false, $kickMessage);
        }
    }
}