let mutations = {
  NEW_ROOM(state, room) {
    state.rooms.unshift(room)
  },
  LIST_ROOMS(state, rooms) {
    return state.rooms = rooms
  },
  UPDATE_ROOM(state, room) {
    let index = state.rooms.findIndex(item => item.id === room.id)
    state.rooms[index] = room
  },
  NEW_GUEST(state, guest) {
    state.guests.unshift(guest)
  },
  LIST_GUESTS(state, guests) {
    return state.guests = guests
  },
  UPDATE_GUEST(state, guest) { 
    let index = state.guests.findIndex(item => item.id === guest.id)
    state.guests[index] = guest
   },
  NEW_BOOKING(state, booking) {
    state.bookings.unshift(booking)
  },
  LIST_BOOKINGS(state, bookings) {
    return state.bookings = bookings
  },
  UPDATE_BOOKING(state, booking) {
    let index = state.bookings.findIndex(item => item.id === booking.id)
    state.bookings[index] = booking
  },
}

export default mutations