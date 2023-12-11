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

    public const IDENTIFIER_TAG = "functional_id";

    final public static function get(Player $player): Item{
        if (static::class === self::class) throw new Exception("Call this function in a sub-class and not directly");
        return static::applyIdTag(static::getItem($player));
    }

    abstract protected static function getItem(Player $player): Item;

    //You shouldn't do it, but in case you're using a custom function to get the item, just call this function.
    protected static function applyIdTag(Item $item): Item{
        $item->getNamedTag()->setString(self::IDENTIFIER_TAG, static::class);
        return $item;
    }

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