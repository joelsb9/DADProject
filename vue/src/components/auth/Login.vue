<script setup>
//import axios from 'axios'
import { useToast } from "vue-toastification"
import { useRouter, RouterLink, RouterView } from 'vue-router'
import { useAdminStore } from '../stores/admin.js'
import { useVcardStore } from '../stores/vcard.js'
import { ref } from 'vue'

const toast = useToast()
const router = useRouter()
const adminStore = useAdminStore()
const vcardStore = useVcardStore()

const credentials = ref({
    username: '',
    password: ''
})

const emit = defineEmits(['login','registerVcard'])

const login = async () => {
    if (await vcardStore.login(credentials.value)) {
        toast.success('Vcard ' + vcardStore.vcardName + ' has entered the application.')
        emit('login')
        router.back()
    } else {
        credentials.value.password = ''
        toast.error('User credentials are invalid!')
    }
}
const goToRegister = () => {
    // Use router to navigate to the register page
    router.push({ name: 'RegisterVcard' })
}
</script>
<template>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form @submit.prevent="login">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" v-model="credentials.username" required />
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" v-model="credentials.password" required />
                </div>
                <div class="center-button">
                    <button type="submit" class="full-width">Login</button>
                </div>
            </form>
            <div>
                <!--Create an hipper link to emit a function-->
                <a href="#" class="register-link" @click="goToRegister">Register</a>
            </div>
        </div>
    </div>
</template>
  
<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #62beff;
    /* Blue Ocean color */
}

.login-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #fff;
    /* White background for the form box */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #62beff;
    /* Blue Ocean color for the heading */
}

form {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    /* Adjust the value to control the spacing between form groups */
}

label {
    color: #333;
    /* Dark text color */
    margin-bottom: 8px;
}

input {
    padding: 8px;
    border: 1px solid #ccc;
    /* Light border color */
    border-radius: 4px;
}

.center-button {
    display: flex;
    justify-content: center;
}

.full-width {
    width: 100%;
    /* Make the button fill the entire width of the container */
}

button {
    padding: 10px;
    background-color: #62beff;
    /* Blue Ocean color for the button */
    color: #fff;
    /* White text color */
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    /* Adjust the value to control the spacing above the button */
}

button:hover {
    background-color: #005580;
    /* Darker shade on hover */
}

.register-link {
    color: #62beff;
    /* Blue Ocean color for the link */
    text-decoration: none;
}

.register-link:hover {
    text-decoration: underline;
    /* Underline on hover */
}</style>
    