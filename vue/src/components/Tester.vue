<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

let cancelAsync = false;
const vcardData = ref(null);

onMounted(async () => {
    const data = await loadVcard();
    if (!cancelAsync) {
        vcardData.value = data;
    }
});

onBeforeUnmount(() => {
    cancelAsync = true;
});
</script>

<template>
    <div v-if="vcardData.value">
        <h1>{{ vcardData.value.name }}</h1>
        <p>Phone Number: {{ vcardData.value.phone_number }}</p>
        <p>Email: {{ vcardData.value.email }}</p>
        <p>Photo URL: {{ vcardData.value.photo_url }}</p>
        <p>Blocked: {{ vcardData.value.blocked }}</p>
        <p>Balance: {{ vcardData.value.balance }}</p>
        <p>Max Debit: {{ vcardData.value.max_debit }}</p>
    </div>
</template>