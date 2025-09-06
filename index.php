<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ปริ้นต์ป้าย Location</title>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .print-button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>สร้างป้าย Location</h1>
        <form action="print.php" method="post" target="_blank">
            <div class="form-group">
                <label for="location_code">Location Code (สำหรับ QR Code):</label>
                <input type="text" id="location_code" name="location_code" value="A19-08-A1" required>
            </div>
            <div class="form-group">
                <label for="quantity">จำนวนที่ต้องการปริ้นต์:</label>
                <input type="number" id="quantity" name="quantity" value="5" min="1" required>
            </div>
            <button type="submit" class="print-button">สร้าง PDF</button>
        </form>
    </div>

</body>

</html>