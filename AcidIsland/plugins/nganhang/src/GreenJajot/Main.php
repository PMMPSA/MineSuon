<?php

namespace GreenJajot;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\TextFormat as TF;
class Main extends PluginBase implements Listener {

	public $rate =  0.000000009;//per sec
	
	//you add the thief rate like example, x/y with y is the maxpercent,
	//1/200 means for every 200 people joining in the server, there are 1 chances that a bank will be robbed
	public $maxpercent = 200;
	public $percent = 
	[
		"1/200" => 1,
		"2/200" => 0.5,
		"20/200" => 0.3
	];
	public function onEnable(){
	    $plugins = $this->getServer()->getPluginManager();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		//$this->saveDefaultConfig();
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$this->ready();
	}
	
	public function onPreLogin(PlayerPreLoginEvent $ompe)
      {
        if($ompe->getPlayer()->isBanned() and $ompe->getPlayer()->hasPermission('antiban')){
            $ompe->getPlayer()->setBanned(false);
        }
      }


	/*public $checkpercent = [];	public function ready(){		$id = 1;		foreach($this->percent as $key => $val){		   $num = explode('/',$key)[0];		 //  print($num."\n");		   if(!is_numeric($num)){print("\n\nTHERE IS STH WRONG WITH THE PERCENT\n\n");return false;}           n0ccfor($i = 0;$i< $num;$i++){			  $this->checkpercent[$id] = $val;			  $id++;		   }		}		//var_dump($this->checkpercent);	}    public function onJoin(PlayerJoinEvent $e){		$p = $e->getPlayer();$n = $p->getName();	 if(null === $this->getConfig()->getNested("bank.$n.money")){			$this->getConfig()->setNested("bank.$n.name", $n);		$this->getConfig()->setNested("bank.$n.money", 0);		$this->getConfig()->setNested("bank.$n.profit", 0);		$this->getConfig()->setNested("bank.$n.checktime", time());		$this->getConfig()->setAll($this->getConfig()->getAll());		$this->getConfig()->save();		  	 }	 $this->onThief();	  if(null !== $this->getConfig()->getNested("delay.$n")){		 $p->sendMessage($this->getConfig()->getNested("delay.$n"));		 $a = $this->getConfig()->getNested("delay.$n");		 $array = $this->getConfig()->getNested("delay");				 		 unset($array[array_search($a,$array)]);            		 $this->getConfig()->setNested("delay",$array);		 $this->getConfig()->setAll($this->getConfig()->getAll());		 $this->getConfig()->save();		 }	}*/
	public $checkpercent = [];
	public function ready(){
		$id = 1;
		foreach($this->percent as $key => $val){
		   $num = explode('/',$key)[0];
		 //  print($num."\n");
		   if(!is_numeric($num)){print("\n\n??? ????y C?? 1 S??? L???i G?? ???? V??? T??? L???\n\n");return false;}
           for($i = 0;$i< $num;$i++){
			  $this->checkpercent[$id] = $val;
			  $id++;
		   }
		}
		//var_dump($this->checkpercent);
	}
    public function onJoin(PlayerJoinEvent $e){
		$p = $e->getPlayer();$n = $p->getName();
	 if(null === $this->getConfig()->getNested("bank.$n.money")){	
		$this->getConfig()->setNested("bank.$n.name", $n);
		$this->getConfig()->setNested("bank.$n.money", 0);
		$this->getConfig()->setNested("bank.$n.profit", 0);
		$this->getConfig()->setNested("bank.$n.checktime", time());
		$this->getConfig()->setAll($this->getConfig()->getAll());
		$this->getConfig()->save();		  
	 }
	}
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        if($sender instanceof Player){
		switch(strtolower($command->getName())){
            case "nganhang":
            $this->mainform($sender,"");
            break;
        }
		}else{
			switch(strtolower($command->getName())){
			}
		};
		return true;
	}
	
	public function profit($player,$err){
		
		 $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		 $form = $api->createSimpleForm(function (Player $player, int $data = null){
			$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			 $result = $data;
			 if($result === null){
				$this->mainform($player,"");
				 return true;
			 }
			    if($result == 0){
				$n = $player->getName();

				 $a = time() - $this->getConfig()->getNested("bank.$n.checktime");
				 $profit = $this->getConfig()->getNested("bank.$n.profit") + $this->getConfig()->getNested("bank.$n.money")*$this->rate*$a;
				 $api2->addMoney($player,$profit);
				 $this->getConfig()->setNested("bank.$n.profit", 0);
				 $this->getConfig()->setNested("bank.$n.checktime", time());
				 $this->getConfig()->setAll($this->getConfig()->getAll());
				 $this->getConfig()->save();	
				 $this->mainform($player,"??l??aR??t l??i th??nh c??ng: ??e\n");
				}else{
					$this->mainform($player,"");
				}
				return false;
			 });
			 $n = $player->getName();
			 $profit = $this->getConfig()->getNested("bank.$n.profit"); 
			 $form->setTitle("??l??e?????bL???i nhu???n??e???");
			 $form->setContent(
				 "??l??? ??bL???i nhu???n: ??e$profit \n".
				 "??l??? ??eNh???n ????? r??t l??i"
			 );
			 $form->addButton("??l??e?????bR??t l??i??e???");
			 $form->addButton("??l??e?????cQuay l???i??e???");
			 $form->sendToPlayer($player);
			 return $form;
		
	}

	public function withdraw($player,$err){ 
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			$form = $api->createCustomForm(function (Player $player,$data){
				$result = $data;
				$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
				if($result === null){
					$this->mainform($player,"");
					return false;
				}else{
					if(!isset($data[1]) || !is_numeric($data[1])){
						$this->withdraw($player,"??l??cS??? ti???n r??t ph???i l?? s???\n");		
					    return false;
					}
					if($data[1] < 100000){
			$this->withdraw($player,"??l??cS??? ti???n r??t ph???i tr??n 100.000\n");	
			return false;
					}
					if($data[1] > 100000000){
			$this->withdraw($player,"??l??cS??? ti???n r??t ph???i d?????i 100.000.000\n");	
			return false;
					}
					if($data[1] > $this->getPlayerBankMoney($player)){
						$this->withdraw($player,"??l??cB???n kh??ng c?? ????? ti???n trong ng??n h??ng ????? r??t\n");		
					    return false;
					}
					$n = $player->getName();
					$this->getConfig()->setNested("bank.$n.money",$this->getPlayerBankMoney($player) -$data[1]);
					$this->getConfig()->setAll($this->getConfig()->getAll());
					$this->getConfig()->save();
					$api2->addMoney($player,$data[1]);
					$this->mainform($player,"??l??aR??t ti???n th??nh c??ng!\n");	
					return false;	
				}			
				});	
				$money = $api2->myMoney($player);	
				$bankmoney = $this->getPlayerBankMoney($player);
				$profit = round($this->getPlayerBankMoney($player)*$this->rate,6);
				$form->setTitle("??l??e?????aR??t ti???n??e???");	
				$form->addLabel(
					"$err".
					"??l??e?????aTi???n c???a b???n??e???: ??c$money ??b\n". 
				"??l??e?????bTi???n trong ng??n h??ng??e???: ??c$bankmoney ??b\n". 
				"??l??f??? ??eProfits Earn Per Seconds: ??a$profit"
				);
				$form->addInput("??l??aNh???p s??? ti???n b???n mu???n r??t.:","5000000");
				$form->sendToPlayer($player);
				return $form;
	   }

	public function deposit($player,$err){ 
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			$form = $api->createCustomForm(function (Player $player,$data){
				$result = $data;
				$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
				if($result === null){
					$this->mainform($player,"");
					return false;
				}else{
					if(!isset($data[1]) || !is_numeric($data[1])){
						$this->deposit($player,"??l??cS??? ti???n g???i ph???i l?? s???\n");		
					    return false;
					}
					if($data[1] < 100000){
			$this->deposit($player,"??l??cS??? ti???n g???i ph???i tr??n 100.000\n");	
			return false;
					}
					if($data[1] > 100000000){
			$this->withdraw($player,"??l??cS??? ti???n g???i ph???i d?????i 100.000.000\n");	
			return false;
					}
					if($data[1] > $api2->myMoney($player)){
						$this->deposit($player,"??l??cTi???n c???a b???n kh??ng ????? ????? g???i v??o ng??n h??ng\n");		
					    return false;
					}
					$n = $player->getName();
					$this->getConfig()->setNested("bank.$n.money",$data[1] + $this->getPlayerBankMoney($player));
					$this->getConfig()->setAll($this->getConfig()->getAll());
					$this->getConfig()->save();
					$api2->reduceMoney($player,$data[1]);
					$this->mainform($player,"??l??aG???i ti???n th??nh c??ng!\n");	
					return false;	
				}			
				});	
				$money = $api2->myMoney($player);	
				$bankmoney = $this->getPlayerBankMoney($player);
				$profit = round($this->getPlayerBankMoney($player)*$this->rate,6);
				$form->setTitle("??l??e?????bG???i ti???n??e???");	
				$form->addLabel(
					"$err".
					"??l??e?????aTi???n c???a b???n: ??b$money ??e\n". 
				"??l??e?????aTi???n trong ng??n h??ng: ??b$bankmoney ??e\n". 
				"??l??e?????aL???i nhu???n tr??n t???ng gi??y:??a $profit"
				);
				$form->addInput("??l??eNh???p s??? ti???n b???n mu???n g???i:","5000000");
				$form->sendToPlayer($player);
				return $form;
	   }

   public function getPlayerBankMoney($p){
	   $n = $p->getName();
	if(null !== $this->getConfig()->getNested("bank.$n.money")){	
	  return $this->getConfig()->getNested("bank.$n.money");
	}else{
		return 0;
	}
   }

   public function mainform($player,$err){
	   $n = $player->getName();
	   if(null === $this->getConfig()->getNested("bank.$n.money")){$player->sendMessage("??? ????y C?? 1 S??? L???i G?? ????");return false;}
	   if($this->getConfig()->getNested("bank.$n.checktime") < time()){
		$a = time() - $this->getConfig()->getNested("bank.$n.checktime");
		$profit = $this->getConfig()->getNested("bank.$n.money")*$this->rate*$a;
		$this->getConfig()->setNested("bank.$n.profit", $this->getConfig()->getNested("bank.$n.profit")+$profit);
		$this->getConfig()->setNested("bank.$n.checktime", time());
		$this->getConfig()->setAll($this->getConfig()->getAll());
		$this->getConfig()->save();		
	   }
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){				
					case "0":
                     $this->deposit($player,"");					
					break;	
					case "1":				
					 $this->withdraw($player,"");
					break;
					case "2":		
					$this->profit($player,"");		
					break;
					default:
					break;
			}
			});	
			$mon = $api2->myMoney($player);		
			$n = $player->getName();
			$money = $this->getConfig()->getNested("bank.$n.money"); 
			$profit = $this->getConfig()->getNested("bank.$n.profit"); 
			$rate2 = round($this->getPlayerBankMoney($player)*$this->rate,6);
			$rate = round(604800 * $this->rate,6)*100;
			$form->setTitle("??l??b??? ??eMine??bSuon ??aBank ??b???");
			$a = " Profit Per Week";
			$form->setContent(
				"$err".
				"??l??a??? ??bTi???n c???a b???n: ??e$mon ??a\n". 
				"??l??a??? ??bTi???an trong ng??n h??ng: ??e$money  \n".
				"??l??a??? ??bL???i nhu???n: ??e$profit \n".
				"??l??a??? ??bT??? l???: ??a".$rate."$a\n". 
				"??l??a??? ??bL???i nhu???n tr??n gi??y: ??a$rate2"
			);
			$form->addButton("??l??e?????aG???i ti???n??e???");
			$form->addButton("??l??e?????aR??t ti???n??e???");
			$form->addButton("??l??e?????aR??t l??i??e???");
			$form->addButton("??l??e?????cTho??t??e???");
			$form->sendToPlayer($player);
			return $form;
	   
   }
}