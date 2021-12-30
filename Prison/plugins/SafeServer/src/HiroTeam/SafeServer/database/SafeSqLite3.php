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
use SQLite3;

class SafeSqLite3 implements ISafeData
{
    /**
     * @var SQLite3
     */
    private $db;

    /**
     * SafeSqLite3 constructor.
     * @param SafeServerMain $main
     */
    public function __construct(SafeServerMain $main)
    {
        $this->db = new SQLite3($main->getDataFolder() . 'safeServer.db');
        $this->db->exec('CREATE TABLE IF NOT EXISTS safeServer (xuid TEXT, username TEXT COLLATE NOCASE);');
    }

    /**
     * @param string $xuid
     * @param string $playerName
     */
    public function addUser(string $xuid, string $playerName): void
    {
        $db = $this->db->prepare('INSERT INTO safeServer (xuid, username) VALUES (:xuid, :username);');
        $db->bindValue(':xuid', $xuid);
        $db->bindValue(':username', $playerName);
        $db->execute();
    }

    /**
     * @param string $playerName
     */
    public function removeUser(string $playerName): void
    {
        $this->db->query("DELETE FROM safeServer WHERE username LIKE '$playerName';");
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $result = $this->db->query('SELECT * FROM safeServer;');
        $all = [];
        while ($resultArr = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($all, $resultArr);
        }
        return $all;
    }
}
