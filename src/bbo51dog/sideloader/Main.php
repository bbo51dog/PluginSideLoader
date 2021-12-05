<?php

namespace bbo51dog\sideloader;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginEnableOrder;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use function file_exists;
use function is_dir;

class Main extends PluginBase{

    public function onLoad(): void {
        $config = new Config($this->getDataFolder() . 'SideLoader.yml', Config::YAML, [
            'dirs' => [
                'directory/',
            ],
            'plugins' => [
                'ExamplePlugin',
            ],
        ]);
        $loader = new FolderPluginLoader();
        $this->getServer()->getPluginManager()->registerInterface($loader);
        /** @var string[] $dirs */
        $dirs = $config->get('dirs');
        /** @var string[] $plugins */
        $plugins = $config->get('plugins');
        foreach($dirs as $dir){
            if(!str_ends_with($dir, '/')){
                $dir .= '/';
            }
            foreach($plugins as $k => $plugin){
                $path = $dir . $plugin;
                if(file_exists($path) && is_dir($path)){
                    $loader->loadPlugin($path);
                    unset($plugins[$k]);
                    $this->getLogger()->info(TF::AQUA . "Plugin " . TF::GOLD . $plugin . TF::AQUA . " is successfully loaded from {$path}");
                }
            }
        }
        if(empty($plugins)){
            return;
        }
        foreach($plugins as $plugin){
            $this->getLogger()->warning("Plugin {$plugin} not found");
        }
    }

    public function onEnable(): void {
        $this->getServer()->enablePlugins(PluginEnableOrder::STARTUP());
    }
}