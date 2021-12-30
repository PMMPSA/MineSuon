<?php

namespace Sell;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\{Command, CommandSender};
use pocketmine\{Player, item\Item, utils\Config, plugin\PluginBase};

class Main extends PluginBase {

    public function onEnable() {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->cfg = $this->getConfig()->getAll();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if(!($sender instanceof Player) && !(isset($args[0]))) {
            $sender->sendMessage("⨞§l§c Vui Lòng Thực Hiện Lệnh Trong Trò Chơi!");
            return true;
        }
        switch($command->getName()) {
            case "sell":
            if(isset($args[0])) {
                switch(strtolower($args[0])) {
                    case "hand":
                    $item = $sender->getInventory()->getItemInHand();
                    if(isset($this->cfg[$item->getID().":".$item->getDamage()])) {
                        $price = $this->cfg[$item->getID().":".$item->getDamage()];
                        $count = $item->getCount();
                        $totalprice = $price * $count;
                        EconomyAPI::getInstance()->addMoney($sender->getName(), (int)$totalprice);
                        $item->setCount($item->getCount() - (int)$count);
                        $sender->getInventory()->setItemInHand($item);
                        $sender->sendMessage("⨞§l§c Đã Bán §e{$count} {$item->getName()}§c Và Nhận Được§e {$totalprice} Xu!");
                        return true;
                    }elseif(isset($this->cfg[$item->getID()])) {
                        $price = $this->cfg[$item->getID()];
                        $count = $item->getCount();
                        $totalprice = $price * $count;
                        EconomyAPI::getInstance()->addMoney($sender->getName(), (int)$totalprice);
                        $item->setCount($item->getCount() - (int)$count);
                        $sender->getInventory()->setItemInHand($item);
                        $sender->sendMessage("⨞§l§c Đã Bán §e{$count} {$item->getName()}§C Và Nhận Được§e {$totalprice} Xu!");
                        return true;
                    }
                    $sender->sendMessage("⨞§l§c Bạn Không Thể Bán Vật Phẩm Này!");
                    return true;
                    break;

                    case "all":
                    $inv = $sender->getInventory()->getContents();
                    $revenue = 0;
                    foreach($inv as $item) {
                        if(isset($this->cfg[$item->getID().":".$item->getDamage()])) {
                            $revenue = $revenue + ($item->getCount() * $this->cfg[$item->getID().":".$item->getDamage()]);
                            $sender->getInventory()->remove($item);
                        }elseif(isset($this->cfg[$item->getID()])) {
                            $revenue = $revenue + ($item->getCount() * $this->cfg[$item->getID()]);
                            $sender->getInventory()->remove($item);
                        }
                    }
                    if($revenue <= 0) {
                        $sender->sendMessage("⨞§l§c Trong Túi Đồ Của Bạn Không Có Gì Có Thể Bán!");
                        return true;
                    }
                    EconomyAPI::getInstance()->addMoney($sender->getName(), (int)$revenue);
                    $sender->sendMessage("⨞§l§c Đã Bán Toàn Bộ Vật Phẩm Trong Túi Đồ Và Nhận Được §e{$revenue} Xu!");
                    return true;
                    break;
                }
            }
            $sender->sendMessage("⨞§l§e /sell hand §f-§c Bán Vật Phẩm Trên Tay!");
            $sender->sendMessage("⨞§l§e /sell all §f-§c Bán Toàn Bộ Vật Phẩm Trong Túi Đồ!");
            return true;
        }
	}
}