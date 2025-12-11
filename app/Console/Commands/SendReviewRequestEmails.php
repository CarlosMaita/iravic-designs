<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReviewRequestEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-review-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review request emails to customers 7 days after their order was shipped';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notificationService = app(NotificationService::class);
        
        // Get orders shipped exactly 7 days ago
        $sevenDaysAgo = Carbon::now()->subDays(7)->startOfDay();
        $sevenDaysAgoEnd = Carbon::now()->subDays(7)->endOfDay();
        
        $orders = Order::where('status', Order::STATUS_SHIPPED)
            ->whereBetween('updated_at', [$sevenDaysAgo, $sevenDaysAgoEnd])
            ->with('customer')
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            // Check if review request was already sent
            $alreadySent = $order->customer->notifications()
                ->where('type', \App\Models\Notification::TYPE_REVIEW_REQUEST)
                ->where('action_url', 'like', '%/e/ordenes/' . $order->id)
                ->exists();

            if (!$alreadySent) {
                $notificationService->sendReviewRequestNotification($order);
                $count++;
            }
        }

        $this->info("Review request emails sent: {$count}");
        
        return Command::SUCCESS;
    }
}
