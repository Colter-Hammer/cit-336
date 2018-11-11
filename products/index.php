<?php

// Products Controller

$action = filter_input(INPUT_POST, 'action');
if ($action == null) {
    $action = filter_input(INPUT_GET, 'action');
}

// Create or access a Session
session_start();

// Get the database connection file
require_once '../library/connections.php';
// Get the acme model for use as needed
require_once '../model/acme-model.php';
// Get the products model
require_once '../model/products-model.php';
// Get functions
require_once '../library/functions.php';

// Get array of categories from acme-model
$categories = getCategories();

// Build a navigation bar using the $categories array
$navList = navBar($categories);

// // Build a dropdown using the $categories array

// $catList = '<select name="categoryId" id="categoryId">';
// foreach ($categories as $category) {
//     // use urlencode to get rid of any spaces.
//     $catList .= '<option value="' . $category['categoryId'] . '">' . urlencode($category['categoryName']) . '</option>';
// }

// $catList .= '</select>';

// Deliver views

switch ($action) {
    case 'category':

        // Filter and store the data
        $catName = filter_input(INPUT_POST, 'catName', FILTER_SANITIZE_STRING);

        // Check for missing data
        if (empty($catName)) {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include '../view/category.php';
            exit;
        }

        // Send the data to the model
        $catOutcome = addCategory($catName);

        // Check and report the result
        if ($catOutcome === 1) {
            header('Location: http://localhost/acme/products/index.php');
            exit;
        } else {
            $message = "<p>$catName not successfully added. Please try again.</p>";
            include '../view/category.php';
            exit;
        }

        break;

    case 'product':

        // Filter and store the data
        $invName = filter_input(INPUT_POST, 'invName', FILTER_SANITIZE_STRING);
        $invDescription = filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_STRING);
        $invImage = filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_STRING);
        $invThumbnail = filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_STRING);
        $invPrice = filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $invStock = filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_INT);
        $invSize = filter_input(INPUT_POST, 'invSize', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $invWeight = filter_input(INPUT_POST, 'invWeight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $invLocation = filter_input(INPUT_POST, 'invLocation', FILTER_SANITIZE_STRING);
        $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        $invVendor = filter_input(INPUT_POST, 'invVendor', FILTER_SANITIZE_STRING);
        $invStyle = filter_input(INPUT_POST, 'invStyle', FILTER_SANITIZE_STRING);

        $invPrice = checkValue($invPrice);

        // Check for missing data
        if (empty($invName) || empty($invDescription) || empty($invImage) || empty($invThumbnail) || empty($invPrice) || empty($invStock) || empty($invSize) || empty($invWeight) || empty($invLocation) || empty($categoryId) || empty($invVendor) || empty($invStyle)) {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include '../view/product.php';
            exit;
        }

        // Send the data to the model
        $prodOutcome = addProduct($invName, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invSize, $invWeight, $invLocation, $categoryId, $invVendor, $invStyle);

        // Check and report the result
        if ($prodOutcome === 1) {
            $message = "<p>$invName was added correctly. That's awesome!</p>";
            include '../view/product.php';

            exit;
        } else {
            $message = "<p>$invName wasn't added correctly. Please try again.</p>";
            include '../view/product.php';

            exit;
        }
        break;
    case 'addprod':
        include '../view/product.php';
        break;
    case 'addcat':
        include '../view/category.php';
        break;

    default:
        include '../view/product-management.php';
        break;
}
