<script setup>
import { ref, inject, computed } from 'vue';
import { useRouter, RouterLink, RouterView } from 'vue-router'
import avatarNone from '@/assets/avatar-none.png'
const axios = inject('axios')

const previewImage =ref(avatarNone)
const router = useRouter()


const formData = ref({
  name: '',
  email: '',
  phone_number: '',
  password: '',
  password_confirmation: '',
  confirmation_code: '',
  confirm_confirmation_code: '',
  photo: previewImage,
  // Add other fields as needed
});

const register = () => {
  // Create a FormData object to handle file upload
  const formDataForApi = new FormData();
  for (const key in formData) {
    formDataForApi.append(key, formData[key]);
  }

  // Make an API call to your Laravel backend to handle registration
  // You can use Axios or another HTTP library for this purpose
  axios.post('/api/register', formDataForApi)
    .then(response => {
      // Handle success, e.g., redirect to a new page
      console.log(response.data);
    })
    .catch(error => {
      // Handle error, e.g., show validation errors
      console.error(error.response.data);
    });
};

const goBack = () => {
  router.back()
}

const save = () => {
  const userToSave = editingUser.value
  userToSave.deletePhotoOnServer = deletePhotoOnTheServer.value
  userToSave.base64ImagePhoto = editingImageAsBase64.value
  emit("save", userToSave);
}

function uploadImage(e) {
  const image = e.target.files[0];
  const reader = new FileReader();
  reader.readAsDataURL(image);
  reader.onload = e => {
    previewImage.value = e.target.result;
    //console.log(this.previewImage);
  };
}
function cleanPhoto(){
  previewImage.value = avatarNone
}

</script>

<template>
  <div class="register-container">
    <div class="register-box">
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div class="form-group double">
          <div>
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" v-model="formData.name" required />
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number:</label>
              <input type="text" v-model="formData.phone_number" required />
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" v-model="formData.password" required />
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm Password:</label>
              <input type="password" v-model="formData.password_confirmation" required />
            </div>
            <div class="form-group">
              <label for="confirmation_code">Code:</label>
              <input type="text" v-model="formData.confirmation_code" required />
            </div>
            <div class="form-group">
              <label for="confirm_confirmation_code">Confirm Code:</label>
              <input type="text" v-model="formData.confirm_confirmation_code" required />
            </div>
          </div>
          <div class="form-group flex-column justify-content-between">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" v-model="formData.email" required />
            </div>
            <div class="fixed-size-container">
              <img :src="previewImage" alt="Uploaded Photo" class="uploaded-photo" />
            </div>
            <div class="d-flex justify-content-between flex-wrap align-items-baseline">
              <label  for="inputPhoto" class="labels flex-grow-1 mx-1">Carregar</label>
              <label class="labels flex-grow-1 mx-1" @click.prevent="cleanPhoto">Apagar</label>
            </div>
            <div>
              <field-error-message :errors="errors" fieldName="base64ImagePhoto"></field-error-message>
            </div>
          </div>
        </div>
        <button type="submit">Register</button>
        <label class="labels" @click="goBack"> Back</label>
      </form>
      <input type="file" accept="image/*" id="inputPhoto" @change="uploadImage" style="display: none;" />
      <!-- <input type="file" style="visibility:hidden;" id="inputPhoto" ref="inputPhotoFile" @change="changePhotoFile" /> -->
    </div>
  </div>
</template>
  
  
<style scoped>
.labels{
  padding: 10px;
  background-color: #62beff;
  /* Blue Ocean color for the button */
  color: #fff;
  /* White text color */
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
  text-align: center;
}
.labels:hover {
  background-color: #005580;
  /* Darker shade on hover */
}

.custom-file-input {
  position: relative;
  display: inline-block;
}

.file-button {
  display: inline-block;
  padding: 8px 16px;
  background-color: #62beff;
  color: #fff;
  cursor: pointer;
  border-radius: 4px;
  border: none;
}

.file-button .icon {
  margin-right: 8px;
}


.fixed-size-container {
  width: 300px; /* Set your desired width */
  height: 376px; /* Set your desired height */
  overflow: hidden; /* Ensure the image doesn't overflow the container */
}
.uploaded-photo {
  width: 100%;
  height: 100%;
  max-width: px;
  max-height: 376px;
  object-fit:scale-down; /* or object-fit: contain; depending on your requirements */
  border-radius: 2px;
}

.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #62beff;
  /* Blue Ocean color */
}

.register-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #fff;
  /* White background for the form box */
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-group.double {
  display: flex;
  flex-direction: row;
  align-items: stretch;
}

.form-group.double div {
  width: 100%;
  margin-right: 2%;
  /* Adjust as needed, this provides a small space between the fields */
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
}
</style>
    
  