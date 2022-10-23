<?php

function to_status($value): string
{

    if($value !== null)
    {
        if((int) $value === 0)
        {
            return '<span class="badge bg-danger p-2">Disapproved</span>';
        } elseif((int) $value === 1)
        {
            return  '<span class="badge bg-success p-2">Approved</span>';
        } else
        {
            return '<span class="badge bg-warning p-2">Cancelled</span>';
        }
    } else
    {
        return '<span class="badge bg-secondary p-2">Pending</span>';
    }

}