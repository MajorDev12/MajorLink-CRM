<?php
require_once "../views/header.php";
require_once  '../database/pdo.php';
// JSON data
$jsonData = json_decode(file_get_contents("../assets/countryData.json"), true);
$connect = connectToDatabase($host, $dbname, $username, $password);

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

$country = get_setup($connect);

var_dump($country);
?>

<script>
	// PHP to JS: Pass JSON data to JavaScript
	// const jsonData = <?php //echo json_encode($jsonData); 
						?>;

	// const removedEmptyCurrency = jsonData.filter(country => country.currencies.name === undefined);

	// // Extract only the required data (name, code, currencies, region, flags, phoneCode)
	// const filteredData = removedOceania.map(country => ({
	// 	name: country.name.common,
	// 	code: country.cca2,
	// 	currencies: country.currencies,
	// 	timezones: country.region + '/' + country.capital,
	// 	flags: country.flags.png,
	// 	phoneCode: (country.idd && country.idd.suffixes) ? country.idd.suffixes.map(suffix => country.idd.root + suffix) : []
	// }));






	// Rearrange the data alphabetically by name
	// const sortedData = filteredData.sort((a, b) => a.name.localeCompare(b.name));

	// const modifiedData = sortedData.map(country => {
	// 	// Change "Americas" to "America"
	// 	country.timezones = country.timezones.replace(/Americas/g, 'America');
	// 	return country;
	// });


	// // Convert the sorted data to JSON format
	// const sortedJson = JSON.stringify(modifiedData, null, 2);

	// // Display the result
	// console.log(sortedJson);
</script>