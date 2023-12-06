import axios from 'axios';
import { ref, computed, inject } from 'vue';
import { defineStore } from 'pinia';
import avatarNoneUrl from '@/assets/avatar-none.png';
import { useToast } from 'vue-toastification';

export const useVcardStore = defineStore('vcard', () => {
  const socket = inject('socket');
  const toast = useToast();

  const serverBaseUrl = inject('serverBaseUrl');
  const vcard = ref(null);

  const vcardName = computed(() => vcard.value?.name ?? 'Vcard');

  const vcardId = computed(() => vcard.value?.phone_number ?? '');

  const vcardType = computed(() => vcard.value?.type ?? 'V');

  const vcardPhotoUrl = computed(() =>
    vcard.value?.photo_url
      ? `${serverBaseUrl}/storage/vcard_photos/${vcard.value.photo_url}`
      : avatarNoneUrl
  );

  async function loadVcard() {
    try {
      console.log('1----')
      const response = await axios.get('vcards/me');
      console.log('2----')

      vcard.value = response.data.data;
    } catch (error) {
      clearVcard();
      throw error;
    }
  }

  function clearVcard() {
    delete axios.defaults.headers.common.Authorization;
    sessionStorage.removeItem('vcardToken');
    vcard.value = null;
  }

  async function login(credentials) {
    try {
      console.log(credentials)
      const response = await axios.post('auth/login', credentials);
      axios.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token;
      sessionStorage.setItem('vcardToken', response.data.access_token);
      await loadVcard();
      socket.emit('loggedIn', vcard.value);
      return true;
    } catch (error) {
      clearVcard();
      return false;
    }
  }

  async function logout() {
    try {
      await axios.post('logout');
      socket.emit('loggedOut', vcard.value);
      clearVcard();
      return true;
    } catch (error) {
      return false;
    }
  }

  async function restoreToken() {
    let storedToken = sessionStorage.getItem('vcardToken');
    if (storedToken) {
      axios.defaults.headers.common.Authorization = 'Bearer ' + storedToken;
      await loadVcard();
      socket.emit('loggedIn', vcard.value);
      return true;
    }
    clearVcard();
    return false;
  }

  socket.on('insertedVcard', (insertedVcard) => {
    toast.info(`Vcard #${insertedVcard.phone_number} (${insertedVcard.name}) has registered successfully!`);
  });

  socket.on('updatedVcard', (updatedVcard) => {
    if (vcard.value?.phone_number === updatedVcard.phone_number) {
      vcard.value = updatedVcard;
      toast.info('Your vcard profile has been changed!');
    } else {
      toast.info(`Vcard profile #${updatedVcard.phone_number} (${updatedVcard.name}) has changed!`);
    }
  });

  return {
    vcard,
    vcardId,
    vcardName,
    vcardType,
    vcardPhotoUrl,
    loadVcard,
    clearVcard,
    login,
    logout,
    restoreToken,
  };
});
