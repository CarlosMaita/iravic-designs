<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\MyProfileRequest;
use App\Models\Customer;
use App\Repositories\Eloquent\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public $repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->repository = $user_repository;
    }

    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = Customer::first();        if($request->ajax()){
            return response()->json($customer);
        }
        
        $orders = $customer->orders()->orderBy('date', 'desc')->get();
        $refunds = $customer->refunds()->orderBy('date', 'desc')->get();
        $showOrdersTab = isset($request->pedidos) ? true : false;
        $showRefundsTab = isset($request->devoluciones) ? true : false;
        $planningCollection = $customer->getPlanningCollection();
        
        return view('dashboard.my-profile.index')
                ->withCustomer($customer)
                ->withOrders($orders)
                ->withRefunds($refunds)
                ->withShowOrdersTab($showOrdersTab)
                ->withShowRefundsTab($showRefundsTab)
                ->withPlanningCollection($planningCollection);
    }

    /**
     * Show the form for editing the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('dashboard.my-profile.edit');
    }
    
    /**
     * Update the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(MyProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $this->repository->update($user->id, $request->all());
            flash("Su perfil ha sido actualizado con éxito")->success();

            return response()->json([
                'success' => 'true',
                'data' => [
                    'redirect' => route('my-profile.edit')
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('dashboard.general.operation_error')
            ]);
        }
    }
}
