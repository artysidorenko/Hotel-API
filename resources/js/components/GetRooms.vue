<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header">Room Manifest</h3>
                    <div class="results">
                        <ul>
                            <li
                                v-for="room in rooms"
                                v-bind:key="room.id"
                                :class="room.available && 'green'"
                            >
                                <strong class="subtitle">Room Number : {{room.id}}</strong><br/>
                                <span class="subtitle">Floor : {{room.floor}}</span><br/>
                                <span class="subtitle">Beds : {{room.beds}}</span><br/>
                                <span class="subtitle">Rate : {{room.price}}</span><br/>
                                <span class="subtitle">Availability : {{room.available ? 'free' : 'occupied'}}</span><br/>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    ul {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        padding: 0;
    }
    li {
        list-style-type: none;
        border: 1px solid grey;
        padding: 10px;
        width: 200px;
        margin: 2px;
        background-color: #F78181;
    }
    .green {
        background-color: #BEF781;
    }
</style>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name: "Rooms",
        mounted() {
            this.$store.dispatch('listRooms')
            console.log('Component mounted.')
        },
        computed: {
            ...mapGetters([
                'rooms'
            ])
        }
    }
</script>
