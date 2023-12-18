import axios from 'axios'
import { ref, inject, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from "vue-toastification"
import { useUserStore } from './user.js'


export const useTransactionsStore = defineStore('transactions', () => {
    const socket = inject("socket")
    const toast = useToast()

    //const userStore = useUserStore() // Use the user store
    const transactions = ref([])

    async function sendMoney(transaction) {
        try {
            const response = await axios.post(`/transactions`, transaction);
            transactions.value.push(response.data);
            if(response.data.msg){
                return response.data.msg
            }
            return true;
        } catch (error) {   
            console.error('Error loading transactions:', error) // Log the error
            return error
        }
    };

    async function loadTransactions (vcardId) {
        //console.log(`vcards/${vcard}/transactions`)
        try {
            const response = await axios.get(`vcards/${vcardId}/transactions`);
            transactions.value = response.data;
        } catch (error) {
            console.error('Error loading transactions:', error) // Log the error
            clearTransactions()
        }
    };

    function clearTransactions() {
        transactions.value = null
    }
    const lastTransaction = computed(() => {
        if (transactions.value && transactions.value.length > 0) {
            return transactions.value[transactions.value.length - 1];
        } else {
            // Handle the case where transactions.value is null or empty
            return null; // Or you can return a default value or handle it differently
        }
    });

    socket.on('insertedTransaction', (insertedTransaction) => {
        transactions.value.push(insertedTransaction)
        toast.success(`Transaction #${insertedTransaction.id} has been added successfully!`)
    })

    socket.on('updatedTransaction', (updatedTransaction) => {
        const index = transactions.value.findIndex(t => t.id === updatedTransaction.id)
        if (index !== -1) {
            transactions.value[index] = updatedTransaction
            toast.success(`Transaction #${updatedTransaction.id} has been updated!`)
        }
    })

    return {
        transactions,
        clearTransactions,
        lastTransaction,
        loadTransactions,
        sendMoney,
    };
})