<?php



namespace App\Http\Controllers;

// ini_set('memory_limit', '256M');

use App\Models\Ingredient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CookieController extends Controller
{
    public function getIngredients()
    {
        $ingredients = Ingredient::all();
        return response()->json(['ingredients' => $ingredients]);
    }


    // private function findHighestScore($ingredients, $teaspoons)
    // {
    //     // Create a 2D array to store scores for each amount of teaspoons and ingredient
    //     $scores = array_fill(0, $teaspoons + 1, array_fill_keys(array_column($ingredients, 'id'), 0));
    
    //     // Initialize the highest score
    //     $highestScore = PHP_INT_MIN;
    
    //     for ($i = 1; $i <= $teaspoons; $i++) {
    //         foreach ($ingredients as $ingredient) {
    //             for ($amount = 0; $amount <= $i; $amount++) {
    //                 $remainingTeaspoons = $i - $amount;
    
    //                 // Calculate the score for the current amount of teaspoons and ingredient
    //                 $score = $scores[$remainingTeaspoons][$ingredient['id']] + $amount * $ingredient['id'];
    
    //                 // Update the highest score
    //                 $highestScore = max($highestScore, $score);
    
    //                 // Store the score for the current amount of teaspoons and ingredient
    //                 $scores[$i][$ingredient['id']] = max(0, $score);
    //             }
    //         }
    //     }
    
    //     return $highestScore;
    // }

//     private function findHighestScore($ingredients, $teaspoons)
// {
//     $memo = [];
//     $highestScore = PHP_INT_MIN;

//     for ($i = 0; $i <= $teaspoons; $i++) {
//         foreach ($ingredients as $ingredient) {
//             for ($amount = 0; $amount <= $i; $amount++) {
//                 $remainingTeaspoons = $i - $amount;

//                 if (!isset($memo[$remainingTeaspoons])) {
//                     $memo[$remainingTeaspoons] = $this->calculateScore($ingredients, $remainingTeaspoons);
//                 }

//                 $score = $memo[$remainingTeaspoons];
//                 $propertyScores = [
//                     'capacity' => max(0, $amount * $ingredient['capacity']),
//                     'durability' => max(0, $amount * $ingredient['durability']),
//                     'flavor' => max(0, $amount * $ingredient['flavor']),
//                     'texture' => max(0, $amount * $ingredient['texture']),
//                 ];

//                 // Multiply the property scores together
//                 $product = array_product($propertyScores);
//                 $score *= $product;

//                 $highestScore = max($highestScore, $score);
//             }
//         }
//     }

//     return $highestScore;
// }

    // private function findHighestScore($ingredients, $teaspoons)
    // {
    //     $memo = [];
    //     $highestScore = PHP_INT_MIN;
    
    //     for ($i = 0; $i <= $teaspoons; $i++) {
    //         foreach ($ingredients as $ingredient) {
    //             for ($amount = 0; $amount <= $i; $amount++) {
    //                 $remainingTeaspoons = $i - $amount;
    
    //                 if (!isset($memo[$remainingTeaspoons])) {
    //                     $memo[$remainingTeaspoons] = $this->calculateScore($ingredients, $remainingTeaspoons);
    //                 }
    
    //                 $score = $memo[$remainingTeaspoons];
    //                 foreach (['capacity', 'durability', 'flavor', 'texture'] as $property) {
    //                     $score *= max(0, $amount * $ingredient[$property]); // Multiply instead of add
    //                 }
    
    //                 $highestScore = max($highestScore, $score);
    //             }
    //         }
    //     }
    
    //     return $highestScore;
    // }


    

    

    // private function findHighestScore($ingredients, $teaspoons)
    // {
    //     $memo = [];
    //     $highestScore = PHP_INT_MIN;
    
    //     for ($i = 0; $i <= $teaspoons; $i++) {
    //         foreach ($ingredients as $ingredient) {
    //             for ($amount = 0; $amount <= $i; $amount++) {
    //                 $remainingTeaspoons = $i - $amount;
    
    //                 if (!isset($memo[$remainingTeaspoons])) {
    //                     $memo[$remainingTeaspoons] = $this->calculateScore($ingredients, $remainingTeaspoons);
    //                 }
    
    //                 $score = $memo[$remainingTeaspoons];
    //                 foreach (['capacity', 'durability', 'flavor', 'texture'] as $property) {
    //                     $score += max(0, $amount * $ingredient[$property]);
    //                 }
    
    //                 $highestScore = max($highestScore, $score);
    //             }
    //         }
    //     }
    
    //     return $highestScore;
    // }

    private function findHighestScore($ingredients, $teaspoons)
{
    $memo = [];
    $highestScore = PHP_INT_MIN; // Initialize the variable here

    for ($i = 0; $i <= $teaspoons; $i++) {
        foreach ($ingredients as $ingredient) {
            for ($amount = 0; $amount <= $i; $amount++) {
                $remainingTeaspoons = $i - $amount;

                if (!isset($memo[$remainingTeaspoons])) {
                    $memo[$remainingTeaspoons] = $this->calculateScore($ingredients, $remainingTeaspoons);
                }

                $score = $memo[$remainingTeaspoons];
                $score += $amount * $ingredient['id']; // Adjust this line based on your scoring logic

                // Update the highest score
                $highestScore = max($highestScore, $score);
            }
        }
    }

    return $highestScore;
}


public function calculateHighestScore()
{
    try {
        $ingredients = Ingredient::all()->toArray();
        Log::info('Ingredients: ' . json_encode($ingredients));

        $teaspoons = 100;
        $highestScore = $this->findHighestScore($ingredients, $teaspoons);

        // Get the teaspoons used for each ingredient
        $teaspoonsUsed = $this->calculateTeaspoonsUsed($ingredients, $teaspoons);

        Log::info('Highest Score: ' . $highestScore);
        Log::info('Teaspoons Used: ' . json_encode($teaspoonsUsed));

        return response()->json(['highestScore' => $highestScore, 'teaspoonsUsed' => $teaspoonsUsed]);
    } catch (\Exception $e) {
        Log::error('Exception: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

private function calculateTeaspoonsUsed($ingredients, $totalTeaspoons)
{
    $teaspoonsUsed = [];

    // Calculate the total sum of all criteria (e.g., capacity, flavor, etc.)
    $totalCriteriaSum = array_reduce($ingredients, function ($carry, $ingredient) {
        return $carry + $ingredient['capacity'] + $ingredient['flavor'] + $ingredient['durability'] + $ingredient['texture'] + $ingredient['calories'];
    }, 0);

    // Calculate teaspoons used for each ingredient proportionally
    foreach ($ingredients as $ingredient) {
        $proportionalTeaspoons = ($totalTeaspoons * ($ingredient['capacity'] + $ingredient['flavor'] + $ingredient['durability'] + $ingredient['texture'] + $ingredient['calories'])) / $totalCriteriaSum;
        $teaspoonsUsed[$ingredient['id']] = round($proportionalTeaspoons);
    }

    return $teaspoonsUsed;
}

// public function calculateHighestScore()
// {
//     try {
//         $ingredients = Ingredient::all()->toArray();
//         Log::info('Ingredients: ' . json_encode($ingredients));

//         // Set the desired amount of teaspoons (in this case, 100)
//         $teaspoons = 100;

//         // Call the findHighestScore method with the specified ingredients and teaspoons
//         $highestScore = $this->findHighestScore($ingredients, $teaspoons);

//         Log::info('Highest Score: ' . $highestScore);
//         return response()->json(['highestScore' => $highestScore]);
//     } catch (\Exception $e) {
//         Log::error('Exception: ' . $e->getMessage());
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

private function calculateScore($ingredients, $currentAmounts)
{
    $properties = ['capacity', 'durability', 'flavor', 'texture'];
    $totalScore = 1;

    foreach ($properties as $property) {
        $propertyScore = 0;

        foreach ($ingredients as $ingredient) {
            // Ensure $currentAmounts is an associative array and use correct indexing
            $propertyScore += ($currentAmounts[$ingredient['id']] ?? 0) * ($ingredient[$property] ?? 0);
        }

        $totalScore *= max(0, $propertyScore);
    }

    return $totalScore;
}
}
