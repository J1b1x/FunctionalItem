# FunctionalItem

![php](https://img.shields.io/badge/php-8.1-informational)
![api](https://img.shields.io/badge/pocketmine-5.0-informational)

FunctionalItem is a PocketMine-MP library to give items functionalities using actual classes instead of Events.

## Categories
+ [Library registration](#library-registration)
+ [Item registration](#item-registration)
+ [Functional Item](#functional-item)
    - [Item Flags](#item-flags)
      - [Flags](#flags)
      - [Apply a flag](#apply-a-flag-to-a-functionalitem)
    - [Item functions](#item-functions)
    - [Example Item](#how-to-make-a-functionalitem)
    - [Give a Functional item](#how-to-give-a-functionalitem)

## Library registration
### Just do this in the onEnable function of your plugin
```php
\Jibix\FuctionalItem\FunctionalItemManager::register($this);
```

## Item registration
### In order to make a functional item actually work, you first need to register it, just like this
```php
\Jibix\FuctionalItem\FunctionalItemManager::getInstance()->registerFunctionalItem(new MyFunctionalItem());
```

## Functional Item
### Item Flags
#### Flags
- NonPlaceableFlag
#### Apply a flag to a FunctionalItem
```php
//Basically do implements YourFlag
class ExampleItem extends \Jibix\FuctionalItem\item\FunctionalItem implements ItemFlag{
```
### Item Functions
```php
    //Checks if $item equals the functional item
    //Example: ExampleItem::equals($player->getInventory()->getItemInHand())
    public static function equals(Item $item): bool;

    //Removes the functional item from the player's inventory
    public static function remove(Inventory $inventory): void;


    //Returns the cooldown time (in ticks) until the item can be used again
    public function getCooldownTicks(Player $player, Item $item): int;

    //Called when the player right-clicks this item
    //If it returns false, the interaction will be canceled
    public function onUse(Player $player, ?Vector3 $useVector = null): bool;

    //Called when the player drops this item
    //If it returns false, the drop will be canceled
    public function onDrop(Player $player): bool;

    //Called when the player helds this item
    //If it returns false, the slot switch will be canceled
    public function onHeld(Player $player, int $slot): bool;

    //Called when the player clicks this item in their inventory
    //If it returns false, the inventory transaction will be canceled
    public function onInvClick(Player $player): bool;

    //Called when the player hits an entity with this item
    //If it returns false, the damage event will be canceled
    public function onHitEntity(Player $player, Entity $entity): bool;

    //Called when the player right-clicks an entity with this item
    //If it returns false, the interaction will be canceled
    public function onInteractEntity(Player $player, Entity $entity, Vector3 $clickPos): bool;
```
### How to make a FunctionalItem
```php
class ExampleItem extends \Jibix\FuctionalItem\item\FunctionalItem{
    
    private const USE_COOLDOWN = 5 * 20; //5 seconds
    
    public static function getItem(?Player $player = null, string $customName = "§bExample"): Item{
        return self::getInternalItem(VanillaItems::STICK()->setCustomName($customName));
    }
    
    public function getCooldownTicks(Player $player, Item $item): int{
        return self::USE_COOLDOWN;
    }
    
    public function onDrop(Player $player): bool{
        return false; //Can't be dropped
    }
    
    public function onUse(Player $player, ?Vector3 $useVector = null): bool{
        $player->sendMessage("You just used the FunctionalItem example-stick!");
        return true;
    }
}
```
### How to give a FunctionalItem
```php
$player->getInventory()->addItem(MyFunctionalItem::getItem($player, ...$customArgs));
```
