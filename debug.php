<?php

// ตั้งค่าให้แสดง Error ทั้งหมด จะได้เห็นข้อมูลมากที่สุด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debugging Report</h1>";
echo "--------------------------------<br>";

// 1. ตรวจสอบเวอร์ชัน PHP ที่ Apache กำลังใช้งานอยู่
echo "<h2>PHP Version (Apache): " . phpversion() . "</h2>";
echo "--------------------------------<br>";

// 2. เรียกใช้ Autoloader
require 'vendor/autoload.php';
echo "Autoloader included successfully.<br>";
echo "--------------------------------<br>";


// 3. ตรวจสอบคลาส QrCode
$className = 'Endroid\QrCode\QrCode';
echo "<h2>Inspecting Class: {$className}</h2>";

if (class_exists($className) || interface_exists($className)) {
    echo "<b>Status:</b> Class/Interface '{$className}' FOUND.<br><br>";

    // ใช้ Reflection เพื่อส่องดูข้อมูลภายในคลาส
    $reflector = new ReflectionClass($className);

    // มันเป็น Class หรือ Interface กันแน่?
    echo "<b>Type:</b> " . ($reflector->isInterface() ? 'Interface' : 'Class') . "<br>";

    // คลาสนี้ถูกโหลดมาจากไฟล์ไหน?
    echo "<b>File Location:</b> " . $reflector->getFileName() . "<br><br>";

    // แสดงเมธอดทั้งหมดที่มีในคลาสนี้
    echo "<h3>Available Methods:</h3>";
    echo "<pre>"; // ใช้ <pre> เพื่อให้อ่านง่าย
    print_r($reflector->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_STATIC));
    echo "</pre>";
} else {
    echo "<b>Status:</b> <span style='color:red;'>FATAL - Class/Interface '{$className}' NOT FOUND.</span><br>";
}
