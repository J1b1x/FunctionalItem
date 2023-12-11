<?php
namespace Jibix\FunctionalItem\item;
use Exception;
use pocketmine\entity\Entity;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;


/**
 * Class FunctionalItem
 * @package Jibix\FunctionalItem\item
 * @author Jibix
 * @date 08.07.2023 - 21:56
 * @project FunctionalItem
 */
abstract class FunctionalItem{

    public const IDENTIFIER_TAG = "id";

    public static function get(Player $player): Item{
        if (static::class === self::class) throw new Exception("Call this function in a sub-class and not directly");
        $item = static::getItem($player);
        $item->getNamedTag()->setString(self::IDENTIFIER_TAG, static::class);
        return $item;
    }

    abstract protected static function getItem(Player $player): Item;

    public static function equals(Item $item): bool{
        return $item->getNamedTag()->getString(self::IDENTIFIER_TAG, "") === static::class;
    }

    public static function remove(Inventory $inventory): void{
        foreach ($inventory->getContents() as $slot => $item) {
            if ($item->getNamedTag()->getString(self::IDENTIFIER_TAG, "") === static::class) {
                $inventory->setItem($slot, VanillaItems::AIR());
            }
        }
    }

    public function getCooldownTicks(Player $player, Item $item): int{
        return $item->getCooldownTicks();
    }

    public function onUse(Player $player, ?Vector3 $useVector = null): bool{
        return true;
    }

    public function onDrop(Player $player, Item $item): bool{
        return true;
    }

    public function onHeld(Player $player, int $slot): bool{
        return true;
    }

    public function onInvClick(Player $player): bool{
        return true;
    }

    public function onHitEntity(Player $player, Entity $entity): bool{
        return true;
    }

    public function onInteractEntity(Player $player, Entity $entity, Vector3 $clickPos): bool{
        return true;
    }
}