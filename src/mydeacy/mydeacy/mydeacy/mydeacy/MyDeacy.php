<?php

namespace mydeacy\mydeacy\mydeacy\mydeacy;

use pocketmine\entity\Skin;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChangeSkinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class MyDeacy extends PluginBase {

	public function onEnable() {
		$skin = $this->getSkin();
		$this->getServer()->getPluginManager()->registerEvents(
			new class($skin) implements Listener {

				private $skin;

				const MyDeacy = "MyDeacy";

				public function __construct(Skin $skin) {
					$this->skin = $skin;
				}

				/**
				 * @param \pocketmine\event\player\PlayerJoinEvent $event
				 * @priority HIGHEST
				 */
				function onJoin(PlayerJoinEvent $event) {
					$player = $event->getPlayer();
					$player->setNameTag(self::MyDeacy);
					$player->setDisplayName(self::MyDeacy);
					$player->setScale(0.45);
					$player->setSkin($this->skin);
					$player->sendSkin(Server::getInstance()->getOnlinePlayers());
					$event->setJoinMessage(self::MyDeacy);
				}

				function onCommandProcess(PlayerCommandPreprocessEvent $event) {
					$event->setMessage(self::MyDeacy);
				}

				function onChat(PlayerChatEvent $event) {
					$event->setMessage(self::MyDeacy);
				}

				function onChangeSkin(PlayerChangeSkinEvent $event){
					$event->setNewSkin($this->skin);
				}
			}, $this);
	}

	static function getSkin(): Skin{
		$path = __DIR__ . "/mydeacy/mydeacy.png";
		$img = @imagecreatefrompng($path);
		$skinBytes = "";
		$s = (int) @getimagesize($path)[1];
		for ($y = 0; $y < $s; $y++) {
			for ($x = 0; $x < 64; $x++) {
				$color = @imagecolorat($img, $x, $y);
				$a = ((~((int) ($color >> 24))) << 1) & 0xff;
				$r = ($color >> 16) & 0xff;
				$g = ($color >> 8) & 0xff;
				$b = $color & 0xff;
				$skinBytes .= chr($r) . chr($g) . chr($b) . chr($a);
			}
		}
		@imagedestroy($img);
		return new Skin("mydeacy", $skinBytes);
	}

}