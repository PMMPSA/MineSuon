<?php

declare(strict_types=1); /*The plugin may crash if you try to remove this line!*/

namespace AntiToolBox;

use pocketmine\{
    event\Listener,
    plugin\PluginBase,
    command\ConsoleCommandSender,
    network\mcpe\protocol\LoginPacket,
    event\server\DataPacketReceiveEvent
};
// use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
    
    //public Config $config;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        // $this->saveResource("config.yml");
        // $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @priority NORMAL
     * @ignoreCancelled TRUE
     */

    public function onRecieve (DataPacketReceiveEvent $event) {
        // $this->getServer()->dispatchCommand(new ConsoleCommandSender(),"playerinfo ".$event->getPlayer()->getName());
        $player = $event->getPlayer();
        $packet = $event->getPacket();
        // "GokuvipVN1508"
        if(in_array($event->getPlayer()->getName(), array("Steve", "Alex"))) {
            $player->close("", "Bị Cấm Vĩnh Viễn Vì Lạm Dụng Lỗi!\nLiên Hệ Zalo Quản Trị Viên Máy Chủ: @thanhnhanaz");
        }
        // if(in_array($event->getPlayer()->getName(), array("Steve", "Alex"))) {
        //     $player->close("", "Bị cấm vĩnh viễn vì spam!\nLiên hệ Zalo quản trị viên máy chủ: @thanhnhanaz");
        // }

        if ($packet instanceof LoginPacket) {
            $deviceOS = (int)$packet->clientData["DeviceOS"];
            $deviceModel = (string)$packet->clientData["DeviceModel"];

            //AndroidOS
            if ($deviceOS !== 1) {
                return;
            }

            /*
             * Something about device model check, for example:
             * Original client: XIAOMI Note 8 Pro
             * Toolbox client: Xiaomi Note 8 Pro
             *
             * For another Example
             * Original client: SAMSUNG SM-A105F
             * Toolbox client: samsung SM-A105F
             */

            $name = explode(" ", $deviceModel);
            if (!isset($name[0])) {
                return;
            }
            $check = $name[0];
            $check = strtoupper($check);
            // If a player can prove they are not using cheat software, you can add their name below.
            if(!in_array($event->getPlayer()->getName(), array("NhanAZ"))) {
                if ($check !== $name[0]) {
                $player->close("", "Phát Hiện Phần Mềm Gian Luận!\nLiên Hệ Zalo Quản Trị Viên Máy Chủ: @thanhnhanaz");
                }
            }
        }
    }
}
