<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class RequestModel extends Model
{

    public abstract function getAllRequest(string $id): \CodeIgniter\Database\BaseBuilder;
    public abstract function getRequest(int $id): array;
    public abstract function hasRequest(string $id): ?array;
    public abstract function getStats(string $id): array;

}