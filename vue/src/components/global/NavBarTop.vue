<script setup>
import { ref, computed } from 'vue';
import { useRouter, RouterLink } from 'vue-router'
import { useUserStore } from '../stores/user';
import { useToast } from 'vue-toastification';

const toast = useToast();
const router = useRouter();
const userStore = useUserStore();

async function leaveAccount() {
    // Implement your logic for leaving the account here
    let userName = userStore.userName;
    let logout = await userStore.logout();
    if (logout === 1) {
        toast.success('Vcard ' + userName + ' left')
        router.push({ name: 'Login' })
    }
    else if (logout === -1) {
        toast.error(userName + ' was not logged in!')
    }
    else {
        toast.error('Problem logging out of the application!')
    }
}

</script>
  

<template>
    <div class="navbar">
        <ul class="nav nav-pills nav-fill gap-2 p-1 small bg-primary rounded-5 shadow-sm" id="pillNav2" role="tablist"
            style="--bs-nav-link-color: var(--bs-white); --bs-nav-pills-link-active-color: var(--bs-primary); --bs-nav-pills-link-active-bg: var(--bs-white);">
            <li class="nav-item" role="presentation">
                <router-link :to="{ name: 'Home' }" class="nav-link active rounded-5" id="home-tab2" data-bs-toggle="tab" type="button"
                    role="tab"  :aria-selected="$route.name === 'Home' ? 'true' : 'false'"><i class="bi bi-house" @click.prevent></i> Home</router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link :to="{ name: 'Transactions' }" class="nav-link rounded-5" id="profile-tab2" data-bs-toggle="tab"
                    type="button" role="tab"  :aria-selected="$route.name === 'Transactions' ? 'true' : 'false'" ><i class="bi bi-cash"></i> Transactions</router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link :to="{ name: 'Profile' }" class="nav-link rounded-5" id="contact-tab2" data-bs-toggle="tab" type="button"
                    role="tab"  :aria-selected="$route.name === 'Profile' ? 'true' : 'false'" @click.prevent><i class="bi bi-person"></i> Profile</router-link>
            </li>
            <li class="nav-item" role="presentation" @click="leaveAccount">
                <a href="#" class="nav-link rounded-5" id="contact-tab2" data-bs-toggle="tab" type="button" role="tab"
                    aria-selected="false" @click.prevent><i class="bi bi-door-closed"></i> Leave</a>
            </li>
        </ul>
    </div>
</template>
  

<style scoped>
.navbar {
    padding: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
  