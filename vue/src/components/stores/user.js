import axios from 'axios'
import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useProjectsStore } from "./projects.js"
import { useToast } from "vue-toastification"

export const useUserStore = defineStore('user', () => {
    const socket = inject("socket")
    const toast = useToast()

    const serverBaseUrl = inject('serverBaseUrl')
    const projectsStore = useProjectsStore()

    const user = ref(null)

    const userName = computed(() => user.value?.name ?? 'Anonymous')

    const userId = computed(() => user.value?.id ?? -1)

    const userType = computed(() => user.value?.type ?? 'M')

    const userPhotoUrl = computed(() =>
        user.value?.photo_url
        ? serverBaseUrl + '/storage/fotos/' + user.value.photo_url
        : avatarNoneUrl)

    async function loadUser() {
        try {
            const response = await axios.get('users/me')
            user.value = response.data.data
        } catch (error) {
            clearUser()
            throw error
        }
    }

    function clearUser() {
        delete axios.defaults.headers.common.Authorization
        projectsStore.clearProjects()
        sessionStorage.removeItem('token')
        user.value = null
    }

    async function login(credentials) {
        try {
            const response = await axios.post('login', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await loadUser()
            socket.emit('loggedIn', user.value)
            await projectsStore.loadProjects()
            return true
        }
        catch(error) {
            clearUser()
            return false
        }
    }

    async function logout () {
        try {
            await axios.post('logout')
            socket.emit('loggedOut', user.value)
            clearUser()
            return true
        } catch (error) {
            return false
        }
    }

    async function changePassword(credentials) {
        if (userId.value < 0) {
            throw 'Anonymous users cannot change the password!'
        }
        try {
            await axios.patch(`users/${user.value.id}/password`, credentials)
            return true
        } catch (error) {
            throw error
        }
    }

    async function restoreToken () {
        let storedToken = sessionStorage.getItem('token')
        if (storedToken) {
            axios.defaults.headers.common.Authorization = "Bearer " + storedToken
            await loadUser()
            socket.emit('loggedIn', user.value)
            await projectsStore.loadProjects()
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
        loadUser,
        clearUser,
        login,
        logout,
        restoreToken,
        changePassword
    }
})
