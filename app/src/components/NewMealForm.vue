<template>
  <div>
    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
      <b-form-group
        id="groupMealName"
        label="Meal Name:"
        label-for="inputMealName"
      >
        <b-form-input
          id="inputMealName"
          v-model="form.mealName"
          type="text"
          required
          placeholder="New Meal Name"
        ></b-form-input>
      </b-form-group>

      <b-form-group
        id="groupMealType"
        label="Meal Type:"
        label-for="selectMealType"
      >
        <b-form-select
          id="selectMealType"
          v-model="form.mealType"
          :options="mealTypes"
          required
        ></b-form-select>
      </b-form-group>

      <b-form-group
        id="groupMealDate"
        label="Meal Date:"
        label-for="inputMealDate"
      >
        <b-form-input
          type="date"
          id="inputMealDate"
          v-model="form.mealDate"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group
        id="groupMealIngredients"
        label="Meal Ingredients:"
        label-for="inputMealIngredients"
      >
        <b-form-input
          type="text"
          id="inputMealIngredients"
          v-model="form.mealIngredients"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group
        id="groupMealCalorieCount"
        label="Calorie Count:"
        label-for="inputMealCalorieCount"
      >
        <b-form-input
          type="number"
          id="inputMealCalorieCount"
          v-model="form.mealCalorieCount"
          required
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" variant="primary">Submit</b-button>
      <b-button type="reset" variant="danger">Reset</b-button>
    </b-form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      form: {
        mealName: "",
        mealType: null,
        mealDate: "",
        mealIngredients: "",
        mealCalorieCount: ""
      },
      mealTypes: [
        { text: "Select One", value: null },
        "Breakfast",
        "Lunch",
        "Dinner"
      ],
      show: true
    };
  },
  methods: {
    onSubmit(evt) {
      alert(evt.type);
      evt.preventDefault();
      // POST the form data
      axios
        .post("http://10.0.0.44:8000/apis/meal/index.php", {
          headers: {
              "Content-type": "application/x-www-form-urlencoded"
          },
          body: {
            mealName: this.form.mealName,
            mealType: this.form.mealType,
            mealDate: this.form.mealDate,
            mealIngredients: this.form.mealIngredients,
            mealCalorieCount: this.form.mealCalorieCount
          }
        })
        .then(function(response) {
          alert(response);
        })
        .catch(function(error) {
          throw new error();
        });
    },
    onReset(evt) {
      evt.preventDefault();
      // Reset our form values
      this.form.mealName = "";
      this.form.mealType = null;
      this.form.mealDate = "";
      this.form.mealIngredients = "";
      this.form.mealCalorieCount = "";
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  }
};
</script>
