<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyCrates\crates;

use DaPigGuy\PiggyCrates\PiggyCrates;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;

class Crate
{
    /** @var PiggyCrates */
    private $plugin;

    /** @var string */
    public $name;
    /** @var string */
    public $floatingText;
    /** @var CrateItem[] */
    public $drops;
    /** @var int */
    public $dropCount;
    /** @var string[] */
    public $commands;

    /**
     * @param CrateItem[] $drops
     * @param string[] $commands
     */
    public function __construct(PiggyCrates $plugin, string $name, string $floatingText, array $drops, int $dropCount, array $commands)
    {
        $this->plugin = $plugin;
        $this->name = $name;
        $this->floatingText = $floatingText;
        $this->drops = $drops;
        $this->dropCount = $dropCount;
        $this->commands = $commands;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFloatingText(): string
    {
        return $this->floatingText;
    }

    /**
     * @return CrateItem[]
     */
    public function getDrops(): array
    {
        return $this->drops;
    }

    /**
     * @return CrateItem[]
     */
    public function getDrop(int $amount): array
    {
        $dropTable = [];
        foreach ($this->drops as $drop) {
            for ($i = 0; $i < $drop->getChance(); $i++) {
                $dropTable[] = $drop;
            }
        }

        $keys = array_rand($dropTable, $amount);
        if (!is_array($keys)) $keys = [$keys];
        return array_map(function ($key) use ($dropTable) {
            return $dropTable[$key];
        }, $keys);
    }

    public function getDropCount(): int
    {
        return $this->dropCount;
    }

    /**
     * @return string[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function giveKey(Player $player, int $amount): void
    {
        $key = Item::get((int)$this->plugin->getConfig()->getNested("keys.id"), (int)$this->plugin->getConfig()->getNested("keys.meta"), $amount);
        $key->setCustomName(ucfirst(str_replace("{CRATE}", $this->getName(), $this->plugin->getConfig()->getNested("keys.name"))));
        $key->setLore([str_replace("{CRATE}", $this->getName(), $this->plugin->getConfig()->getNested("keys.lore"))]);
        $key->setNamedTagEntry(new ListTag(Item::TAG_ENCH));
        $key->setNamedTagEntry(new StringTag("KeyType", $this->getName()));
        $player->getInventory()->addItem($key);
    }

    public function isValidKey(Item $item): bool
    {
        return $item->getId() === (int)$this->plugin->getConfig()->getNested("keys.id") &&
            $item->getDamage() === (int)$this->plugin->getConfig()->getNested("keys.meta") &&
            ($tag = $item->getNamedTagEntry("KeyType")) !== null &&
            $tag->getValue() === $this->getName();
    }
}