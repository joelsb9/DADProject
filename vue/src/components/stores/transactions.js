import axios from 'axios'
import { ref, inject, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from "vue-toastification"
import { useUserStore } from './user.js'



export const sendMoney = async (transaction) => {
    const toast = useToast()
    console.log(transaction)
    const response = await axios.post(`/transactions`, {
        vcard: transaction.vcard,
        payment_type: transaction.payment_type,
        payment_reference: transaction.payment_reference,
        type: 'D',
        value: transaction.amount,
        category_id: transaction.category_id,
    }, {
        headers: {
            'Content-Type': 'application/json',
        },
    }).catch((error) => {

        console.log(error.response.data.message);
        toast.error(error.response.data.message);

    });


    /*if (!response.ok) {
        if (response.status === 422) {

            throw new Error(data.message);
        } else {
            const message = `An error has occurred: ${response.status}`;
            throw new Error(message);
        }
    }*/

    const data = await response.data;
    return data;
};

export const useTransactionsStore = defineStore('transactions', () => {
    const socket = inject("socket")
    const toast = useToast()

    //const userStore = useUserStore() // Use the user store
    const transactions = ref([])

    const loadTransactions = async (vcardId) => {
        //console.log(`vcards/${vcard}/transactions`)
        const response = await axios.get(`vcards/${vcardId}/transactions`, {
            params: {
                columns: 'id,vcard,date,datetime,type,value,old_balance,new_balance,payment_type,payment_reference,pair_transaction,pair_vcard,category_id,description'
            }
        });
        transactions.value = response.data;
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