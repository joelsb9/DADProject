<script setup>
import { ref, inject } from 'vue';
import { useToast } from "vue-toastification";
import { useUserStore } from '../stores/user.js';
import axios from 'axios';

const toast = useToast();
const userStore = useUserStore();

userStore.restoreToken();

const serverBaseUrl = inject('serverBaseUrl');

const user = ref(userStore.user);
const editMode = ref(false);

const saveProfile = async () => {
    const response = await axios.put(`vcards/` + userStore.userId, user.value).then((response) => {
        user.value = response.data; // Update the userStore with the new user data
        editMode.value = false;
        toast.success('Profile edited successfully!');
    }).catch((error) => {
        console.log(error);
        toast.error('Failed to edit profile');
    });
    
};

</script>

<template>
    <div class="profile d-flex align-items-center justify-content-center">
        <div class="main-content p-3 bg-white rounded shadow-sm d-flex justify-content-between align-items-center">
            <div class="row flex-grow-1">
                <div class="col-md-6 info-section">
                    <h1 class="greeting">Welcome, <span class="username">{{ user.name }}</span></h1>
                    <p><strong>Email:</strong> {{ user.email }}</p>
                    <p><strong>Phone Number:</strong> {{ user.phone_number }}</p>
                    <p><strong>Balance:</strong> {{ user.balance }}</p>
                    <button class="btn btn-primary" @click="editMode = !editMode">Edit Profile</button>
                </div>
                <div v-if="editMode" class="col-md-6 mt-3 d-flex flex-column justify-content-center edit-form">
                    <input v-model="user.email" placeholder="Email" class="form-control mb-2">
                    <input v-model="user.phone_number" placeholder="Phone Number" class="form-control mb-2">
                    <input v-model="user.name" placeholder="Name" class="form-control mb-2">
                    <button class="btn btn-success" @click="saveProfile">Save</button>
                    <div class="mt-2 todo-box">TODO</div>
                </div>
            </div>
            <img :src="user.photo_url ? userStore.userPhotoUrl : require(userStore.userPhotoUrl)"
                alt="User photo" class="user-photo rounded-circle">
        </div>
    </div>
</template>

<style scoped>
.profile {
    height: 100vh;
    background-color: #62beff;
}

.main-content {
    max-width: 800px;
    width: 100%;
}

.todo-box {
    width: 100%;
    height: 38px;
    /* Adjust this value based on the height of your Save button */
    background-color: #ff0000;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    font-size: 1.2em;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 10px;
    /* Add some margin to separate it from the Save button */
}

.user-photo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-left: 20px;
}

.greeting {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 1.5em;
    text-align: left;
}

.username {
    color: #3498db;
    font-weight: bold;
}

.info-section {
    margin-top: 20px;
    padding: 20px;
    border-radius: 5px;
    background-color: #f8f9fa;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
}

.edit-form {
    margin-left: -50px;
}
</style>