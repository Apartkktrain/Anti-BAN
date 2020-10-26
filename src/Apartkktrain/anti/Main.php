<?php
namespace Apartkktrain\anti;

use Apartkktrain\anti\Task\chatTask;
use MongoDB\Driver\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
class Main extends PluginBase implements Listener
{
	private $chat;
      public function onEnable()
	  {
		  $this->getServer()->getPluginManager()->registerEvents($this, $this);
		  $this->chat = new Config($this->getDataFolder() . "chat.yml",Config::YAML);
	  }

	/**
	 * @priority MONITOR
	 * @ignoreCancelled true
	 * @param BlockBreakEvent $event
	 */
	  public function BlockBreakEvent(BlockBreakEvent $event)
	  {
	  	$player = $event->getPlayer();
	  	$blocks = $event->getDrops();
		  foreach ($blocks as $key => $item)
		  {
			  if($player->getInventory()->canAddItem($item))
			  {
				  $event->setDrops([]);
				  $player->getInventory()->addItem($item);
			  }else{
			  	$event->setCancelled();
			  	$player->sendTip("§eインベントリが満タンです!");
			  }
	  	}

	  }

	  public function onchat(PlayerChatEvent $event)
	  {
	  	$name = $event->getPlayer()->getName();
	  	if (!$this->chat->exists($name))
		{
			$this->chat->set($name,"送信済み");
			$this->chat->save();
			$this->chat->reload();
			$this->getScheduler()->scheduleRepeatingTask(new chatTask($name,$this->chat,3),20);
		}else{
			$event->setCancelled();
			$event->getPlayer()->sendMessage("§6[Anti-BAN]§a連投はお控えください。");
		}
	  }
	public function onExplode(EntityExplodeEvent $event)
	{
		$event->setCancelled();
	}
	public function guardFarmland(BlockFormEvent $event)
	{
		$event->setCancelled();
	}
	public function onFly(PlayerToggleFlightEvent $event)
	{
		$player = $event->getPlayer();
		if (!$player->isOp())
		{
			$player->isBanned();
		}
	}

	  public function onDisable()
	  {
		  $this->chat->setall([]);
		  $this->chat->save();
		  $this->chat->reload();
	  }

}