<?php
session_start();
include('includes/config.php');

echo "<h2>Payment Debug Information</h2>";
echo "<pre>";

// Check if there's payment data in the session
if (isset($_SESSION['payment_data'])) {
    echo "<h3>Session Payment Data:</h3>";
    print_r($_SESSION['payment_data']);
} else {
    echo "No payment data in session.<br>";
}

// Get the latest booking
$useremail = $_SESSION['login'] ?? '';
if (!empty($useremail)) {
    echo "<h3>Latest Booking:</h3>";
    $sql = "SELECT tblvehicles.VehiclesTitle, tblbrands.BrandName, tblbooking.FromDate, 
            tblbooking.ToDate, tblvehicles.PricePerDay, 
            DATEDIFF(tblbooking.ToDate, tblbooking.FromDate) as totaldays,
            tblbooking.BookingNumber
            FROM tblbooking 
            JOIN tblvehicles ON tblbooking.VehicleId = tblvehicles.id 
            JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
            WHERE tblbooking.userEmail = :useremail 
            ORDER BY tblbooking.id DESC LIMIT 1";
    
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    
    if ($query->rowCount() > 0) {
        $result = $query->fetch(PDO::FETCH_OBJ);
        print_r($result);
        
        // Calculate total amount
        $totalDays = $result->totaldays;
        $pricePerDay = $result->PricePerDay;
        $totalAmount = $totalDays * $pricePerDay;
        
        echo "<h3>Payment Calculation:</h3>";
        echo "Total Days: " . $totalDays . "<br>";
        echo "Price Per Day: " . $pricePerDay . "<br>";
        echo "Total Amount: " . $totalAmount . "<br>";
    } else {
        echo "No bookings found for this user.<br>";
    }
}

echo "</pre>";
echo "<p><a href='my-booking.php'>Return to My Bookings</a></p>";
?>
