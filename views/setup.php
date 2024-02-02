<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "../views/header.php"; ?>
<?php require_once "style.config.php"; ?>
<?php

// JSON data
$countries = json_decode(file_get_contents("../assets/countryData.json"), true);

?>

<style>
    p {
        color: var(--dark-grey);
    }

    .step {
        display: none;
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }

    .step.active {
        display: block;
    }

    .step .steptext {
        color: var(--dark-grey);
        font-weight: 100;
    }

    .step .content {
        position: absolute;
        top: 0;
        width: 90%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 10%;
        text-align: center;
    }

    .nextbtn {
        position: absolute;
        bottom: 10%;
        right: 5%;
    }

    .backbtn {
        position: absolute;
        bottom: 10%;
        left: 5%;
        background-color: var(--grey);
        color: var(--dark-grey);
        border: none;
        outline: none;
    }

    h4 {
        color: var(--dark-grey);
    }
</style>

<div class="content">

    <div class="step active">
        <h3 class="m-4 steptext">Step 1 Of 4</h3>

        <div class="content">
            <h3 class="text-primary">To make your experience smoothless, please fill out some basics to set up the system.</h3>
            <h6 class="mt-5">What country do you reside in?</h6>
            <select name="countryName" id="countryName" class="form-select mt-2" onchange="updateData()">
                <option value="" selected disabled>Choose...</option>
                <?php
                foreach ($countries as $country) {
                    echo '<option value="' . $country['name'] . '">' . $country['name'] . '</option>';
                }
                ?>
            </select>
            <button type="button" class="btn btn-primary nextbtn" onclick="nextStep()">Next</button>
        </div>
    </div>

    <div class="step">
        <h3 class="m-4 steptext">Step 2 Of 4</h3>

        <div class="content">
            <h4 class="">The time is really crucial in this system.Emails, sms, Invoices and even active duration for clients is based on time.Choose wisely</h4>
            <h6 class="mt-5">Choose Your Prefered Timezone</h6>
            <select name="timezone" id="timezone" class="form-select mt-2"></select>

            <button type="button" class="btn btn-secondary backbtn" onclick="prevStep()">Back</button>
            <button type="button" class="btn btn-primary nextbtn" onclick="nextStep()">Next</button>
        </div>
    </div>

    <div class="step">
        <h3 class="m-4 steptext">Step 3 Of 4</h3>

        <div class="content">
            <h6 class="mt-5">Choose Your Prefered currencies</h6>
            <select name="currency" id="currency" class="form-select mt-2">
            </select>
            <button type="button" class="btn btn-secondary backbtn" onclick="prevStep()">Back</button>
            <button type="button" class="btn btn-primary nextbtn" onclick="nextStep()">Next</button>
        </div>
    </div>

    <div class="step">
        <h3 class="m-4 steptext">Step 4 Of 4</h3>

        <div class="content">
            <h6 class="mt-5">Choose Your Prefered Phone Code</h6>
            <select name="phoneCode" id="phoneCode" class="form-select mt-2"></select>
            <button type="button" class="btn btn-secondary backbtn" onclick="prevStep()">Back</button>
            <button type="button" class="btn btn-primary nextbtn" onclick="nextStep()">Finish</button>
        </div>
    </div>

    <div class="step"></div>

</div>

<script>
    let currentStep = 0;
    const totalSteps = document.querySelectorAll('.step').length;
    const bucketList = {
        name: null,
        timezone: null,
        currency: null,
        currencySymbol: null,
        currencyCode: null,
        phoneCode: null
    };

    function nextStep() {
        if (currentStep < totalSteps - 1) {
            const step = document.querySelector('.step.active');
            step.style.opacity = 0; // Fade out the current step
            // Disable the next button and set a delay
            const nextbtn = document.querySelector(".nextbtn");
            nextbtn.disabled = true;
            nextbtn.style.display = "none";

            // Wait for the fade-out animation to complete before moving to the next step
            setTimeout(() => {
                step.classList.remove('active');
                step.style.opacity = 1; // Reset opacity for future transitions

                currentStep++;
                const nextStepElement = document.querySelectorAll('.step')[currentStep];
                nextStepElement.style.opacity = 0.5; // Set initial opacity for the next step
                nextStepElement.classList.add('active');

                // Trigger a reflow to ensure the fade-in animation works
                void nextStepElement.offsetWidth;

                nextStepElement.style.opacity = 1; // Fade in the next step
            }, 600);

            // Enable the next button after a delay
            // setTimeout(() => {
            //     nextbtn.disabled = false;
            // }, 3000);

            // Perform additional tasks based on the current step
            switch (currentStep) {
                case 0:
                    // Add selected data to the bucket list
                    bucketList.name = document.getElementById('countryName').value;
                    break;
                case 1:
                    // Add selected data to the bucket list
                    bucketList.timezone = document.getElementById('timezone').value;
                    break;
                case 2:
                    // Add selected data to the bucket list
                    bucketList.currency = document.getElementById('currency').value;
                    bucketList.currencySymbol = document.getElementById('currencySymbol').value;
                    bucketList.currencyCode = document.getElementById('currencyCode').value;
                    // console.log(bucketList.currencyCode)
                    break;
                case 3:
                    // Add selected data to the bucket list
                    bucketList.phoneCode = document.getElementById('phoneCode').value;
                    saveData(bucketList.name, bucketList.timezone, bucketList.currency, bucketList.currencySymbol, bucketList.currencyCode, bucketList.phoneCode);
                    break;
                default:
                    break;
            }
        }
    }


    function prevStep() {
        if (currentStep > 0) {
            document.querySelector('.step.active').classList.remove('active');
            currentStep--;
            document.querySelectorAll('.step')[currentStep].classList.add('active');
        }
    }


    function updateData() {
        const selectedCountry = document.getElementById('countryName').value;
        const timezonesSelect = document.getElementById('timezone');
        const currenciesSelect = document.getElementById('currency');
        const phoneCodeSelect = document.getElementById('phoneCode');

        // Reset select options
        timezonesSelect.innerHTML = '<option value="" selected disabled>Choose...</option>';
        currenciesSelect.innerHTML = '<option value="" selected disabled>Choose...</option>';
        phoneCodeSelect.innerHTML = '<option value="" selected disabled>Choose...</option>';

        // Find the selected country in the dataset
        const selectedCountryData = <?php echo json_encode($countries); ?>.find(country => country.name === selectedCountry);

        if (selectedCountryData) {
            // Populate timezones
            selectedCountryData.timezones.forEach(timezone => {
                const option = document.createElement('option');
                option.value = timezone;
                option.text = timezone;
                timezonesSelect.add(option);
            });


            // Populate currencies
            for (const currencyCode in selectedCountryData.currencies) {
                const currencyDetails = selectedCountryData.currencies[currencyCode];
                // Create a hidden input element for currency code
                const hiddenInputCode = document.createElement('input');
                hiddenInputCode.type = 'hidden';
                hiddenInputCode.id = 'currencyCode';
                hiddenInputCode.value = currencyDetails.code;

                // Append the hidden input for currency code to the form or container (adjust accordingly)
                currenciesSelect.parentNode.appendChild(hiddenInputCode);

                // Create a hidden input element for currency name
                const hiddenInputName = document.createElement('input');
                hiddenInputName.type = 'hidden';
                hiddenInputName.id = 'currencyName'; // Note: You might want to use a unique ID for each currency name
                hiddenInputName.value = currencyDetails.name;

                // Append the hidden input for currency name to the form or container (adjust accordingly)
                currenciesSelect.parentNode.appendChild(hiddenInputName);



                // Create a hidden input element for currency symbol
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'currencySymbol';
                hiddenInput.value = '';

                // Set the value of the hidden input to the currency symbol
                hiddenInput.value = currencyDetails.symbol;

                // Append the hidden input to the form or container (adjust accordingly)
                currenciesSelect.parentNode.appendChild(hiddenInput);

                // Create an option element for the visible dropdown
                const option = document.createElement('option');
                option.value = currencyDetails.name;
                option.text = `${currencyDetails.name} - ${currencyDetails.symbol}`;

                // Append the option to the visible dropdown
                currenciesSelect.add(option);
            }


            // Populate phone codes
            selectedCountryData.phoneCode.forEach(phoneCode => {
                const option = document.createElement('option');
                option.value = phoneCode;
                option.id = 'phoneCode';
                option.text = phoneCode;
                phoneCodeSelect.add(option);
            });

        }

    }




    // Send selected data to PHP file
    function saveData(name, timezone, currency, symbol, code, phonecode) {
        document.getElementById("loading").display = "flex";
        const formData = new FormData();
        formData.append('country', name);
        formData.append('timezone', timezone);
        formData.append('currency', currency);
        formData.append('symbol', symbol);
        formData.append('code', code);
        formData.append('phonecode', phonecode);


        fetch('../controllers/setup_contr.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.href = "index.php";
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>