<script setup>
import { ref, inject } from 'vue'
const axios = inject('axios')
const socket = inject('socket')
socket.on('connect', () => {
    console.log("Connected to socket server")
})
socket.on('disconnect', () => {
    console.log("Disconnected from socket server")
})
socket.on('error', (error) => {
    console.log("Socket error: " + error)
})

const message = ref('DAD Intermediate Submission')
const responseData = ref('')

const send = () => {
    console.log("Sending message: " + message.value)
    socket.emit('echo', message.value)
}

socket.on('echo', (message) => {
    responseData.value = message
})
</script>

<template>
    <div class="my-5">
        <h2>Web Socket Tester</h2>

        <form>
            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <input type="text" class="form-control" id="message" v-model="message">
            </div>
            <button @click.prevent="send" type="submit" class="btn btn-primary">Send</button>
            <div class="my-5" v-if="responseData">
                <label for="exampleFormControlTextarea1" class="form-label">Response</label>
                <textarea :value="responseData" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
        </form>
    </div>
</template>