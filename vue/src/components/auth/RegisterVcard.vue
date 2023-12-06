<script setup>
  import { ref, inject } from 'vue';
  import { useRouter, RouterLink, RouterView } from 'vue-router'
  const axios = inject('axios')
  
  const formData = ref({
    name: '',
    email: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    confirmation_code: '',
    confirm_confirmation_code: '',
    photo: null,
    // Add other fields as needed
  });
  
  // const handleFileUpload = (event) => {
  //   formData.value.photo = event.target.files[0];
  // };
  
  // const register = () => {
  //   // Create a FormData object to handle file upload
  //   const formDataForApi = new FormData();
  //   for (const key in formData) {
  //     formDataForApi.append(key, formData[key]);
  //   }
  
  //   // Make an API call to your Laravel backend to handle registration
  //   // You can use Axios or another HTTP library for this purpose
  //   axios.post('/api/register', formDataForApi)
  //     .then(response => {
  //       // Handle success, e.g., redirect to a new page
  //       console.log(response.data);
  //     })
  //     .catch(error => {
  //       // Handle error, e.g., show validation errors
  //       console.error(error.response.data);
  //     });
  // };

  const goBack = () => {
    const router = useRouter()
    router.back()
  }
  </script>

<template>
    <div class="register-container">
      <div class="register-box">
        <h2>Register</h2>
        <form @submit.prevent="register">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" v-model="formData.name" required />
          </div>
  
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" v-model="formData.email" required />
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
            <label for="confirmation_code">Confirmation Code:</label>
            <input type="text" v-model="formData.confirmation_code" required />
          </div>
  
          <div class="form-group">
            <label for="confirm_confirmation_code">Confirm Confirmation Code:</label>
            <input type="text" v-model="formData.confirm_confirmation_code" required />
          </div>
  
          <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" @change="handleFileUpload" />
          </div>
  
          <!-- Add other fields as needed -->
  
          <button type="submit">Register</button>
          <button @click="goBack"> Back</button>
        </form>
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
    
  