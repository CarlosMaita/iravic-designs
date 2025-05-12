<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\MyProfileRequest;
use App\Models\Customer;
use App\Repositories\Eloquent\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardEcommerceController extends Controller
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
        return view('ecommerce.dashboard.index'); // Crea esta vista
    }
    
}
