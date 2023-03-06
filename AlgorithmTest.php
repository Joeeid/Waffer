<?php

/*
markets: [
{
id: 1,
products: [
apple: 5,
orange: 6,
banana: 7
],
deliveryCharge: 5,
},
{
id: 2,
products: [
apple: 100,
orange: 200,
banana: 300
],
deliveryCharge: 10,
}
]
*/

class Market
{
    public $id;
    public $products;
    public $deliveryCharge;

    function __construct($id, $products, $deliveryCharge)
    {
        $this->id = $id;
        $this->products = $products;
        $this->deliveryCharge = $deliveryCharge;
    }
}

class Product
{
    public $name;
    public $price;

    function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
}

class Combination
{

    public $productId;
    public $marketId;

    public $price;


    function __construct($productId, $marketId, $price)
    {
        $this->productId = $productId;
        $this->marketId = $marketId;
        $this->price = $price;
    }
}

function getCheapestCombination($targetProducts, $markets)
{
    $pricesList = createPriceList($targetProducts, $markets);
    $prices = $pricesList["prices"];
    $deliveryCharges = $pricesList["deliveryCharges"];
    if (sizeof($prices) != sizeof($targetProducts)) {
        return [];
    }
    $combinations = createCombinationFromPricesList(array_values($prices), $deliveryCharges, 0);
    usort($combinations, function ($a, $b) {
        return $a['finalPrice'] <=> $b['finalPrice'];
    });
    return $combinations;
}

function createCombinationFromPricesList($prices,$deliveryCharges, $index, $previousSelection = []) {
    if ($index >= sizeof($prices)) {
        return [];
    }
    $results = [];
    if ($index == sizeof($prices) - 1){
        for ($i = 0; $i < sizeof($prices[$index]); $i++){
            $temp = $previousSelection;
            array_push($temp, $prices[$index][$i]);
            array_push($results, calculateLastPrice($temp, $deliveryCharges));
        }
        return $results;
    }
    for ($i = 0; $i < sizeof($prices[$index]); $i++){
        $temp = $previousSelection;
        array_push($temp, $prices[$index][$i]);
        $results = array_merge($results, createCombinationFromPricesList($prices, $deliveryCharges, $index + 1, $temp));

    }
    return $results;
}

function calculateLastPrice($combinations, $deliveryCharges) {
    $temp = $combinations;
    $finalPrice = 0;
    $calculatedDeliveryCharges = [];
    foreach($combinations as $combination) {
        $finalPrice += $combination->price;
        if (!(in_array($combination->marketId, $calculatedDeliveryCharges))) {
            array_push($calculatedDeliveryCharges, $combination->marketId);
            $finalPrice += $deliveryCharges[$combination->marketId];
        }
    }
    $temp["finalPrice"] = $finalPrice;
    return $temp;
}


function createPriceList($targetProducts, $markets) {
    $prices = [];
    $deliveryCharges = [];
    foreach($targetProducts as $targetProduct) {
        $prices[$targetProduct] = [];
    }
    foreach($markets as $market) {
        $deliveryCharges[$market->id] = $market->deliveryCharge;
        foreach($market->products as $product) {
            if (in_array($product->name, $targetProducts)) {
                array_push($prices[$product->name], new Combination($product->name, $market->id,$product->price));
            }
        }
    }
     
    return ["prices" => $prices, "deliveryCharges" => $deliveryCharges];
}

$markets = [
    new Market(
        "Market1",
        [
            new Product("Banana", 5),
            new Product("Apple", 5),
            new Product("Orange", 10)
        ],
        5
    ),
    new Market(
        "Market2",
        [
            new Product("Banana", 1),
            new Product("Apple", 5),
            new Product("Orange", 15)
        ],
        5
    )
];

echo json_encode(getCheapestCombination(["Banana", "Orange"], $markets), JSON_PRETTY_PRINT);

?>