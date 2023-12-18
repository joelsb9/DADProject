import axios from 'axios'
import { ref, inject } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from "vue-toastification"

export const useUsersStore = defineStore('users', () => {
    const socket = inject("socket")
    const toast = useToast()

    const vcards = ref([])
    const admins = ref([])

    async function loadVcards(){
        let response = await axios.get('vcards');
        vcards.value = response.data.data;
    };

    function clearVcards(){
        vcards.value = null
    }
    async function loadAdmins() {
        let response = await axios.get('admins');
        admins.value = response.data.data;
    };
    
    function clearAdmins() {
        admins.value = null;
    }

    socket.on('insertedVcard', (insertedVcard) => {
        vcards.value.push(insertedVcard)
        toast.success(`Vcard #${insertedVcard.id} has been added successfully!`)
    })
    
    socket.on('updatedVcard', (updatedVcard) => {
        const index = vcards.value.findIndex(v => v.id === updatedVcard.id)
        if (index !== -1) {
            vcards.value[index] = updatedVcard
            toast.success(`Vcard #${updatedVcard.id} has been updated!`)
        }
    })

    return {
        vcards,
        clearVcards,
        loadVcards,
        admins,
        clearAdmins,
        loadAdmins,
    }
})