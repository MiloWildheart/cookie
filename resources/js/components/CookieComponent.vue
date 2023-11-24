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
          // Log the detailed error information
          console.log('Error details:', error.response.data);
          console.log('Status code:', error.response.status);
        });
    },
  },
};
</script>