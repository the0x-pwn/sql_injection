<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductsModel extends BaseModel
{
    public  function search($query)
    {
        static::$query .=  " WHERE name LIKE '%$query%'";
        return $this;
    }
}
