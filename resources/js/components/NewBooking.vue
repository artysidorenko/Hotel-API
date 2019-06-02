<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form action @submit="newBooking(booking)">
          <h4 class="text-center font-weight-bold">Booking Form</h4>
          <div class="form-group">
            <label for="arrival">Arrival Date:</label>
            <input id="arrival" type="date" v-model="booking.arrival" class="form-control">
          </div>
          <div class="form-group">
            <label for="departure">Departure Date:</label>
            <input
              id="departure"
              type="date"
              placeholder="Departure Date"
              v-model="booking.departure"
              class="form-control"
            >
          </div>
          <div class="form-group">
            <label for="room">Room Number:</label>
            <select id="room" v-model="booking.room_id">
              <option v-for="room in rooms" v-bind:key="room.id" :value="room.id">{{room.id}}</option>
            </select>
            <label for="guest">Guest ID:</label>
            <select id="guest" v-model="booking.guest_id">
              <option v-for="guest in guests" v-bind:key="guest.id" :value="guest.id">{{guest.id}}</option>
            </select>
          </div>
          <div class="form-group">
            <button
              :disabled="!isValid"
              class="btn btn-block btn-primary"
              @click.prevent="createBooking(booking)"
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
input, select {
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
  name: "NewBooking",
  data() {
    return {
      booking: {
        arrival: "",
        departure: "",
        room_id: "",
        guest_id: ""
      },
      submitted: false
    };
  },
  methods: {
    createBooking(booking) {
      this.$store.dispatch("newBooking", booking)
      this.submitted = true
    },
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
  mounted() {
    this.$store.dispatch("listGuests");
    this.$store.dispatch("listRooms");
  },
  computed: {
    isValid() {
      return true;
    },
    ...mapGetters(["guests", "rooms"])
  }
};
</script>
