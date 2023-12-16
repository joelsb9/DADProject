<script setup>
import { ref } from 'vue';
import { useToast } from "vue-toastification";
import { useUserStore } from './stores/user.js';
import { useTransactionsStore } from './stores/transactions.js'

const toast = useToast();
const userStore = useUserStore();
const transactionsStore = useTransactionsStore();

userStore.restoreToken();

const user = ref(userStore.user);
const editMode = ref(false);

const lastTransaction = ref(transactionsStore.lastTransaction);

</script>

<template>
    <div class="home">
        <div class="main-content">
            <h1 class="greeting">Hello, <span class="username">{{ user && user.name ? user.name : 'Guest' }}</span></h1>
            <div class="section">
                <div class="balance-section">
                    <h2 class="section-title">Balance</h2>
                    <h3 class="balance text-primary">{{ user.balance }}</h3>
                </div>
            </div>
            <div class="section">
                <div class="transaction-section">
                    <h3 class="section-title">Last Transaction</h3>
                    <h3 class="transaction text-primary" v-if="lastTransaction">
                        {{ lastTransaction ? `${lastTransaction.type === 'C' ? '+' :
                            '-'}${lastTransaction.value}€ ${lastTransaction.type === 'C' ? 'from' : 'to'}
                                                ${lastTransaction.payment_reference}` : '' }}
                    </h3>
                </div>
            </div>
            <div class="section">
                <h4 class="section-title">Send Money</h4>
                <!-- Add your send money content here -->
            </div>
        </div>
    </div>
</template>

<style scoped>
.home {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #62beff;
}

.balance-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.transaction-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.main-content {
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    width: 100%;
}

.main-content:hover {
    transform: scale(1.01);
}

.greeting {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 1.5em;
    text-align: center;
    animation: fadeIn 2s;
}

.username {
    color: #3498db;
    font-weight: bold;
}

.section {
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 5px;
    background-color: #e9e8e8;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease-in-out;
}

.section:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
}

.section-title {
    color: #7f8c8d;
    font-size: 1.2em;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}


@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

body {
    background-color: #62beff;
    margin: 0;
    padding: 0;
    height: 100vh;
}
</style>