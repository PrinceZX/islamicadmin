<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* Define your styles for the invoice here */
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            width: 80%;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .invoice h2 {
            text-align: center;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details table th, .invoice-details table td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .text-right {
            text-align: right;
        }
        .user-details {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <h2>Invoice</h2>
        
        <!-- User Information -->
        <div class="user-details">
            <p><strong>User Name:</strong> {{ $user->full_name }}</p>
            <p><strong>User Email:</strong> {{ $user->email }}</p>
            <p><strong>Mobile No:</strong> {{ $user->mobile_number }}</p>
        </div>
        
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>{{$audio['name']}}</td>
                    <td>{{$aiaudio['currency_code']}} {{$aiaudio['amount']}}</td>
                    <td>{{$aiaudio['currency_code']}} {{$aiaudio['amount']}}</td>
                </tr>
                <!-- Add more rows for additional items -->
            </table>
        </div>
        <div class="text-right">
            <p><strong>Subtotal:</strong>{{$aiaudio['currency_code']}} {{$aiaudio['amount']}}</p>
            <p><strong>Total:</strong>{{$aiaudio['currency_code']}} {{$aiaudio['amount']}}</p>
        </div>
    </div>

</body>
</html>
