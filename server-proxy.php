<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json; charset=UTF-8");

    $url = 'https://aliexpress.ru/item/1005007641037367.html';
    $cookieFile = 'cookie.txt';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 5);
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
        echo json_encode(['error' => 'Lỗi server: ' . curl_error($ch)]);
        exit;
    }

    libxml_use_internal_errors(true); 
    $dom = new DOMDocument();
    $dom->loadHTML($product);
    libxml_clear_errors(); 

    $xpath = new DOMXPath($dom);

    $productData = [
        'title' => '',
        'colors' => [],
        'sizes' => [],
        'price' => '',
        'images' => []
    ];

    $titles = $xpath->query('//*[@id="__aer_root__"]/div/div[7]/div[2]/div[1]/div/div/div[2]/h1');
    $colors = $xpath->query('//*[@id="__aer_root__"]/div/div[7]/div[2]/div[3]/div/div/div/div[1]/div/span[2]');
    $sizes = $xpath->query('//span[contains(@class, "SnowSku_SkuPropertyItem__optionText__1xt6v")]');
    $prices = $xpath->query('//*[@id="__aer_root__"]/div/div[7]/div[2]/div[4]/div/div[1]/div[1]/div/div[1]/div/div/div[1]');
    $imageClass = $xpath->query('//div[contains(@class, "SnowProductGallery_SnowProductGallery__previews__1ryr7")]');

    if ($titles->length > 0) {
        $productData['title'] = trim($titles->item(0)->nodeValue);
    }

    foreach ($colors as $color) {
        $productData['colors'][] = trim($color->nodeValue);
    }
    
    foreach ($sizes as $size) {
        $productData['sizes'][] = $size->nodeValue;
    }

    if ($prices->length > 0) {
        $productData['price'] = trim($prices->item(0)->nodeValue);
    }

    if ($imageClass->length > 0) {
        $imgItems = $xpath->query('.//div[contains(@class, "SnowProductGallery_SnowProductGallery__previewItem__1ryr7")]', $imageClass->item(0));
        foreach ($imgItems as $item) {
            $imgTag = $xpath->query('.//img', $item);
            if ($imgTag->length > 0) {
                $productData['images'][] = $imgTag->item(0)->getAttribute('src');
            }
        }
    }

    echo json_encode($productData);
    curl_close($ch);
?>
