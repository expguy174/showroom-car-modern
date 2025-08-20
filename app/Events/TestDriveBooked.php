<?php

namespace App\Events;

use App\Models\TestDrive;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestDriveBooked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TestDrive $testDrive;

    public function __construct(TestDrive $testDrive)
    {
        $this->testDrive = $testDrive;
    }
}


