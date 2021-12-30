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

use HiroTeam\SafeServer\commands\CommandFactory;
use HiroTeam\SafeServer\database\SafeMySql;
use HiroTeam\SafeServer\database\SafeSqLite3;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class SafeServerMain extends PluginBase
{

    /**
     * @var SafeSqLite3|SafeMySql
     */
    private $provider;

    /**
     * @var SafeServerManager
     */
    private $manager;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new SafeServerListener($this), $this);
        $this->initProvider();
        $this->manager = new SafeServerManager($this);
        new CommandFactory($this);
    }

    private function initProvider(): void
    {
        switch ($this->getConfig()->get('provider')){
            case 'sqlite3':
                $this->provider = new SafeSqLite3($this);
                break;
            case 'mysql':
                $this->provider = new SafeMySql($this);
                break;
        }
    }

    /**
     * @return SafeMySql|SafeSqLite3
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return SafeServerManager
     */
    public function getManager(): SafeServerManager
    {
        return $this->manager;
    }

}
