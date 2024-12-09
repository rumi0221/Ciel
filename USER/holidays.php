<?php
function getHoliday($year) {

    $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/JP";

    try {

        $response = file_get_contents($url);

        if ($response === false) {

            return [];

        }

        $data = json_decode($response, true);

        if (!$data) {

            return [];

        }

        // APIのレスポンスを既存の形式に変換

        $holidays = [];

        foreach ($data as $holiday) {

            // date形式を 'Y-m-d' のままキーとして使用

            $dateKey = $holiday['date'];   // すでに 'YYYY-MM-DD' 形式

            $localName = $holiday['localName'];

            $holidays[$dateKey] = $localName;

        }

        return $holidays;

    } catch (Exception $e) {

        error_log('Holiday API Error: ' . $e->getMessage());

        return [];

    }

}

?>
 