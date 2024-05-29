<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
}