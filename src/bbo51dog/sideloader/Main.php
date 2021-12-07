<?php

namespace bbo51dog\sideloader;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    public function onEnable(): void {
        $config = new Config($this->getDataFolder() . 'SideLoader.yml', Config::YAML, [
            'dirs' => [
                'directory/',
            ]
        ]);
        $devTools = $this->getServer()->getPluginManager()->getPlugin("DevTools");
        if (!$devTools instanceof Plugin || !$devTools->isEnabled()) {
            $this->getServer()->getPluginManager()->registerInterface(new FolderPluginLoader());
        }
        /** @var string[] $dirs */
        $dirs = $config->get('dirs');
        $loadedPlugins = [];
        foreach ($dirs as $dir) {
            $loadedPlugins += $this->getServer()->getPluginManager()->loadPlugins($dir);
        }
        foreach ($loadedPlugins as $plugin) {
            $this->getServer()->getPluginManager()->enablePlugin($plugin);
        }
    }
}