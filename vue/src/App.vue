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

if (!isLoggedIn.value) {
  router.push({ name: 'Login' }); // Substitua 'Login' pelo nome da sua rota de login
}
</script>


<template>
  <div id="app" v-if="!isLoggedIn">
    <router-view />
    <footer>
      <p>&copy; 2023 vCard Platform by Pedro Alfaiate, Tiago Cabecinhas, Amelia Górska & Joel Bastos</p>
    </footer>
  </div>
</template>

<style>
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

footer {
  margin-top: auto; /* Push the footer to the bottom */
  text-align: center;
  padding: 10px;
  background-color: #333;
  color: white;
}

/* Add your global styles here */
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
</style>