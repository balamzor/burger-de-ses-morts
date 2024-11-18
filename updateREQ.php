<?php
require '../db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = DataBase::connect();
    $query = 'SELECT * FROM items
    WHERE id = :id';
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $_POST['id']]);
    $current_product = $stmt->fetch(PDO::FETCH_ASSOC);
    $query = 'UPDATE items SET ';
    $finalExecute = [];
    $flag = false;
    foreach ($_POST as $key => $value) {
        // if ($key == 'name' && !empty($value) && $value != $current_product['name']) {
        //     $nom = htmlspecialchars($value);
        //     $query .= ' name = "' . $nom . '"';
        //     array_push($finalExecute, $nom);
        // }
        // if ($key == 'description' && !empty($value) && $value != $current_product['description']) {
        //     $description = htmlspecialchars($value);
        //     $query .= ' description = "' . $description . '"';
        //     array_push($finalExecute, $description);
        // }
        // if ($key == 'price' && !empty($value) && $value != $current_product['price']) {
        //     $price = htmlspecialchars($value);
        //     $query .= ' price = "' . $price . '"';
        //     array_push($finalExecute, $price);
        // }
        // if ($key == 'category' && !empty($value) && $value != $current_product['category']) {
        //     $category = htmlspecialchars($value);
        //     $query .= ' category = "' . $category . '"';
        //     array_push($finalExecute, $category);
        // }
        if (!empty($value) && $value != $current_product[$key]) {
            $val = htmlspecialchars($value);
            // if ($key == 'price') {
            //     $query .= ' ' . $key . ' = ' . floatval($val);
            // } else if ($key == 'category') {
            //     $query .= ' ' . $key . ' = ' . (int) $val;
            // } else {
            //     $query .= ' ' . $key . ' = "' . $val . '"';
            // }
            if ($key == 'price' || $key == 'category') {
                $query .= ' ' . $key . ' = ' . $val;
            } else {
                $query .= '' . $key . ' = "' . $val . '"';
            }
            $flag = true;
            // array_push($finalExecute, $category);
        }
    }

    if (isset($_FILES['image']) && $_FILES['image']['size'] <= 1024 * 1024 && $_FILES['image']['name'] != $current_product['image']) {
        $extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowedExt = ['jpg', 'png', 'jpeg'];
        if (in_array($extention, $allowedExt)) {
            $newName = uniqid('img') . '.' . $extention;
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $newName);
            $query .= 'image = "' . $newName . '" ';
            // array_push($finalExecute, $newName);
        }
    }
    $id = (int) $_POST['id'];
    $query .= ' WHERE id = ' . $id;
    var_dump($query, $flag);
    // array_push($finalExecute, $id);
    // $query = 'UPDATE items SET (' . $finalQuery . ') WHERE id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute();
    DataBase::disconnect();

    // if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category'])) {
    //     $img = false;
    //     if (isset($_FILES['image']) && $_FILES['image']['size'] <= 1024 * 1024) {

    //         $extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    //         $allowedExt = ['jpg', 'png', 'jpeg'];
    //         $img = true;
    //         if (in_array($extention, $allowedExt)) {
    //             $newName = uniqid('img') . '.' . $extention;
    //             move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $newName);
    //         }
    //     }
    //     $nom = htmlspecialchars($_POST['name']);
    //     $description = htmlspecialchars($_POST['description']);
    //     $price = htmlspecialchars($_POST['price']);
    //     $category = htmlspecialchars($_POST['category']);
    //     $id = is_numeric($_POST['id']);
    //     $db = DataBase::connect();
    //     if ($img) {
    //         $query = "UPDATE items SET (name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?)";
    //         $stmt = $db->prepare($query);
    //         $stmt->execute([$nom, $description, $price, $category, $newName, $id]);
    //     } else {
    //         $query = "UPDATE items SET (name = ?, description = ?, price = ?, category = ? WHERE id = ?)";
    //         $stmt = $db->prepare($query);
    //         $stmt->execute([$nom, $description, $price, $category, $id]);
    //     }
    //     $stmt = $db->prepare($query);
    //     $stmt->execute([$nom, $description, $price, $category, $newName, $id]);
    //     DataBase::disconnect();
    //     header('Location: index.php');
    // }
}