<?php

function display_error($validation, $field)
{
    if(!empty($validation) && $validation->hasError($field)) {
        return $validation->getError($field);
    }
    return false;
}