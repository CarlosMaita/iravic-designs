<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\RefundRepository;
use App\Helpers\FormatHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $boxRepository;
    private $orderRepository;
    private $paymentRepository;
    private $refundRepository;

    public function __construct(OrderRepository $orderRepository, BoxRepository $boxRepository, PaymentRepository $paymentRepository, RefundRepository $refundRepository)
    {
        $this->boxRepository = $boxRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
        $this->refundRepository = $refundRepository;
    }

    public function index(Request $request)
    {
        if($request->ajax ()){
            $data = $this->getDataTotalCards($request->filter, $request->start ?? null, $request->end ?? null);
            return response()->json($data);
        }

        return view('dashboard.homepage');
    }

    private function getDataTotalCards($filter, $start = null, $end = null)
    {
        $fechaActual = Carbon::now();
        switch ($filter) {
            case 'month':
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0, 0) . ' / ' . $this->boxRepository->countBoxes_perMonths(1, 0);
                $total_sales = FormatHelper::formatCurrency($this->orderRepository->getTotalSales_perMonths(0));
                $total_paid = FormatHelper::formatCurrency($this->orderRepository->getTotalPaid_perMonths(0));
                $total_collected = FormatHelper::formatCurrency($this->paymentRepository->getTotalCollections_perMonths(0));
                $total_returns = FormatHelper::formatCurrency($this->refundRepository->getTotalRefunds_perMonths(0));
                $total_credit = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'credit'));
                $total_cash = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'cash'));
                $total_card = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'card'));
                $total_transfer = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'transfer'));
                $total_sales_graph = $this->orderRepository->getOrdersGraph_perDays(0);
                $total_returns_graph = $this->refundRepository->getRefundsGraph_perDays(0);
                $total_credit_graph = $this->orderRepository->getTotalPaymentMethodGraph_perDay(0, 'credit');
                $total_collected_graph = $this->paymentRepository->getTotalCollectedGraph_perDay();
                $total_paid_graph = $this->orderRepository->getPaidGraph_perDay();
                $total_cash_and_transfer_graph = $this->orderRepository->getTotalPaymentMethodGraph_perDay(0, 'cash', 'bankwire');
                $total_card_graph = $this->orderRepository->getTotalPaymentMethodGraph_perDay(0, 'card');
                break;
            case 'six_months':
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0, 6) . ' / ' . $this->boxRepository->countBoxes_perMonths(1, 6);
                $total_sales = FormatHelper::formatCurrency($this->orderRepository->getTotalSales_perMonths(6));
                $total_paid = FormatHelper::formatCurrency($this->orderRepository->getTotalPaid_perMonths(6));
                $total_collected = FormatHelper::formatCurrency($this->paymentRepository->getTotalCollections_perMonths(6));
                $total_returns = FormatHelper::formatCurrency($this->refundRepository->getTotalRefunds_perMonths(6));
                $total_credit = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(6, 'credit'));
                $total_cash = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(6, 'cash'));
                $total_card = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(6, 'card'));
                $total_transfer = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(6, 'transfer'));
                $total_sales_graph = $this->orderRepository->getOrdersGraph_perMonths(6);
                $total_returns_graph = $this->refundRepository->getRefundsGraph_perMonths(6);
                $total_credit_graph = $this->orderRepository->getTotalPaymentMethodGraph_perMonths(6, 'credit');
                $total_collected_graph = $this->paymentRepository->getTotalCollectedGraph_perMonths(6);
                $total_paid_graph = $this->orderRepository->getPaidGraph_perMonths(6);
                $total_cash_and_transfer_graph = $this->orderRepository->getTotalPaymentMethodGraph_perMonths(6, 'cash', 'bankwire');
                $total_card_graph = $this->orderRepository->getTotalPaymentMethodGraph_perMonths(6, 'card');
                break;
            case 'twelve_months':
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0, 12) . ' / ' . $this->boxRepository->countBoxes_perMonths(1, 12);
                $total_sales = FormatHelper::formatCurrency($this->orderRepository->getTotalSales_perMonths(12));
                $total_paid = FormatHelper::formatCurrency($this->orderRepository->getTotalPaid_perMonths(12));
                $total_collected = FormatHelper::formatCurrency($this->paymentRepository->getTotalCollections_perMonths(12));
                $total_returns = FormatHelper::formatCurrency($this->refundRepository->getTotalRefunds_perMonths(12));
                $total_credit = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(12, 'credit'));
                $total_cash = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(12, 'cash'));
                $total_card = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(12, 'card'));
                $total_transfer = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(12, 'transfer'));
                $total_sales_graph = $this->orderRepository->getOrdersGraph_perMonths(12);
                $total_returns_graph = $this->refundRepository->getRefundsGraph_perMonths(12);
                $total_credit_graph = $this->orderRepository->getTotalPaymentMethodGraph_perMonths(12, 'credit');
                $total_collected_graph = $this->paymentRepository->getTotalCollectedGraph_perMonths(12);
                $total_paid_graph = $this->orderRepository->getPaidGraph_perMonths(12);
                $total_cash_and_transfer_graph = $this->orderRepository->getTotalPaymentMethodGraph_perMonths(12, 'cash', 'bankwire');
                $total_card_graph = $this->orderRepository->getTotalPaymentMethodGraph_perMonths(12, 'card');
                break;
            case 'on_date':
                $open_closed_boxes = $this->boxRepository->countBoxes_OnDates(0, $start, $end) . ' / ' . $this->boxRepository->countBoxes_OnDates(1, $start, $end);
                $total_sales = FormatHelper::formatCurrency($this->orderRepository->getTotalSales_OnDates($start, $end));
                $total_paid = FormatHelper::formatCurrency($this->orderRepository->getTotalPaid_OnDates($start, $end));
                $total_collected = FormatHelper::formatCurrency($this->paymentRepository->getTotalCollections_OnDates($start, $end));
                $total_returns = FormatHelper::formatCurrency($this->refundRepository->getTotalRefunds_OnDates($start, $end));
                $total_credit = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnDates($start, $end, 'credit'));
                $total_cash = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnDates($start, $end, 'cash'));
                $total_card = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnDates($start, $end, 'card'));
                $total_transfer = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnDates($start, $end, 'transfer'));
                $total_sales_graph = $this->orderRepository->getOrdersGraph_OnDates($start, $end);
                $total_returns_graph = $this->refundRepository->getRefundsGraph_OnDates($start, $end);
                $total_credit_graph = $this->orderRepository->getTotalPaymentMethodGraph_OnDates($start, $end, 'credit');
                $total_collected_graph = $this->paymentRepository->getTotalCollectedGraph_OnDates($start, $end);
                $total_paid_graph = $this->orderRepository->getPaidGraph_OnDates($start, $end);
                $total_cash_and_transfer_graph = $this->orderRepository->getTotalPaymentMethodGraph_OnDates($start, $end, 'cash', 'bankwire');
                $total_card_graph = $this->orderRepository->getTotalPaymentMethodGraph_OnDates($start, $end, 'card');
                break;
            case 'on_months':
                $open_closed_boxes = $this->boxRepository->countBoxes_OnMonths(0, $start, $end) . ' / ' . $this->boxRepository->countBoxes_OnMonths(1, $start, $end);
                $total_sales = FormatHelper::formatCurrency($this->orderRepository->getTotalSales_OnMonths($start, $end));
                $total_paid = FormatHelper::formatCurrency($this->orderRepository->getTotalPaid_OnMonths($start, $end));
                $total_collected = FormatHelper::formatCurrency($this->paymentRepository->getTotalCollections_OnMonths($start, $end));
                $total_returns = FormatHelper::formatCurrency($this->refundRepository->getTotalRefunds_OnMonths($start, $end));
                $total_credit = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnMonths($start, $end, 'credit'));
                $total_cash = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnMonths($start, $end, 'cash'));
                $total_card = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnMonths($start, $end, 'card'));
                $total_transfer = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_OnMonths($start, $end, 'transfer'));
                $total_sales_graph = $this->orderRepository->getOrdersGraph_OnMonths($start, $end);
                $total_returns_graph = $this->refundRepository->getRefundsGraph_OnMonths($start, $end);
                $total_credit_graph = $this->orderRepository->getTotalPaymentMethodGraph_OnMonths($start, $end, 'credit');
                $total_collected_graph = $this->paymentRepository->getTotalCollectedGraph_OnMonths($start, $end);
                $total_paid_graph = $this->orderRepository->getPaidGraph_OnMonths($start, $end);
                $total_cash_and_transfer_graph = $this->orderRepository->getTotalPaymentMethodGraph_OnMonths($start, $end, 'cash', 'bankwire');
                $total_card_graph = $this->orderRepository->getTotalPaymentMethodGraph_OnMonths($start, $end, 'card');
                break;
            default:
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0, 0) . ' / ' . $this->boxRepository->countBoxes_perMonths(1, 0);
                $total_sales = FormatHelper::formatCurrency($this->orderRepository->getTotalSales_perMonths(0));
                $total_paid = FormatHelper::formatCurrency($this->orderRepository->getTotalPaid_perMonths(0));
                $total_collected = FormatHelper::formatCurrency($this->paymentRepository->getTotalCollections_perMonths(0));
                $total_returns = FormatHelper::formatCurrency($this->refundRepository->getTotalRefunds_perMonths(0));
                $total_credit = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'credit'));
                $total_cash = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'cash'));
                $total_card = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'card'));
                $total_transfer = FormatHelper::formatCurrency($this->orderRepository->getTotalPaymentMethod_perMonths(0, 'transfer'));
                $total_sales_graph = $this->orderRepository->getOrdersGraph_perDays(0);
                $total_returns_graph = $this->refundRepository->getRefundsGraph_perDays(0);
                $total_credit_graph = $this->orderRepository->getTotalPaymentMethodGraph_perDay(0, 'credit');
                $total_collected_graph = $this->paymentRepository->getTotalCollectedGraph_perDay(0);
                $total_paid_graph = $this->orderRepository->getPaidGraph_perDay(0);
                $total_cash_and_transfer_graph = $this->orderRepository->getTotalPaymentMethodGraph_perDay(0, 'cash', 'bankwire');
                $total_card_graph = $this->orderRepository->getTotalPaymentMethodGraph_perDay(0, 'card');
                break;
        }

        $data = [
            'open_closed_boxes' => $open_closed_boxes,
            'total_sales' => $total_sales,
            'total_paid' => $total_paid,
            'total_collected' => $total_collected,
            'total_returns' => $total_returns,
            'total_credit' => $total_credit,
            'total_cash' => $total_cash,
            'total_card' => $total_card,
            'total_transfer' => $total_transfer,
            'total_sales_graph' => $total_sales_graph ?? null,
            'total_returns_graph' => $total_returns_graph ?? null,
            'total_credit_graph' => $total_credit_graph ?? null,
            'total_collected_graph' => $total_collected_graph ?? null,
            'total_paid_graph' => $total_paid_graph ?? null,
            'total_cash_and_transfer_graph' => $total_cash_and_transfer_graph ?? null,
            'total_card_graph' => $total_card_graph ?? null,
        ];  
        return $data;
    }
}

