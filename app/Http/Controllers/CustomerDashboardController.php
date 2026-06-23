<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $totalOrders    = Order::where('user_id', $user->id)->count();
        $pendingOrders  = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('status', 'completed')->count();
        $totalSpent     = Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total');

        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders',
        ));
    }

    public function orders(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderShow(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('customer.order-show', compact('order'));
    }

    public function profile(): View
    {
        $user = auth()->user();

        return view('customer.profile', compact('user'));
    }

    public function profileUpdate(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city'    => 'nullable|string|max:255',
            'state'   => 'nullable|string|max:255',
            'zip'     => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
