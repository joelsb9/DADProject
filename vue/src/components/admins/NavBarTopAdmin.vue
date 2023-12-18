<script setup>
import { ref, computed } from 'vue';
import { useRouter, RouterLink } from 'vue-router'
import { useUserStore } from '../stores/user';
import { useToast } from 'vue-toastification';

const toast = useToast();
const router = useRouter();
const userStore = useUserStore();
const userType = computed(() => userStore.userType ?? 'U');

async function goToSend() {
    router.push({ name: 'Send' })
}
async function goToUsers() {
    router.push({ name: 'Users' })
}
async function goToDefaultCategories() {
    router.push({ name: 'DefaultCategories' })
}

</script>
  

<template>
    <div class="navbar">
        <ul class="nav nav-pills nav-fill gap-2 p-1 small bg-primary rounded-5 shadow-sm" id="pillNav2" role="tablist"
            style="--bs-nav-link-color: var(--bs-white); --bs-nav-pills-link-active-color: var(--bs-primary); --bs-nav-pills-link-active-bg: var(--bs-white);">
            <li class="nav-item" role="presentation" @click="goToSend">
                <router-link :to="{ name: 'Send' }" :class="['nav-link', 'rounded-5', $route.path === '/homepage' ? 'active' : '']"
                    id="home-tab2" data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Home' ? 'true' : 'false'">
                    <i class="bi bi-send-check-fill"></i> Send
                </router-link>
            </li>
            <li class="nav-item" role="presentation" @click="goToUsers">
                <router-link :to="{ name: 'Users' }"
                    :class="['nav-link', 'rounded-5', $route.path === '/transactions' ? 'active' : '']" id="profile-tab2"
                    data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Transactions' ? 'true' : 'false'">
                    <i class="bi bi-people"></i> Users
                </router-link>
            </li>
            <li class="nav-item" role="presentation" @click="goToDefaultCategories">
                <router-link :to="{ name: 'DefaultCategories' }"
                    :class="['nav-link', 'rounded-5', $route.name === 'Profile' ? 'active' : '']" id="contact-tab2"
                    data-bs-toggle="tab" type="button" role="tab"
                    :aria-selected="$route.name === 'Profile' ? 'true' : 'false'">
                    <i class="bi bi-stack"></i> Default Categories
                </router-link>
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
  