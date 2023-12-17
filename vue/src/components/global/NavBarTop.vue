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
    let userType = userStore.userType;
    let logout = await userStore.logout();
    if (logout === 1) {
        if(userType == 'V'){
            toast.success('Vcard ' + userName + ' left')
        }
        else if(userType == 'A'){
            toast.success('Admin ' + userName + ' left')
        }
        router.push({ name: 'Login' })
    }
    else if (logout === -1) {
        toast.error(userName + ' was not logged in!')
    }
    else {
        toast.error('Problem logging out of the application!')
    }
}
async function goToTransactions() {
    router.push({ name: 'Transactions' })
}
async function goToHomePage() {
    router.push({ name: 'Home' })
}
async function goToStatistics() {
    router.push({ name: 'Statistics' })
}
async function goToProfile() {
    router.push({ name: 'Profile' })
}

</script>
  

<template>
    <div class="navbar">
        <ul class="nav nav-pills nav-fill gap-2 p-1 small bg-primary rounded-5 shadow-sm" id="pillNav2" role="tablist"
            style="--bs-nav-link-color: var(--bs-white); --bs-nav-pills-link-active-color: var(--bs-primary); --bs-nav-pills-link-active-bg: var(--bs-white);">
            <li class="nav-item" role="presentation" @click="goToHomePage">
                <router-link to="/" :class="['nav-link', 'rounded-5', $route.path === '/homepage' ? 'active' : '']"
                    id="home-tab2" data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Home' ? 'true' : 'false'" @click.prevent>
                    <i class="bi bi-house"></i> Home
                </router-link>
            </li>
            <li class="nav-item" role="presentation" @click="goToTransactions">
                <router-link :to="'/transactions'"
                    :class="['nav-link', 'rounded-5', $route.path === '/transactions' ? 'active' : '']" id="profile-tab2"
                    data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Transactions' ? 'true' : 'false'">
                    <i class="bi bi-cash"></i> Transactions
                </router-link>
            </li>
            <li class="nav-item" role="presentation" @click="goToProfile">
                <router-link :to="{ name: 'Profile' }"
                    :class="['nav-link', 'rounded-5', $route.name === 'Profile' ? 'active' : '']" id="contact-tab2"
                    data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Profile' ? 'true' : 'false'" @click.prevent>
                    <i class="bi bi-person"></i> Profile
                </router-link>
            </li>
            <li class="nav-item" role="presentation" @click="goToStatistics">
                <router-link :to="{ name: 'Statistics' }"
                    :class="['nav-link', 'rounded-5', $route.name === 'Statistics' ? 'active' : '']" id="contact-tab2"
                    data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Statistics' ? 'true' : 'false'" @click.prevent>
                    <i class="bi bi-bar-chart-line-fill"></i> Statistics
                </router-link>
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
  