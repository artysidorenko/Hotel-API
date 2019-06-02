<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <h3 class="card-header">Guest Manifest</h3>
          <div class="results">
            <table class="table" cellspacing="0">
              <thead>
                  <tr>
                    <th>Guest ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Age</th>
                    <th>Room Number</th>
                </tr>
            </thead>
            <tbody class="table__body">
              <tr v-for="guest in guests" v-bind:key="guest.id">
                <td>{{guest.id}}</td>
                <td>{{guest.name}}</td>
                <td>{{guest.surname}}</td>
                <td>{{guest.age}}</td>
                <td>{{guest.room_id ? guest.room_id : ''}}</td>
              </tr>
            </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
    .table {
  display: inline-block;
  margin: auto;
  table-layout: fixed;
}
.table__body {
  display: block;
  max-height: 500px;
  overflow-y: scroll;
}
td,
th {
  width: 20%;
  padding: 5px;
  border-bottom: 1px solid grey;
}
</style>

<script>
import { mapGetters } from "vuex";

export default {
  name: "Guests",
  mounted() {
    this.$store.dispatch("listGuests");
    console.log("Component mounted.");
  },
  computed: {
    ...mapGetters(["guests"])
  }
};
</script>
