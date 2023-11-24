<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CookieController extends Controller
{
    public function calculateHighestScore()
    {
        try {
            $ingredients = Ingredient::all()->toArray();

            // Find the best distribution of teaspoons
            $teaspoonsUsed = $this->findBestDistribution($ingredients);

            // Calculate the properties of the resulting cookie
            $properties = $this->calculateCookieProperties($ingredients, $teaspoonsUsed);

            Log::info('Properties: ' . json_encode($properties));

            // Calculate the total score
            $highestScore = $this->calculateScore($properties);

            Log::info('Highest Score: ' . $highestScore);

            return response()->json(['highestScore' => $highestScore, 'teaspoonsUsed' => $teaspoonsUsed]);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function findBestDistribution($ingredients)
    {
        $teaspoonsUsed = [];
        $remainingTeaspoons = 100;

        // Sort ingredients by their flavor in descending order
        usort($ingredients, function ($a, $b) {
            return $b['flavor'] - $a['flavor'];
        });

        foreach ($ingredients as $ingredient) {
            $teaspoons = min($remainingTeaspoons, rand(1, 10));
            $teaspoonsUsed[$ingredient['id']] = $teaspoons;
            $remainingTeaspoons -= $teaspoons;
        }

        return $teaspoonsUsed;
    }

    private function calculateCookieProperties($ingredients, $teaspoonsUsed)
{
    $properties = [];

    foreach ($ingredients as $ingredient) {
        foreach ($ingredient as $property => $value) {
            if ($property != 'id' && $property != 'name') {
                if (!isset($properties[$property])) {
                    $properties[$property] = 0;
                }

                // Check if the value is numeric before performing multiplication
                if (is_numeric($value) && is_numeric($teaspoonsUsed[$ingredient['id']])) {
                    $properties[$property] += $value * $teaspoonsUsed[$ingredient['id']];
                } else {
                    // Handle non-numeric values, for example, by setting them to 0
                    $properties[$property] += 0;
                }
            }
        }
    }

    return $properties;
}



    private function calculateScore($properties)
    {
        $score = 1;

        foreach ($properties as $value) {
            $score *= max(0, $value);
        }

        return $score;
    }
}
