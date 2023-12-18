<template>
    <div class="send-money home">
        <div class="main-content">
            <h1 class="greeting">Send Money</h1>
            <form @submit.prevent="submitTransaction" class="section">
                <div>
                    <label for="payment_type">Payment Type:</label>
                    <select id="payment_type" v-model="payment_type" required>
                        <option disabled value="">Please select one</option>
                        <option>VCARD</option>
                        <!-- Add other payment types here -->
                    </select>
                </div>
                <div>
                    <label for="vcard">Reference:</label>
                    <input id="vcard" v-model="payment_reference" type="text" required>
                </div>
                <div>
                    <label for="amount">Amount:</label>
                    <input id="amount" v-model="amount" type="number" min="0" required>
                </div>
                <div>
                    <label for="payment_reference">Category (Optional):</label>
                    <input id="payment_reference" v-model="category" type="text" Optional>
                </div>
                <button>Send Money</button>
            </form>
        </div>
    </div>
    <!--
vcard: The username of the authenticated user who owns the vCard. This is checked at the beginning of the code snippet you provided.
fetch the number from the logged in user

payment_type: This determines if the transaction is a vCard transaction. If it is (payment_type equals 'VCARD'), a new transaction is created for the destination card.
v-model="payment_type"

payment_reference: If the payment_type is 'VCARD', this is used to find the destination vCard and create a new transaction for it. It's also used to set the pair_vcard for both the new and original transactions.
v-model="payment_reference"

type: This is used to set the type of the transaction. It also affects the calculation of the new_balance for the new transaction if the payment_type is 'VCARD'.
Always Debit, witch is a 'D' in the Db

value: This is used to set the value of the transaction. It also affects the calculation of the new_balance for the new transaction if the payment_type is 'VCARD'.
v-model.number="amount"

category_id (optional): If this is set in the data, it's used to set the category_id for the new transaction. 
v-model="category"
-->
</template>
  
<script setup>
import { ref, computed } from 'vue';
import { useUserStore } from '../stores/user.js';
import { sendMoney } from '../stores/transactions.js'; // Add this line


const store = useUserStore();
const user = ref(store.user);

const amount = ref(0);
const vcard = computed(() => user.value.phone_number);
const payment_type = ref('');
const payment_reference = ref('');
const category = ref('');


const submitTransaction = async () => {
    console.log(amount.value)
    const transaction = {
        vcard: vcard.value,
        payment_type: payment_type.value,
        payment_reference: payment_reference.value,
        amount: amount.value,
        category: category.value
    };
    await sendMoney(transaction);
};
</script>
  
<style scoped>
.home {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #62beff;
}

.main-content {
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    width: 100%;
}

.greeting {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 1.5em;
    text-align: center;
}

.section {
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 5px;
    background-color: #e9e8e8;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
}

.send-money-section {
    margin-top: 20px;
    align-self: flex-end;
}

.section div {
    margin-bottom: 10px;
}

.section label {
    display: block;
    margin-bottom: 5px;
}

.section input,
.section select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.section button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #3498db;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.section button:hover {
    background-color: #2980b9;
}
</style>