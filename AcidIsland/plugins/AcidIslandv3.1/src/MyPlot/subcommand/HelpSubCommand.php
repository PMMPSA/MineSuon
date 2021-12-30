<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use jojoe77777\FormAPI\CustomForm;
class HelpSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return $sender->hasPermission("myplot.command.help");
    }

    /**
     * @return \MyPlot\Commands
     */
    private function getCommandHandler()
    {
        return $this->getPlugin()->getCommand($this->translateString("command.name"));
    }

    public function execute(CommandSender $sender, array $args) {
         $this->help($sender);
    }
    public function ailenh($sender){
        $formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result == null){
              return;
          }
          switch($result){
              case 0:
              break;
}
 });
        $form->setTitle("§l§e『§b §d✿§aAcidland§cHelp§d✿ §e』");
        $form->setContent("§a►§b-§c►§d=§e►§f-§9► §e§lBảng lệnh §aAcid§eLand§9◄§f-§e◄§d=§c◄§b-§a◄§r§l\n§f►§e /ai auto§7 - §fĐi đến một hòn đảo\n§f►§e /ai claim§7 - §fNhận ngay hòn đảo bạn đang đứng\n§f►§e /ai addhelper §e<player>§7 - §fThêm người vào đảo của bạn\n§f►§e /ai removehelper §e<player>§7 - §fXóa người chơi trong đảo của bạn\n§f►§e /ai homes§7 - §fDanh sách đảo của bạn\n§f►§e /ai home §e<Số> §7 - §fDịch chuyển về đảo của bạn\n§f►§e /ai info§7 - §fXem thông tin hòn đảo\n§f►§e /ai give §e<Tên người chơi> §7 - §fCho người khác hòn đảo của bạn\n§f►§e /ai warp §e<X;Y> §7 - §fDi chuyển đến hòn đảo nào đó\n");
        $form->addButton("§l§c❖ §6Thoát §c❖");
        $form->sendToPlayer($sender);
}


	 public function help($sender){
        $formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result == null){
              return;
          }
          switch($result){
              case 0:
              break;
              case 1:
              $this->aiui($sender);
              break;
              case 2:
              $this->ailenh($sender);
              break;
         }
        });
        $form->setTitle("§l§e『§b §d✿§aAcidland§d✿ §e』");
		$form->addButton("§l§c❖ §6Thoát §c❖");
		$form->addButton("§d✿§a Acidland§cMenu §d✿\n§8Chạm vào để xem chi tiết !");
		$form->addButton("§d✿§a Acidland§cHelp §d✿\n§8Chạm vào để xem chi tiết");
        $form->sendToPlayer($sender);
	}
	 public function aiui($sender){
        $formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result == null){
             return;
          }
          switch($result){
              case 0:
              break;
              case 1:
              $command = "ai auto";
              $this->getPlugin()->getServer()->getCommandMap()->dispatch($sender, $command);
              break;
              case 2:
              $command = "ai claim";
              $this->getPlugin()->getServer()->getCommandMap()->dispatch($sender, $command);
              break;
              case 3:
              $this->home
($sender);
              break;
              case 4:
              $this->addhelper
($sender);
              break;
              case 5:
              $this->removehelper
($sender);
              break;
              case 6:
              $this->give
($sender);
              break;
              case 7:
              $command = "ai info";
              $this->getPlugin()->getServer()->getCommandMap()->dispatch($sender, $command);
              break;
              case 8:
              $this->warp
($sender);
              break;
          }
        });
        $form->setTitle("§l§e『§b §d✿§aAcidland§cMenu§d✿ §e』");
		$form->addButton("§l§c❖ §6Thoát §c❖");
		$form->addButton("§d✿§a Tạo Đảo §d✿\n§8Create island");
		$form->addButton("§d✿§a Nhận Đảo §d✿\n§8Claim island");
		$form->addButton("§d✿§a Về Đảo §d✿\n§8Join island");
        $form->addButton("§d✿§a Thêm Hỗ Trợ §d✿\n§8Add Support");
		$form->addButton("§d✿§a Xóa Hỗ Trợ §d✿\n§8Remove Support");
		$form->addButton("§d✿§a Tặng Đảo §d✿\n§8Giveaway island");
		$form->addButton("§d✿§a Thông Tin Đảo §d✿\n§8Information island");
		$form->addButton("§d✿§a Qua Đảo Khác §d✿\n§8Go to another island");
        $form->sendToPlayer($sender);
	}
	public function home($player){
		$formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data;
			if($result == null){
				return;
			}
			$cmd = "ai home $data[0]";
			$this->getPlugin()->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§l§e『§b §d✿§eHome§d✿ §e』");
		$form->addInput("VD:1;2");
		$form->sendToPlayer($player);
	}
	public function addhelper($player){
		$formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data;
			if($result == null){
				return;
			}
			$cmd = "ai addhelper $data[0]";
			$this->getPlugin()->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§l§e『§b §d✿§cAdd Helper§d✿ §e』");
		$form->addInput("VD:NuTaVN");
		$form->sendToPlayer($player);
	}
	public function removehelper($player){
		$formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data;
			if($result == null){
				return;
			}
			$cmd = "ai removehelper $data[0]";
			$this->getPlugin()->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§l§e『§b §d✿§cRemove Helper§d✿ §e』");
		$form->addInput("VD: NuTaVN");
		$form->sendToPlayer($player);
	}
	public function give($player){
		$formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data;
			if($result == null){
				return;
			}
			$cmd = "ai give $data[0]";
			$this->getPlugin()->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§l§e『§b §d✿§bGive Island§d✿ §e』");
		$form->addInput("VD: PTN");
		$form->sendToPlayer($player);
	}
	public function warp($player){
		$formapi = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data;
			if($result == null){
				return;
			}
			$cmd = "ai warp $data[0]";
			$this->getPlugin()->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§l§e『§b §d✿§cGo Anathor Island§d✿ §e』");
		$form->addInput("VD: 1;2");
		$form->sendToPlayer($player);
	}
}
