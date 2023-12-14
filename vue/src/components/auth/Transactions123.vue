<script setup>
import { onMounted, computed } from 'vue';
import { useVcardStore } from '../stores/vcard';

const vcardStore = useVcardStore();
onMounted(vcardStore.loadTransactionsVcard);

const transactions = computed(() => vcardStore.transactions);
</script>

<template>
    <div>
        <table>
            <thead>
                <tr>
                    <th>
                        <h1>ID</h1>
                    </th>
                    <th>
                        <h1>Type</h1>
                    </th>
                    <th>
                        <h1>Valor</h1>
                    </th>
                    <th>
                        <h1>Saldo Anterior</h1>
                    </th>
                    <th>
                        <h1>Novo Saldo</h1>
                    </th>
                    <th>
                        <h1>Data</h1>
                    </th>
                    <th>
                        <h1>Categoria</h1>
                    </th>
                    <th>
                        <h1>Referencia</h1>
                    </th>
                </tr>
            </thead>
        <tbody>
            <tr v-for="transaction in transactions" :key="transaction.id">
                <td>
                    <h3>{{ transaction.id }}</h3>
                </td>
                <td>
                    <h3>{{ transaction.type === 'C' ? 'Crédito' : 'Débito'}}</h3>
                </td>
                <td>
                    <h3>{{ transaction.value }}</h3>
                </td>
                <td>
                    <h3>{{ transaction.old_balance }}</h3>
                </td>
                <td>
                    <h3>{{ transaction.new_balance }}</h3>
                </td>
                <td>
                    <h3>{{ transaction.datetime }}</h3>
                </td>
                <td>
                    <h3>{{ transaction.category || 'sem categoria' }}</h3>
                </td>
                <td>
                    <h3>{{ transaction.payment_reference }}</h3>
                </td>
            </tr>
        </tbody>
    </table>
</div></template>