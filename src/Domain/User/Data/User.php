<?php

namespace App\Domain\User\Data;

class User
{
    protected $ckey;
    protected $permissions = false;
    public $rank;
    public $displayName;
    public $feedback = null;
    public $authSource = null;

    public function __construct($ckey, $rank, ?array $permissions = [], ?int $flags = 0, ?string $feedback = null, $authSource = null)
    {
        $this->setCkey($ckey);
        $this->setRank($rank);
        $this->setDisplayName($ckey);
        if ($permissions) {
            $this->setPermissions($permissions);
        }
        $this->flags = $flags;
        $this->feedback = $feedback;
        $this->authSource = $authSource;
    }

    private function setCkey($ckey)
    {
        $this->ckey = $ckey;
    }

    public function getCkey()
    {
        return $this->ckey;
    }

    private function setRank($rank)
    {
        $this->rank = $rank;
    }

    private function setDisplayName($ckey)
    {
        $this->displayName = $ckey;
    }

    private function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }
    public function hasPermission(string $permission)
    {
        if (!isset($this->permissions[$permission])) {
            return false;
        }
        return $this->permissions[$permission];
    }

    public function setFeedback($link)
    {
        $this->feedback = $link;
    }
}
