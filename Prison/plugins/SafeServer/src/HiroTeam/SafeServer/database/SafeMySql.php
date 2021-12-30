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

namespace HiroTeam\SafeServer\database;

use HiroTeam\SafeServer\SafeServerMain;
use MySQLi;
use pocketmine\utils\Config;

class SafeMySql implements ISafeData
{

    /**
     * @var Config
     */
    private $config;

    /**
     * SafeMySql constructor.
     * @param SafeServerMain $main
     */
    public function __construct(SafeServerMain $main)
    {
        $this->config = $main->getConfig();
        $db = $this->getDatabase();
        $db->query('CREATE TABLE IF NOT EXISTS safeServer (xuid VARCHAR(255), username VARCHAR(255));');
        $db->close();
    }

    /**
     * @param string $xuid
     * @param string $playerName
     */
    public function addUser(string $xuid, string $playerName): void
    {
        $db = $this->getDatabase();
        $db->query("INSERT INTO safeServer (xuid, username) VALUES ('$xuid', '$playerName');");
        $db->close();
    }

    /**
     * @param string $playerName
     */
    public function removeUser(string $playerName): void
    {
        $db = $this->getDatabase();
        $db->query("DELETE FROM safeServer WHERE username LIKE '$playerName';");
        $db->close();
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $db = $this->getDatabase();
        $result = $db->query('SELECT * FROM safeServer;');
        $all = [];
        while ($resultArr = $result->fetch_assoc()) {
            array_push($all, $resultArr);
        }
        $db->close();
        return $all;
    }

    /**
     * @return MySQLi
     */
    private function getDatabase(): MySQLi
    {
        return new MySQLi(
            $this->config->get('mysql-host'),
            $this->config->get('mysql-user'),
            $this->config->get('mysql-password'),
            $this->config->get('mysql-database'));
    }
}
