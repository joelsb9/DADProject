<script setup>
import { useRouter, RouterLink, RouterView } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useUserStore } from './components/stores/user.js'
import NavBarTop from './components/global/NavBarTop.vue'
import NavBarTopAdmin from './components/admins/NavBarTopAdmin.vue'

import { ref, onMounted, watch, computed } from 'vue'


// import avatarNoneUrl from '@/assets/avatar-none.png';

const toast = useToast()
const router = useRouter()

const userStore = useUserStore()
// Simulate authentication check on component mount
const isLoggedIn = ref(false)
const userType = computed(() => userStore.userType ?? 'U')

// On component mount, check if the user is already logged in
onMounted(async () => {
  isLoggedIn.value = await userStore.restoreToken()
  //console.log('isLoggedIn.value: ', isLoggedIn.value)
})

</script>


<template>
  <div id="app">
    <NavBarTop/>
    <NavBarTopAdmin v-if="userType === 'A'" />
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