<script setup>
import { ref, onMounted, watch, defineProps } from 'vue'
import { useTransactionsStore } from '../stores/transactions.js'

const props = defineProps({
    vcard: String
})

const transactionsStore = useTransactionsStore()

watch(props, (newProps) => {
    if (newProps.vcard) {
        transactionsStore.loadTransactions(newProps.vcard)
    }
})
</script>

<template>
    <div class="profile d-flex align-items-center justify-content-center">
        <div class="main-content p-3 rounded shadow-sm">
            <h2 class="greeting mb-4">Transactions</h2>
            <table class="table table-striped custom-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Old Balance</th>
                        <th>New Balance</th>
                        <th>Payment Type</th>
                        <th>Payment Reference</th>
                        <th>Category ID</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transactionsStore.transactions" :key="transaction.id">
                        <td>{{ transaction.date }}</td>
                        <td>{{ transaction.type }}</td>
                        <td>{{ transaction.value }}</td>
                        <td>{{ transaction.old_balance }}</td>
                        <td>{{ transaction.new_balance }}</td>
                        <td>{{ transaction.payment_type }}</td>
                        <td>{{ transaction.payment_reference }}</td>
                        <td>{{ transaction.category_id }}</td>
                        <td><i class="bi bi-eye-fill"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.profile {
    height: 100vh;
    background-color: #62beff;
}

.main-content {
    max-width: 1200px;
    margin: auto;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    border: 1px solid #b8daff;
    border-radius: 20px;
}

.greeting {
    color: #1890ff;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    font-weight: 300;
    /* Make the column titles thinner */
    color: #fff;
    /* Make the column titles white */
    padding: 10px;
    /* Add some padding to the column titles */
    background-color: #1890ff;
    /* Add a blue background to the column titles */
}

tbody td {
    color: #333;
    padding: 20px;
    border: 1px solid #ddd;
    /* Add a light gray border to the table cells */
}

.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}</style>