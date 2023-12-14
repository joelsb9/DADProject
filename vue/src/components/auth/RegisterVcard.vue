<script setup>
import { ref, toRefs, computed } from 'vue';
import { useRouter, onBeforeRouteLeave, RouterView } from 'vue-router'
import { useUserStore } from '../stores/user';
import { useToast } from 'vue-toastification';
import axios from 'axios'
import avatarNone from '@/assets/avatar-none.png'

//const previewImage = ref(avatarNone)
const router = useRouter()
const userStore = useUserStore()
const toast = useToast()
//const socket = inject("socket")
const password_confirmation = ref('');
const confirm_confirmation_code = ref('');
const inputPhotoFile = ref(null);

const formData = ref({
  name: '',
  email: '',
  phone_number: '',
  password: '',
  confirmation_code: '',
  base64ImagePhoto: avatarNone,
});

const credentials = ref({
  username: '',
  password: ''
})

const register = async () => {
  // Create a FormData object to handle file upload
  try {
    // Make an API call to your Laravel backend to handle registration
    // You can use Axios or another HTTP library for this purpose
    console.log('formData.value: ', formData.value) 
    const response = await axios.post('register', formData.value)
    // Handle success, e.g., redirect to a new page
    toast.success('Vcard #' + formData.value.phone_number + ' registered successfully.')
    credentials.value.username = formData.value.phone_number
    credentials.value.password = formData.value.password
    await userStore.login(credentials.value)
    router.push('/');
    console.log(response.data);
    //    socket.emit('insertedUser', user.value)
  }
  catch (error) {
    if (error.response && error.response.status === 422) {
      //errors.value = error.response.data.errors
      toast.error('User was not registered due to validation errors!')
    } else {
      console.log(error)
      toast.error('User was not registered due to unknown server error!')
    }
  }
};

const goBack = () => {
  router.back()
}

//let originalValueStr = ''

function uploadImage(e) {
  try {
    const file = e.target.files[0]
    if (!file) {
      formData.value.base64ImagePhoto = null
    } else {
      const reader = new FileReader()
      reader.addEventListener('load', (e) => {
        // convert image file to base64 string
        formData.value.base64ImagePhoto = e.target.result
      },
        false,
      )
      if (file) {
        reader.readAsDataURL(file)
      }
    }
  } catch (error) {
    formData.value.base64ImagePhoto = null
  }
  // const image = e.target.files[0];
  // const reader = new FileReader();
  // reader.readAsDataURL(image);
  // reader.onload = e => {
  //   formData.value.base64ImagePhoto = e.target.result;
  //   //console.log(this.previewImage);
  // };
}
function cleanPhoto() {
  formData.value.base64ImagePhoto = null;
}

// onBeforeRouteLeave((to, from, next) => {
//   nextCallBack = null
//   let newValueStr = JSON.stringify(user.value)
//   if (originalValueStr != newValueStr) {
//     // Some value has changed - only leave after confirmation
//     nextCallBack = next
//     confirmationLeaveDialog.value.show()
//   } else {
//     // No value has changed, so we can leave the component without confirming
//     next()
//   }
// })

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
              <input type="password" v-model="password_confirmation" required />
            </div>
            <div class="form-group">
              <label for="confirmation_code">Code:</label>
              <input type="text" v-model="formData.confirmation_code" required />
            </div>
            <div class="form-group">
              <label for="confirm_confirmation_code">Confirm Code:</label>
              <input type="text" v-model="confirm_confirmation_code" required />
            </div>
          </div>
          <div class="form-group flex-column justify-content-between">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" v-model="formData.email" required />
            </div>
            <div class="fixed-size-container">
              <img :src="formData.base64ImagePhoto" alt="Uploaded Photo" class="uploaded-photo" />
            </div>
            <div class="d-flex justify-content-between flex-wrap align-items-baseline">
              <label for="inputPhoto" class="labels flex-grow-1 mx-1">Carregar</label>
              <label class="labels flex-grow-1 mx-1" @click.prevent="cleanPhoto">Apagar</label>
            </div>
            <div>
              <!-- <field-error-message :errors="errors" fieldName="base64ImagePhoto"></field-error-message> -->
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
.labels {
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
  width: 300px;
  /* Set your desired width */
  height: 376px;
  /* Set your desired height */
  overflow: hidden;
  /* Ensure the image doesn't overflow the container */
}

.uploaded-photo {
  width: 100%;
  height: 100%;
  max-width: px;
  max-height: 376px;
  object-fit: scale-down;
  /* or object-fit: contain; depending on your requirements */
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
    
  