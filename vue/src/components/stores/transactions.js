import axios from 'axios'
import { ref, inject, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from "vue-toastification"
import { useUserStore } from './user.js'



export const useTransactionsStore = defineStore('transactions', () => {
    const socket = inject("socket")
    const toast = useToast()
    const serverBaseUrl = inject('serverBaseUrl')

    const userStore = useUserStore() // Use the user store
    console.log(`${serverBaseUrl}/api/transactions`)

    const transactions = ref([])


    const loadTransactions = async (vcard) => {
        console.log(`${serverBaseUrl}/api/vcards/${vcard}/transactions`)
        const response = await axios.get(`${serverBaseUrl}/api/vcards/${vcard}/transactions`, {
            params: {
                columns: 'id,vcard,date,datetime,type,value,old_balance,new_balance,payment_type,payment_reference,pair_transaction,pair_vcard,category_id,description'
            }
        });
        transactions.value = response.data;
        console.log(response.data);
    };

    const lastTransaction = computed(() => {
        return transactions.value[transactions.value.length - 1];
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
        serverBaseUrl,
        lastTransaction,
        loadTransactions,
    }
})