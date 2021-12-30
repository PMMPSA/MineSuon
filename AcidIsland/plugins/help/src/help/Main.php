<?php

namespace help;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

Class Main extends PluginBase implements Listener{
    
    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
@mkdir($this->getDataFolder(), 0744, true);
       $this->pass = new Config($this->getDataFolder()."pass.yml",Config::YAML);
}

    public function onJoin(PlayerJoinEvent $ev) {
        $api = $this->getServer()->getPluginManager()->getPlugin("restart_in");
        $check = $api->check->get($ev->getPlayer()->getName());
        if($check == 0){
        $this->menu($ev->getPlayer());
        }
    }
    public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "hd":
                $this->menu($sender);
           return true;
        }
        return true;
    }
                
    public function help($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          switch($result){
                   case 0:
                  $this->menu($sender);
                   break;
          }
         });
        $form->setTitle("§l§a[§6AIS§a]§c ˃ §eCommand");
        $form->setContent("§7- §3/sb help§e: dùng mở nhận và quản lý đảo\n\n§7- §3/sell all§e: dùng để bán khoáng sản trong máy chủ\n\n§7- §3/autosell§e: dùng tự động sell ore\n\n§7- §3/kho§e: mở kho cất giữ khoáng sản\n\n§7- §3/ah§e: nơi bạn có thể trao đổi các món đồ lậu\n\n§3/auto kho§e: khi đào khoáng sản sẽ cất vào kho\n\n§3/sellkho§e: bán các khoáng sản trong kho nhận tiền ! \n\n§3/player§e: mở chức năng tỉ số trong máy chủ\n\n§7- §3/warps gui§e: mở các khu vực cho máy chủ\n\n§7- §3/game§e: cảnh báo nó theo may mắn đóa \n\n§7- §3/shop§e: mở shop buôn bán\n\n§7- §3/axe§e: mở menu axe level\n\n§7- §3/prestige§e: mở chức năng prestige trong máy chủ\n\n§7- §3/sellexp§e: dùng để bán kinh nghiệm bạn mine được\n\n§7- §3/autosellexp§e: dùng để tự động bán exp\n\n§7- §3/item§e: mở shop item giúp nâng chỉ số trong máy chủ\n\n§7- §3/cay§e: mở hệ thống cây phát tài của máy chủ\n\n§7- §3/fly§e: bật bay\n\n§7- §3/quyenrank§e: xem cách quyền lợi khi mua rank trong máy chủ\n\n§7- §3/mob§e: mở shop mob\n\n§7- §3/luckyblock§e: mở menu luckyblock vui lòng đến khu luckyblock để đập luckyblock\n\n§7- §3/capdao§e: mở menu cấp đảo\n\n§3/up§e: để nâng cấp trang bị , vũ khí\n\n§3/nganhang§e: mở giao diện ngân hàng giúp bạn gửi tiền có lãi nhóa ^^\n\n§3HIỆN TẠI MÁY CHỦ VẪN ĐANG TRONG QUÁ TRÌNH PHÁT TRIỂN TƯƠNG LAI SẼ TIẾN XA HƠN VÀ MÁY CHỦ SẼ MỞ TRONG THÁNG 6 NĂM 2021 CÓ LẼ LÀ VẬY :))\n\n\n§7-----------------------------\n\n§7- §aLink vote§e: http://bit.do/mssb\n\n§7- §6Support§e: LetTIHL and NuTaVN\n\n§7- §bFacebook§e: http://bit.do/adminsetup or http://bit.do/pluginsv\n\n§7- §cYoutube§f: http://bit.do/lettihl\n\n§7- §9Discord§f: http://bit.do/dicordsv\n\n§7- §aVerison Server§c: 1.16.220\n\n§7- §cSoftware§f: Pocketmine\n\n§7-----------------------------\n\n");
        $form->addButton("§l§d● §l§6Trở Lại §l§d●",0,"textures/ui/arrow");
        $form->sendToPlayer($sender);
    }
    public function luat($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          switch($result){
                   case 0:
                  $this->menu($sender);
                   break;
          }
         });
        $form->setTitle("§l§a[§6AIS§a]§c ˃ §eRules");
        $form->setContent("§b§lQuy Định Chung:§r\n§6 •§a Đối với mọi người §c- §eChúng ta hiểu, trách nhiệm và chấp nhận rằng  Luật của Server và Minecraft sẽ là hai thứ bạn phải tuân thủ, với phần lớn Server sẽ phụ trách bảo vệ, quản lí các bạn. Hình phạt trong Server theo mức độ tăng dần và sẽ nghiêm trọng hơn nếu bạn cố tình lạm dụng, cậy thế và không nghe lời. Bạn cũng không được phép spam quảng cáo Server khác. Không kích động gây war với những cá nhân hoặc tổ chức khác. Không giả mạo để lừa đảo trục lợi cho bản thân không gian lận tuyệt đối không sử dụng bất kì hành vi gian lận nào điều đó sẽ bị xử phạt mạnh tay.§r\n§6 •§a Đối với Member§c - §eTôn trọng mọi người, tập thể, bình đẳng giới, các thành viên trong ban quản lí (Staff) và những người làm việc trông coi Server (Admin), không phân biệt đối xử, không bắt nạt, trêu ghẹo, xúc phạm nhân phẩm, biểu hiện khiếm nhã. Không bè phái, tẩy chay, đả kích nội bộ.§r\n§6 • §aGiữa Member và Staff/Admin§c -§e Staff/Admin là những người hành luật để bảo vệ môi trường Server. Việc lạm dụng Staff/Admin và cậy người bắt nạt hộ trong Server hiển nhiên luôn xảy ra, Staff/Admin không trả lời bạn thì bạn không nên điều kiến quá nhiều, Staff/Admin không làm theo Điều bạn thì không nên bức xúc, tâm lí của các Staff/Admin cũng như các bạn, việc xử lí tình huống còn nằm ở rất nhiều khía cạnh. Vậy nếu có gì muốn Staff/Admin giao lưu hãy hỏi các câu hỏi, hỏi càng nhiều có nghĩa bạn không phải thiếu hiểu biết, nhưng đừng làm họ bực với thái độ của bạn (nhất là hỏi đi hỏi lại một câu hỏi đã có sẵn câu trả lời.)§r\n§6 • §a Quy Định Cho Staff và bao gồm cả Admin §c- §eKhông lạm dụng quyền để đi gian lận, hỗ trợ gian lận, không bắt nạt ai cả. Staff/Admin vẫn phải tôn trọng mọi người Admin không được cậy quyền setting vớ vẫn phá hoại, Admin cũng nên tôn trọng ý kiến của mọi người, không đặt cái tôi quá cao. Staff/Admin là người thi hành trật tự hãy làm gương cho người khác!§r\n§6 • §aĐối với Admin §c- §eKhi được op, Admin không có quyền cho người khác quyền lệnh của op, nếu phát hiện sẽ có hình phạt nặng đối với hành vi lạm quyền này!§r\n§6 • §aHình phạt §c-§eHình thức phạt khi phạm quy luật.\n - Ban nick vĩnh viễn\n - Mute nick vĩnh viễn ( các trường hợp Quảng cáo )\n - Các trường hợp vi phạm sẽ đc xử lý theo tùy trường hợp\n - Staff không chấp hành sẽ bị xử lý hạ rank!! §r\n§c§l==============");
        $form->addButton("§l§d● §l§6Trở Lại §l§d●",0,"textures/ui/arrow");
        $form->sendToPlayer($sender);
    }
    public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
         if($result == null){
               return;
          }
          switch($result){
             case 0:
             $sender->sendTitle("§l§bChào Mừng Đến Với §aMine§esuon ^^");
             break;
             case 1:
            $this->luat($sender);
             break;
             case 2:
            $this->help($sender);
              break;
              case 3:
            $this->dungeonhelp($sender);
              break;
              case 4:
            $this->chisohelp($sender);
              break;
        }
       });
        $form->setTitle("§l§a[§6AIS§a]§c ˃ §eMine§aSuon");
        $form->addButton("§l§d● §l§6Chơi §l§d●",0,"textures/ui/controller_glyph_color");
        $form->addButton("§l§d● §l§6Luật Lệ §l§d●",0,"textures/ui/Caution");
        $form->addButton("§l§d● §l§6Chi Tiết Về Lệnh §l§d●",0,"textures/ui/Feedback");
        $form->addButton("§l§d● §l§6Chi Tiết Về §cDungeon §l§d●",0,"textures/ui/Wrenches1");
        $form->addButton("§l§d● §l§6Chi Tiết Về §eChỉ Số §l§d●",0,"textures/ui/blindness_effect");
        $form->sendToPlayer($sender);
    }
    public function dungeonhelp($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          switch($result){
                   case 0:
                  $this->menu($sender);
                   break;
          }
         });
        $form->setTitle("§l§a[§6AIS§a]§c ˃ §eDungeon");
        $form->setContent("§e-§f Dungeon là 1 trong những khu vực có thể nói là thú vị là bởi !\n§e- §fBạn có thể tiêu diệt chúng và bạn sẽ nhận được đồ vứt đi như thịt thôi vân vân\n-§e-§f Có lẽ máy chủ chúng tôi sẽ khác 1 chút là khi bạn giết 1 con quái là bạn sẽ nhận được 1 kinh nghiệm\n§e-§f Thì trong server chúng tôi có chức năng §cchỉ số§f người chơi chức năng này sẽ hiển thị máu giáp , sát thương của bạn\n§e-§f Khi bạn đi phần trên của thanh đồ sẽ hiện lên những chỉ số bạn đang có mặc định máu là 20 giáp 0 dame 0 để nâng cấp\n§e§f Bạn cần đến dungeon tiêu diệt quai tích tụ đủ số kinh nghiệm nâng cấp\n§e-§f Ngoài ra bạn cũng có thể mua item nâng cấp chỉ số bản thân ở shop Đan vì chúng có thể nâng giúp bạn chỉ số mà không cần farm quái server play to win bạn chỉ cầy là có chúng thôi\n§rHi!");
        $form->addButton("§l§d● §l§6Trở Lại §l§d●",0,"textures/ui/arrow");
        $form->sendToPlayer($sender);
}
    public function chisohelp($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          switch($result){
                   case 0:
                  $this->menu($sender);
                   break;
          }
         });
        $form->setTitle("§l§a[§6AIS§a]§c ˃ §eIndex");
        $form->setContent("§l§cChi tiết về chỉ số:\n§cHP:§f Là một loại máu sử dụng bằng task vì để tránh vướng màn hình nhiều tim quá nhiều nên chúng tôi sử dụng task để tránh vướng bạn cũng có thể nâng cấp hp tại /player với 50 Kinh nghiệm\n§eGiáp:§f là 2 trong những yếu tố quan trọng giúp bạn chịu sát thương lâu hơn từ đối phương cũng như bạn sẽ được trâu bò hơn đấy :)) để nâng cấp bạn cần 100 kinh nghiệm\n§bDame:§f Đây là chỉ số có lẽ là tác dụng manh trong pvp nếu bạn nâng thì khi đấm bạn sẽ đấm sát thương cao hơn bình thường à giống sơn súng tăng dăm quá để nâng cấp bạn cần 75 kinh nghiệm \n\n\n");
        $form->addButton("§l§d● §l§6Trở Lại §l§d●",0,"textures/ui/arrow");
        $form->sendToPlayer($sender);
    }
}
