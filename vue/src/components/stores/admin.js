import axios from 'axios'
import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from 'vue-toastification'

export const useAdminStore = defineStore('admin', () => {
  const socket = inject("socket")
  const toast = useToast()

  const admin = ref(null)

  const adminName = computed(() => admin.value?.name ?? 'Admin')

  const adminId = computed(() => admin.value?.id ?? -1)

  const adminType = computed(() => admin.value?.type ?? 'A')

  
  async function loadAdmin() {
    try {
      const response = await axios.get('admins/me')
      admin.value = response.data.data
    } catch (error) {
      clearAdmin()
      throw error
    }
  }

  function clearAdmin() {
    delete axios.defaults.headers.common.Authorization
    sessionStorage.removeItem('token')
    admin.value = null
  }

  async function loginAdmin(credentials) {
    try {
      const response = await axios.post('login', credentials)
      axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
      sessionStorage.setItem('token', response.data.access_token)
      await loadAdmin()
      socket.emit('loggedIn', admin.value)
      return true
    } catch (error) {
      clearAdmin()
      return false
    }
  }

  async function logoutAdmin() {
    try {
      await axios.post('logout')
      socket.emit('loggedOut', admin.value)
      clearAdmin()
      return true
    } catch (error) {
      return false
    }
  }

  async function changeAdminPassword(newPassword) {
    if (adminId.value < 0) {
      throw 'Anonymous admins cannot change the password!'
    }
    // eslint-disable-next-line no-useless-catch
    try {
      await axios.put(`admins/${adminId.value}/password`, { new_password: newPassword })
      return true
    } catch (error) {
      throw error
    }
  }

  async function restoreAdminToken() {
    let storedToken = sessionStorage.getItem('token')
    if (storedToken) {
      axios.defaults.headers.common.Authorization = "Bearer " + storedToken
      await loadAdmin()
      socket.emit('loggedIn', admin.value)
      return true
    }
    clearAdmin()
    return false
  }

  socket.on('insertedAdmin', (insertedAdmin) => {
    toast.info(`Admin #${insertedAdmin.id} (${insertedAdmin.name}) has registered successfully!`)
  })

  socket.on('updatedAdmin', (updatedAdmin) => {
    if (admin.value?.id == updatedAdmin.id) {
      admin.value = updatedAdmin
      toast.info('Your admin profile has been changed!')
    } else {
      toast.info(`Admin profile #${updatedAdmin.id} (${updatedAdmin.name}) has changed!`)
    }
  })

  return {
    admin,
    adminName,
    adminId,
    adminType,
    loadAdmin,
    clearAdmin,
    loginAdmin,
    logoutAdmin,
    changeAdminPassword,
    restoreAdminToken,
  }
})

