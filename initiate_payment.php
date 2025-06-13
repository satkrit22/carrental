<?php
session_start();
// if (!isset($_SESSION['id'])) {
//     header('Location: index.php');
//     exit();
// }

include('includes/config.php');

// Extract the necessary information from the form submission
$fullname = $_POST['fullname'];
$services = $_POST['Ride']; // Car name/model
$amount = $_POST['amount']; // Amount in currency units

// IMPORTANT: Check if the amount needs to be multiplied by 100 for the payment gateway
// If the payment gateway expects amount in paisa/cents instead of rupees/dollars
// Uncomment the line below if needed
$amount = $amount * 100;

$id = $_POST['id'];

// Get booking details from session if available
$bookingNumber = isset($_SESSION['bid']) ? $_SESSION['bid'] : 'BR'.rand(10000,99999);

// Generate a unique purchase_order_id
$purchase_order_id = uniqid("car_rental_");

// Store payment data in session before creating the request payload
$_SESSION['payment_data'] = array(
    'Ride' => $services,
    'id' => $id,
    'amount' => $amount,
    'fullname' => $fullname,
    'booking_number' => $bookingNumber
);

// Prepare the request payload
$data = array(
    'return_url' => 'http://localhost/carrental/return_url.php', // Update with your return URL
    'website_url' => 'http://localhost/',
    'amount' => $amount,
    'purchase_order_id' => $purchase_order_id,
    'purchase_order_name' => 'Car Rental: ' . $services,
    'customer_info' => array(
        'name' => $fullname,
        'email' => $_SESSION['login'] ?? 'user@example.com', // Use session email if available
        'phone' => '9800000000' // Replace with actual user phone if available
    ),
    'amount_breakdown' => array(
        array('label' => 'Car Rental Fee: ' . $services, 'amount' => $amount)
    ),
    'product_details' => array(
        array(
            'identity' => $bookingNumber,
            'name' => $services,
            'total_price' => $amount,
            'quantity' => 1,
            'unit_price' => $amount
        )
    )
);

// Debug information - can be removed in production
echo "<pre>";
echo "Amount being sent to payment gateway: " . $amount . "<br>";
echo "</pre>";

$payload = json_encode($data);

// Initialize CURL
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => array(
        'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455', // Replace with your live or test key
        'Content-Type: application/json',
    ),
));

// Execute CURL request
$response = curl_exec($ch);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    echo 'CURL Error: ' . $curl_error;
    exit;
}

$response_data = json_decode($response, true);

if (isset($response_data['payment_url'])) {
    // Redirect to Khalti payment page
    header('Location: ' . $response_data['payment_url']);
    exit();
} else {
    echo 'Error initiating payment: ';
    echo '<pre>';
    print_r($response_data);
    echo '</pre>';
    echo '<p>Please try again or contact support.</p>';
    echo '<p><a href="my-booking.php">Return to My Bookings</a></p>';
}
?>
