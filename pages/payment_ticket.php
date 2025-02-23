<?php
session_start();
include 'config.php';

if (!isset($_SESSION['ticket_type'])) {
    // Redirect to the ticket selection page if no ticket type is selected
    header('Location: ticket.html');
    exit();
}

$ticketType = $_SESSION['ticket_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal - PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AWDn25W9UaIiWxg6Pmmz1a1IPP5azedEoy6PdFCGgW3g_4LQkrrrfqy8dYYswAnZ-0imaJzOMVkiggW6&currency=USD"></script>
</head>
<body>
    <h2>Confirm Your Purchase</h2>
    <p>Selected Ticket Type: <span id="ticket-type"><?php echo htmlspecialchars($ticketType); ?></span></p>
    <div id="paypal-button-container"></div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ticketType = <?php echo json_encode($ticketType); ?>;
            
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: ticketType, // Use the actual ticket price
                            },
                        }],
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert("Transaction completed by " + details.payer.name.given_name);
                        
                        // Make an AJAX request to save the ticket purchase in the database
                        fetch('save_ticket.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                ticketType: ticketType,
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Ticket saved successfully');
                                window.location.href = "home-login.php";
                            } else {
                                console.error('Error saving ticket');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    });
                },
            }).render("#paypal-button-container");
        });
    </script>
</body>
</html>