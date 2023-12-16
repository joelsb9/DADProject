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

const expandedRow = ref(null);

const handleEyeClick = (index) => {
    console.log(`Eye icon clicked on row ${index}`);
    expandedRow.value = expandedRow.value === index ? null : index;
};

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
                    <template v-for="(transaction, index) in transactionsStore.transactions" :key="transaction.id">
                        <tr>
                            <td>{{ transaction.date }}</td>
                            <td>{{ transaction.type }}</td>
                            <td>{{ transaction.value }}</td>
                            <td>{{ transaction.old_balance }}</td>
                            <td>{{ transaction.new_balance }}</td>
                            <td>{{ transaction.payment_type }}</td>
                            <td>{{ transaction.payment_reference }}</td>
                            <td>{{ transaction.category_id }}</td>
                            <td>
                                <i class="bi bi-eye-fill clickable"
                                    v-if="transaction.description && transaction.description.trim() !== ''"
                                    @click="handleEyeClick(index)"></i>
                            </td>
                        </tr>
                        <transition name="slide-down">
                            <tr v-if="expandedRow === index" class="description-row">
                                <td colspan="9">
                                    <div class="description-content">Description: {{ transaction.description ?
                                        transaction.description : 'No Description' }}</div>
                                </td>
                            </tr>
                        </transition>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.slide-fade-enter-active {
    transition: all .3s ease;
}

.slide-fade-leave-active {
    transition: all .3s ease;
}

.slide-fade-enter,
.slide-fade-leave-to {
    transform: translateY(10px);
    opacity: 0;
}

.description-content {
    overflow: hidden;
}

.description-row .description-content {
    max-height: 100px;
    /* Adjust this to fit your content */
}

.profile {
    height: 100vh;
    background-color: #62beff;
}

.description-row {
    border-top: none;
    background-color: #fff;
    /* Change this to match the color of your other rows */
}

.clickable {
    cursor: pointer;
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
}
</style>