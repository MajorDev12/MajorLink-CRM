<?php

function CompanySetup($CompanyName, $Email, $PhoneNumber, $Country, $Address, $Website, $Zipcode, $City, $TimeZone, $currencyName, $currencySymbol, $currencyCode, $PhoneCode, $LogoURL, $connect)
{
    try {
        $query = "INSERT INTO companysettings (
            CompanyName,
            Email,
            PhoneNumber,
            Country,
            Address,
            Website,
            Zipcode,
            City,
            TimeZone,
            currencyName,
            currencySymbol,
            currencyCode,
            PhoneCode,
            LogoURL
        ) VALUES (
            :CompanyName,
            :Email,
            :PhoneNumber,
            :Country,
            :Address,
            :Website,
            :Zipcode,
            :City,
            :TimeZone,
            :currencyName,
            :currencySymbol,
            :currencyCode,
            :PhoneCode,
            :LogoURL
        )";

        $statement = $connect->prepare($query);
        $statement->bindParam(':CompanyName', $CompanyName);
        $statement->bindParam(':Email', $Email);
        $statement->bindParam(':PhoneNumber', $PhoneNumber);
        $statement->bindParam(':Country', $Country);
        $statement->bindParam(':Address', $Address);
        $statement->bindParam(':Website', $Website);
        $statement->bindParam(':Zipcode', $Zipcode);
        $statement->bindParam(':City', $City);
        $statement->bindParam(':TimeZone', $TimeZone);
        $statement->bindParam(':currencyName', $currencyName);
        $statement->bindParam(':currencySymbol', $currencySymbol);
        $statement->bindParam(':currencyCode', $currencyCode);
        $statement->bindParam(':PhoneCode', $PhoneCode);
        $statement->bindParam(':LogoURL', $LogoURL);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}










function set_setup($countryName, $countryTimezone, $currencyName, $currencySymbol, $currencyCode, $phoneCode, $settingId, $connect)
{
    try {
        $query = "UPDATE companysettings SET 
        Country = :countryName,
        TimeZone = :countryTimezone,
        CurrencyName = :currencyName,
        CurrencySymbol = :currencySymbol,
        currencyCode = :currencyCode,
        PhoneCode = :phoneCode
        WHERE SettingID = :settingId";

        $statement = $connect->prepare($query);
        $statement->bindParam(':countryName', $countryName);
        $statement->bindParam(':countryTimezone', $countryTimezone);
        $statement->bindParam(':currencyName', $currencyName);
        $statement->bindParam(':currencySymbol', $currencySymbol);
        $statement->bindParam(':currencyCode', $currencyCode);
        $statement->bindParam(':phoneCode', $phoneCode);
        $statement->bindParam(':settingId', $settingId);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function updateCompanyDetails($settingID, $nameInput, $emailInput, $websiteInput, $addressInput, $cityInput, $zipcodeInput, $numberInput, $connect)
{
    try {
        $query = "UPDATE companysettings SET 
        CompanyName = :nameInput,
        Email = :emailInput,
        Website = :websiteInput,
        Address = :addressInput,
        City = :cityInput,
        Zipcode = :zipcodeInput,
        PhoneNumber = :numberInput
        WHERE SettingID = :settingID";

        $statement = $connect->prepare($query);
        $statement->bindParam(':nameInput', $nameInput);
        $statement->bindParam(':emailInput', $emailInput);
        $statement->bindParam(':websiteInput', $websiteInput);
        $statement->bindParam(':addressInput', $addressInput);
        $statement->bindParam(':cityInput', $cityInput);
        $statement->bindParam(':zipcodeInput', $zipcodeInput);
        $statement->bindParam(':numberInput', $numberInput);
        $statement->bindParam(':settingID', $settingID);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function updateSystemSettings($settingID, $countrySelect, $timezoneSelect, $phoneCodeSelect, $currencySelect, $currencyCode, $currencySymbol, $connect)
{
    try {
        $query = "UPDATE companysettings SET 
        Country = :countrySelect,
        TimeZone = :timezoneSelect,
        PhoneCode = :phoneCodeSelect,
        CurrencyName = :currencySelect,
        CurrencyCode = :currencyCode,
        CurrencySymbol = :currencySymbol
        WHERE SettingID = :settingID";

        $statement = $connect->prepare($query);
        $statement->bindParam(':countrySelect', $countrySelect);
        $statement->bindParam(':timezoneSelect', $timezoneSelect);
        $statement->bindParam(':phoneCodeSelect', $phoneCodeSelect);
        $statement->bindParam(':currencySelect', $currencySelect);
        $statement->bindParam(':currencyCode', $currencyCode);
        $statement->bindParam(':currencySymbol', $currencySymbol);
        $statement->bindParam(':settingID', $settingID);
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


function update_currency($connect, $name, $symbol, $code, $settingId)
{
    try {
        $query = "UPDATE companysettings SET 
        CurrencyName = :name,
        CurrencySymbol = :symbol,
        CurrencyCode = :code
        WHERE SettingID = :settingId";

        $statement = $connect->prepare($query);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':symbol', $symbol);
        $statement->bindParam(':code', $code);
        $statement->bindParam(':settingId', $settingId);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function generateInvoiceNumber()
{
    $prefix = "INV";
    $randomDigits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    return $prefix . $randomDigits;
}
