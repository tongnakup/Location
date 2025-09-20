<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>อัปโหลดไฟล์ Excel เพื่อปริ้นต์ป้าย</title>
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
            text-align: center;
        }

        h1 {
            margin-top: 0;
        }

        .upload-button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        input[type="file"] {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        .instructions {
            color: #666;
            font-size: 14px;
            text-align: left;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>ปริ้นต์ป้าย Location</h1>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="excel_file">เลือกไฟล์ Excel (.xlsx):</label><br><br>
            <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required>

            <div class="instructions">
                <strong>คำแนะนำ:</strong>
                <ul>
                    <li>ไฟล์ต้องเป็น .xlsx หรือ .xls</li>
                    <li>ข้อมูล Location Code ต้องอยู่ในคอลัมน์แรก (A)</li>
                    <li>โปรแกรมจะเริ่มอ่านข้อมูลตั้งแต่แถวที่ 2 เป็นต้นไป</li>
                </ul>
            </div>

            <button type="submit" class="upload-button">อัปโหลดและสร้าง PDF</button>
        </form>
    </div>

</body>

</html>