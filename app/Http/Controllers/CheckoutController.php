<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Store a new checkout.
     *
     * Accepts: name, mobile, message, product_ids (array or JSON string)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50',
            'message' => 'nullable|string',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|distinct|min:1',
        ]);

        $checkout = Checkout::create($data);

        return response()->json([
            'success' => true,
            'data' => $checkout,
        ], 201);
    }
}
