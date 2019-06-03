<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <h3 class="card-header">Bookings Ledger</h3>
          <div class="results">
            <table class="table" cellspacing="0">
              <thead>
                <tr>
                  <th>Booking ID</th>
                  <th>Room Number:</th>
                  <th>Arriving:</th>
                  <th>Departing:</th>
                  <th>Guest id:</th>
                </tr>
              </thead>
              <tbody class="table__body">
                <tr v-for="booking in bookings" v-bind:key="booking.id">
                  <td>{{booking.id}}</td>
                  <td>{{booking.room_id}}</td>
                  <td>{{formatDate(booking.arrival)}}</td>
                  <td>{{formatDate(booking.departure)}}</td>
                  <td>{{booking.guest_id}}</td>
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
  display: block;
  margin: auto;
  table-layout: fixed;
}
thead {
  display: table-header-group;
}
.table__body {
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
  name: "bookings",
  // props: ['results', 'rooms'],
  mounted() {
    this.$store.dispatch("listBookings");
    console.log("Component mounted.");
  },
  methods: {
    formatDate(date) {
      const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
      const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ];
      const dateObj = new Date(date);
      return `${days[dateObj.getDay()]} ${dateObj.getDate()} ${
        months[dateObj.getMonth()]
      } '${dateObj
        .getFullYear()
        .toString()
        .slice(2)}`;
    }
  },
  computed: {
    ...mapGetters(["bookings"])
  }
};
</script>
