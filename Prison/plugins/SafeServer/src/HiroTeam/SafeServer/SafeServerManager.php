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

class SafeServerManager
{
    /**
     * @var SafeServerMain
     */
    private $main;

    /**
     * @var array
     */
    private $safePlayer = [];

    /**
     * SafeServerManager constructor.
     * @param SafeServerMain $main
     */
    public function __construct(SafeServerMain $main)
    {
        $this->main = $main;
        $this->init();
    }

    private function init(): void
    {
        foreach ($this->main->getProvider()->findAll() as $row) {
            $this->safePlayer[strtolower($row['username'])] = $row['xuid'];
        }
    }

    /**
     * @param string $xuid
     * @param string $playerName
     * @return bool
     */
    public function isPlayerSafe(string $xuid, string $playerName): bool
    {
        $playerName = strtolower($playerName);
        if (!isset($this->safePlayer[$playerName])) {
            $this->addNewPlayer($xuid, $playerName);
            return true;
        }
        if ($this->safePlayer[$playerName] === $xuid) {
            return true;
        }
        return false;
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function deleteOne(string $playerName): bool
    {
        $playerName = strtolower($playerName);
        if (isset($this->safePlayer[$playerName])) {
            unset($this->safePlayer[$playerName]);
            $this->main->getProvider()->removeUser($playerName);
            return true;
        }
        return false;
    }

    /**
     * @param string $xuid
     * @param string $playerName
     */
    private function addNewPlayer(string $xuid, string $playerName): void
    {
        $playerName = strtolower($playerName);
        $this->safePlayer[$playerName] = $xuid;
        $this->main->getProvider()->addUser($xuid, $playerName);
    }
}
