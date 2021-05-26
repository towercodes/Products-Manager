<html>
<head>
<title>Product List</title>
</head>
<body>
<h1>Add Products</h1>
<form action = "" method = "get" accept-charset="UTF-8">
Product: <br> <input type = "text" name = "product" required> <br>
Quantity: <br> <input type = "text" name = "quantity" required> <br>
Price per item: <br> <input type = "text" name = "price" required> <br>
<input type = "hidden" name = "time" value = "<?php echo date('d/M/Y h:i:s'); ?>">
<input type = "submit">
</form>
<hr>

<?php
error_reporting(0);

if (!empty($_GET)) {
//retrieve existing records in order to generate product id by adding 1 to the last entered id
    $products = file_get_contents("products.xml");
    $data = simplexml_load_string($products);
foreach ($data as $val) {
//get a list of all id's as an array
$productId[] = $val->id;
}
//create new id
$id = $productId[0] +1; //this is because the last product entered is the first record in the xml file

//retrieve form fields
    $item = $_GET['product'];
    $amount = $_GET['quantity'];
    $price = $_GET['price'];
    $time = $_GET['time'];

//prepare data for xml
    $xml = "<store>
    <products>
<id>$id</id>
    <item>$item</item>
    <amount>$amount</amount>
    <price>$price</price>
    <time>$time</time>
    </products>";

//retrieve all the xml data already in the xml file
//this will allow us to replace the root tag in order to avoid duplications of root with every insertion
    $insert = fopen("products.xml", "r");
    $store = fread($insert, filesize("products.xml"));
    $replace = str_replace("<store>", "", $store);
    fclose($insert);

//insert new data into the xml file
    file_put_contents("products.xml", $xml);
//re enter the old data
    file_put_contents("products.xml", $replace, FILE_APPEND);
}


//display records
    echo "<h1>Product list</h1>
    <table>
    <tr> <th>Product Name</th> <th>Quantity In Stock</th> <th>Price Per Item</th> <th>Datetime Submited</th> <th> Total Product Price </th> </tr>";

    $products = file_get_contents("products.xml");
    $data = simplexml_load_string($products);
foreach ($data as $value) {

//calculate the total
$total = $value->amount * $value->price;

    echo "<tr>
    <td> $value->item </td> <td> $value->amount </td> <td> $value->price </td> <td> $value->time</td> <td>$total </td>
    </tr>";

//create arrays of results so we can generate grand totals using array_sum
$amounts[] = (int) $value->amount;
$prices[] = (int) $value->price;
$totals[] = $total;
}
//generate grand totals
$totalAmount = array_sum($amounts);
$totalPrice = array_sum($prices);
$grandTotal = array_sum($totals);

    echo " <tr> <td>Total</td>
<td>$totalAmount</td> <td>$totalPrice</td> <td> </td> <td>$grandTotal </td>
</tr> </table>"; 

    $date = date(Y);
    echo "&copy copyright $date  Tawanda Mutasa";



//end of program