<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a paginated listing of customers (users with role=customer).
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer with their orders.
     */
    public function show(User $customer)
    {
        $customer->load(['orders' => function ($query) {
            $query->latest();
        }]);

        return view('admin.customers.show', compact('customer'));
    }
}
