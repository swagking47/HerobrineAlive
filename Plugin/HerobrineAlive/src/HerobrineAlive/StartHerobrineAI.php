<?php

namespace HerobrineAlive;

use pocketmine\scheduler\PluginTask;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\Player;

class StartHerobrineAI extends PluginTask implements Main{
    public $p;
	protected $distance = 5, $settings; // settings
	protected $eid;
    public function onRun(){
        $xcord = rand(1, 256);
        $ycord = rand(1, 256);
        $zcord = rand(1, 256);
        $xd = /*cos(deg2rad(floatval($this->p->entity->yaw)))*/1*$this->distance;
	$zd = /*sin(deg2rad(floatval($this->p->entity->pitch)))*/0*$this->distance;
	$pk = new AddPlayerPacket;
	$pk->clientId = 0;
	$this->username = "Herobrine"
	$pk->username = $this->username;
	$pk->eid = $this->eid;
	$pk->x = $this->p->entity->x+$xd;
	$pk->y = $this->p->entity->y;
	$pk->z = $this->p->entity->z+$zd;
	$pk->yaw = 180+$this->p->yaw;
	$pk->pitch = 180+$this->p->pitch;
	$pk->unknown1 = 0;
	$pk->unknown2 = 0;
	$pk->metadata = array(
		0 => array("type" => 0, "value" => 0b00),
		1 => array("type" => 1, "value" => 300),
		16 => array("type" => 0, "value" => 0),
		17 => array("type" => 6, "value" => array(0, 0, 0))
	);
	$this->p->dataPacket($pk);
    }
}
