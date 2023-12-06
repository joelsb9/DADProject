<script setup>
import { useRouter, RouterLink, RouterView } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useAdminStore } from './components/stores/admin.js'
import Login from './components/auth/Login.vue'
import { useVcardStore } from './components/stores/vcard.js'
import { ref, onMounted } from 'vue'


// import avatarNoneUrl from '@/assets/avatar-none.png';

// const toast = useToast()
// const adminStore = useAdminStore()
const router = useRouter()

// const logout = async () => {
//   if (await adminStore.logout()) {
//     toast.success('User has logged out of the application.')
//     clickMenuOption()
//     router.push({ name: 'home' })
//   } else {
//     toast.error('There was a problem logging out of the application!')
//   }
// }

// const clickMenuOption = () => {
//   const domReference = document.getElementById('buttonSidebarExpandId')
//   if (domReference) {
//     if (window.getComputedStyle(domReference).display !== "none") {
//       domReference.click()
//     }
//   }
// }

const vcardStore = useVcardStore()
// Simulate authentication check on component mount
const isLoggedIn = ref(false)

// On component mount, check if the user is already logged in
onMounted(async () => {
  isLoggedIn.value = await vcardStore.restoreToken()  
  console.log('isLoggedIn.value: ', isLoggedIn.value)
})

const registerVcard = () => {
  router.push({ name: 'RegisterVcard' })
}
</script>


<template >
  <!-- v-if="!isLoggedIn" -->
  <!-- Login and listening to the emit (registerVcard)-->
  <Login @registerVcard="registerVcard" />  
  <div v-if="isLoggedIn">
    <main>
      
      <aside>
        <!-- if isLoggedIn.value == flase show the login page-->
        <!-- <router-view />
        <router-link :to="{ name: 'Login' }">Login</router-link>
        
        <router-link :to="{ name: 'ChangePassword' }">Forgot password?</router-link> -->
        <!-- <router-link to="/dashboard">Dashboard</router-link>
        <router-link to="/transactions">Transactions</router-link>
        <router-link to="/profile">Profile</router-link>
        <router-link to="/settings">Settings</router-link> -->
      </aside>

      <!-- Display dynamic content based on the current route -->
    </main>

    <!-- Footer -->
    <footer>
      <p>&copy; 2023 vCard Platform</p>
    </footer>
  </div>
</template>


<style>
/* Add your global styles here */
body {
  margin: 0;
  font-family: 'Arial', sans-serif;
}

#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

header {
  background-color: #333;
  color: white;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

header h1 {
  margin: 0;
}

main {
  flex: 1;
  display: flex;
}

aside {
  width: 200px;
  background-color: #f0f0f0;
  padding: 20px;
}

router-link {
  display: block;
  margin-bottom: 10px;
  color: #333;
  text-decoration: none;
  font-weight: bold;
}

router-link:hover {
  color: #555;
}

footer {
  text-align: center;
  padding: 10px;
  background-color: #333;
  color: white;
}
</style>
 
