<?php

namespace App\Factory;

class SettingsFactory
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $this->sanitizeSettings($settings);
    }

    private function sanitizeSettings($settings)
    {
        unset($settings['db']);
        return $settings;
    }

    public function getSettingsByKey($key)
    {
        return $this->settings[$key];
    }
}
