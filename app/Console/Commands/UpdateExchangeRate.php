<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

class UpdateExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange-rate:update {--force : Force update even if rate hasn\'t changed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update USD to VES exchange rate from BCV';

    /**
     * Exchange rate service instance.
     *
     * @var ExchangeRateService
     */
    private $exchangeRateService;

    /**
     * Create a new command instance.
     *
     * @param ExchangeRateService $exchangeRateService
     */
    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando tasa de cambio USD/VES desde el BCV...');

        $result = $this->exchangeRateService->updateFromBCV();

        if ($result['success']) {
            if ($result['updated'] ?? true) {
                $this->info("✓ Tasa de cambio actualizada: {$result['current_rate']} VES por USD");
            } else {
                $this->comment("• Tasa de cambio sin cambios: {$result['current_rate']} VES por USD");
            }
        } else {
            $this->error("✗ Error: {$result['message']}");
            return Command::FAILURE;
        }

        // Mostrar información adicional si se solicita verbosidad
        if ($this->output->isVerbose()) {
            $rateInfo = $this->exchangeRateService->getRateInfo();
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Tasa actual', $rateInfo['formatted_rate'] . ' VES'],
                    ['Última actualización', $rateInfo['last_update_formatted']],
                    ['Tasa BCV', $result['bcv_rate'] ?? 'N/A'],
                ]
            );
        }

        return Command::SUCCESS;
    }
}
