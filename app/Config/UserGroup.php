<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class UserGroup extends BaseConfig
{
    public array $userGroup = [
        'admin'      => 'Admin',
        'manager'    => 'Manager',
        'team-lead'  => 'Team Leader',
        'tsr'        => 'Technical Support Representative',
        ];
}
