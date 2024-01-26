<?php
// $settingId = 1;

function set_setup($countryName, $countryTimezone, $currencyName, $currencySymbol, $phoneCode, $settingId, $connect)
{
    try {
        $query = "UPDATE companysettings SET 
        Country = :countryName,
        TimeZone = :countryTimezone,
        CurrencyName = :currencyName,
        CurrencySymbol = :currencySymbol,
        PhoneCode = :phoneCode
        WHERE SettingID = :settingId";

        $statement = $connect->prepare($query);
        $statement->bindParam(':countryName', $countryName);
        $statement->bindParam(':countryTimezone', $countryTimezone);
        $statement->bindParam(':currencyName', $currencyName);
        $statement->bindParam(':currencySymbol', $currencySymbol);
        $statement->bindParam(':phoneCode', $phoneCode);
        $statement->bindParam(':settingId', $settingId);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function get_setup($connect)
{
    $query = "SELECT * FROM companysettings";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    // Check if the 'Country' is null or not
    if ($result[0]["Country"] === null) {
        return true;
    } else {
        return false;
    }
}

function get_Settings($connect)
{
    $query = "SELECT * FROM companysettings";

    $statement = $connect->prepare($query);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function update_currency($connect, $name, $symbol, $settingId)
{
    try {
        $query = "UPDATE companysettings SET 
        CurrencyName = :name,
        CurrencySymbol = :symbol
        WHERE SettingID = :settingId";

        $statement = $connect->prepare($query);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':symbol', $symbol);
        $statement->bindParam(':settingId', $settingId);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
