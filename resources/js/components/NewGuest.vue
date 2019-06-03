<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form action @submit="newGuest(guest)">
          <h3 class="text-center font-weight-bold">New Guest Form</h3>
          <div class="form-group">
            <input type="text" placeholder="First Name" v-model="guest.name" class="form-control">
          </div>
          <div class="form-group">
            <input type="text" placeholder="Surname" v-model="guest.surname" class="form-control">
          </div>
          <div class="form-group">
            <input type="number" placeholder="Age" v-model="guest.age" class="form-control">
          </div>
          <div class="form-group">
            <button
              :disabled="!isValid"
              class="btn btn-block btn-primary"
              @click.prevent="createGuest(guest)"
            >Submit</button>
          </div>
        </form>
        <div class="message" v-if="submitted">Your Request Has Been Sent</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.form-group * {
  font-family: inherit;
  text-align: center;
  margin: 5px;
}
button {
  cursor: pointer;
}
.message {
  padding: 10px;
  font-weight: bold;
}
</style>

<script>
import { mapGetters } from "vuex";

export default {
  name: "NewGuest",
  data() {
    return {
      guest: {
        name: "",
        surname: "",
        age: null
      }
    };
  },
  methods: {
    createGuest(guest) {
      this.$store.dispatch("newGuest", guest);
    }
  },
  computed: {
    isValid() {
      return true;
    }
  }
};
</script>
