<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$url = 'https://aliexpress.ru/item/1005007641037367.html';
$cookieFile = 'cookie.txt';

// Sử dụng cURL để lấy dữ liệu
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept-Language: en-US,en;q=0.9',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
    'Cookie: ' . file_get_contents($cookieFile)
]);

$product = curl_exec($ch);
if ($product === false) {
    echo 'Lỗi server: ' . curl_error($ch);
} else {
    $dom = new DOMDocument();
    @$dom->loadHTML($product);
    $xpath = new DOMXPath($dom);

    // Tối ưu hóa các truy vấn XPath
    $titles = $xpath->query('//*[@id="__aer_root__"]/div/div[7]/div[2]/div[1]/div/div/div[2]/h1');
    foreach ($titles as $title) {
        echo "<h2>" . $title->nodeValue . "</h2>";
    }

    $colors = $xpath->query('//*[@id="__aer_root__"]//span[contains(@class, "SnowSku_SkuPropertyItem__value__1xt6v")]');
    foreach ($colors as $color) {
        echo "<h2>" . $color->nodeValue . "</h2>";
    }


    $sizes = $xpath->query('//li[contains(@class, "SnowSku_SkuPropertyItem__optionWrap__1xt6v")]/span[2]');
    foreach ($sizes as $size) {
        echo $size->nodeValue . "<br>";
    }

    $prices = $xpath->query('//*[@id="__aer_root__"]/div/div[7]/div[2]/div[4]/div/div[1]/div[1]/div/div[1]/div/div/div[1]');
    foreach ($prices as $price) {
        echo "<h2>" . $price->nodeValue . "</h2>";
    }

    $imageClass = $xpath->query('//div[contains(@class, "SnowProductGallery_SnowProductGallery__previews__1ryr7")]');
    if ($imageClass->length > 0) {
        $imgItems = $xpath->query('.//div[contains(@class, "SnowProductGallery_SnowProductGallery__previewItem__1ryr7")]', $imageClass->item(0));
        foreach ($imgItems as $item) {
            $imgTag = $xpath->query('.//img', $item);
            if ($imgTag->length > 0) {
                echo $imgTag->item(0)->getAttribute('src') . "<br>";
            }
        }
    }

    // echo $product;
}

curl_close($ch);
?>

