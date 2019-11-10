<?php

declare(strict_types=1);

namespace Zedstar16\Logger;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\Internet;

class Main extends PluginBase implements Listener
{

    public $url;

    public function onEnable(): void
    {
        $this->initialiseFiles();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function initialiseFiles(): void
    {
        $defaulturl = "https://discordapp.com/api/webhooks/643170268029779968/pfx1fKj1zUR4xX7D9CPlUcBvDkkZ3uCGaOWPQU6nTVLmC4Kdhi8weJqGDMO7R_b5w_xm";
        $file = $this->getDataFolder() . "config.yml";
        if (!file_exists($file)) {
            yaml_emit_file($file, ["url" => $defaulturl]);
        }
        $this->url = yaml_parse_file($file)["url"] ?? $defaulturl;
    }


    /**
     * @param PlayerChatEvent $event
     * @priority HIGHEST
     * @ignoreCancelled False
     */
    public function onChat(PlayerChatEvent $event)
    {
        $name = $event->getPlayer()->getName();
        $msg = $event->getMessage();
        $data = $event->isCancelled() ? "```MutedUser: $name > $msg```" : "⭢ $name > $msg";
        $this->getServer()->getAsyncPool()->submitTask(new AsyncDispatch($this->url, $data));
    }

}
