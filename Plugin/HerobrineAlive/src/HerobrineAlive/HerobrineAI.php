<?php

namespace HerobrineAlive;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

class HerobrineAI extends PluginTask{
	/** @var Herobrine */
	private $hb;
	public function __construct(Main $plugin, Herobrine $hb, $tick){
		$this->hb = $hb;
		Server::getInstance()->getScheduler()->scheduleRepeatingTask($this, $tick * 20);
	}
	public function onRun($ticks){
		$this->hb->tick($ticks); // DECISION make the AI here or at Herobrine.php?
	}
	public function getHerobrine(){
		return $this->hb;
	}
}
