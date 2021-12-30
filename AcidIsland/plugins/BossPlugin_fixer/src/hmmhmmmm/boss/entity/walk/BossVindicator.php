<?php

namespace hmmhmmmm\boss\entity\walk;

use revivalpmmp\pureentities\entity\monster\walking\Vindicator;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

class BossVindicator extends Vindicator{
   public $health = 24;
   public $boss_data = "0xAAA001";
  
   public function getName(): string{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return "BossVindicator";
      }else{
         return parent::getName();
      }
   }

   public function attackEntity(Entity $player){
      if($this->attackDelay > 10 && $this->distanceSquared($player) < 2){
         $this->attackDelay = 0;
         if($player instanceof Player){
            $damage = $this->getDamage();
            if($this->getMobEquipment() !== null){
               $damage = $damage + $this->getMobEquipment()->getWeaponDamageToAdd();
            }
            $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage);
            $player->attack($ev);
            $this->checkTamedMobsAttack($player);
         }
      }
   }
   
   public function getMaxHealth(): int{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return BossData::getHealth($name);
      }else{
         return $this->health;
      }
   }
   
      public function entityBaseTick(int $tickDiff = 1): bool{
      $hasUpdate = parent::entityBaseTick($tickDiff);
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         $this->setNameTag($name."\n§f[§c".$this->getHealth()."§f/§c".$this->getMaxHealth()."§f]");
         $this->setNameTagAlwaysVisible(true);
         $this->setNameTagVisible(true);
         if($this->isOnFire()){
            $this->extinguish();
         }
         return $hasUpdate;
      }else{
         return parent::entityBaseTick($tickDiff);
      }
   }
   
   public function targetOption(Creature $creature, float $distance) : bool{
      if(!($creature instanceof SlapperHuman)){
         if($creature instanceof Player){
            return parent::targetOption($creature, $distance);
         }
      }
      return false;
   }
   
}