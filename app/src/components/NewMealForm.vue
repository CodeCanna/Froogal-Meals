<!-- HTML -->
<template>
  <div id="newMealForm">
    <form action="http://192.168.0.110:8000/index.php" method="POST">
      <!-- mealName -->
      <label for="mealName">Meal Name:</label>
      <input name="mealName" v-model="mealName" type="text" id="mealName" />

      <!-- mealType -->
      <label for="mealType">Meal Type:</label>
      <select name="mealType" id="mealType">
        <option value="Breakfast">Breakfast</option>
        <option value="Lunch">Lunch</option>
        <option value="Dinner">Dinner</option>
      </select>

      <!-- mealDate -->
      <label for="mealDate">Meal Date:</label>
      <input name="mealDate" id="mealDate" type="date" />

      <!-- mealIngredients -->
      <label for="mealIngredients">Meal Ingredients:</label>
      <input name="mealIngredients" id="mealIngredients" type="text" />

      <!-- mealCalorieCount -->
      <label for="mealCalorieCount">Calorie Count:</label>
      <input name="mealCalorieCount" id="mealCalorieCount" type="number"/>

      <!-- Submit Button -->
      <button type="submit" value="Submit">Submit</button>
    </form>
  </div>
</template>

<!-- JavaScript -->
<script></script>

<!-- CSS -->
<style></style>
