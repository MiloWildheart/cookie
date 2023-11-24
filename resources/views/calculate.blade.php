<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Recipe Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-8 text-center">
    <h1 class="text-3xl font-bold mb-4">Cookie Recipe Calculator</h1>

    <button onclick="calculateHighestScore()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Calculate Highest Score
    </button>

    <div id="result" class="mt-4">
        <!-- Result will be displayed here -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function calculateHighestScore() {
        axios.get('/calculate-highest-score')
            .then(response => {
                const result = document.getElementById('result');
                result.innerHTML = `
                    <strong>Highest Score:</strong> ${response.data.highestScore}<br>
                    <strong>Teaspoons Used:</strong> ${JSON.stringify(response.data.teaspoonsUsed)}
                `;
            })
            .catch(error => {
                console.error('Error calculating highest score:', error);
                const result = document.getElementById('result');
                result.innerHTML = `<strong>Error:</strong> ${error.response.data.error}`;
            });
    }
</script>

</body>
</html>
