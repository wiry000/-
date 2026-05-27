<?php
$city = $_GET['city'];
$town = $_GET['town'];
$url = 'https://data.nhi.gov.tw/resource/mask/maskdata.csv';
$maskData = [];
if (($handle = fopen($url, 'r')) !== false) {
    $row = 0;
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        if ($row === 0) {
            $headers = $data;
        } else {
            $rowList = [];
            foreach ($data as $index => $value) {
                $rowList[$headers[$index]] = $value;
            }
            $maskData[] = $rowList;
        }
        $row++;
    }
    fclose($handle);
}
$filteredData = [];
foreach ($maskData as $item) {
    if (str_contains($item['醫事機構地址'], $city . $town)) {
        $filteredData[] = [
            'med' => $item['醫事機構地址'],
            'med_address' => $item['醫事機構名稱'],
            'adultMask' => $item['成人口罩剩餘數'],
            'childMask' => $item['兒童口罩剩餘數']
        ];
    }
}
header('Content-Type: application/json');
echo json_encode($filteredData);