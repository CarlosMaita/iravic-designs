<?php

namespace App\Console\Commands;

use App\Models\Debt;
use App\Models\Operation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Console\Command;

class CreateActualsOperations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operations.create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $debts = Debt::all();
        $orders = Order::all();
        $payments = Payment::all();
        $refunds = Refund::all();

        $this->processCollection($debts, 'debt_id');
        $this->processCollection($orders, 'order_id');
        $this->processCollection($payments, 'payment_id');
        $this->processCollection($refunds, 'refund_id');
    }

    public function processCollection($data, $col)
    {
        foreach ($data as $model) {
            if (!$model->operation) {
                Operation::create([
                    $col => $model->id,
                    'customer_id' => $model->customer_id,
                    'created_at' => $model->created_at,
                    'updated_at' => $model->created_at
                ]);
            }
        }
    }
}
