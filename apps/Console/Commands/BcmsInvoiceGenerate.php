<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Billing\InvoiceGenerator;

class BcmsInvoiceGenerate extends Command
{
    protected $signature = 'bcms:invoice-generate';
    protected $description = 'Generate invoices per billing cycle';

    public function handle(InvoiceGenerator $generator): int
    {
        $generator->generate();
        $this->info('Invoice generation dispatched.');
        return self::SUCCESS;
    }
}
