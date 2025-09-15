<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TogglePaymentMock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:mock {--enable : Enable mock mode} {--disable : Disable mock mode} {--status : Show current status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle payment gateway mock mode for development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $envFile = base_path('.env');
        
        if ($this->option('status')) {
            $currentMode = env('PAYMENT_GATEWAY_MODE', 'sandbox');
            $this->info("Current payment mode: {$currentMode}");
            return;
        }
        
        if ($this->option('enable')) {
            $this->setPaymentMode('mock');
            $this->info('✅ Payment mock mode ENABLED - All payments will use mock gateway');
        } elseif ($this->option('disable')) {
            $this->setPaymentMode('sandbox');
            $this->info('✅ Payment mock mode DISABLED - Using real payment gateways');
        } else {
            $this->error('Please specify --enable, --disable, or --status');
        }
    }
    
    private function setPaymentMode($mode)
    {
        $envFile = base_path('.env');
        $envContent = file_exists($envFile) ? file_get_contents($envFile) : '';
        
        if (strpos($envContent, 'PAYMENT_GATEWAY_MODE=') !== false) {
            $envContent = preg_replace('/PAYMENT_GATEWAY_MODE=.*/', "PAYMENT_GATEWAY_MODE={$mode}", $envContent);
        } else {
            $envContent .= "\nPAYMENT_GATEWAY_MODE={$mode}\n";
        }
        
        file_put_contents($envFile, $envContent);
    }
}
