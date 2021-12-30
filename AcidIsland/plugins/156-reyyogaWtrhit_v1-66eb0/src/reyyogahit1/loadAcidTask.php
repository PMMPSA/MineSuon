<?php

namespace reyyogahit1;



use pocketmine\scheduler\Task;
use pocketmine\Server;

class loadAcidTask extends Task
{

    private $level;
    private $time;
    function __construct(string $level,int $time)
    {
        $this->level = $level;
        $this->time = $time;
    }

    /**
     * Actions to execute when run
     *
     * @param int $currentTick
     *
     * @return void
     */
    public function onRun(int $currentTick)
    {
        if(!isset(Main::getInstance()->isRain[$this->level])){
            $this->time--;
            if($this->time == 1800){
                $level = Server::getInstance()->getLevelByName($this->level);
                if($level != null){
                    foreach ($level->getPlayers() as $player){
                        $player->sendMessage("§l§eThông báo§c>§f do nước biển có axit ,  tác động lên bầu không khí và mây khoảng 30 phút nữa sẽ xuất hiện 1 con mưa axit mọi người hãy che lại mái nhà của mình để tránh");
                    }
                }
            }
            if($this->time <= 0){
                Main::getInstance()->isRain[$this->level] = true;
                $level = Server::getInstance()->getLevelByName($this->level);
                if($level != null){
                    foreach ($level->getPlayers() as $player){
                        $player->sendMessage("§l§eThông báo§c>§f Bầu trời đang xuất hiện cơn mưa axit mau tìm chỗ trú");
                    }
                }

                $this->time = Main::getInstance()->getConfig()->get("acid-rain-load(s)");
            }
        }else{
            $this->time--;
            if($this->time <= 0){
                unset(Main::getInstance()->isRain[$this->level]);
                $level = Server::getInstance()->getLevelByName($this->level);
                if($level != null){
                    foreach ($level->getPlayers() as $player){
                        $player->sendMessage("§l§eThông báo§c>§f Cơn mưa axit đã qua giờ bạn có thể làm việc lại");
                    }
                }
                $this->time = Main::getInstance()->getConfig()->get("acid-rain-time(s)");
            }
        }
    }
}
