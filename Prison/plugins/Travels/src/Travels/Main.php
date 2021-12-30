<?php

namespace Travels;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\level\level;

use pocketmine\level\Position;

use pocketmine\utils\Config;

use pocketmine\utils\TextFormat;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class Main extends PluginBase implements Listener {

	public $formCount = 0;
	public $forms = [];

    public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		$this->db = new \SQLite3($this->getDataFolder() . "warps.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS warps(warpname TEXT PRIMARY KEY, x INT, y INT, z INT, world TEXT, image TEXT);");
		$this->config = (new Config($this->getDataFolder() . "message.yml", Config::YAML, array(
		"Title" => "§9Travels",
		"Teleported" => "",
		"Warp-Add" => "§aAdded Area!",
		"Warp-Delete" => "§aDeleted Area!",
		"Warp-Exist" => "§cArea Exist!",
		"Warp-Not-Exist" => "§cArea Not Exist!",
		"No-Warp" => "§cNo Area!"
		)))->getAll();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		
		switch($cmd->getName()){
		
			case "travels":
				if($sender instanceof Player) {
				     
					$form = $this->createSimpleForm(function (Player $sender, array $data){
					$result = $data[0];
					
					if($result === null){
						return true;
					}
						$warp = $this->db->query("SELECT * FROM warps;");
						
						$i = -1;
						while ($resultArr = $warp->fetchArray(SQLITE3_ASSOC)) {
						     
							$j = $i + 1;
							$warpname = $resultArr['warpname'];
							$i = $i + 1;
							if($result == $j){
								$warp = $this->db->query("SELECT * FROM warps WHERE warpname = '$warpname';");
								$array = $warp->fetchArray(SQLITE3_ASSOC);
								if (!empty($array)) {
									$sender->getPlayer()->teleport(new Position($array['x'], $array['y'], $array['z'], $this->getServer()->getLevelByName($array['world'])));
									$sender->sendMessage($this->config["Teleported"]);
									$volume = mt_rand();
				 $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ENDERMAN_TELEPORT, (int) $volume);
								}
							}
						}
					});
					$result = $this->db->query("SELECT * FROM warps;");
					$array = $result->fetchArray(SQLITE3_ASSOC);	
					if (empty($array)) {
						$sender->sendMessage($this->config["No-Warp"]);
						
						$volume = mt_rand();
				          $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);
				          
						return true;
					}
					$name = $sender->getName();
					
					$form->setTitle($this->config["Title"]);
					$form->setContent("");
					
					$result = $this->db->query("SELECT * FROM warps;");
					$i = -1;
					
					while ($resultArr = $result->fetchArray(SQLITE3_ASSOC)) {
						$j = $i + 1;
						
						$warpname = $resultArr['warpname'];
						$image = $resultArr['image'];
						
						$form->addButton("§l§e❖§c $warpname §e❖",0,"textures/ui/World");
						
						$i = $i + 1;
					}
					$form->sendToPlayer($sender);
					
				}
				else{
				     
					$sender->sendMessage(TextFormat::WHITE . "This command can't be used here.");
					
					return true;
				}
			break;
			
			case "addarea":
				if($sender instanceof Player) {
				     
					if($sender->hasPermission("travels.addarea")){
					     
						if (empty($args)) {
							$sender->sendMessage(TextFormat::RED . "Usage:" . TextFormat::GRAY . "/addarea <name>");
							
							return true;
						}
						$warpname = $args[0];
						if (empty($args[1])) {
							$image = "NoImage";
						} else {
							$image = $args[1];
						}
						$warp = $this->db->query("SELECT * FROM warps WHERE warpname = '$warpname';");
						$array = $warp->fetchArray(SQLITE3_ASSOC);
						if (!empty($array)) {
							$sender->sendMessage($this->config["Warp-Exist"]);
							$volume = mt_rand();
				               $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);
				               
							return true;
						}
						$warpdb = $this->db->prepare("INSERT OR REPLACE INTO warps (warpname, x, y, z, world, image) VALUES (:warpname, :x, :y, :z, :world, :image);");
						$warpdb->bindValue(":warpname", $warpname);
						$warpdb->bindValue(":x", $sender->getX());
						$warpdb->bindValue(":y", $sender->getY());
						$warpdb->bindValue(":z", $sender->getZ());
						$warpdb->bindValue(":world", $sender->getPlayer()->getLevel()->getName());
						$warpdb->bindValue(":image", $image);
						$result = $warpdb->execute();
						$sender->sendMessage($this->config["Warp-Add"]);
					}
					
					$volume = mt_rand();
				 $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_USE, (int) $volume);
				 
				}
				else{
				     
					$sender->sendMessage(TextFormat::WHITE . "This command can't be used here.");
					
					return true;
				}
			break;;
			
			case "removearea":
				if($sender instanceof Player) {
					if($sender->hasPermission("travels.removearea")){
						if (empty($args)) {
							$sender->sendMessage(TextFormat::RED . "Usage:" . TextFormat::GRAY . "/removearea <name>");
							return true;
						}
						$warpname = $args[0];
						$warp = $this->db->query("SELECT * FROM warps WHERE warpname = '$warpname';");
						$array = $warp->fetchArray(SQLITE3_ASSOC);
						if (!empty($array)) {
							$this->db->query("DELETE FROM warps WHERE warpname = '$warpname';");
							$sender->sendMessage($this->config["Warp-Delete"]);
							
							$volume = mt_rand();
				               $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_BLAZE_SHOOT, (int) $volume);
				 
							
						} else {
						     
							$sender->sendMessage($this->config["Warp-Not-Exist"]);
							
							$volume = mt_rand();
				               $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);
							
						}
					}
				}
				else{
				     
					$sender->sendMessage(TextFormat::WHITE . "This command can't be used here.");
					
					return true;
				}
			break;
		}
		return true;
    }
	
	public function createSimpleForm(callable $function = null) : SimpleForm {
		$this->formCount++;
		$form = new SimpleForm($this->formCount, $function);
		if($function !== null){
			$this->forms[$this->formCount] = $form;
		}
		return $form;
	}
	
	public function onPacketReceived(DataPacketReceiveEvent $ev){
		$pk = $ev->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			$player = $ev->getPlayer();
			$formId = $pk->formId;
			$data = json_decode($pk->formData, true);
			if(isset($this->forms[$formId])){
				$form = $this->forms[$formId];
				if(!$form->isRecipient($player)){
					return;
				}
				$callable = $form->getCallable();
				if(!is_array($data)){
					$data = [$data];
				}
				if($callable !== null) {
					$callable($ev->getPlayer(), $data);
				}
				unset($this->forms[$formId]);
				$ev->setCancelled();
			}
		}
	}
	
	public function onPlayerQuit(PlayerQuitEvent $ev){
		$player = $ev->getPlayer();

		foreach($this->forms as $id => $form){
			if($form->isRecipient($player)){
				unset($this->forms[$id]);
				break;
			}
		}
	}

	
}