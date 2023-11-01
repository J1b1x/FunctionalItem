<?php
namespace Jibix\FunctionalItem;
use Jibix\FunctionalItem\item\flag\NonPlaceableFlag;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerEntityInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\Player;


/**
 * Class EventListener
 * @package Jibix\FunctionalItem
 * @author Jibix
 * @date 08.07.2023 - 21:55
 * @project FunctionalItem
 */
class EventListener implements Listener{

    /**
     * Function onPlace
     * @param BlockPlaceEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onPlace(BlockPlaceEvent $event): void{
        if (FunctionalItemManager::getInstance()->getItem($event->getItem()) instanceof NonPlaceableFlag) $event->cancel();
    }

    /**
     * Function onDrop
     * @param PlayerDropItemEvent $event
     * @return void
     * @handleCancelled
     * @priority LOWEST
     */
    public function onDrop(PlayerDropItemEvent $event): void{
        if (($item = FunctionalItemManager::getInstance()->getItem($event->getItem())) !== null && !$item?->onDrop($event->getPlayer(), $event->getItem())) $event->cancel();
    }

    /**
     * Function onItemHeld
     * @param PlayerItemHeldEvent $event
     * @return void
     * @handleCancelled
     * @priority LOWEST
     */
    public function onItemHeld(PlayerItemHeldEvent $event): void{
        if (($item = FunctionalItemManager::getInstance()->getItem($event->getItem())) !== null && !$item?->onHeld($event->getPlayer(), $event->getSlot())) $event->cancel();
    }

    /**
     * Function onUseItem
     * @param PlayerItemUseEvent $event
     * @return void
     * @handleCancelled
     * @priority LOW
     */
    public function onUseItem(PlayerItemUseEvent $event): void{
        $player = $event->getPlayer();
        $item = $event->getItem();
        $functionalItem = FunctionalItemManager::getInstance()->getItem($item);
        if ($functionalItem === null) return;
        if ($player->hasItemCooldown($item) || !$functionalItem->onUse($player, $event->getDirectionVector())) {
            $event->cancel();
        } else {
            $player->resetItemCooldown($item, $functionalItem->getCooldownTicks($player, $item));
        }
    }

    /**
     * Function onInvTransaction
     * @param InventoryTransactionEvent $event
     * @return void
     * @handleCancelled
     * @priority LOWEST
     */
    public function onInvTransaction(InventoryTransactionEvent $event): void{
        $player = $event->getTransaction()->getSource();
        foreach ($event->getTransaction()->getActions() as $action) {
            $source = $action->getSourceItem();
            $target = $action->getTargetItem();
            if (
                (($item = FunctionalItemManager::getInstance()->getItem($source)) !== null && !$item?->onInvClick($player)) ||
                (($item = FunctionalItemManager::getInstance()->getItem($target)) !== null && !$item?->onInvClick($player))
            ) $event->cancel();
        }
    }

    /**
     * Function onEntityInteract
     * @param PlayerEntityInteractEvent $event
     * @return void
     * @handleCancelled
     * @priority LOWEST
     */
    public function onEntityInteract(PlayerEntityInteractEvent $event): void{
        $player = $event->getPlayer();
        if (
            ($item = FunctionalItemManager::getInstance()->getItem($player->getInventory()->getItemInHand())) !== null &&
            !$item?->onInteractEntity($player, $event->getEntity(), $event->getClickPosition())
        ) $event->cancel();
    }

    /**
     * Function onDamageByEntity
     * @param EntityDamageByEntityEvent $event
     * @return void
     * @handleCancelled
     * @priority LOWEST
     */
    public function onDamageByEntity(EntityDamageByEntityEvent $event): void{
        $damager = $event->getDamager();
        if (
            $damager instanceof Player &&
            ($item = FunctionalItemManager::getInstance()->getItem($damager->getInventory()->getItemInHand())) !== null && !$item->onHitEntity($damager, $event->getEntity())
        ) $event->cancel();
    }
}