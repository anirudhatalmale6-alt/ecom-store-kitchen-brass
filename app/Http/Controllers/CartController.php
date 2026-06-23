<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->getCart();
        $cartItems = $cart
            ? $cart->cartItems()->with('product.category')->get()
            : collect();

        $subtotal = $cartItems->sum(fn (CartItem $item) => $item->product->current_price * $item->quantity);

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::active()->findOrFail($request->input('product_id'));
        $cart = $this->getCart(true);

        $cartItem = $cart->cartItems()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->input('quantity'),
            ]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity'   => $request->input('quantity'),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();

        if (! $cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->update([
            'quantity' => $request->input('quantity'),
        ]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem): RedirectResponse
    {
        $cart = $this->getCart();

        if (! $cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    /**
     * Get or create a cart based on authentication state.
     */
    protected function getCart(bool $create = false): ?Cart
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->first();

            if (! $cart && $create) {
                // Check for a guest cart to migrate
                $sessionCart = Cart::where('session_id', session()->getId())
                    ->whereNull('user_id')
                    ->first();

                if ($sessionCart) {
                    $sessionCart->update(['user_id' => auth()->id(), 'session_id' => null]);
                    return $sessionCart;
                }

                $cart = Cart::create(['user_id' => auth()->id()]);
            }

            return $cart;
        }

        $cart = Cart::where('session_id', session()->getId())->first();

        if (! $cart && $create) {
            $cart = Cart::create(['session_id' => session()->getId()]);
        }

        return $cart;
    }
}
