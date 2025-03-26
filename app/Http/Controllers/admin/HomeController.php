<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\Order;
use App\Models\Refund;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\RefundRepository;
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
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0,  0 ) . ' / ' . $this->boxRepository->countBoxes_perMonths(1,  0);
                $total_sales = '$ ' . number_format( $this->orderRepository->getTotalSales_perMonths(0) , 2, '.', ',') ;
                $total_paid = '$ ' . number_format( $this->orderRepository->getTotalPaid_perMonths(0) , 2, '.', ',') ;
                $total_collections = '$ ' . number_format( $this->paymentRepository->getTotalCollections_perMonths(0) , 2, '.', ',') ;
                $total_returns =  '$ ' . number_format($this->refundRepository->getTotalRefunds_perMonths(0), 2, '.', ','); 
                $total_credit = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0, 'credit'), 2, '.', ',');
                $total_cash =  '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0, 'cash'), 2, '.', ',');
                $total_card = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0 , 'card'), 2, '.', ',');
                $total_transfer = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0, 'transfer'), 2, '.', ',');
                break;
            case 'six_months':
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0,  6 ) . ' / ' . $this->boxRepository->countBoxes_perMonths(1,  6);
                $total_sales = '$ ' . number_format( $this->orderRepository->getTotalSales_perMonths(6) , 2, '.', ',') ;
                $total_paid = '$ ' . number_format( $this->orderRepository->getTotalPaid_perMonths(6) , 2, '.', ',') ;
                $total_collections = '$ ' . number_format( $this->paymentRepository->getTotalCollections_perMonths(6) , 2, '.', ',') ;
                $total_returns =  '$ ' . number_format($this->refundRepository->getTotalRefunds_perMonths(6), 2, '.', ',');
                $total_credit = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(6, 'credit'), 2, '.', ',');
                $total_cash =  '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(6, 'cash'), 2, '.', ',');
                $total_card = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(6 , 'card'), 2, '.', ',');
                $total_transfer = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(6, 'transfer'), 2, '.', ',');
                break;
            case 'twelve_months':
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0,  12) . ' / ' . $this->boxRepository->countBoxes_perMonths(1, 12 );
                $total_sales = '$ ' . number_format( $this->orderRepository->getTotalSales_perMonths(12) , 2, '.', ',') ;
                $total_paid = '$ ' . number_format( $this->orderRepository->getTotalPaid_perMonths(12) , 2, '.', ',') ;
                $total_collections = '$ ' . number_format( $this->paymentRepository->getTotalCollections_perMonths(12) , 2, '.', ',') ;
                $total_returns =  '$ ' . number_format($this->refundRepository->getTotalRefunds_perMonths(12), 2, '.', ',');
                $total_credit = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(12, 'credit'), 2, '.', ',');
                $total_cash =  '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(12, 'cash'), 2, '.', ',');
                $total_card = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(12 , 'card'), 2, '.', ',');
                $total_transfer = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(12, 'transfer'), 2, '.', ',');
                break;
            case 'on_date':
                $open_closed_boxes = $this->boxRepository->countBoxes_OnDates(0, $start, $end) . ' / ' . $this->boxRepository->countBoxes_OnDates(1, $start, $end );
                $total_sales = '$ ' . number_format( $this->orderRepository->getTotalSales_OnDates($start, $end) , 2, '.', ',') ;
                $total_paid = '$ ' . number_format( $this->orderRepository->getTotalPaid_OnDates($start, $end) , 2, '.', ',') ;
                $total_collections = '$ ' . number_format( $this->paymentRepository->getTotalCollections_OnDates($start, $end) , 2, '.', ',') ;
                $total_returns =  '$ ' . number_format( $this->refundRepository->getTotalRefunds_OnDates($start, $end), 2, '.', ',');
                $total_credit = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnDates($start , $end, 'credit'), 2, '.', ',');
                $total_cash =  '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnDates($start , $end, 'cash'), 2, '.', ',');
                $total_card = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnDates($start , $end , 'card'), 2, '.', ',');
                $total_transfer = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnDates($start , $end, 'transfer'), 2, '.', ',');
                break;
            case 'on_months':
                $open_closed_boxes = $this->boxRepository->countBoxes_OnMonths(0, $start, $end) . ' / ' . $this->boxRepository->countBoxes_OnMonths(1, $start, $end );
                $total_sales = '$ ' . number_format( $this->orderRepository->getTotalSales_OnMonths($start, $end) , 2, '.', ',') ;
                $total_paid = '$ ' . number_format( $this->orderRepository->getTotalPaid_OnMonths($start, $end) , 2, '.', ',') ;
                $total_collections = '$ ' . number_format( $this->paymentRepository->getTotalCollections_OnMonths($start, $end) , 2, '.', ',') ;
                $total_returns =  '$ ' . number_format($this->refundRepository->getTotalRefunds_OnMonths($start, $end), 2, '.', ',');
                $total_credit = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnMonths($start , $end, 'credit'), 2, '.', ',');
                $total_cash =  '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnMonths($start , $end, 'cash'), 2, '.', ',');
                $total_card = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnMonths($start , $end , 'card'), 2, '.', ',');
                $total_transfer = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_OnMonths($start , $end, 'transfer'), 2, '.', ',');
                break;
            default:
                $open_closed_boxes = $this->boxRepository->countBoxes_perMonths(0,  0 ) . ' / ' . $this->boxRepository->countBoxes_perMonths(1,  0);
                $total_sales = '$ ' . number_format( $this->orderRepository->getTotalSales_perMonths(0) , 2, '.', ',') ;
                $total_paid = '$ ' . number_format( $this->orderRepository->getTotalPaid_perMonths(0) , 2, '.', ',') ;
                $total_collections = '$ ' . number_format( $this->paymentRepository->getTotalCollections_perMonths(0) , 2, '.', ',') ;
                $total_returns =  '$ ' . number_format($this->refundRepository->getTotalRefunds_perMonths(0), 2, '.', ','); 
                $total_credit = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0, 'credit'), 2, '.', ',');
                $total_cash =  '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0, 'cash'), 2, '.', ',');
                $total_card = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0 , 'card'), 2, '.', ',');
                $total_transfer = '$ ' . number_format( $this->orderRepository->getTotalPaymentMethod_perMonths(0, 'transfer'), 2, '.', ',');
                break;
        }

        $data = [
            'open_closed_boxes' => $open_closed_boxes,
            'total_sales' => $total_sales,
            'total_paid' => $total_paid,
            'total_collected' => $total_collections,
            'total_returns' => $total_returns,
            'total_credit' => $total_credit,
            'total_cash' => $total_cash,
            'total_card' => $total_card,
            'total_transfer' => $total_transfer,
        ];  
        return $data;
    }
}

