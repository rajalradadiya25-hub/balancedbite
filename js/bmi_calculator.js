document.addEventListener('DOMContentLoaded', function() {
    const heightValuesContainer = document.getElementById('height-values');
    for (let i = 3; i <= 10; i++) {
        const heightValue = document.createElement('div');
        heightValue.classList.add('height-value');
        heightValue.textContent = `${i}'0"`;
        heightValuesContainer.appendChild(heightValue);
    }

    const weightValuesContainer = document.getElementById('weight-values');
    for (let i = 30; i <= 100; i++) {
        const weightValue = document.createElement('div');
        weightValue.classList.add('weight-value');
        weightValue.textContent = `${i}`; // Remove the "kg" suffix
        weightValuesContainer.appendChild(weightValue);
    }

    const heightValues = document.getElementById('height-values');
    const selectedHeightDisplay = document.getElementById('selected-height');
    const scrollUpButton = document.getElementById('scroll-up');
    const scrollDownButton = document.getElementById('scroll-down');
    let displayInFeet = true;

    const renderHeights = () => {
        heightValues.innerHTML = '';
        if (displayInFeet) {
            for (let feet = 3; feet <= 8; feet++) {
                for (let inches = 0; inches < 12; inches++) {
                    const div = document.createElement('div');
                    div.className = 'height-value';
                    div.textContent = `${feet}'${inches}"`;
                    heightValues.appendChild(div);
                }
            }
        } else {
            for (let inches = 36; inches <= 96; inches++) {
                const div = document.createElement('div');
                div.className = 'height-value';
                div.textContent = `${inches}"`;
                heightValues.appendChild(div);
            }
        }
        heightValues.scrollTop = (heightValues.scrollHeight - heightValues.clientHeight) / 2;
    };

    renderHeights();

    heightValues.addEventListener('click', (event) => {
        if (event.target.classList.contains('height-value')) {
            document.querySelectorAll('.height-value').forEach(height => height.classList.remove('selected'));
            event.target.classList.add('selected');
            selectedHeightDisplay.textContent = event.target.textContent;
        }
    });

    scrollUpButton.addEventListener('click', () => {
        heightValues.scrollTop -= heightValues.clientHeight / 4;
    });

    scrollDownButton.addEventListener('click', () => {
        heightValues.scrollTop += heightValues.clientHeight / 4;
    });

    document.getElementById('btnFeet').addEventListener('click', () => {
        displayInFeet = true;
        renderHeights();
    });

    document.getElementById('btnInches').addEventListener('click', () => {
        displayInFeet = false;
        renderHeights();
    });

    const weightValues = document.getElementById('weight-values');
    const selectedWeightDisplay = document.getElementById('selected-weight');
    const scrollLeftButton = document.getElementById('scroll-left');
    const scrollRightButton = document.getElementById('scroll-right');
    let currentMin = 20;
    let currentMax = 30;
    const step = 10;

    const renderWeights = () => {
        weightValues.innerHTML = '';
        for (let i = currentMin; i <= currentMax; i++) {
            const div = document.createElement('div');
            div.className = 'weight-value';
            div.textContent = `${i}`; // Remove the "kg" suffix
            weightValues.appendChild(div);
        }
        weightValues.scrollLeft = (weightValues.scrollWidth - weightValues.clientWidth) / 2;
    };

    renderWeights();

    weightValues.addEventListener('click', (event) => {
        if (event.target.classList.contains('weight-value')) {
            document.querySelectorAll('.weight-value').forEach(weight => weight.classList.remove('selected'));
            event.target.classList.add('selected');
            selectedWeightDisplay.textContent = event.target.textContent;
        }
    });

    scrollLeftButton.addEventListener('click', () => {
        currentMin -= step;
        currentMax -= step;
        if (currentMin < 20) {
            currentMin = 20;
            currentMax = 30;
        }
        renderWeights();
    });

    scrollRightButton.addEventListener('click', () => {
        currentMin += step;
        currentMax += step;
        renderWeights();
    });

    $(document).ready(function() {
        $('.gender-option').click(function() {
            $('.gender-option').removeClass('selected').addClass('unselected');
            $(this).removeClass('unselected').addClass('selected');

            var selectedGender = $(this).attr('id');
            var imageSrc = selectedGender === 'male' ? 'images/male.jpg' : 'images/female.jpg';
            $('#characterImage').attr('src', imageSrc);
        });
    });

    document.getElementById('btnCalculate').addEventListener('click', function() {
        const selectedHeight = document.getElementById('selected-height').textContent;
        const selectedWeight = document.getElementById('selected-weight').textContent;
        const selectedGender = document.querySelector('.gender-option.selected').id;

        if (selectedHeight !== 'None' && selectedWeight !== 'None') {
            const heightInFeetInches = selectedHeight.split("'");

            let heightInInches;
            // Convert height to inches
            if (heightInFeetInches.length > 1) {
                heightInInches = parseInt(heightInFeetInches[0]) * 12 + parseInt(heightInFeetInches[1].replace('"', ''));
            } else {
                heightInInches = parseInt(heightInFeetInches[0].replace('"', ''));
            }

            // Send data to PHP script for calculation
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'calculate_bmi.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('bmiValue').textContent = xhr.responseText;
                    document.getElementById('bmiResult').style.display = 'block';
                    
                    // Unselect all values
                    document.querySelectorAll('.height-value').forEach(height => height.classList.remove('selected'));
                    selectedHeightDisplay.textContent = 'None';
                    document.querySelectorAll('.weight-value').forEach(weight => weight.classList.remove('selected'));
                    selectedWeightDisplay.textContent = 'None';
                    document.querySelectorAll('.gender-option').forEach(gender => gender.classList.remove('selected'));
                    document.getElementById('male').classList.add('selected');
                }
            };
            xhr.send(`height=${heightInInches}&weight=${selectedWeight}&gender=${selectedGender}`);
        } else {
            alert('Please select both height and weight.');
        }
    });
});
