<?php

namespace instantlyta\fcaclan;

use _64FF00\PureChat\PureChat;
use instantlyta\fcaclan\event\ClanChangeEvent;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
//use LDX\iProtector\Main as Iprotect;

class FCAClan extends PluginBase implements Listener
{
    const OWNER_RANK = 3;

    const ACTION_INVITE = 0;
    const ACTION_KICK = 1;
    const ACTION_REQUEST_CONTROL = 2;
    const ACTION_SET_MOTD = 3;
    const ACTION_SET_RANK = 4;
    const ACTION_LEVEL_UP = 5;

    const SETTING_MAX_PLAYERS = 0;
    const SETTING_REQUIRED_POINT = 1;
    const SETTING_REQUIRED_MONEY = 2;
    const SETTING_CLAN_TAG = 3;

    private $settings = [];

    private $clans = [];
    private $invitePending = [];
    private $inviteSendConfirm = [];
    private $kickPending = [];
    private $quitPending = [];
    private $welcomePending = [];
    private $clanPromotePending = [];
    private $clanDeletePending = [];

    public $topClan = [];

    private static $instance = null;

    public function onLoad()
    {
        self::$instance = $this;
    }

    /**
     * @return FCAClan
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->reloadConfig();
        @mkdir($this->getDataFolder() . "profiles/");
        $this->clans = (new Config($this->getDataFolder() . "clans.yml", Config::YAML))->getAll();
        $this->settings = $this->getConfig()->get("settings");
		$this->iprotect = $this->getServer()->getPluginManager()->getPlugin("iProtector");
        $this->updateTopClan();
        $this->getScheduler()->scheduleDelayedRepeatingTask(new UpdateTopClanTask(), 36000, 36000);
    }

    public function onDisable()
    {
        $this->save();
    }

    // Events

    /*public function onJoin(PlayerLoginEvent $event) {
        $player = $event->getPlayer();
        if ($this->haveClan($player)) {
            $player->setDisplayName("[??l??3??? ??a" . $this->getClanName($player) . "??f ???]" . $player->getName());
        }
    }*/

    public function updateNametag(ClanChangeEvent $event)
    { // PureChat API
        /** @var PureChat $pureChat */
        $pureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
        if ($pureChat === null) return;
        $p = $event->getPlayer();

        $isMultiWorldSupportEnabled = $pureChat->getConfig()->get("enable-multiworld-support");

        $levelName = $isMultiWorldSupportEnabled ? $p->getLevel()->getName() : null;

        $p->setNameTag($pureChat->getNameTag($p, $levelName));
    }


    public function onDamagee(EntityDamageEvent $event)
    {
        if($event->isCancelled() or !$this->iprotect->canGetHurt($event->getEntity())){
	    return false;
		}else{
            if ($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();
                $entity = $event->getEntity();
                if (!($damager instanceof Player) || !($entity instanceof Player) || !$this->iprotect->canGetHurt($damager)) return;
                if ($this->haveClan($damager) && $this->haveClan($entity) && $this->getProfile($damager)->get("clan") === $this->getProfile($entity)->get("clan")) {
                    $event->setCancelled();
                    $damager->sendMessage("?????l??c T?? Nh??n ??e" . $entity->getName() . "??c C??ng Clan V???i B???n!");
                    return;
                } elseif ($entity->getHealth() - $event->getFinalDamage() <= 0) {
                    if ($this->haveClan($entity)) {
                        $this->clanAnnounce("?????l??e " . $entity->getName() . " ??cB??? T?? Nh??n??e " . $damager->getName() . ($this->haveClan($damager) ? " ??f[??cClan??e " . $this->getClanName($damager) . "??f]??c " : "") .
                            " ??c????nh Ch???t. H??y Tr??? Th?? N??o! ??e", $this->getClanName($entity));
                    }
                    if ($this->haveClan($damager)) {
                        $point = $this->haveClan($entity) ? 2 : 1;
                        $this->clanAnnounce("?????l??e " . $damager->getName() . "??c ???? Gi???t Ch???t ??e" . $entity->getName() . "??c, Gi??nh ??e$point ??i???m??c V??? Cho Clan!", $this->getClanName($damager));
                        $this->addPoint($point, $this->getClanName($damager));
                    }
                    return;
				}
			}
		}
	}

    protected function paginateText(CommandSender $sender, $pageNumber, array $txt)
    {
        $hdr = array_shift($txt);
        if ($sender instanceof ConsoleCommandSender) {
            $sender->sendMessage(TextFormat::GREEN . $hdr . TextFormat::RESET);
            foreach ($txt as $ln) $sender->sendMessage($ln);
            return true;
        }
        $pageHeight = 5;
        $lineCount = count($txt);
        $pageCount = intval($lineCount / $pageHeight) + ($lineCount % $pageHeight ? 1 : 0);
        $hdr = TextFormat::GREEN . $hdr . TextFormat::RESET;
        if ($pageNumber > $pageCount) {
            $sender->sendMessage($hdr);
            $sender->sendMessage("?????l??c Kh??ng C?? Trang H?????ng D???n N??y!");
            return true;
        }
        $hdr .= TextFormat::RED . " ??l??f[??e{$pageNumber}??f/??e{$pageCount}??f]";
        $sender->sendMessage($hdr);
        for ($ln = ($pageNumber - 1) * $pageHeight; $ln < $lineCount && $pageHeight--; ++$ln) {
            $sender->sendMessage($txt[$ln]);
        }
        return true;
    }

    protected function getPageNumber(array &$args)
    {
        $pageNumber = 1;
        if (count($args) && is_numeric($args[count($args) - 1])) {
            $pageNumber = (int)array_pop($args);
            if ($pageNumber <= 0) $pageNumber = 1;
        }
        return $pageNumber;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "clan":
                if (!isset($args[0])) {
                    $sender->sendMessage("?????l??c S??? D???ng: /clan help");
                    return true;
                }
                /** @var string[] $altArgs */
                $altArgs = array_slice($args, 1);
                switch ($args[0]) {
                    case "help":
                        $help = [
                            "??r?????l??b H?????ng D???n S??? D???ng L???nh Clan ??r???",
                            "??r?????l??e /c ??f:??c Tr?? Chuy???n K??nh Clan",
                            "??r?????l??e /clan top ??f:??c Xem X???p H???ng Clan",
                            "??r?????l??e /clan create ??f:??c T???o M???t Clan M???i",
                            "??r?????l??e /clan delete ??f:??c Xo?? Clan C???a B???n",
                            "??r?????l??e /clan join ??f:??c G???i Y??u C???u Xin Gia Nh???p Clan",
                            "??r?????l??e /clan quit ??f:??c Tho??t Clan C???a B???n",
                            "??r?????l??e /clan accept ??f:??c Ch???p Nh???n L???i M???i V??o Clan",
                            "??r?????l??e /clan decline ??f:??c T??? Ch???i L???i M???i V??o Clan",
                            "??r?????l??e /clan donate ??f:??c C???ng Hi???n Cho Clan",
                            "??r?????l??e /clan lookup ??f:??c Xem T?? Nh??n Thu???c Clan N??o",
                            "??r?????l??e /clan requestlist ??f:??c Xem Danh S??ch C??c Th??nh Vi??n Xin V??o Clan",
                            "??r?????l??e /clan status ??f:??c Xem Th??ng Tin Clan",
                            "??r?????l??e /clan invite ??f:??c M???i Th??m T?? Nh??n V??o Clan",
                            "??r?????l??e /clan kick ??f:??c ??u???i Th??nh Vi??n Kh???i Clan",
                            "??r?????l??e /clan motd ??f:??c S???a Kh???u Hi???u Clan",
                            "??r?????l??e /clan setrank ??f:??c Ch???nh Ch???c V??? cho Th??nh Vi??n Trong Clan",
                            "??r?????l??e /clan levelup ??f:??c N??ng C???p Clan",
                        ];
                        $this->paginateText($sender, $this->getPageNumber($args), $help);
                        break;
                    case "create":
                        if ($sender instanceof ConsoleCommandSender || $sender instanceof RemoteConsoleCommandSender) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng L???nh Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") !== false) {
                            $sender->sendMessage("?????l??c B???n ???? C?? Clan!");
                            return true;
                        }
                        if (count($altArgs) <> 1) {
                            $cost = $this->getConfig()->get("create-cost");
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan create <T??n Clan>\n?????l??c L??u ??: T???o Clan T???n??e $cost Xu!");
                            return true;
                        }

                        if (strpos($altArgs[0], "??")) {
                            $sender->sendMessage("?????l??c Kh??ng ???????c Ghi N??u Trong T??n Clan!");
                            return true;
                        }

                        if (strlen($altArgs[0]) > 20) {
                            $sender->sendMessage("?????l??c T??n Clan Ph???i Ph???i Ng???n H??n 20 K?? T???!");
                            return true;
                        }

                        if (isset($this->clans[$altArgs[0]])) {
                            $sender->sendMessage("?????l??c Clan ???? T???n T???i!");
                            return true;
                        }

                        $eco = EconomyAPI::getInstance();
                        $money = $eco->myMoney($sender);
                        $cost = $this->getConfig()->get("create-cost");
                        if ($money < $cost) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? ????? Ti???n T???o Clan\n??r?????l??f [??cPh?? T???o Clan:??e $cost Xu??c, B???n C??:??e $money Xu??f]");
                            return true;
                        }

                        $eco->reduceMoney($sender, $cost);

                        $this->clans[$altArgs[0]] = [
                            "motd" => $altArgs[0],
                            "level" => 1,
                            "point" => 0,
                            "members" => [
                                strtolower($sender->getName()) => self::OWNER_RANK // see config.yml
                            ],
                            "request" => []
                        ];

                        $profile->set("clan", $altArgs[0]);
                        $profile->set("rank", self::OWNER_RANK);
                        $profile->save();

                        $this->save();
                        $sender->sendMessage("?????l??c T???o Clan Th??nh C??ng!");
                        break;
                    case "invite":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "accept":
                                if (isset($this->invitePending[$sender->getName()]) && $this->invitePending[$sender->getName()][0] > time()) {
                                    $inviter = $this->getServer()->getPlayerExact($this->invitePending[$sender->getName()][2]);
                                    if ($inviter !== null) {
                                        $inviter->sendMessage("?????l??e " . $sender->getName() . "??c ???? Ch???p Nh???n L???i M???i V??o Clan C???a B???n!");
                                    }

                                    $this->addPlayerToClan($sender, $this->invitePending[$sender->getName()][1]);

                                    unset($this->invitePending[$sender->getName()]);
                                    return true;
                                } else {
                                    $sender->sendMessage("?????l??c L???i M???i Kh??ng T???n T???i!");
                                    return true;
                                }
                                break;
                            case "decline":
                                if (!isset($this->invitePending[$sender->getName()])) {
                                    $sender->sendMessage("?????l??c B???n Kh??ng ???????c B???t C??? Ai M???i!");
                                    return true;
                                } else {
                                    $inviter = $this->getServer()->getPlayerExact($this->invitePending[$sender->getName()][2]);
                                    if ($inviter !== null) {
                                        $inviter->sendMessage("?????l??e " . $sender->getName() . "??c ???? T??? Ch???i L???i M???i V??o Clan C???a B???n!");
                                    }
                                    unset($this->invitePending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c ???? T??? Ch???i L???i M???i!");
                                    return true;
                                }
                                break;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || !$this->canDo($sender, self::ACTION_INVITE)) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n M???i T?? Nh??n Kh??c V??o Clan!");
                            return true;
                        }
                        if ($this->isClanFull($profile->get("clan"))) {
                            $sender->sendMessage("?????l??c Th??nh Vi??n Clan ???? ?????y!");
                            return true;
                        }
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "yes":
                                if (isset($this->inviteSendConfirm[$sender->getName()]) && $this->inviteSendConfirm[$sender->getName()][0] > time()) {
                                    $player = $this->getServer()->getPlayerExact($this->inviteSendConfirm[$sender->getName()][1]);
                                    $this->invitePending[$player->getName()] = [time() + 50, $profile->get("clan"), $sender->getName()];
                                    $sender->sendMessage("?????l??c L???i M???i ???? ???????c G???i!");
                                    $player->sendMessage("?????l??e " . $sender->getName() . "??c ???? G???i Cho B???n M???t L???i M???i V??o Clan??e '" . $profile->get("clan") . "'!");
                                    $player->sendMessage("?????l??c B???m ??e/clan invite accept??c ????? ?????ng ?? Ho???c ??edecline ??c????? T??? Ch???i.");
                                    $player->sendMessage("?????l??c L???i M???i S??? T??? ?????ng H???y Sau 50 Gi??y!");
                                    return true;
                                } else {
                                    $sender->sendMessage("?????l??c L???i M???i Kh??ng T???n T???i!");
                                    return true;
                                }
                                break;
                            case "no":
                                if (!isset($this->inviteSendConfirm[$sender->getName()])) {
                                    $sender->sendMessage("?????l??c B???n Kh??ng M???i B???t K?? Ai!");
                                    return true;
                                } else {
                                    unset($this->inviteSendConfirm[$sender->getName()]);
                                    $sender->sendMessage("?????l??c ???? H???y L???i M???i!");
                                    return true;
                                }
                                break;
                        }
                        if (count($altArgs) <> 1) {
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan invite <T??n T?? Nh??n>");
                            return true;
                        }
                        $player = $this->getServer()->getPlayer($altArgs[0]);
                        if ($player === null || $player->getName() === $sender->getName()) { // TODO this. is. bad.
                            $sender->sendMessage("?????l??c Kh??ng T??m Th???y T?? Nh??n N??o V???i T??? Kh??a??e '" . $altArgs[0] . "'");
                            return true;
                        } else if ($player->getName() === $altArgs[0] && $this->getProfile($player)->get("clan") !== false) {
                            $sender->sendMessage("?????l??c T?? Nh??n??e " . $altArgs[0] . "??c ???? C?? Clan R???i!");
                            return true;
                        }
                        if (strtolower($altArgs[0]) <> strtolower($player->getName())) {
                            $this->inviteSendConfirm[$sender->getName()] = [time() + 50, $player->getName()];
                            $sender->sendMessage("?????l??c H??? Th???ng Kh??ng T??m Th???y T?? Nh??n ??e'" . $altArgs[0] . "'.\n?????l??c C?? Ph???i B???n Mu???n M???i??e '" . $player->getName() . "'??c? G????e /clan invite <yes/no>??c ????? ?????ng ?? Ho???c T??? Ch???i.");
                            $sender->sendMessage("?????l??c L???i M???i S??? T??? ?????ng H???y Sau 50 Gi??y!");
                            return true;
                        }
                        $this->invitePending[$player->getName()] = [time() + 50, $profile->get("clan"), $sender->getName()];
                        $sender->sendMessage("?????l??c L???i M???i ???? ???????c G???i!");
                        $player->sendMessage("?????l??e " . $sender->getName() . " ??c???? G???i Cho B???n M???t L???i L???i V??o Clan??e '" . $profile->get("clan") . "'.");
                        $player->sendMessage("?????l??c G?? ??e/clan invite accept??c ????? ?????ng ?? Ho???c??e decline ??c????? T??? Chu???i.");
                        $player->sendMessage("?????l??c L???i M???i S??? T??? ?????ng H???y Sau 50 Gi??y!");
                        break;
                    case "my":
                    case "info":
                    case "status":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false) {
                            $sender->sendMessage("?????l??c B???n Kh??ng ??? Trong Clan N??o!");
                            return true;
                        }
                        $clan = $this->clans[$profile->get("clan")];
                        $mes = "?????l??c ??eClan??e " . $clan["motd"] . " ??f[??e" . $profile->get("clan") . "??f]??c:\n";
                        $mes .= "??e?????l??c C???p Clan: ??e" . $clan["level"] . "\n";
                        $mes .= "?????l??c ??i???m Clan: ??e" . $clan["point"] . "\n";
                        $mes .= "?????l??c Ch???c V??? C???a B???n: ??e" . $this->getRankName($profile->get("rank")) . "\n";
                        $mes .= "?????l??c Th??nh Vi??n:??e\n";
                        foreach ($clan["members"] as $name => $rank) {
                            $mes .= "  $name: " . $this->getRankName($rank) . "??c, ";
                        }
                        $sender->sendMessage($mes);
                        break;
                    case "kick":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || !$this->canDo($sender, self::ACTION_KICK)) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n ??u???i Th??nh Vi??n Kh???i Clan!");
                            return true;
                        }
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "yes":
                                if (isset($this->kickPending[$sender->getName()]) && $this->kickPending[$sender->getName()][0] > time()) {
                                    $pName = $this->kickPending[$sender->getName()][2];
                                    $targetProfile = $this->getProfile($pName);
                                    if ($targetProfile->get("clan") !== $profile->get("clan")) {
                                        $sender->sendMessage("?????l??c Ng?????i N??y Kh??ng O??? Trong Clan C???a B???n!");
                                        unset($this->kickPending[$sender->getName()]);
                                        $sender->sendMessage("?????l??c ???? H???y Y??u C???u ??u???i Th??nh Vi??n!");
                                        return true;
                                    }
                                    $result = $this->removePlayerFromClan($pName);
                                    unset($this->kickPending[$sender->getName()]);
                                    $sender->sendMessage($result ? "?????l??c ???? ??u???i T?? Nh??n. " : "C?? L???i X???y Ra Trong Qu?? Tr??nh ??u???i.");
                                    return true;
                                } else {
                                    $sender->sendMessage("?????l??c Y??u C???u Kh??ng T???n T???i!");
                                    return true;
                                }
                                break;
                            case "no":
                                if (!isset($this->kickPending[$sender->getName()])) {
                                    $sender->sendMessage("?????l??c B???n Kh??ng ??u???i B???t C??? Ai!");
                                    return true;
                                } else {
                                    unset($this->kickPending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c ???? H???y Y??u C???u!");
                                    return true;
                                }
                                break;
                        }
                        if (count($altArgs) <> 1) {
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan kick <T??n T?? Nh??n>");
                            return true;
                        }
                        //$clan = $this->getClan($profile->get("clan"));
                        $needle = $altArgs[0];
                        $found = $this->getPlayerInClan($needle, $this->getClanName($sender), $sender->getName());
                        if ($found === null) {
                            $sender->sendMessage("?????l??c Kh??ng T??m Th???y T?? Nh??n N??o V???i T??? Kh??a??e '$needle' ??cTrong Clan C???a B???n!.");
                            return true;
                        }
                        $this->kickPending[$sender->getName()] = [time() + 50, $profile->get("clan"), $found];
                        $sender->sendMessage("?????l??c B???n C?? Ch???c Ch???n Mu???n ??u???i??e '$found' ??cKh???i Clan?");
                        $sender->sendMessage("?????l??c G??:??e /clan kick yes ??c????? X??c Nh???n!");
                        $sender->sendMessage("?????l??c Y??u C???u S??? T??? ?????ng H???y Sau 50 Gi??y!");
                        break;
                    case "requestlist":
                    case "rl":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || !$this->canDo($sender, self::ACTION_REQUEST_CONTROL)) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n Ch???p Nh???n/T??? Ch???i Y??u C???u V??o Clan.");
                            return true;
                        }
                        $clan =& $this->clans[$profile->get("clan")];
                        if (isset($altArgs[0])) {
                            //in_array($altArgs[0], $clan["request"])
                            if (($found = $this->getPlayerInRequestList($altArgs[0], $profile->get("clan"))) !== null) {
                                if ($this->isClanFull($profile->get("clan"))) {
                                    $sender->sendMessage("?????l??c Th??nh Vi??n Clan ???? ?????y!");
                                    return true;
                                }
                                $this->addPlayerToClan($found, $profile->get("clan"));
                                unset($clan["request"][array_search($found, $clan["request"])]);
                                $this->save();
                                $sender->sendMessage("?????l??c ???? Ch???p Nh???n Y??u C???u N??y!");
                            } else {
                                $sender->sendMessage("?????l??c Kh??ng T??m Th???y T?? Nh??n N??y!");
                            }
                            return true;
                        }
                        $mes = "?????l??c Danh S??ch Y??u C???u V??o Clan:\n";
                        foreach ($clan["request"] as $name) {
                            $mes .= "??e{$name}??c, ";
                        }
                        $mes .= "\n?????l??a G????e /clan requestlist <T??n T?? Nh??n>??a ????? Ch???p Nh???n T?? Nh??n V??o Clan.";
                        $sender->sendMessage($mes);
                        break;
                    case "delete":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || $profile->get("rank") !== self::OWNER_RANK) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n X??a Clan!");
                            return true;
                        }
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "yes":
                                if (isset($this->clanDeletePending[$sender->getName()]) && $this->clanDeletePending[$sender->getName()][0] > time()) {
                                    $this->clanAnnounce("?????l??c Clan ???? B??? Gi???i T??n B???i Ch??? Clan!", $profile->get("clan"));
                                    foreach ($this->clans[$profile->get("clan")]["members"] as $name => $rank) {
                                        $this->removePlayerFromClan($name);
                                    }
                                    unset($this->clans[$profile->get("clan")]);
                                    $this->save();
                                    unset($this->clanDeletePending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c X??a Clan Th??nh C??ng!");
                                    return true;
                                } else {
                                    $sender->sendMessage("?????l??c Y??u C???u Kh??ng T???n T???i!");
                                    return true;
                                }
                                break;
                            case "no":
                                if (!isset($this->clanDeletePending[$sender->getName()])) {
                                    $sender->sendMessage("?????l??c Y??u C???u Kh??ng T???n T???i!");
                                    return true;
                                } else {
                                    unset($this->clanDeletePending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c ???? H???y Y??u C???u!");
                                    return true;
                                }
                                break;
                        }
                        $this->clanDeletePending[$sender->getName()] = [time() + 50];
                        $sender->sendMessage("?????l??c B???n ??ang X??a Clan??e " . $profile->get("clan") . ".");
                        $sender->sendMessage("?????l??c G????e /clan delete yes ??c????? X??c Nh???n!");

                        break;
                    case "quit":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Gia Nh???p Clan N??o");
                            return true;
                        }
                        if ($profile->get("rank") === self::OWNER_RANK) {
                            $sender->sendMessage("?????l??c B???n Kh??ng Th??? Tho??t Clan Khi L?? Ch??? Clan H??y Chuy???n Nh?????ng Ch???c V??? Tr?????c Ho???c Gi???i T??n Clan!");
                            return true;
                        }
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "yes":
                                if (isset($this->quitPending[$sender->getName()]) && $this->quitPending[$sender->getName()][0] > time()) {
                                    $result = $this->removePlayerFromClan($sender);
                                    unset($this->quitPending[$sender->getName()]);
                                    $sender->sendMessage($result ? "?????l??c Tho??t Clan Th??nh C??ng." : "C?? L???i X???y Ra Trong Qu?? Tr??nh Tho??t Clan.");
                                    return true;
                                } else {
                                    $sender->sendMessage("?????l??c Y??u C???u Kh??ng T???n T???i!");
                                    return true;
                                }
                                break;
                            case "no":
                                if (!isset($this->quitPending[$sender->getName()])) {
                                    return true;
                                } else {
                                    unset($this->quitPending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c ???? H???y Y??u C???u!");
                                    return true;
                                }
                                break;
                        }
                        $this->quitPending[$sender->getName()] = [time() + 50];
                        $sender->sendMessage("?????l??c B???n C?? Ch???c Ch???n Mu???n R???i Clan??e " . $profile->get("clan") . "??c?");
                        $sender->sendMessage("?????l??c G????e /clan quit yes ??c????? X??c Nh???n!");
                        break;
                    case "admin":
                        // TODO
                        break;
                    case "motd":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || !$this->canDo($sender, self::ACTION_SET_MOTD)) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n Ch???nh S???a Kh???u Hi???u Clan!");
                            return true;
                        }
                        if (!isset($altArgs[0])) {
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan motd <Kh???u Hi???u>");
                            return true;
                        }
                        $this->clans[$profile->get("clan")]["motd"] = $altArgs[0];
                        $this->save();
                        $sender->sendMessage("?????l??c ???? Ch???nh S???a Kh???u Hi???u Clan!");
                        break;
                    case "welcome":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? ??? Trong Clan N??o!");
                            return true;
                        }
                        if (isset($this->welcomePending[$profile->get("clan")])) {
                            foreach ($this->welcomePending[$profile->get("clan")] as $key => $data) {
                                if (!in_array($sender->getName(), $data[1])) {
                                    $this->clanChat($sender, "?????l??c Ch??o M???ng??e " . $data[0] . "??c ???? Tham Gia Clan!");
                                    $this->welcomePending[$profile->get("clan")][$key][1][] = $sender->getName();
                                }
                            }
                        }
                        break;
                    case "setrank":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || !$this->canDo($sender, self::ACTION_SET_RANK)) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n Ch???nh S???a C???p B???c Th??nh Vi??n Clan!");
                            return true;
                        }
                        if (!isset($altArgs[0])) {
                            $sender->sendMessage("?????l??c /clan setrank <T??n Th??nh Vi??n> <C???p B???c>"); // TODO Rank Name
                            $mes = "?????l??c Danh S??ch C???p B???c:??e \n";
                            foreach ($this->getConfig()->get("rank") as $rank => $info) {
                                $mes .= "$rank ??f-??e " . $info["name"] . "\n";
                            }
                            $sender->sendMessage($mes);
                            return true;
                        }
                        //$clan =& $this->clans[$profile->get("clan")];
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "yes":
                                if (isset($this->clanPromotePending[$sender->getName()]) && $this->clanPromotePending[$sender->getName()][0] > time()) {
                                    $newOwner = $this->clanPromotePending[$sender->getName()][1];
                                    $this->setRank($newOwner, self::OWNER_RANK);
                                    $this->setRank($sender, 1);
                                    unset($this->clanPromotePending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c B???n ???? Chuy???n Nh?????ng Clan Cho:??e $newOwner");
                                    $this->clanAnnounce("?????l??c Clan ???? ???????c Chuy???n Nh?????ng To??n B??? Quy???n S??? H???u Cho:??e {$newOwner}??c, H??y Ch??c M???ng!", $profile->get("clan"));
                                    return true;
                                } else {
                                    $sender->sendMessage("?????l??c Y??u C???u Kh??ng T???n T???i!");
                                    return true;
                                }
                                break;
                            case "no":
                                if (!isset($this->clanPromotePending[$sender->getName()])) {
                                    return true;
                                } else {
                                    unset($this->clanPromotePending[$sender->getName()]);
                                    $sender->sendMessage("?????l??c ???? H???y Y??u C???u!");
                                    return true;
                                }
                                break;
                        }
                        if (isset($altArgs[0])) {
                            if (isset($altArgs[1])) {
                                $altArgs[1] = (int)$altArgs[1];
                                if (($found = $this->getPlayerInClan($altArgs[0], $profile->get("clan"), $sender->getName())) === null) {
                                    $sender->sendMessage("?????l??c T?? Nh??n Kh??ng C?? Trong Clan");
                                    return true;
                                }
                                $targetProfile = $this->getProfile($found);
                                if ($targetProfile->get("rank") >= $profile->get("rank")) {
                                    $sender->sendMessage("?????l??c Ng?????i N??y C?? Ch???c V??? Cao H??n Ho???c Ngang B???ng B???n!");
                                    return true;
                                }
                                if ((string)((int)$altArgs[1]) !== (string)$altArgs[1]) {
                                    $sender->sendMessage("?????l??c C???p B???c Ph???i L?? 1 S???");
                                    return true;
                                }
                                $altArgs[1] = (int)$altArgs[1];
                                if ($altArgs[1] >= $profile->get("rank") && $profile->get("rank") !== self::OWNER_RANK) {
                                    $sender->sendMessage("?????l??c B???n Kh??ng Th??? ?????t Ch???c V??? Ng?????i Kh??c Cao H??n Ho???c Ngang B???ng B???n!");
                                    return true;
                                } else if ($altArgs[1] === $targetProfile->get("rank")) {
                                    $sender->sendMessage("?????l??c Ng?????i N??y ??ang ??? Ch???c R???i!");
                                    return true;
                                }
                                if ($altArgs[1] === self::OWNER_RANK) {
                                    if ($profile->get("rank") !== self::OWNER_RANK) {
                                        $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n Chuy???n Nh?????ng Quy???n S??? H???u Clan!");
                                        return true;
                                    }
                                    $this->clanPromotePending[$sender->getName()] = [time() + 50, $found];
                                    $sender->sendMessage("?????l??c B???n c????e CH???C CH???N??c Mu???n Chuy???n Quy???n S??? H???u Clan Cho:??e $found?");
                                    $sender->sendMessage("?????l??c B???n S??? Kh??ng C??n Quy???n S??? H???u Clan N??y V?? Tr??? V??? C???p B???c:??e " . $this->getRankName(1) . ".");
                                    $sender->sendMessage("?????l??c G????e /clan setrank yes ??c????? X??c Nh???n. Y??u C???u Chuy???n Nh?????ng S??? B??? H???y Sau 50 Gi??y!");
                                    return true;
                                }
                                if (!isset($this->getConfig()->get("rank")[$altArgs[1]])) {
                                    $sender->sendMessage("?????l??c Kh??ng C?? C???p B???c N??y!");
                                    return true;
                                }
                                $promote = $altArgs[1] > $targetProfile->get("rank");
                                $this->setRank($found, $altArgs[1]);
                                $sender->sendMessage("?????l??c ?????t Ch???c V??? Th??nh C??ng!");
                                $this->clanAnnounce("?????l??e " . $found . "??c ???? ???????c " . ($promote ? "N??ng" : "H???") . " Ch???c??e " . $this->getRankName($altArgs[1]) . " ??cB???i??e " . $sender->getName(), $profile->get("clan"));
                            }
                        }
                        break;
                    case "lookup":
                        if (count($altArgs) <> 1) {
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan lookup <T??n T?? Nh??n>");
                            return true;
                        }
                        $sender->sendMessage("?????l??c T?? Nh??n " . $altArgs[0] . " " . ($this->haveClan($altArgs[0]) ? "Thu???c Clan " . $this->getClanName($altArgs[0]) : "Kh??ng Thu???c Clan N??o."));
                        break;
                    case "reload":
                        if (!($sender->isOp())) return true;
                        $this->saveDefaultConfig();
                        $this->reloadConfig();
                        $sender->sendMessage("?????l??c ???? T???i L???i??e config.yml");
                        break;
                    case "top":
                        $mes = "??e?????l??c X???p H???ng ??e10 Clan??c Xu???t S???c Nh???t Nh?? T?? ??eMine??aSuon ??e???\n";
                        for ($i = 0; $i < 10; $i++) {
                            $mes .= "??r?????l??b X???p H???ng??e " . ($i + 1) . " ??f:??a " . (isset($this->topClan[$i]) ? $this->topClan[$i] : "??cCh??a c???p nh???t!") . "\n";
                        }
                        if ($sender instanceof Player && $this->haveClan($sender)) {
                            $mes .= "??r?????l??a Clan B???n ??ang X???p Th???: ??e" . (isset(array_flip($this->topClan)[$this->getClanName($sender)]) ? array_flip($this->topClan)[$this->getClanName($sender)] + 1 : "Ch??a C???p Nh???t!") . "\n";
                        }
                        $mes .= "??r?????l??a X???p H???ng Clan S??? ???????c C???p Nh???t M???i:??e 30 Ph??t";
                        $sender->sendMessage($mes);
                        break;
                    case "levelup":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false || !$this->canDo($sender, self::ACTION_LEVEL_UP)) {
                            $sender->sendMessage("?????l??c B???n Kh??ng C?? Quy???n N??ng C???p Clan!");
                            return true;
                        }
                        $clan = $this->getClan($profile->get("clan"));
                        $nextLevel = $clan["level"] + 1;
                        if ($nextLevel > $this->getConfig()->get("max-clan-level")) {
                            $sender->sendMessage("?????l??c Clan C???a B???n ???? ?????t C???p T???i ??a!");
                            return true;
                        }
                        $requiredPoint = $this->getSetting(self::SETTING_REQUIRED_POINT, $nextLevel);
                        $requiredMoney = $this->getSetting(self::SETTING_REQUIRED_MONEY, $nextLevel);
                        $eco = EconomyAPI::getInstance();
                        if (isset($altArgs[0]) && $altArgs[0] === "up") {
                            if ($clan["point"] < $requiredPoint) {
                                $sender->sendMessage("?????l??c Clan C???a B???n Kh??ng C?? ????? ??i???m ????? N??ng C???p ??f[??cC???n:??e " . $requiredPoint . "??f]");
                                return true;
                            }
                            if ($eco->myMoney($sender) < $requiredMoney) {
                                $sender->sendMessage("?????l??c B???n Kh??ng ????? Ti???n ????? N??ng C???p Clan ??f[??cC???n:??e " . $eco->getMonetaryUnit() . $requiredMoney . "??f]");
                                return true;
                            }
                            $this->clans[$profile->get("clan")]["point"] -= $requiredPoint;
                            $eco->reduceMoney($sender, $requiredMoney);
                            $this->clans[$profile->get("clan")]["level"] = $nextLevel;
                            $this->save();
                            //$eco->save();
                            $sender->sendMessage("?????l??c N??ng C???p Clan Th??nh C??ng!");
                            $this->clanAnnounce("?????l??c Clan ???? ???????c N??ng C???p L??n C???p??e $nextLevel.??c Xin Ch??c M???ng!", $profile->get("clan"));
                            return true;
                        }
                        $sender->sendMessage("?????l??c ????? N??ng C???p Clan L??n C???p??e $nextLevel ??cC???n Ti??u Hao??e $requiredPoint ??i???m ??cClan V????e " . $eco->getMonetaryUnit() . "$requiredMoney.");
                        $sender->sendMessage("?????l??c ??i???m Clan C?? Th??? Ki???m ???????c B???ng C??ch Gi???t Th??nh Vi??n Clan Kh??c(+2) Ho???c T?? Nh??n B??nh Th?????ng(+1)");
                        $sender->sendMessage("?????l??c G?? ??e/clan??c levelup up ????? X??c Nh???n N??ng C???p Clan!");
                        break;
                    case "join":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") !== false) {
                            $sender->sendMessage("?????l??c B???n ???? C?? Clan!");
                            return true;
                        }
                        if (isset($altArgs[0])) switch ($altArgs[0]) {
                            case "cancel":
                                if (!isset($altArgs[1])) {
                                    $sender->sendMessage("?????l??c S??? D???ng:??e /clan join cancel <T??n Clan>");
                                    return true;
                                }
                                if (!isset($this->clans[$altArgs[1]])) {
                                    $sender->sendMessage("?????l??c Clan Kh??ng T???n T???i!");
                                    return true;
                                }
                                $clan =& $this->clans[$altArgs[1]];
                                if (!in_array($sender->getName(), $clan["request"])) {
                                    $sender->sendMessage("?????l??c B???n Ch??a Y??u C???u V??o Clan N??y!");
                                    return true;
                                }
                                unset($clan["request"][array_search($sender->getName(), $clan["request"])]);
                                $this->save();
                                $sender->sendMessage("?????l??c H??y Y??u C??u fTh??nh C??ng!");
                                break;
                        }
                        if (!isset($altArgs[0])) {
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan join <T??n Clan>");
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan join cancel <T??n Clan>");
                            return true;
                        }
                        if (!isset($this->clans[$altArgs[0]])) {
                            $sender->sendMessage("?????l??c Clan ??e" . $altArgs[0] . "??c Kh??ng T???n T???i.");
                            return true;
                        }
                        if (in_array(strtolower($sender->getName()), $this->clans[$altArgs[0]]["request"])) {
                            $sender->sendMessage("?????l??c B???n ???? Xin V??o Clan N??y R???i!");
                            return true;
                        }
                        $this->clans[$altArgs[0]]["request"][] = strtolower($sender->getName());
                        $this->save();
                        $sender->sendMessage("?????l??c Xin V??o Clan??e " . $altArgs[0] . "??c Th??nh C??ng. B???n C?? Th??? H??y Y??u C???u Xin V??o Clan B???ng L???nh:??e /clan join cancel");
                        break;
                    case "donate":
                        if (!($sender instanceof Player)) {
                            $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                            return true;
                        }
                        $profile = $this->getProfile($sender);
                        if ($profile->get("clan") === false) {
                            $sender->sendMessage("?????l??c B???n Kh??ng ??? Trong Clan N??o!");
                            return true;
                        }
                        $perPointCost = $this->getConfig()->get("point-cost");
                        $eco = EconomyAPI::getInstance();
                        if (!isset($altArgs[0]) || (string)(int)$altArgs[0] !== $altArgs[0]) {
                            $sender->sendMessage("?????l??c S??? D???ng:??e /clan donate <??i???m>\n?????l??c1 ??i???m = ??e" . $eco->getMonetaryUnit() . $perPointCost);
                            return true;
                        }
                        $point = (int)$altArgs[0];
                        if ($point <= 0) {
                            $sender->sendMessage("?????l??c ??i???m C???ng Hi???n Kh??ng Ph?? H???p!");
                            return true;
                        }
                        if ($eco->myMoney($sender) - $point * $perPointCost < 0) {
                            $sender->sendMessage("?????l??c B???n Kh??ng ????? Ti???n ??f[??cC???n:??e " . ($point * $perPointCost) . "??c, B???n C??:??e " . $eco->myMoney($sender) . "??f]");
                            return true;
                        }
                        $this->addPoint($point, $this->getClanName($sender));
                        $eco->reduceMoney($sender, $point * $perPointCost);
                        $this->save();
                        $sender->sendMessage("?????l??c ???? ????ng G??p??e $point ??i???m!");
                        $this->clanAnnounce("?????l??e " . $sender->getName() . "??c ???? ????ng G??p??e $point ??i???m.", $this->getClanName($sender));
                        break;
                    default:
                        $sender->sendMessage("?????l??c Kh??ng T??m Th???y L???nh. G?? ??e/clan help ??c????? Xem Chi Ti???t.");
                        return true;
                }

                return true;
            case "clanchat":
                if (!($sender instanceof Player)) {
                    $sender->sendMessage("?????l??c Vui L??ng S??? D???ng C??u L???nh N??y Trong Tr?? Ch??i!");
                    return true;
                }
                $profile = $this->getProfile($sender);
                if ($profile->get("clan") === false) {
                    $sender->sendMessage("?????l??c B???n Kh??ng ??? Trong Clan N??o!");
                    return true;
                }
                if (!isset($args[0])) {
                    $sender->sendMessage("?????l??c S??? D???ng:??e /cchat <Tin Nh???n>");
                    return true;
                }
                $this->clanChat($sender, implode(" ", $args));
                return true;
            default:
                return false;
        }
    }

    /**
     * @param Player|string $player
     * @return Config
     */
    public function getProfile($player)
    {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        $dir = $this->getDataFolder() . "/profiles/" . substr($player, 0, 1) . "/";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $cfg = new Config($dir . "$player.yml", Config::YAML, ["clan" => false, "rank" => 0]);
        return $cfg;
    }

    /**
     * @param int $rank
     * @return string
     */
    public function getRankName(int $rank)
    {
        return $this->getConfig()->get("rank")[$rank]["name"];
    }

    public function save()
    {
        $data = new Config($this->getDataFolder() . "clans.yml", Config::YAML);
        $data->setAll($this->clans);
        $data->save();
    }

    /**
     * @param Player|string $player
     * @param int $action
     * @return bool|null
     */
    public function canDo($player, int $action)
    {
        $profile = $this->getProfile($player);
        if ($profile === null) return null;
        if (($actionString = $this->actionToString($action)) === null) return null;
        return $this->getConfig()->get("rank")[$profile->get("rank")]["control"][$actionString];
    }

    public function actionToString(int $action)
    {
        switch ($action) {
            case self::ACTION_INVITE:
                return "invite";
            case self::ACTION_KICK:
                return "kick";
            case self::ACTION_REQUEST_CONTROL:
                return "requestcontrol";
            case self::ACTION_SET_MOTD:
                return "setmotd";
            case self::ACTION_SET_RANK:
                return "setrank";
            case self::ACTION_LEVEL_UP:
                return "levelup";
        }
        return null;
    }

    /**
     * @param Player|string $player
     * @param string $clanName
     * @return bool
     */
    public function addPlayerToClan($player, string $clanName)
    {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $profile = $this->getProfile($player);
        if (!isset($this->clans[$clanName])) return false;
        $clan =& $this->clans[$clanName];
        if (count($clan["members"]) >= $this->getSetting(self::SETTING_MAX_PLAYERS, $clan["level"])) return false;
        $clan["members"][strtolower($player)] = 1;
        $this->save();
        $profile->set("clan", $clanName);
        $profile->set("rank", 1);
        $profile->save();
        $this->welcomePending[$clanName][] = [$player, []];
        if (($tmp = $this->getServer()->getPlayerExact($player)) !== null) {
            $this->getServer()->getPluginManager()->callEvent(new ClanChangeEvent($tmp, $clanName));
        }
        $this->clanAnnounce("?????l??e ". $player . "??c ???? V??o Clan! G?? ??e/clan welcome??c ????? Ch??o M???ng!", $clanName);
        return true;
    }

    /**
     * @param Player|string $player
     * @return bool
     */
    public function removePlayerFromClan($player)
    {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $profile = $this->getProfile($player);
        $clanName = $profile->get("clan");
        try {
            unset($this->clans[$clanName]["members"][strtolower($player)]);
        } catch (\Exception $exception) {
            $this->getLogger()->error("?????l??c T??n Clan Kh??ng H???p L???!");
            return false;
        }
        $this->save();
        $profile->set("clan", false);
        $profile->set("rank", 0);
        $profile->save();
        if (($tmp = $this->getServer()->getPlayerExact($player)) !== null) {
            $this->getServer()->getPluginManager()->callEvent(new ClanChangeEvent($tmp, $clanName));
            $tmp->sendMessage("?????l??c B???n ???? B??? ??u???i Kh???i Clan!");
        }
        $this->clanAnnounce("?????l??e " . $player . "??c ???? R???i Clan.", $clanName);
        return true;
    }

    public function clanAnnounce(string $message, string $clanName)
    {
        $clan = $this->clans[$clanName];
        foreach ($clan["members"] as $name => $rank) {
            $member = $this->getServer()->getPlayerExact($name);
            if (!($member instanceof Player)) continue;
            //$member->sendMessage("?????l??c " . $message);
            $member->sendMessage($message);
        }
    }

    public function clanChat(Player $player, string $message)
    {
        $profile = $this->getProfile($player);
        $clan = $this->clans[$profile->get("clan")];

        /** @var PureChat $pureChat */
        $pureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
        if ($pureChat === null) return;
        $format = $this->getConfig()->get("clan-chat-format");
        $format .= $message;
        $mes = $pureChat->applyPCTags($format,$player,"",null);
        foreach ($clan["members"] as $name => $rank) {
            $member = $this->getServer()->getPlayerExact($name);
            if (!($member instanceof Player)) continue;
            $member->sendMessage($mes);
        }
    }

    public function setRank($player, int $rank)
    {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $profile = $this->getProfile($player);
        $clan =& $this->clans[$profile->get("clan")];
        $clan["members"][strtolower($player)] = $rank;
        $this->save();
        $profile->set("rank", $rank);
        $profile->save();
    }

    public function haveClan($player)
    {
        if ($player instanceof Player) $player = $player->getName();
        $profile = $this->getProfile($player);
        return $profile->get("clan") !== false;
    }

    public function getClanName($player)
    {
        $profile = $this->getProfile($player);
        return $profile->get("clan");
    }

    public function getClanTag($player)
    {
        /** @var string $style */
        $style = $this->getSetting(self::SETTING_CLAN_TAG, $this->getClan($this->getClanName($player))["level"]);
        $style = str_replace("%s", $this->getClanName($player), $style);
        return $style;
    }

    public function getClan(string $clanName)
    {
        return $this->clans[$clanName];
    }

    public function isClanFull(string $clanName)
    {
        $clan = $this->getClan($clanName);
        return count($clan["members"]) >= $this->getSetting(self::SETTING_MAX_PLAYERS, $clan["level"]);
    }

    public function getSetting(int $settingId, int $clanLevel)
    {
        switch ($settingId) {
            case self::SETTING_MAX_PLAYERS:
                $key = "max_players";
                break;
            case self::SETTING_REQUIRED_POINT:
                $key = "required_points";
                break;
            case self::SETTING_REQUIRED_MONEY:
                $key = "required_money";
                break;
            case self::SETTING_CLAN_TAG:
                $key = "clan_tag";
                break;
            default:
                return null;
        }
        return $this->settings[$key][$clanLevel - 1];
    }

    public function updateTopClan()
    {
        $topClan = array_keys($this->clans);
        for ($i = 0; $i <= count($topClan) - 2; $i++) {
            for ($j = $i + 1; $j <= count($topClan) - 1; $j++) {
                if ($this->clans[$topClan[$j]]["level"] > $this->clans[$topClan[$i]]["level"] ||
                    ($this->clans[$topClan[$j]]["level"] === $this->clans[$topClan[$i]]["level"] && $this->clans[$topClan[$j]]["point"] > $this->clans[$topClan[$i]]["point"])
                ) {
                    $t = $topClan[$j];
                    $topClan[$j] = $topClan[$i];
                    $topClan[$i] = $t;
                }
            }
        }
        $this->topClan = $topClan;
        $this->getServer()->broadcastMessage("?????l??c X???p H???ng Clan ???? ???????c C???p Nh???t!");
    }

    public function addPoint(int $point, string $clanName)
    {
        $clan =& $this->clans[$clanName];
        $clan["point"] += $point;
    }

    /**
     * @param string $needle
     * @param string $clanName
     * @param string $except
     * @return null|string
     */
    public function getPlayerInClan(string $needle, string $clanName, string $except = null)
    {
        $clan = $this->getClan($clanName);
        $found = null;
        $needle = strtolower($needle);
        $delta = PHP_INT_MAX;
        foreach ($clan["members"] as $name => $rank) {
            if ($except !== null) {
                if ($name === $except) continue;
            }
            if (stripos($name, $needle) === 0) {
                $curDelta = strlen($name) - strlen($needle);
                if ($curDelta < $delta) {
                    $found = $name;
                    $delta = $curDelta;
                }
                if ($curDelta === 0) {
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * @param string $needle
     * @param string $clanName
     * @param string $except
     * @return null|string
     */
    public function getPlayerInRequestList(string $needle, string $clanName, string $except = null)
    {
        $clan = $this->getClan($clanName);
        $found = null;
        $needle = strtolower($needle);
        $delta = PHP_INT_MAX;
        foreach ($clan["request"] as $name) {
            if ($except !== null) {
                if ($name === $except) continue;
            }
            if (stripos($name, $needle) === 0) {
                $curDelta = strlen($name) - strlen($needle);
                if ($curDelta < $delta) {
                    $found = $name;
                    $delta = $curDelta;
                }
                if ($curDelta === 0) {
                    break;
                }
            }
        }

        return $found;
    }

}
