<template>
  <div class="container mx-auto mt-8 text-center">
    <h1 class="text-3xl font-bold mb-4">Cookie Recipe Calculator</h1>
    
    <div class="mb-4">
      <button @click="calculateHighestScore" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Calculate Highest Score
      </button>
    </div>
    
    <div v-if="ingredients.length">
      <h2 class="text-xl font-semibold mb-2">Available Ingredients:</h2>
      <ul class="list-disc pl-4">
        <li v-for="ingredient in ingredients" :key="ingredient.id">
          {{ ingredient.name }} - 
          Capacity: {{ ingredient.capacity }}, Durability: {{ ingredient.durability }},
          Flavor: {{ ingredient.flavor }}, Texture: {{ ingredient.texture }},
          Calories: {{ ingredient.calories }}, 
          Teaspoons: {{ teaspoonsUsed[ingredient.id] || 0 }},
        </li>
      </ul>
    </div>

    <div v-if="highestScore !== null" class="mt-4">
      <strong>Highest Score:</strong> {{ highestScore }}
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      highestScore: null,
      ingredients: [],
      teaspoonsUsed: {}, // Track teaspoons used for each ingredient
    };
  },
  methods: {
  calculateHighestScore() {
    axios.get('/calculate-highest-score')
      .then(response => {
        this.highestScore = response.data.highestScore;
        this.teaspoonsUsed = response.data.teaspoonsUsed; // Update teaspoonsUsed
      })
      .catch(error => {
        console.error('Error fetching highest score:', error);
        console.log('Error details:', error.response.data);
        console.log('Status code:', error.response.status);
      });
  },
  updateTeaspoonsUsed(teaspoonsUsed) {
    // Update the teaspoonsUsed object with the data received from the server
    this.teaspoonsUsed = teaspoonsUsed;
  },
  fetchIngredients() {
    axios.get('/get-ingredients')
      .then(response => {
        this.ingredients = response.data.ingredients;
      })
      .catch(error => {
        console.error('Error fetching ingredients:', error);
      });
  },
},
  mounted() {
    this.fetchIngredients();
  },
};
</script>
<!-- <script>
import axios from 'axios';

export default {
  data() {
    return {
      highestScore: null,
      ingredients: [],
      teaspoonsUsed: {},
    };
  },
  methods: {
    calculateHighestScore() {
      axios.get('/calculate-highest-score')
        .then(response => {
          this.highestScore = response.data.highestScore;
        })
        .catch(error => {
          console.error('Error fetching highest score:', error);
          // Log the detailed error information
          console.log('Error details:', error.response.data);
          console.log('Status code:', error.response.status);
        });
    },
    fetchIngredients() {
      axios.get('/get-ingredients')
        .then(response => {
          this.ingredients = response.data.ingredients;
        })
        .catch(error => {
          console.error('Error fetching ingredients:', error);
        });
    },
  },
  mounted() {
    this.fetchIngredients();
  },
};
</script>
 -->
