<script setup>
import { useRouter, RouterLink, RouterView } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useUserStore } from './components/stores/user.js'
import NavBarTop from './components/global/NavBarTop.vue'

import { ref, onMounted } from 'vue'


// import avatarNoneUrl from '@/assets/avatar-none.png';

const toast = useToast()
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

const userStore = useUserStore()
// Simulate authentication check on component mount
const isLoggedIn = ref(false)

// On component mount, check if the user is already logged in
onMounted(async () => {
  isLoggedIn.value = await userStore.restoreToken()
  //console.log('isLoggedIn.value: ', isLoggedIn.value)
})

</script>


<template>
  <div id="app">
    <NavBarTop />
    <router-view />
  </div>
</template>

<style>
html {
  background-color: #62beff;
}

#app {
  display: flex;
  flex-direction: column;
  max-height: 100vh;
  background-color: #62beff;
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