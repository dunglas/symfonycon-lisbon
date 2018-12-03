// assets/feedback/index.js
import Vue from 'vue';
import StarRating from 'vue-star-rating'; // yarn add vue-star-rating
import Feedback from './Feedback';

Vue.component('star-rating', StarRating);

new Vue({
    el: '#feedback',
    components: {Feedback}
});
