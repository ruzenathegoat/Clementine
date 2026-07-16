<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $user = \App\Models\User::first();
    $product = \App\Models\Product::first();
    $app->make('auth')->login($user);

    \App\Models\CartItem::updateOrCreate([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ], [
        'quantity' => 1
    ]);

    $request = \Illuminate\Http\Request::create('/checkout', 'POST', [
        'contact_email' => $user->email,
        'shipping_full_name' => 'John Doe',
        'shipping_address1' => '123 Test St',
        'shipping_city' => 'Jakarta',
        'shipping_postal_code' => '12345',
        'shipping_country' => 'ID',
        'payment_method' => 'card',
    ]);
    
    // bypass middleware and call directly
    $controller = new \App\Http\Controllers\CheckoutController();
    $response = $controller->store($request);
    
    if (method_exists($response, 'getStatusCode')) {
        echo "Status: " . $response->getStatusCode() . "\n";
        if ($response->getStatusCode() >= 400) {
            echo $response->getContent();
        } elseif ($response->getStatusCode() === 302) {
            echo "Redirect: " . $response->headers->get('Location') . "\n";
        }
    } else {
        echo "Response: " . get_class($response) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
