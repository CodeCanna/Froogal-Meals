<template>
  <div id="app">
    <NewMealForm/>
  </div>
</template>

<script>
//import MealList from './components/MealList.vue'
import NewMealForm from './components/NewMealForm.vue'

export default {
  name: 'app',
  components: {
    //MealList,
    NewMealForm
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
