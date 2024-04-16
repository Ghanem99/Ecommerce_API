<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() 
    {
        $orders = Order::with('user')->paginate(10);

        if ($orders) {
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $product = Product::where('id', $item->product_id)->pluck('name')->first();
                    $item->product_name = $product['0'];
                }
            }
            return response()->json($orders);
        } else {
            return response()->json(null, 404);
        }

        return response()->json($orders);
    }

    public function show(Order $order) 
    {
        return response()->json($order);
    }

    public function store(Request $request) 
    {
        $location = Location::where('user_id', $request->user_id)->first();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|string',
            'delivered_at' => 'required|date'
        ]);
        
        $order = Order::create([
            'user_id' => $request->user_id,
            'location_id' => $location->id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'delivered_at' => $request->delivered_at
        ]);

        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
            $product = Product::where('id', $item['product_id'])->first();
            $product->update([
                'amount' => $product->amount - $item['quantity']
            ]);
        }
        return response()->json($order, 201);
    }

    public function getOrderItems(Order $order) 
    {
        $items = $order->items;
        if ($items->isEmpty()) {
            return response()->json('No items found', 404);
        } else {
            foreach ($items as $item) {
                $product = Product::where('id', $item->product_id)->pluck('name')->first();
                $item->product_name = $product['0'];
            }
            return response()->json($items);
        }
    }

    public function getUserOrders($user_id) 
    {
        $orders = Order::where('user_id', $user_id)::with('items', function ($query) {
            $query->orderBy('created_at', 'desc');
        })->get();

        if ($orders->isEmpty()) {
            return response()->json('No orders found', 404);
        } else {
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $product = Product::where('id', $item->product_id)->pluck('name')->first();
                    $item->product_name = $product['0'];
                }
            }
            return response()->json($orders);
        }
    }

    public function changeOrderStatus(Request $request, Order $order) 
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json($order);
    }
}
