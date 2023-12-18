import axios from 'axios'
import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
//import { useProjectsStore } from "./projects.js"
import { useToast } from "vue-toastification"
import { useTransactionsStore } from './transactions.js'
import { useUsersStore } from './users.js'

export const useUserStore = defineStore('user', () => {
    const socket = inject("socket")
    const toast = useToast()

    const serverBaseUrl = inject('serverBaseUrl')
    //const projectsStore = useProjectsStore()

    const user = ref(null)

    const userName = computed(() => user.value?.name ?? 'Anonymous')

    const userId = ref(null)

    const userType = ref('U')

    const transactionsStore = useTransactionsStore()

    const usersStore = useUsersStore()

    const userPhotoUrl = computed(() =>
        user.value?.photo_url && user.value.phone_number
            ? serverBaseUrl + '/storage/fotos/' + user.value.photo_url
            : avatarNoneUrl)

    async function loadUser(user_type) {
        try {
            if (user_type == 'V') {
                const response = await axios.get('vcards/me')
                user.value = response.data
                userType.value = 'V'
                userId.value = user.value.phone_number
                transactionsStore.loadTransactions(userId.value)
            }
            else if (user_type == 'A') {
                const response = await axios.get('admins/me')
                user.value = response.data
                userType.value = 'A'
                userId.value = user.value.id
                usersStore.loadAdmins()
                usersStore.loadVcards()
            }
        } catch (error) {
            console.error('Error loading user or transactions:', error) // Log the error
            clearUser()
        }
    }

    function clearUser() {
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        user.value = null
        userId.value = null
        userType.value = 'U'
        transactionsStore.clearTransactions();
    }

    async function login(credentials) {
        try {
            const response = await axios.post('login', credentials);
            axios.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token;
            sessionStorage.setItem('token', response.data.access_token);
            if (response.data.msg) {
                return response.data.msg
            }
            await loadUser(response.data.user_type);

            if (user.value) {
                // Ensure user.value is defined before accessing properties
                // socket.emit('loggedIn', user.value);
                socket.on(user.value.phone_number, () => {
                    console.log('You have a new transaction!');
                });
            }
            await loadUser();
            return true;
        } catch (error) {
            clearUser();
            console.error(error);
            if (error.response && error.response.data && error.response.data.msg) {
                const errorMessage = error.response.data.msg;
                console.error(errorMessage);
                return errorMessage;
            } else {
                // Handle other types of errors
                console.error('An unexpected error occurred.');
                return 'An unexpected error occurred.';
            }
        }
    }

    function isVcard(input) {
        // Check if the input is a 9-digit number
        return /^\d{9}$/.test(input.username);
    }

    async function logout() {
        try {
            let response = await axios.post('logout');

            // Assuming a successful logout response has a specific status code (e.g., 200)
            if (response.status === 200) {
                socket.emit('loggedOut', user.value);
                clearUser();
                return 1;
            } else {
                // Handle other successful response codes if needed
                console.error('Unexpected response status:', response.status);
                return 0;
            }
        } catch (error) {
            // Check if the error is a 401 (Unauthorized)
            if (error.response && error.response.status === 401) {
                console.error('Unauthorized request:', error.response.status);
                return -1;
                // Handle unauthorized user (e.g., redirect to login page)
                // Example: router.push('/login');
            } else {
                console.error('Error:', error);
            }
            return 0;
        }
    }


    async function changePassword(credentials) {
        if (userId.value == -1) {
            throw 'Anonymous users cannot change the password!'
        }
        try {
            if (userType.value == 'V') {
                await axios.patch(`vcards/${userId.value}/password`, credentials)
            }
            else if (userType.value == 'A') {
                await axios.patch(`admins/${userId.value}/password`, credentials)
            }
            return true
        } catch (error) {
            console.log("Error changing password:", error);
            throw error
        }
    }

    async function restoreToken() {
        let storedToken = sessionStorage.getItem('token')
        if (storedToken) {
            axios.defaults.headers.common.Authorization = "Bearer " + storedToken
            await loadUser()
            socket.emit('loggedIn', user.value)

            await loadUser()

            return true
        }
        clearUser()
        return false
    }

    socket.on('insertedUser', (insertedUser) => {
        toast.info(`User #${insertedUser.id} (${insertedUser.name}) has registered successfully!`)
    })

    socket.on('updatedUser', (updatedUser) => {
        if (user.value?.id == updatedUser.id) {
            user.value = updatedUser
            toast.info('Your user profile has been changed!')
        } else {
            toast.info(`User profile #${updatedUser.id} (${updatedUser.name}) has changed!`)
        }
    })

    return {
        user,
        userId,
        userName,
        userType,
        userPhotoUrl,
        serverBaseUrl,
        loadUser,
        clearUser,
        login,
        logout,
        restoreToken,
        changePassword
    }
})
