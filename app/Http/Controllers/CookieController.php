<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CookieController extends Controller
{

    public function getIngredients()
    {
        $ingredients = Ingredient::all();

        return response()->json(['ingredients' => $ingredients]);
    }

    public function calculateHighestScore()
    {
        try {
            $ingredients = Ingredient::all()->toArray();
            $highestScore = $this->findHighestScore($ingredients, 100, []);
            return response()->json(['highestScore' => $highestScore]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function findHighestScore($ingredients, $teaspoonsLeft, $currentAmounts)
    {

        if ($teaspoonsLeft === 0) 
        {
            return $this->calculateScore($ingredients, $currentAmounts);
        }

        $highestScore = PHP_INT_MIN;

        foreach ($ingredients as $ingredient => $properties) 
        {
            if($teaspoonsLeft >= 0)
            {
                $currentAmounts[$ingredient] = isset($currentAmounts[$ingredient]) ? $currentAmounts[$ingredient] +1 : 1; 
                $score = $this->findHighestScore($ingredients, $teaspoonsLeft - 1, $currentAmounts);

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
                if(isset($ingredientProperties[$property])){
                $propertyScore += $currentAmounts[$ingredient] * $ingredientProperties [$property];}
                else {
                    $propertyScore +=0;
                }
            }

            $propertyScore = max(0, $propertyScore);

            $totalScore *= $propertyScore;

        }
        return $totalScore;
    }

}
