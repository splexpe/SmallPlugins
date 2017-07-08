<?php

namespace ServerTransfer;

use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {

    /** @var EventListener */
    public $listener;

    public function onEnable() {
        $this->listener = new EventListener($this);
        $this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
        if(!file_exists($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_file($this->getDataFolder()."/config.yml")) {
            $this->saveResource("/config.yml");
        }
    }

    public function transfer(Player $player, $ip, $port = 19132) {
        $pk = new TransferPacket();
        $pk->address = $ip;
        $pk->port = $port;
        $player->dataPacket($pk);
    }

    public function onDisable() {
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $this->getLogger()->debug($player->getName()." is transfered.");
            $this->transfer($player, $this->getConfig()->get("disable-server-ip"), intval($this->getConfig()->get("disable-server-port")));
        }
    }
}
