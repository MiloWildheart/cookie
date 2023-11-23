<!-- resources/js/components/CookieComponent.vue -->

<template>
  <div>
    <button @click="calculateHighestScore">Calculate Highest Score</button>
    <div v-if="highestScore !== null">Highest Score: {{ highestScore }}</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      highestScore: null,
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
          // Optionally, you can update your component state to display the error to the user.
        });
    },
  },
};
</script>