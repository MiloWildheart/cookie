<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function calculateHighestScore()
    {
        $ingredients = Ingredient::all()->toArray();

        $highestScore = $this->findHighestScore($ingredients, 100, []);

        return response()->json(['HighestScore' => $highestScore]);
    }

    private function findHighestScore($ingredients, $teaspponsLeft, $currentAmounts)
    {

        if ($teaspponsLeft === 0) 
        {
            return $this->calculateScore($ingredients, $currentAmounts);
        }

        $highestScore = PHP_INT_MIN;

        foreach ($ingredients as $ingredient => $properties) 
        {
            if($teaspponsLeft >= 0)
            {
                $currentAmounts[$ingredient] = isset($currentAmounts[$ingredient]) ? $currentAmounts[$ingredient] +1 : 1; 
                $score = $this->findHighestScore($ingredients, $teaspponsLeft - 1, $currentAmounts);

                $highestScore = max($highestScore, $score);

                $currentAmounts[$ingredient] -= 1;
            }
        }
        return $highestScore;
    }

    private function calculateScore($ingredients, $currentAmounts)
    {
        $properties = ['capacity', 'durability', 'flavor', 'texture'];
        $totalScore = 1;

        foreach($properties as $property) {
            $propertyScore = 0;

            foreach ($ingredients as $ingredient => $ingredientProperties) {
                $propertyScore += $currentAmounts[$ingredient] * $ingredientProperties [$property];
            }

            $propertyScore = max(0, $propertyScore);

            $totalScore *= $propertyScore;

        }
        return $totalScore;
    }

}
