<?php
namespace Apartkktrain\anti\Task;

use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use function MongoDB\Driver\Monitoring\removeSubscriber;

class chatTask extends Task
{
	private $name;
	private $second;
	private $chat;

	public function __construct($name,Config $chat,int $second)
	{
         $this->name = $name;
         $this->chat = $chat;
         $this->second = $second;
	}
   public function onRun(int $tick)
   {
   	   $this->second;
   	   $this->second--;
   	   if ($this->second === 0)
	   {
		   $this->chat->remove($this->name);
		   $this->chat->save();
		   $this->chat->reload();
	   }
	   $this->getHandler()->isCancelled();
   }
}