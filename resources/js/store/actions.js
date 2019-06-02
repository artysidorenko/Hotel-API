import Axios from "axios";

let actions = {
  newRoom({commit}, room) {
    Axios.post('/api/new/room', room)
      .then(res => commit('NEW_ROOM', res.data))
      .catch(err => console.log(err))
  },
  listRooms({commit}) {
    Axios.get('/api/rooms')
      .then(res => commit('LIST_ROOMS', res.data))
      .catch(err => console.log(err))
  },
  updateRoom({commit}, room) {
    Axios.post(`/api/room/${room.id}`)
      .then(res => (res.status === 200) && commit('UPDATE_ROOM', room))
      .catch(err => console.log(err))
  },
  newGuest({ commit }, guest) {
    Axios.post('/api/new/guest', guest)
      .then(res => commit('NEW_GUEST', res.data))
      .catch(err => {
        console.log('test fail')
        console.log(err)})
  },
  listGuests({ commit }) {
    Axios.get('/api/guests')
      .then(res => commit('LIST_GUESTS', res.data))
      .catch(err => console.log(err))
  },
  updateGuest({ commit }, guest) {
    Axios.post(`/api/guest/${guest.id}`)
      .then(res => (res.status === 200) && commit('UPDATE_GUEST', guest))
      .catch(err => console.log(err))
  },
  newBooking({ commit }, booking) {
    Axios.post('/api/new/booking', booking)
      .then(res => {
        commit('NEW_BOOKING', res.data)
      })
      .catch(err => console.log(err))
  },
  listBookings({ commit }) {
    Axios.get('/api/bookings')
      .then(res => commit('LIST_BOOKINGS', res.data))
      .catch(err => console.log(err))
  },
  updateBooking({ commit }, booking) {
    Axios.post(`/api/booking/${booking.id}`)
      .then(res => (res.status === 200) && commit('UPDATE_BOOKING', booking))
      .catch(err => console.log(err))
  }
}

export default actions