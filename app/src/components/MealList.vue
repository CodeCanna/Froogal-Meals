<!-- HTML -->
<template>
  <div class="MealList">
    <ul id="mealListUL">
      <li v-bind:mealName="mealName" v-for="mealName in list">
        {{ mealName }}
      </li>
    </ul>
  </div>
</template>

<!-- JavaScript -->
<script>
//import axios from "axios";

export default {
  data: function() {
      return "No"
  }
};
</script>

<!-- CSS -->
<style scoped></style>
