<?php
include 'admin.php';

$admin = new Admin();
$admin->sendMonthlyPaymentNotifications();
