<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $user   = JWTAuth::user();
        $orders = Order::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->get();
        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items'                      => 'required|array|min:1',
            'items.*.product_id'         => 'required|string',
            'items.*.qty'                => 'required|integer|min:1',
            'payment_method'             => 'required|in:cash,aba,wing,bakong',
            'shipping_address'           => 'required|array',
            'shipping_address.name'      => 'required|string',
            'shipping_address.phone'     => 'required|string',
            'shipping_address.address'   => 'required|string',
        ]);

        $user     = JWTAuth::user();
        $items    = [];
        $subtotal = 0;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['qty']) {
                return response()->json([
                    'message' => "ផលិតផល {$product->name} នៅសល់តែ {$product->stock} គ្រាប់",
                ], 422);
            }

            $price     = $product->sale_price ?? $product->price;
            $subtotal += $price * $item['qty'];

            $items[] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'name_km'    => $product->name_km,
                'price'      => $price,
                'qty'        => $item['qty'],
                'image'      => $product->images[0] ?? null,
            ];

            // Reduce stock
            $product->decrement('stock', $item['qty']);
        }

        $shippingFee = $subtotal >= 50 ? 0 : 2.5;
        $total       = $subtotal + $shippingFee;

        $order = Order::create([
            'user_id'          => $user->id,
            'items'            => $items,
            'subtotal'         => $subtotal,
            'shipping_fee'     => $shippingFee,
            'total'            => $total,
            'payment_method'   => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'note'             => $request->note,
        ]);

        return response()->json([
            'message' => 'បញ្ជាទិញបានជោគជ័យ',
            'order'   => $order,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $user  = JWTAuth::user();
        $order = Order::where('_id', $id)
                      ->where('user_id', $user->id)
                      ->firstOrFail();
        return response()->json($order);
    }

    public function cancel(string $id): JsonResponse
    {
        $user  = JWTAuth::user();
        $order = Order::where('_id', $id)
                      ->where('user_id', $user->id)
                      ->firstOrFail();

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json(['message' => 'មិនអាចលុបការបញ្ជាទិញនេះបានទេ'], 422);
        }

        // Restore stock
        foreach ($order->items as $item) {
            Product::where('_id', $item['product_id'])
                   ->increment('stock', $item['qty']);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json(['message' => 'លុបការបញ្ជាទិញបានជោគជ័យ']);
    }

    // Admin: Get all orders
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Order::query();
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($orders);
    }

    // Admin: Update order status
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'ធ្វើបច្ចុប្បន្នភាពស្ថានភាពបានជោគជ័យ',
            'order'   => $order,
        ]);
    }
}
