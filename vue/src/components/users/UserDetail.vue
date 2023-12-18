<script setup>
import { ref,computed, onMounted } from 'vue';
import { useToast } from "vue-toastification";
import { useUserStore } from '../stores/user.js';
import axios from 'axios';

const toast = useToast();
const userStore = useUserStore();

const emit =defineEmits(["save"]);
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  inserting: {
    type: Boolean,
    default: false,
  },
  errors: {
    type: Object,
    required: false,
  },
  isAdmin: {
    type: Boolean,
    required: false,
    default: false,
  },
  type: {
    type: String,
    required: false,
    default: "V",
  },
});
const editUser = ref(props.user);
const errors = ref(props.errors);
const editMode = ref(false);
const base64ImagePhoto = ref(null);
//userStore.restoreToken();


const saveProfile = async () => {
    editUser.value.base64ImagePhoto = base64ImagePhoto.value;
    emit("save", editUser.value);
};

function uploadImage(e) {
    try {
        const file = e.target.files[0]
        if (!file) {
            base64ImagePhoto.value = null
            editUser.value.base64ImagePhoto = null
            editUser.value.photo_url = null
        } else {
            const reader = new FileReader()
            reader.addEventListener('load', (e) => {
                // convert image file to base64 string
                base64ImagePhoto.value = e.target.result
                //fetch image file name
                editUser.value.photo_url = file.name
            },
                false,
            )
            if (file) {
                reader.readAsDataURL(file)
            }
        }
    } catch (error) {
        base64ImagePhoto.value = null
        editUser.value.photo_url = null
    }
}
function cleanPhoto() {
    base64ImagePhoto.value = null;
}
onMounted(() => {
    console.log(editUser)
});


</script>

<template>
    <div class="profile d-flex align-items-center justify-content-center">
        <div class="main-content p-3 bg-white rounded shadow-sm d-flex justify-content-between align-items-center">
            <div class="row flex-grow-1">
                <div class="col-md-6 info-section">
                    <h1 class="greeting">Welcome, <span class="username">{{ console.log(user) }}</span></h1>
                    <p><strong>Email:</strong> {{ user.email }}</p>
                    <p><strong>Phone Number:</strong> {{ user.phone_number }}</p>
                    <p><strong>Balance:</strong> {{ user.balance }}</p>
                    <button class="btn btn-primary" @click=" editMode = !editMode">Edit Profile</button>
                </div>
                <div v-if="editMode" class="col-md-6 mt-3 d-flex flex-column justify-content-center edit-form">
                    <input v-model="editUser.email" placeholder="Email" class="form-control mb-2" :class="{ 'is-invalid': errors ? errors['email'] : false }">
                    <input v-model="editUser.phone_number" placeholder="Phone Number" class="form-control mb-2" :class="{ 'is-invalid': errors ? errors['phone_number'] : false }">
                    <input v-model="editUser.name" placeholder="Name" class="form-control mb-2" :class="{ 'is-invalid': errors ? errors['name'] : false }">
                    <input type="file" accept="image/*" id="inputPhoto" @change="uploadImage" />
                    <button class="btn btn-success" @click="saveProfile">Save</button>
                </div>
            </div>
            <img :src="base64ImagePhoto ?? userStore.userPhotoUrl" alt="User photo">
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