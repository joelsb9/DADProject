<script setup>
import { ref, inject } from 'vue'

const axios = inject('axios')

const username = ref('a1@mail.pt')
const password = ref('123')
const responseData = ref('')

/*const submit = async () => {
    const responseLogin = await axios.post('/auth/login', {
        username: username.value,
        password: password.value
    })
    axios.defaults.headers.common.Authorization = "Bearer " + responseLogin.data.access_token
    const responseRequest = await axios.get('/categories')
    responseData.value = responseRequest.data.data[0].name

}*/

const submit = async () => {
    try {
        // Correct the endpoint and provide the full URL
        const responseLogin = await axios.post('http://127.0.0.1:8000/api/auth/login', {
            username: username.value,
            password: password.value
        }, {
            // Set the header to accept JSON
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Content-Type": "application/json",
            }
        });

        // Set the authorization header
        axios.defaults.headers.common.Authorization = "Bearer " + responseLogin.data.access_token;

        // Correct the endpoint for the second request
        const responseRequest = await axios.get('http://127.0.0.1:8000/api/categories');

        // Update responseData with the appropriate data from the response
        responseData.value = responseRequest.data.data[0].name;
    } catch (error) {
        // Handle errors (you might want to log or display an error message)
        console.error('Error:', error);
    }
}
</script>

<template>
    <div class="my-5">
        <h2>Laravel Tester</h2>

        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" v-model="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" v-model="password">
            </div>
            <button @click.prevent="submit" type="submit" class="btn btn-primary">Submit</button>
            <div class="my-5" v-if="responseData">
                <label for="exampleFormControlTextarea1" class="form-label">Response</label>
                <textarea :value="responseData" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
        </form>
    </div>
</template>