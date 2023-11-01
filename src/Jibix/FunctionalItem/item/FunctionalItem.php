<?php
namespace Jibix\FunctionalItem\item;
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

    //I know it's a bad/annoying way to do return getInternalItem(stuff) every time, but i want the possibility to pass custom arguments to the getItem function, so this is the best way i can think of so far
    abstract public static function getItem(?Player $player = null): Item;

    protected static function getInternalItem(Item $item): Item{
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