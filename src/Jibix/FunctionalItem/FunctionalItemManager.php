<?php
namespace Jibix\FunctionalItem\item;
use Jibix\FunctionalItem\EventListener;
use pocketmine\item\Item;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;


/**
 * Class FunctionalItemManager
 * @package Jibix\FunctionalItem\item
 * @author Jibix
 * @date 08.07.2023 - 21:56
 * @project FunctionalItem
 */
final class FunctionalItemManager{
    use SingletonTrait{
        setInstance as private;
        reset as private;
    }

    /** @var FunctionalItem[] */
    private array $items = [];

    public static function register(Plugin $plugin): void{
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $plugin);
    }

    public function registerFunctionalItem(FunctionalItem ...$items): void{
        foreach ($items as $item) {
            $this->items[$item::class] = $item;
        }
    }

    public function getItem(Item|string $item): ?FunctionalItem{
        return $this->items[is_string($item) ? $item : $item->getNamedTag()->getString(FunctionalItem::IDENTIFIER_TAG, "")] ?? null;
    }
}