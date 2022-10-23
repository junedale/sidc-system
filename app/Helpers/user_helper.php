<?php

if(!function_exists('has_permission'))
{
    function has_permission(string $permission): bool
    {
    $permissions    = explode('|', $permission);
    $userPermission = session()->get('permissions');

    foreach($permissions as $element)
    {
        if(!in_array($element, $userPermission))
        {
            return false;
        }
    }
    return true;
    }
}

function in_group(string $group): bool
{
    if(strcmp(session()->get('user_group'), $group) === 0)
    {
        return true;
    }
    return false;
}

function is_checked(string $value, array $list): bool
{
    return in_array($value, array_column($list, 'permission'));
}