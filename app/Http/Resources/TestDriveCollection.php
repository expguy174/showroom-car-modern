<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TestDriveCollection extends ResourceCollection
{
    public $collects = TestDriveResource::class;
}


