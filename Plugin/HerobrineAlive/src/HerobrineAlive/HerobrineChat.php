<?php

namespace HerobrineAlive;

use pocketmine\scheduler\PluginTask;

class HerobrineChat extends PluginTask{
	private $messages = [];
	public function __construct(Main $plugin){
		parent::__construct($plugin);
		$this->messages[] = "Where is your god now?";
		$this->messages[] = "You can not defeat me!";
		$this->messages[] = "I am your god now!";
		$this->messages[] = "Your world is mine!";
		$this->messages[] = "You can't escape me!";
		$this->messages[] = "I am your worst nightmare!";
		$this->messages[] = "Not even can Notch save you!"; // inversion xD
		$this->messages[] = "I'm here!";
		$this->messages[] = "No one can save you now!";
		$this->messages[] = "You're next!";
	        $this->messages[] = "Are you ready for death?";
          }
	public function onRun($ticks){
		/** @var Main $plugin */
		$plugin = $this->getOwner();
		$herobrine = $plugin->getHerobrine();
		if($herobrine instanceof Herobrine){
			$herobrine->say(array_rand($this->messages));
		}
	}
}
