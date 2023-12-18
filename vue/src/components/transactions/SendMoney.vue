<script setup>
import { ref, computed, onMounted, watch, defineProps } from 'vue';
import { useUserStore } from '../stores/user.js';
import { useTransactionsStore } from '../stores/transactions.js'; // Add this line
import { useToast } from "vue-toastification";
import axios from 'axios';

const toast = useToast();
const transactionsStore = useTransactionsStore();

const props = defineProps({
    admin: {
        type: Boolean,
        required: true,
        default: false
    }
});

const store = useUserStore();
const user = ref(store.user);

const transaction = ref({
    value: 0,
    vcard: user.value.phone_number,
    type: 'D',
    payment_reference: '',
    payment_type: 'VCARD',
    category_id: '',
    description: '',
    pair_vcard: ''
})

const payment_types = ref([]);
const categories = ref([]);

const errors = ref({
    payment_type: '',
    payment_reference: '',
    value: '',
    category_id: ''
});
const validateForm = () => {
    
    if (!validatePaymentReference()) {
        return false;
    }
    if (transaction.value.value > user.value.balance) {
        toast.error('You dont have enough money')
        errors.value.value = 'You dont have enough money'
        return false;
    }
    //if category.type == 'C' then the transaction.value.type = 'C'
    //if category.type == 'D' then the transaction.value.type = 'D'
    if (transaction.value.category_id != '') {
        let category = categories.value.find(element => element.id == transaction.value.category_id)
        if (!(category.type == 'C' && transaction.value.type == 'D')) {
            errors.value.category_id = 'Credit transactions can only use credit categories'
            return false;
        }
        else if (!(category.type == 'C' && transaction.value.type == 'C')) {
            errors.value.category_id = 'Debit transactions can only use debit categories'
            return false;
        }
    }
    // Return true if the form is valid, false otherwise
    return !Object.values(errors.value).some((error) => error);
};

onMounted(async () => {
    'use strict';
    let response = await axios.get('transactions/payment-types')
    const help = response.data
    //for each element in the array, add the value payment_type to the payment_types array
    help.forEach(element => {
        payment_types.value.push(element.payment_type)
    });
    response = await axios.get(`vcards/${store.userId}/categories`)
    categories.value = response.data.filter(element => element.type == 'D')

    // Add event listener for form validation
    const forms = document.querySelector('.needs-validation');
    Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.validateForm()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
});


const validatePaymentReference = () => {

    switch (transaction.value.payment_type) {
        case 'VCARD':
            if (!/^9\d{8}$/.test(transaction.value.payment_reference)) {
                errors.value.payment_reference = 'Reference must be a valid phone number and start with 9.';
                return false;
            }
            break;

        case 'MBWAY':
            if (!/^9\d{8}$/.test(transaction.value.payment_reference)) {
                errors.value.payment_reference = 'Reference must be a valid phone number and start with 9.';
                return false;
            }
            break;

        case 'PAYPAL':
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(transaction.value.payment_reference)) {
                errors.value.payment_reference = 'Reference must be a valid email address.';
                return false;
            }
            break;

        case 'IBAN':
            if (!/^[A-Z]{2}\d{23}$/.test(transaction.value.payment_reference)) {
                errors.value.payment_reference = 'Reference must be a valid IBAN.';
                return false;
            }
            break;

        case 'MB':
            if (!/^\d{5}-\d{9}$/.test(transaction.value.payment_reference)) {
                errors.value.payment_reference = 'Reference must be a valid MB reference.';
                return false;
            }
            break;

        case 'VISA':
            if (!/^4\d{15}$/.test(transaction.value.payment_reference)) {
                errors.value.payment_reference = 'Reference must be a valid VISA card number.';
                return false;
            }
            break;

        default:
            errors.value.payment_reference = '';
            break;
    }

    errors.value.payment_reference = ''; // Reset the error message if validation passes
    return true;
};

const submitTransaction = async () => {
    if (transaction.value.payment_type == 'VCARD') {
        transaction.value.pair_vcard = transaction.value.payment_reference;
    }
    else {
        transaction.value.pair_vcard = '';
    }
    if (!validateForm()) {
        return;
    }
    const response = await transactionsStore.sendMoney(transaction.value);
    if (response == true) {
        toast.success('Transaction completed');
    }
    else {
        toast.error(response.value ?? 'Failed to send money');
    }
    // console.log(amount.value)
    // const transaction = {
    //     vcard: vcard.value,
    //     payment_type: payment_type.value,
    //     payment_reference: payment_reference.value,
    //     amount: amount.value,
    //     category: category.value
    // };
};



</script>

<template>
     <div class="send-money home">
    <div class="main-content">
      <h1 class="greeting">Send Money</h1>
      <form @submit.prevent="submitTransaction" class="row g-3 needs-validation section" novalidate>
        <div v-if="props.admin">
          <label for="type">Type:</label>
          <select id="type" v-model="transaction.type" required>
            <option disabled value="">Please select one</option>
            <option :key="D" :value="D">Debit</option>
            <option :key="C" :value="C">Credit</option>
          </select>
        </div>
        <div>
          <label for="payment_type">Payment Type:</label>
          <select id="payment_type" :state="errors.payment_type" v-model="transaction.payment_type" required>
            <option disabled value="">Please select one</option>
            <option v-for="type in payment_types" :key="type" :value="type">{{ type }}</option>
          </select>
          <p class=".alert-warning" v-show="errors.payment_type">{{ errors.payment_type }}</p>
        </div>
        <div>
          <label for="vcard">Reference:</label>
          <input id="vcard" :state="errors.payment_reference" v-model="transaction.payment_reference" type="text" required>
          <p class=".alert-warning" v-show="errors.payment_reference">{{ errors.payment_reference }}</p>
        </div>
        <div>
          <label for="amount">Amount:</label>
          <input id="amount" v-model="transaction.value" type="number" min="0" required :state="errors.value">
          <p class=".alert-warning" v-show="errors.value">{{ errors.value }}</p>
        </div>
        <div>
          <label for="category_id">Category (Optional):</label>
          <select id="category_id" v-model="transaction.category_id" :state="errors.category_id">
            <option disabled value="">No option</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ store.userType == 'V' ? 'ID: ' + category.id + ' - Name: ' + category.name : '' }}
            </option>
          </select>
          <p class=".alert-warning" v-show="errors.category_id">{{ errors.category_id }}</p>
        </div>
        <div>
          <label for="description">Description:</label>
          <input id="description" v-model="transaction.description" type="text">
        </div>
        <button class="btn btn-primary" type="submit">Send Money</button>
      </form>
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
    margin-left: 20px;
    margin-right: 20px;
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